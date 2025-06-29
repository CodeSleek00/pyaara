<?php
session_start();
include 'db_connect.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_session_id = session_id();
$checkout_items = [];
$total_checkout_amount = 0;
$cod_fee = 49; // COD fee

// Handle messages
$message = '';
$message_type = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'success';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $required_fields = ['first_name', 'last_name', 'phone_number', 'shipping_address', 'payment_method', 'total_amount'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['message'] = "All fields are required.";
            $_SESSION['message_type'] = "error";
            header("Location: checkout.php");
            exit();
        }
    }

    // Sanitize inputs
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $shipping_address = $conn->real_escape_string($_POST['shipping_address']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $total_amount_from_form = (float)$_POST['total_amount'];

    // Validate payment method
    if (!in_array($payment_method, ['COD', 'Razorpay'])) {
        $_SESSION['message'] = "Invalid payment method selected.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }

    // Calculate actual total from cart
    $stmt = $conn->prepare("SELECT c.quantity, p.original_price, p.discount_price 
                           FROM cart c JOIN products p ON c.product_id = p.id 
                           WHERE c.user_session_id = ?");
    $stmt->bind_param("s", $user_session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $calculated_total_amount = 0;
    while ($row = $result->fetch_assoc()) {
        $price = ($row['discount_price'] > 0 && $row['discount_price'] < $row['original_price']) 
               ? $row['discount_price'] 
               : $row['original_price'];
        $calculated_total_amount += ($price * $row['quantity']);
    }
    $stmt->close();

    if ($payment_method === 'COD') {
        $calculated_total_amount += $cod_fee;
    }

    // Validate total amount
    if (abs($calculated_total_amount - $total_amount_from_form) > 0.01) {
        $_SESSION['message'] = "Total amount mismatch. Please try again.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }

    // Generate order ID
    $order_id = 'ORD_' . time() . '_' . bin2hex(random_bytes(3));

    // Handle Razorpay payment
    if ($payment_method === 'Razorpay') {
        $_SESSION['razorpay_order'] = [
            'order_id' => $order_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone_number' => $phone_number,
            'shipping_address' => $shipping_address,
            'payment_method' => $payment_method,
            'total_amount' => $calculated_total_amount
        ];
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'razorpay',
            'order_id' => $order_id,
            'amount' => $calculated_total_amount * 100,
            'currency' => 'INR',
            'key' => 'rzp_live_pA6jgjncp78sq7',
            'name' => 'Your Store Name',
            'description' => 'Order Payment',
            'prefill' => [
                'name' => $first_name . ' ' . $last_name,
                'email' => '',
                'contact' => $phone_number
            ],
            'notes' => [
                'address' => $shipping_address,
                'merchant_order_id' => $order_id
            ],
            'theme' => [
                'color' => '#3399cc'
            ]
        ]);
        exit();
    }

    // Handle COD payment
    $stmt = $conn->prepare("INSERT INTO orders (order_id, first_name, last_name, phone_number, shipping_address, payment_method, total_amount) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssd", $order_id, $first_name, $last_name, $phone_number, $shipping_address, $payment_method, $calculated_total_amount);

    if ($stmt->execute()) {
        $order_db_id = $conn->insert_id;
        
        // Move cart items to order_items
        $cart_items = $conn->query("SELECT product_id, quantity, size FROM cart WHERE user_session_id = '$user_session_id'");
        while ($item = $cart_items->fetch_assoc()) {
            $product = $conn->query("SELECT original_price, discount_price FROM products WHERE id = {$item['product_id']}")->fetch_assoc();
            $price = ($product['discount_price'] > 0 && $product['discount_price'] < $product['original_price']) 
                   ? $product['discount_price'] 
                   : $product['original_price'];
            
            $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price, size) 
                         VALUES ($order_db_id, {$item['product_id']}, {$item['quantity']}, $price, '{$item['size']}')");
        }

        // Clear cart
        $conn->query("DELETE FROM cart WHERE user_session_id = '$user_session_id'");

        $_SESSION['message'] = "Order placed successfully! Your Order ID: " . $order_id;
        $_SESSION['message_type'] = "success";
        header("Location: thank_you.php?order_id=" . $order_id);
        exit();
    } else {
        $_SESSION['message'] = "Error placing order: " . $stmt->error;
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }
}

// Get cart items for display
$stmt = $conn->prepare("SELECT c.product_id, c.quantity, c.size, p.name, p.image, p.original_price, p.discount_price
                       FROM cart c JOIN products p ON c.product_id = p.id
                       WHERE c.user_session_id = ?");
$stmt->bind_param("s", $user_session_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $price = ($row['discount_price'] > 0 && $row['discount_price'] < $row['original_price']) 
               ? $row['discount_price'] 
               : $row['original_price'];
        $row['price'] = $price;
        $checkout_items[] = $row;
        $total_checkout_amount += ($price * $row['quantity']);
    }
} else {
    $_SESSION['message'] = "Your cart is empty. Please add items before checking out.";
    $_SESSION['message_type'] = "error";
    header("Location: index.php");
    exit();
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Your Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #f59e0b;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #64748b;
            --danger: #dc2626;
            --success: #16a34a;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f1f5f9;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .message {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .message.success {
            background-color: #dcfce7;
            color: var(--success);
            border-left: 4px solid var(--success);
        }
        
        .message.error {
            background-color: #fee2e2;
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        .checkout-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .checkout-header h1 {
            font-size: 28px;
            color: var(--dark);
            margin-bottom: 8px;
        }
        
        .checkout-header p {
            color: var(--gray);
        }
        
        .checkout-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .checkout-form, .order-summary {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }
        
        .checkout-form {
            flex: 2;
            min-width: 300px;
        }
        
        .order-summary {
            flex: 1;
            min-width: 300px;
        }
        
        .section-title {
            font-size: 20px;
            margin-bottom: 20px;
            color: var(--dark);
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .payment-methods {
            margin-top: 15px;
        }
        
        .payment-option {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .payment-option:hover {
            border-color: var(--primary);
        }
        
        .payment-option input {
            margin-right: 10px;
        }
        
        .payment-option label {
            cursor: pointer;
            margin-bottom: 0;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            text-align: center;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
        }
        
        .order-item {
            display: flex;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px dashed #e2e8f0;
        }
        
        .order-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .order-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
        }
        
        .order-item-details {
            flex: 1;
        }
        
        .order-item-title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .order-item-meta {
            font-size: 14px;
            color: var(--gray);
        }
        
        .order-item-price {
            font-weight: 600;
            color: var(--dark);
        }
        
        .order-summary-row {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            padding: 10px 0;
        }
        
        .order-subtotal {
            border-top: 1px dashed #e2e8f0;
            color: var(--gray);
        }
        
        .order-cod-fee {
            border-bottom: 1px dashed #e2e8f0;
        }
        
        .order-total {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
            border-top: 2px solid var(--primary);
            padding-top: 15px;
        }
        
        .secure-payment {
            margin-top: 20px;
            font-size: 12px;
            color: var(--gray);
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .checkout-container {
                flex-direction: column;
            }
            
            .checkout-form, .order-summary {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($message): ?>
            <div class="message <?= $message_type ?>"><?= $message ?></div>
        <?php endif; ?>
        
        <div class="checkout-header">
            <h1>Checkout</h1>
            <p>Complete your purchase by filling the details below</p>
        </div>
        
        <div class="checkout-container">
            <div class="checkout-form">
                <h2 class="section-title">Shipping Information</h2>
                <form action="checkout.php" method="post" id="checkoutForm">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required 
                               value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required
                               value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number" class="form-control" required
                               pattern="[0-9]{10}" value="<?= htmlspecialchars($_POST['phone_number'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="shipping_address">Shipping Address</label>
                        <textarea id="shipping_address" name="shipping_address" class="form-control" required><?= 
                            htmlspecialchars($_POST['shipping_address'] ?? '') ?></textarea>
                    </div>
                    
                    <h2 class="section-title">Payment Method</h2>
                    
                    <div class="form-group">
                        <div class="payment-methods">
                            <div class="payment-option">
                                <input type="radio" id="cod" name="payment_method" value="COD" required checked>
                                <label for="cod">Cash on Delivery (COD) - ₹49 fee</label>
                            </div>
                            
                            <div class="payment-option">
                                <input type="radio" id="razorpay" name="payment_method" value="Razorpay" required>
                                <label for="razorpay">Online Payment (Razorpay)</label>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="total_amount" value="<?= number_format($total_checkout_amount + $cod_fee, 2, '.', '') ?>" id="totalAmountInput">
                    
                    <button type="submit" class="btn" id="placeOrderBtn">Place Order</button>
                </form>
            </div>
            
            <div class="order-summary">
                <h2 class="section-title">Order Summary</h2>
                
                <?php foreach ($checkout_items as $item): ?>
                    <div class="order-item">
                        <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="order-item-img">
                        <div class="order-item-details">
                            <h3 class="order-item-title"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="order-item-meta">
                                Size: <?= htmlspecialchars($item['size'] ?? 'N/A') ?> | 
                                Qty: <?= htmlspecialchars($item['quantity']) ?>
                            </p>
                        </div>
                        <div class="order-item-price">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                    </div>
                <?php endforeach; ?>
                
                <div class="order-summary-row order-subtotal">
                    <span>Subtotal</span>
                    <span>₹<?= number_format($total_checkout_amount, 2) ?></span>
                </div>
                
                <div class="order-summary-row order-cod-fee" id="codFee">
                    <span>COD Fee</span>
                    <span>₹<?= $cod_fee ?></span>
                </div>
                
                <div class="order-summary-row order-total">
                    <span>Total</span>
                    <span id="totalAmount">₹<?= number_format($total_checkout_amount + $cod_fee, 2) ?></span>
                </div>
                
                <div class="secure-payment">
                    <p>Secure payment processing powered by Razorpay</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const baseTotal = <?= $total_checkout_amount ?>;
        const codFee = <?= $cod_fee ?>;
        
        function updateTotal() {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const codFeeElement = document.getElementById('codFee');
            const totalAmountElement = document.getElementById('totalAmount');
            const totalAmountInput = document.getElementById('totalAmountInput');
            
            if (paymentMethod === 'COD') {
                codFeeElement.style.display = 'flex';
                const newTotal = baseTotal + codFee;
                totalAmountElement.textContent = '₹' + newTotal.toFixed(2);
                totalAmountInput.value = newTotal.toFixed(2);
            } else {
                codFeeElement.style.display = 'none';
                totalAmountElement.textContent = '₹' + baseTotal.toFixed(2);
                totalAmountInput.value = baseTotal.toFixed(2);
            }
        }
        
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', updateTotal);
        });
        
        updateTotal();

        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (paymentMethod === 'Razorpay') {
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'razorpay') {
                        const options = {
                            key: data.key,
                            amount: data.amount,
                            currency: data.currency,
                            name: data.name,
                            description: data.description,
                            order_id: data.order_id,
                            handler: function(response) {
                                const verifyForm = document.createElement('form');
                                verifyForm.method = 'POST';
                                verifyForm.action = 'verify_razorpay.php';
                                
                                const paymentData = {
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_order_id: response.razorpay_order_id,
                                    razorpay_signature: response.razorpay_signature,
                                    order_id: data.order_id,
                                    amount: data.amount / 100,
                                    first_name: document.getElementById("first_name").value,
                                    last_name: document.getElementById("last_name").value,
                                    phone_number: document.getElementById("phone_number").value,
                                    shipping_address: document.getElementById("shipping_address").value
                                };
                                
                                Object.entries(paymentData).forEach(([name, value]) => {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = name;
                                    input.value = value;
                                    verifyForm.appendChild(input);
                                });
                                
                                document.body.appendChild(verifyForm);
                                verifyForm.submit();
                            },
                            prefill: data.prefill,
                            notes: data.notes,
                            theme: data.theme,
                            modal: {
                                ondismiss: function() {
                                    alert('Payment was cancelled. You can try again.');
                                }
                            }
                        };
                        
                        const rzp = new Razorpay(options);
                        rzp.open();
                    } else {
                        alert('Error: ' + (data.message || 'Unexpected response from server'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Payment processing failed. Please try again.');
                });
            } else {
                form.submit();
            }
        });
    </script>
</body>
</html>