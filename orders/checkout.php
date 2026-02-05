<?php
// Strict error reporting for production
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/../logs/php_errors.log');

// Ensure session is started securely
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    $_SESSION['message'] = "Please login to proceed with checkout";
    $_SESSION['message_type'] = "error";
    header("Location: ../login.html");
    exit();
}

require('../vendor/autoload.php');
use Razorpay\Api\Api;
include 'db_connect.php';

// CSRF protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Constants
define('COD_FEE', 49);
define('CURRENCY', 'INR');
define('RAZORPAY_KEY_ID', 'rzp_live_pA6jgjncp78sq7');
define('RAZORPAY_KEY_SECRET', 'N7INcRU4l61iijQ2sOjL5YTs');

$user_session_id = session_id();

// Initialize variables
$checkout_items = [];
$total_checkout_amount = 0;

// Fetch Cart Items First (Required for both GET and POST)
$sql_cart_items = "SELECT c.product_id, c.quantity, c.size, p.name, p.image, p.original_price, p.discount_price 
                   FROM cart c JOIN products p ON c.product_id = p.id 
                   WHERE c.user_session_id = ?";
$stmt_cart = $conn->prepare($sql_cart_items);
$stmt_cart->bind_param("s", $user_session_id);
$stmt_cart->execute();
$result_cart = $stmt_cart->get_result();

while ($item = $result_cart->fetch_assoc()) {
    $price = ($item['discount_price'] < $item['original_price'] && $item['discount_price'] > 0) ? $item['discount_price'] : $item['original_price'];
    $item['price'] = $price;
    $checkout_items[] = $item;
    $total_checkout_amount += ($price * $item['quantity']);
}
$stmt_cart->close();

if (empty($checkout_items)) {
    $_SESSION['message'] = "Your cart is empty.";
    header("Location: index.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Security token mismatch.");
    }

    $payment_method = $_POST['payment_method'];
    $final_calculated_total = $total_checkout_amount + ($payment_method === 'COD' ? COD_FEE : 0);

    // Sanitize Inputs
    $first_name = $conn->real_escape_string(trim($_POST['first_name']));
    $last_name = $conn->real_escape_string(trim($_POST['last_name']));
    $phone_number = $conn->real_escape_string(trim($_POST['phone_number']));
    $shipping_address = $conn->real_escape_string(trim($_POST['shipping_address']));
    $pincode = $conn->real_escape_string(trim($_POST['pincode']));

    $order_id = 'ORD_' . time() . '_' . bin2hex(random_bytes(4));

    if ($payment_method === 'Razorpay') {
        try {
            $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
            $razorpay_order = $api->order->create([
                'receipt' => $order_id,
                'amount' => round($final_calculated_total * 100),
                'currency' => CURRENCY,
                'payment_capture' => 1
            ]);

            $_SESSION['razorpay_order'] = [
                'order_id' => $order_id,
                'razorpay_order_id' => $razorpay_order['id'],
                'details' => $_POST,
                'total_amount' => $final_calculated_total,
                'items' => $checkout_items
            ];

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'razorpay',
                'order_id' => $razorpay_order['id'],
                'amount' => round($final_calculated_total * 100),
                'currency' => CURRENCY,
                'key' => RAZORPAY_KEY_ID,
                'prefill' => ['name' => "$first_name $last_name", 'contact' => $phone_number]
            ]);
            exit();
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit();
        }
    }

    // COD Logic
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, order_id, first_name, last_name, phone_number, shipping_address, pincode, payment_method, total_amount, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("isssssssd", $_SESSION['user_id'], $order_id, $first_name, $last_name, $phone_number, $shipping_address, $pincode, $payment_method, $final_calculated_total);
        $stmt->execute();
        $db_order_id = $conn->insert_id;

        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");
        foreach ($checkout_items as $item) {
            $stmt_items->bind_param("iiids", $db_order_id, $item['product_id'], $item['quantity'], $item['price'], $item['size']);
            $stmt_items->execute();
        }

        $conn->prepare("DELETE FROM cart WHERE user_session_id = ?")->execute([$user_session_id]);
        $conn->commit();
        header("Location: thank_you.php?order_id=" . $order_id);
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Order Failed: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="checkout.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: white; padding: 20px; border-radius: 8px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <form action="checkout.php" method="post" id="checkoutForm">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="tel" name="phone_number" placeholder="Phone" required pattern="[0-9]{10}">
            <textarea name="shipping_address" placeholder="Address" required></textarea>
            <input type="text" name="pincode" placeholder="Pincode" required pattern="[0-9]{6}">

            <div class="payment-methods">
                <label><input type="radio" name="payment_method" value="COD" checked> Cash on Delivery</label>
                <label><input type="radio" name="payment_method" value="Razorpay"> Online Payment</label>
            </div>

            <div class="summary">
                <p>Subtotal: ₹<span id="displaySubtotal"><?= $total_checkout_amount ?></span></p>
                <p id="codFeeRow">COD Fee: ₹<?= COD_FEE ?></p>
                <h3>Total: ₹<span id="displayTotal"><?= $total_checkout_amount + COD_FEE ?></span></h3>
            </div>

            <button type="submit" class="btn-submit">Place Order</button>
        </form>
    </div>

    <div class="modal-overlay" id="paymentProcessing">
        <div class="modal-content"><h3>Processing...</h3></div>
    </div>

    <script>
        const baseTotal = <?= $total_checkout_amount ?>;
        const codFee = <?= COD_FEE ?>;
        const form = document.getElementById('checkoutForm');

        function updateTotals() {
            const method = document.querySelector('input[name="payment_method"]:checked').value;
            const feeRow = document.getElementById('codFeeRow');
            const totalDisp = document.getElementById('displayTotal');
            
            if (method === 'COD') {
                feeRow.style.display = 'block';
                totalDisp.textContent = (baseTotal + codFee).toFixed(2);
            } else {
                feeRow.style.display = 'none';
                totalDisp.textContent = baseTotal.toFixed(2);
            }
        }

        document.querySelectorAll('input[name="payment_method"]').forEach(r => r.addEventListener('change', updateTotals));

        form.addEventListener('submit', async (e) => {
            const method = document.querySelector('input[name="payment_method"]:checked').value;
            if (method === 'COD') return; // Allow normal submission

            e.preventDefault();
            document.getElementById('paymentProcessing').style.display = 'flex';

            try {
                const formData = new FormData(form);
                const response = await fetch('checkout.php', { method: 'POST', body: formData });
                const data = await response.json();

                if (data.status === 'razorpay') {
                    const options = {
                        "key": data.key,
                        "amount": data.amount,
                        "order_id": data.order_id,
                        "handler": function (res) {
                            window.location.href = `verify_razorpay.php?pay_id=${res.razorpay_payment_id}&ord_id=${res.razorpay_order_id}&sig=${res.razorpay_signature}`;
                        }
                    };
                    const rzp = new Razorpay(options);
                    rzp.open();
                }
            } catch (err) {
                alert("Payment Error. Please try again.");
            } finally {
                document.getElementById('paymentProcessing').style.display = 'none';
            }
        });
    </script>
</body>
</html>