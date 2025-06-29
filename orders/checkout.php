<?php

include 'db_connect.php';

$user_session_id = session_id();
$checkout_items = [];
$total_checkout_amount = 0;
$cod_fee = 49;

$message = '';
$message_type = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'success';
    unset($_SESSION['message'], $_SESSION['message_type']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $shipping_address = $conn->real_escape_string($_POST['shipping_address']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $total_amount_from_form = (float) $_POST['total_amount'];

    if (empty($first_name) || empty($last_name) || empty($phone_number) || empty($shipping_address) || empty($payment_method)) {
        $_SESSION['message'] = "All fields are required.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }

    if (!in_array($payment_method, ['COD', 'Razorpay'])) {
        $_SESSION['message'] = "Invalid payment method.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT c.quantity, p.original_price, p.discount_price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_session_id = ?");
    $stmt->bind_param("s", $user_session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $calculated_total_amount = 0;

    while ($row = $result->fetch_assoc()) {
        $price = ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0) ? $row['discount_price'] : $row['original_price'];
        $calculated_total_amount += $price * $row['quantity'];
    }
    $stmt->close();

    if ($payment_method === 'COD') {
        $calculated_total_amount += $cod_fee;
    }

    if (abs($calculated_total_amount - $total_amount_from_form) > 0.01) {
        $_SESSION['message'] = "Amount mismatch. Try again.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }

    $receipt_id = uniqid('ORDER_');

    if ($payment_method === 'Razorpay') {
        $key_id = 'rzp_live_pA6jgjncp78sq7';
        $key_secret = 'N7INcRU4l61iijQ2sOjL5YTs';
        $amount_paise = $calculated_total_amount * 100;

        $order_data = [
            'amount' => $amount_paise,
            'currency' => 'INR',
            'receipt' => $receipt_id,
            'payment_capture' => 1
        ];

        $ch = curl_init('https://api.razorpay.com/v1/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$key_id:$key_secret");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $response_data = json_decode($response, true);

        if ($http_code !== 200 || !isset($response_data['id'])) {
            $_SESSION['message'] = "Razorpay order creation failed.";
            $_SESSION['message_type'] = "error";
            header("Location: checkout.php");
            exit();
        }

        $razorpay_order_id = $response_data['id'];

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'razorpay',
            'order_id' => $razorpay_order_id,
            'amount' => $amount_paise,
            'currency' => 'INR',
            'key' => $key_id,
            'name' => 'Pyaara',
            'description' => 'Order Payment',
            'prefill' => [
                'name' => $first_name . ' ' . $last_name,
                'email' => '',
                'contact' => $phone_number
            ],
            'notes' => [
                'address' => $shipping_address,
                'merchant_order_id' => $receipt_id
            ],
            'theme' => [
                'color' => '#3399cc'
            ]
        ]);
        exit();
    }

    // Proceed with COD
    $order_id = $receipt_id;
    $stmt = $conn->prepare("INSERT INTO orders (order_id, first_name, last_name, phone_number, shipping_address, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssd", $order_id, $first_name, $last_name, $phone_number, $shipping_address, $payment_method, $calculated_total_amount);
    
    if ($stmt->execute()) {
        $last_order_id = $conn->insert_id;

        $stmt = $conn->prepare("SELECT product_id, quantity, size FROM cart WHERE user_session_id = ?");
        $stmt->bind_param("s", $user_session_id);
        $stmt->execute();
        $items = $stmt->get_result();

        $insert = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");

        while ($item = $items->fetch_assoc()) {
            $product_id = $item['product_id'];
            $stmt_price = $conn->prepare("SELECT original_price, discount_price FROM products WHERE id = ?");
            $stmt_price->bind_param("i", $product_id);
            $stmt_price->execute();
            $res_price = $stmt_price->get_result()->fetch_assoc();
            $price = ($res_price['discount_price'] < $res_price['original_price'] && $res_price['discount_price'] > 0) ? $res_price['discount_price'] : $res_price['original_price'];
            $stmt_price->close();

            $insert->bind_param("iiids", $last_order_id, $product_id, $item['quantity'], $price, $item['size']);
            $insert->execute();
        }

        $stmt = $conn->prepare("DELETE FROM cart WHERE user_session_id = ?");
        $stmt->bind_param("s", $user_session_id);
        $stmt->execute();

        $_SESSION['message'] = "Order placed successfully! Order ID: $order_id";
        $_SESSION['message_type'] = "success";
        header("Location: thank_you.php?order_id=$order_id");
        exit();
    } else {
        $_SESSION['message'] = "Failed to place order.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }
}

// Fetch cart for GET view
$stmt = $conn->prepare("SELECT c.product_id, c.quantity, c.size, p.name, p.image, p.original_price, p.discount_price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_session_id = ?");
$stmt->bind_param("s", $user_session_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = "Your cart is empty.";
    $_SESSION['message_type'] = "error";
    header("Location: index.php");
    exit();
}

while ($row = $result->fetch_assoc()) {
    $price = ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0) ? $row['discount_price'] : $row['original_price'];
    $row['price'] = $price;
    $checkout_items[] = $row;
    $total_checkout_amount += $price * $row['quantity'];
}
$stmt->close();
$conn->close();
?>
