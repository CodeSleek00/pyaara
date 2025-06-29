<?php
session_start();
include 'db_connect.php';
require('vendor/autoload.php');
use Razorpay\Api\Api;

$key_id = 'rzp_live_pA6jgjncp78sq7';
$key_secret = 'N7INcRU4l61iijQ2sOjL5YTs';
$api = new Api($key_id, $key_secret);

$user_session_id = session_id();
$cod_fee = 49;
$checkout_items = [];
$total_checkout_amount = 0;

$message = $_SESSION['message'] ?? '';
$message_type = $_SESSION['message_type'] ?? 'success';
unset($_SESSION['message'], $_SESSION['message_type']);

// Handle AJAX POST for Razorpay or normal POST for COD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $shipping_address = $conn->real_escape_string($_POST['shipping_address']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $total_amount_from_form = (float)$_POST['total_amount'];

    if (!$first_name || !$last_name || !$phone_number || !$shipping_address || !in_array($payment_method, ['COD', 'Razorpay'])) {
        $error = ['status'=>'error','message'=>'All fields are required and payment method must be valid.'];
        if ($payment_method === 'Razorpay') { echo json_encode($error); }
        else { $_SESSION['message']= $error['message']; $_SESSION['message_type']='error'; header('Location: checkout.php'); }
        exit();
    }

    $sql_cart = "SELECT c.quantity, p.original_price, p.discount_price FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_session_id=?";
    $stmt = $conn->prepare($sql_cart);
    $stmt->bind_param("s", $user_session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $calculated_total = 0;
    while ($row = $result->fetch_assoc()) {
        $price = ($row['discount_price'] > 0 && $row['discount_price'] < $row['original_price']) ? $row['discount_price'] : $row['original_price'];
        $calculated_total += $price * $row['quantity'];
    }
    $stmt->close();

    if ($payment_method === 'COD') $calculated_total += $cod_fee;

    if (abs($calculated_total - $total_amount_from_form) > 0.01) {
        $error=['status'=>'error','message'=>'Amount mismatch, please refresh and try again.'];
        if ($payment_method === 'Razorpay') { echo json_encode($error); }
        else { $_SESSION['message']=$error['message']; $_SESSION['message_type']='error'; header('Location: checkout.php'); }
        exit();
    }

    $order_id = uniqid('ORDER_');
    $stmt = $conn->prepare("INSERT INTO orders (order_id, first_name, last_name, phone_number, shipping_address, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssd", $order_id, $first_name, $last_name, $phone_number, $shipping_address, $payment_method, $calculated_total);
    $stmt->execute();
    $last_db_order = $conn->insert_id;
    $stmt->close();

    $stmt2 = $conn->prepare("SELECT product_id, quantity, size FROM cart WHERE user_session_id=?");
    $stmt2->bind_param("s", $user_session_id);
    $stmt2->execute();
    $items = $stmt2->get_result();

    $stmt3 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");
    while ($it = $items->fetch_assoc()) {
        $pid = $it['product_id']; $qty = $it['quantity']; $sz = $it['size'];
        $pr = $conn->query("SELECT original_price, discount_price FROM products WHERE id=$pid")->fetch_assoc();
        $unit = ($pr['discount_price'] > 0 && $pr['discount_price'] < $pr['original_price']) ? $pr['discount_price'] : $pr['original_price'];
        $stmt3->bind_param("iiids", $last_db_order, $pid, $qty, $unit, $sz);
        $stmt3->execute();
    }
    $stmt2->close(); $stmt3->close();

    $conn->query("DELETE FROM cart WHERE user_session_id='$user_session_id'");

    if ($payment_method === 'COD') {
        $_SESSION['message'] = "Order placed successfully! Order ID: $order_id";
        $_SESSION['message_type'] = "success";
        header("Location: thank_you.php?order_id=$order_id");
        exit();
    }

    // Create Razorpay order using SDK
    $razorpay_order = $api->order->create([
        'receipt'=>$order_id,
        'amount'=>$calculated_total*100,
        'currency'=>'INR',
        'notes'=>[
            'merchant_order_id'=>$order_id,
            'customer_name'=>$first_name.' '.$last_name,
            'customer_phone'=>$phone_number
        ]
    ]);

    echo json_encode([
        'status'=>'razorpay',
        'order_id'=>$razorpay_order['id'],
        'amount'=>$razorpay_order['amount'],
        'currency'=>'INR',
        'key'=>$key_id,
        'name'=>'Pyaara',
        'description'=>'Order Payment',
        'prefill'=>[
            'name'=>$first_name.' '.$last_name,
            'contact'=>$phone_number
        ],
        'notes'=>$razorpay_order['notes'],
        'theme'=>['color'=>'#3399cc']
    ]);
    exit();
}

// GET request: load cart for display
$stmt = $conn->prepare("SELECT c.product_id, c.quantity, c.size, p.name, p.image, p.original_price, p.discount_price FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_session_id=?");
$stmt->bind_param("s", $user_session_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $price = ($row['discount_price']>0 && $row['discount_price']< $row['original_price']) ? $row['discount_price'] : $row['original_price'];
    $row['price'] = $price;
    $checkout_items[] = $row;
    $total_checkout_amount += $price * $row['quantity'];
}
$stmt->close();
$conn->close();

// HTML & form/UI remains exactly as your original version, including Razorpay checkout.js and JS submit listener
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout</title>
        <link rel="stylesheet" href="style.css">
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <style>
            /* Specific styles for checkout.php */
            .checkout-container {
                display: flex;
                flex-wrap: wrap;
                gap: 30px;
                margin-top: 30px;
            }

            .checkout-form, .order-summary {
                background-color: var(--white);
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }

            .checkout-form {
                flex: 2 1 600px; /* Takes more space */
            }

            .order-summary {
                flex: 1 1 300px; /* Takes less space */
            }

            .checkout-form h2, .order-summary h2 {
                color: var(--dark-grey);
                margin-top: 0;
                margin-bottom: 25px;
                text-align: center;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                display: block;
                margin-bottom: 8px;
                font-weight: bold;
                color: var(--medium-grey);
            }

            .form-group input[type="text"],
            .form-group input[type="tel"],
            .form-group textarea,
            .form-group select {
                width: calc(100% - 22px); /* Account for padding */
                padding: 12px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 1em;
                box-sizing: border-box;
            }

            .form-group textarea {
                resize: vertical;
                min-height: 80px;
            }

            .payment-methods {
                margin-top: 15px;
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
            }

            .payment-methods label {
                display: flex;
                align-items: center;
                font-weight: normal;
                cursor: pointer;
                color: var(--dark-grey);
            }

            .payment-methods input[type="radio"] {
                margin-right: 8px;
            }

            .btn-place-order {
                display: block;
                width: 100%;
                padding: 15px;
                background-color: var(--primary-color);
                color: var(--white);
                border: none;
                border-radius: 5px;
                font-size: 1.2em;
                cursor: pointer;
                transition: background-color 0.3s ease;
                margin-top: 30px;
            }

            .btn-place-order:hover {
                background-color: #45a049;
            }

            .order-item {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
                padding-bottom: 15px;
                border-bottom: 1px dashed #eee;
            }

            .order-item:last-child {
                border-bottom: none;
                margin-bottom: 0;
                padding-bottom: 0;
            }

            .order-item img {
                width: 70px;
                height: 70px;
                object-fit: cover;
                border-radius: 5px;
                margin-right: 15px;
            }

            .order-item-details {
                flex-grow: 1;
            }

            .order-item-details h4 {
                margin: 0 0 5px 0;
                color: var(--dark-grey);
                font-size: 1.1em;
            }

            .order-item-details p {
                margin: 0;
                font-size: 0.9em;
                color: var(--medium-grey);
            }

            .order-item-price {
                font-weight: bold;
                color: var(--red);
                font-size: 1em;
            }

            .order-total {
                border-top: 2px solid var(--primary-color);
                padding-top: 20px;
                margin-top: 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 1.4em;
                font-weight: bold;
                color: var(--dark-grey);
            }

            .cod-fee {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 10px;
                padding: 10px 0;
                color: var(--dark-grey);
                font-size: 1em;
                border-bottom: 1px dashed #eee;
            }

            .cod-fee.hidden {
                display: none;
            }

            .subtotal {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 10px;
                padding: 10px 0;
                color: var(--medium-grey);
                font-size: 1em;
            }
        </style>
    </head>
    <body>
       
        <div class="container">
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>
                <div class="checkout">
                    <h2>Checkout</h2>
                <p>Review your order and enter your shipping and payment details below.</p>
                </div>
            <div class="checkout-container">
                <div class="checkout-form">
                    <h2>Shipping & Payment Details</h2>
                    <form action="checkout.php" method="post" id="checkoutForm">
                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input type="text" id="first_name" name="first_name" required placeholder="Enter Your First Name">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" id="last_name" name="last_name" required placeholder="Enter Your Last Name">
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number:</label>
                            <input type="tel" id="phone_number" name="phone_number" required placeholder="Enter Your Phone Number" pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number">
                        </div>
                        <div class="form-group">
                            <label for="shipping_address">Shipping Address:</label>
                            <textarea id="shipping_address" name="shipping_address" required placeholder="Enter your Address"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Payment Method:</label>
                            <div class="payment-methods">
                                <label><input type="radio" name="payment_method" value="COD" required checked> Cash on Delivery (COD) - Additional ₹49 fee</label>
                                <label><input type="radio" name="payment_method" value="Razorpay" required> Razorpay (Card/UPI/NetBanking)</label>
                            </div>
                        </div>

                        <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars(number_format($total_checkout_amount + $cod_fee, 2, '.', '')); ?>" id="totalAmountInput">

                        <button type="submit" class="btn-place-order" id="placeOrderBtn">Place Order</button>
                    </form>
                </div>

                <div class="order-summary">
                    <h2>Your Order Summary</h2>
                    <?php if (!empty($checkout_items)): ?>
                        <?php foreach ($checkout_items as $item): ?>
                            <div class="order-item">
                                <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <div class="order-item-details">
                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <p>Size: <?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?> | Qty: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                </div>
                                <span class="order-item-price">₹<?php echo htmlspecialchars(number_format($item['price'] * $item['quantity'], 2)); ?></span>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="subtotal">
                            <span>Subtotal:</span>
                            <span>₹<?php echo htmlspecialchars(number_format($total_checkout_amount, 2)); ?></span>
                        </div>
                        
                        <div class="cod-fee" id="codFee">
                            <span>COD Fee:</span>
                            <span>₹<?php echo $cod_fee; ?></span>
                        </div>
                        
                        <div class="order-total">
                            <span>Total:</span>
                            <span id="totalAmount">₹<?php echo htmlspecialchars(number_format($total_checkout_amount + $cod_fee, 2)); ?></span>
                        </div>
                        <div class="secure">
                            <p>Secure payment processing powered by Razorpay.</p>
                        </div>
                    <?php else: ?>
                        <p>No items in your order summary.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            const baseTotal = <?php echo $total_checkout_amount; ?>;
            const codFee = <?php echo $cod_fee; ?>;
            
            function updateTotal() {
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                const codFeeElement = document.getElementById('codFee');
                const totalAmountElement = document.getElementById('totalAmount');
                const totalAmountInput = document.getElementById('totalAmountInput');
                
                if (paymentMethod === 'COD') {
                    codFeeElement.classList.remove('hidden');
                    const newTotal = baseTotal + codFee;
                    totalAmountElement.textContent = '₹' + newTotal.toFixed(2);
                    totalAmountInput.value = newTotal.toFixed(2);
                } else {
                    codFeeElement.classList.add('hidden');
                    totalAmountElement.textContent = '₹' + baseTotal.toFixed(2);
                    totalAmountInput.value = baseTotal.toFixed(2);
                }
            }
            
            // Add event listeners to payment method radio buttons
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.addEventListener('change', updateTotal);
            });
            
            // Initialize on page load
            updateTotal();

            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const form = this;
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                
                if (paymentMethod === 'Razorpay') {
                    // Submit form via AJAX to get Razorpay order details
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'razorpay') {
                            // Initialize Razorpay checkout
                            const options = {
                                key: data.key,
                                amount: data.amount,
                                currency: data.currency,
                                name: data.name,
                                description: data.description,
                                order_id: data.order_id,
                                handler: function (response) {
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = 'verify_razorpay.php';

                                    ['razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature'].forEach((field) => {
                                        const input = document.createElement('input');
                                        input.type = 'hidden';
                                        input.name = field;
                                        input.value = response[field];
                                        form.appendChild(input);
                                    });

                                    document.body.appendChild(form);
                                    form.submit();
                                }
                                ,
                                prefill: data.prefill,
                                notes: data.notes,
                                theme: data.theme
                            };
                            
                            const rzp = new Razorpay(options);
                            rzp.open();
                        } else {
                            // Handle other cases or errors
                            console.error('Unexpected response:', data);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                } else {
                    // For COD, submit the form normally
                    form.submit();
                }
            });
            
            // You might also want to add a function to handle Razorpay payment verification
            function verifyPayment(paymentId, orderId, signature) {
                // This would call a server-side script to verify the payment
                // Implementation depends on your server-side setup
            }
        </script>
    </body>
    </html>