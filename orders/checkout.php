<?php
require('../vendor/autoload.php'); // Load Razorpay SDK
use Razorpay\Api\Api;
    include 'db_connect.php';
// At the top of checkout.php, after the database connection
$user_session_id = session_id();

// Check if this is a direct buy now request (you might want to add a flag)
if (isset($_GET['buy_now'])) {
    // Ensure the cart has exactly one item
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_session_id = ?");
    $stmt->bind_param("s", $user_session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] != 1) {
        // If not exactly one item, redirect to cart
        header("Location: cart.php");
        exit();
    }
    $stmt->close();
}

// Rest of your existing checkout.php code...
    $user_session_id = session_id();
    $checkout_items = [];
    $total_checkout_amount = 0;
    $cod_fee = 49; // COD fee constant

    $message = '';
    $message_type = '';
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'success';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }

    // --- Logic for POST Request (Form Submission) ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = $conn->real_escape_string($_POST['first_name']);
        $last_name = $conn->real_escape_string($_POST['last_name']);
        $phone_number = $conn->real_escape_string($_POST['phone_number']);
        $shipping_address = $conn->real_escape_string($_POST['shipping_address']);
        $payment_method = $conn->real_escape_string($_POST['payment_method']);
        $total_amount_from_form = (float)$_POST['total_amount']; // Get total from hidden field

        // Validate input (basic validation)
        if (empty($first_name) || empty($last_name) || empty($phone_number) || empty($shipping_address) || empty($payment_method)) {
            $_SESSION['message'] = "All fields are required.";
            $_SESSION['message_type'] = "error";
            header("Location: checkout.php");
            exit();
        }

        // Validate payment method
        if (!in_array($payment_method, ['COD', 'Razorpay'])) {
            $_SESSION['message'] = "Invalid payment method selected.";
            $_SESSION['message_type'] = "error";
            header("Location: checkout.php");
            exit();
        }

        // Fetch items from cart again to calculate actual total_amount for security
        $sql_cart_items = "SELECT c.quantity, p.original_price, p.discount_price
                        FROM cart c
                        JOIN products p ON c.product_id = p.id
                        WHERE c.user_session_id = ?";
        $stmt_cart = $conn->prepare($sql_cart_items);
        $stmt_cart->bind_param("s", $user_session_id);
        $stmt_cart->execute();
        $result_cart = $stmt_cart->get_result();
        $calculated_total_amount = 0;
        while ($row_cart = $result_cart->fetch_assoc()) {
            $price_to_use = ($row_cart['discount_price'] < $row_cart['original_price'] && $row_cart['discount_price'] > 0) ? $row_cart['discount_price'] : $row_cart['original_price'];
            $calculated_total_amount += ($price_to_use * $row_cart['quantity']);
        }
        
        // Add COD fee if payment method is COD
        if ($payment_method === 'COD') {
            $calculated_total_amount += $cod_fee;
        }
        
        $stmt_cart->close();

        // Basic check against form's total_amount to prevent client-side manipulation
        if (abs($calculated_total_amount - $total_amount_from_form) > 0.01) { // Allow for float precision
            $_SESSION['message'] = "Total amount mismatch. Please try again.";
            $_SESSION['message_type'] = "error";
            header("Location: checkout.php");
            exit();
        }

        $order_id = uniqid('ORDER_'); // Generate a unique order ID

        // If payment method is Razorpay, we'll handle it via AJAX later
        if ($payment_method === 'Razorpay') {
    

    $api = new Api('rzp_live_pA6jgjncp78sq7', 'N7INcRU4l61iijQ2sOjL5YTs'); // Replace with your real key and secret

    $razorpay_order = $api->order->create([
        'receipt' => $order_id,
        'amount' => $calculated_total_amount * 100, // in paise
        'currency' => 'INR'
    ]);

    $real_razorpay_order_id = $razorpay_order['id'];

    // Save the order in session (optional but useful)
    $_SESSION['razorpay_order'] = [
        'order_id' => $order_id,
        'razorpay_order_id' => $real_razorpay_order_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'phone_number' => $phone_number,
        'shipping_address' => $shipping_address,
        'payment_method' => $payment_method,
        'total_amount' => $calculated_total_amount,
        'items' => []
    ];

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'razorpay',
        'order_id' => $real_razorpay_order_id,
        'amount' => $calculated_total_amount * 100,
        'currency' => 'INR',
        'key' => 'rzp_live_pA6jgjncp78sq7',
        'name' => 'Pyaara',
        'description' => 'Order Payment',
        'prefill' => [
            'name' => $first_name . ' ' . $last_name,
            'email' => '', // Add email if available
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


        // For COD, proceed with normal order processing
        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (order_id, first_name, last_name, phone_number, shipping_address, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssd", $order_id, $first_name, $last_name, $phone_number, $shipping_address, $payment_method, $calculated_total_amount);

        if ($stmt->execute()) {
            $last_order_id = $conn->insert_id; // Get the auto-generated ID from the orders table

            // Move items from cart to order_items
            $sql_cart_to_order = "SELECT product_id, quantity, size FROM cart WHERE user_session_id = ?";
            $stmt_get_cart = $conn->prepare($sql_cart_to_order);
            $stmt_get_cart->bind_param("s", $user_session_id);
            $stmt_get_cart->execute();
            $result_get_cart = $stmt_get_cart->get_result();

            $stmt_insert_order_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");

            while ($cart_item = $result_get_cart->fetch_assoc()) {
                // Fetch product price at the time of order
                $stmt_product_price = $conn->prepare("SELECT original_price, discount_price FROM products WHERE id = ?");
                $stmt_product_price->bind_param("i", $cart_item['product_id']);
                $stmt_product_price->execute();
                $result_product_price = $stmt_product_price->get_result();
                $product_price_row = $result_product_price->fetch_assoc();
                $item_price = ($product_price_row['discount_price'] < $product_price_row['original_price'] && $product_price_row['discount_price'] > 0)
                            ? $product_price_row['discount_price']
                            : $product_price_row['original_price'];
                $stmt_product_price->close();

                $stmt_insert_order_item->bind_param("iiids", $last_order_id, $cart_item['product_id'], $cart_item['quantity'], $item_price, $cart_item['size']);
                $stmt_insert_order_item->execute();
                
                // Add to razorpay session if needed
                if ($payment_method === 'Razorpay') {
                    $_SESSION['razorpay_order']['items'][] = [
                        'product_id' => $cart_item['product_id'],
                        'quantity' => $cart_item['quantity'],
                        'price' => $item_price,
                        'size' => $cart_item['size']
                    ];
                }
            }
            $stmt_get_cart->close();
            $stmt_insert_order_item->close();

            // Clear the entire session's cart after a successful order
            $clear_cart_stmt = $conn->prepare("DELETE FROM cart WHERE user_session_id = ?");
            $clear_cart_stmt->bind_param("s", $user_session_id);
            $clear_cart_stmt->execute();
            $clear_cart_stmt->close();

            $_SESSION['message'] = "Order placed successfully! Your Order ID: " . $order_id;
            $_SESSION['message_type'] = "success";
            header("Location: thank_you.php?order_id=" . $order_id); // Redirect to a thank you page
            exit();

        } else {
            $_SESSION['message'] = "Error placing order: " . $stmt->error;
            $_SESSION['message_type'] = "error";
            header("Location: checkout.php");
            exit();
        }
        $stmt->close();
    }

    // --- Logic for GET Request (Initial Load) ---
    // Always fetch from the cart now
    $sql = "SELECT c.product_id, c.quantity, c.size, p.name, p.image, p.original_price, p.discount_price
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_session_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_session_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $price_to_use = ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0) ? $row['discount_price'] : $row['original_price'];
            $row['price'] = $price_to_use;
            $checkout_items[] = $row;
            $total_checkout_amount += ($price_to_use * $row['quantity']);
        }
    } else {
        $_SESSION['message'] = "Your cart is empty. Please add items before checking out.";
        $_SESSION['message_type'] = "error";
        header("Location: index.php"); // Redirect to home if cart is empty
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
        <link rel="icon" type="image/png" href="../images/Pyaara Circle.png">
        <link rel="apple-touch-icon" href="../images/Pyaara Circle.png">
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