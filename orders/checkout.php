<?php
// Strict error reporting for production (log errors instead of displaying)
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
define('RAZORPAY_KEY_ID', 'rzp_test_TMaKHOLutXGYTH');
define('RAZORPAY_KEY_SECRET', 'eyvkr7ljPXve2MnuDjHXZQVE');

$user_session_id = session_id();

// Buy Now functionality
if (isset($_GET['buy_now'])) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_session_id = ?");
    $stmt->bind_param("s", $user_session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row['count'] != 1) {
        header("Location: cart.php");
        exit();
    }
    $stmt->close();
}

// Initialize variables
$checkout_items = [];
$total_checkout_amount = 0;

// Display messages if any
$message = '';
$message_type = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'success';
    unset($_SESSION['message'], $_SESSION['message_type']);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF validation
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['message'] = "Security token mismatch. Please try again.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }

    // Validate and sanitize inputs
    $required_fields = ['first_name', 'last_name', 'phone_number', 'shipping_address', 'pincode', 'payment_method', 'total_amount'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['message'] = "All fields are required.";
            $_SESSION['message_type'] = "error";
            header("Location: checkout.php");
            exit();
        }
    }

    $first_name = $conn->real_escape_string(trim($_POST['first_name']));
    $last_name = $conn->real_escape_string(trim($_POST['last_name']));
    $phone_number = $conn->real_escape_string(trim($_POST['phone_number']));
    $shipping_address = $conn->real_escape_string(trim($_POST['shipping_address']));
    $pincode = $conn->real_escape_string(trim($_POST['pincode']));
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $total_amount_from_form = (float)$_POST['total_amount'];

    // Validate phone number and pincode
    if (!preg_match('/^[0-9]{10}$/', $phone_number)) {
        $_SESSION['message'] = "Please enter a valid 10-digit phone number.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }

    if (!preg_match('/^[0-9]{6}$/', $pincode)) {
        $_SESSION['message'] = "Please enter a valid 6-digit pincode.";
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

    // Calculate total amount from cart items
    $sql_cart_items = "SELECT c.product_id, c.quantity, c.size, p.name, p.image, p.original_price, p.discount_price
                      FROM cart c
                      JOIN products p ON c.product_id = p.id
                      WHERE c.user_session_id = ?";
    $stmt_cart = $conn->prepare($sql_cart_items);
    $stmt_cart->bind_param("s", $user_session_id);
    $stmt_cart->execute();
    $result_cart = $stmt_cart->get_result();
    
    $calculated_total_amount = 0;
    $cart_items = [];
    
    while ($row_cart = $result_cart->fetch_assoc()) {
        $price_to_use = ($row_cart['discount_price'] < $row_cart['original_price'] && $row_cart['discount_price'] > 0) 
                        ? $row_cart['discount_price'] 
                        : $row_cart['original_price'];
        $calculated_total_amount += ($price_to_use * $row_cart['quantity']);
        $cart_items[] = $row_cart;
    }

    // Add COD fee if applicable
    if ($payment_method === 'COD') {
        $calculated_total_amount += COD_FEE;
    }

    $stmt_cart->close();

    // Verify calculated amount matches form amount
    if (abs($calculated_total_amount - $total_amount_from_form) > 0.01) {
        $_SESSION['message'] = "Total amount mismatch. Please try again.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }

    // Generate unique order ID
    $order_id = 'ORD_' . time() . '_' . bin2hex(random_bytes(4));

    // Handle Razorpay payment
    if ($payment_method === 'Razorpay') {
        try {
            $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
            $razorpay_order = $api->order->create([
                'receipt' => $order_id,
                'amount' => $calculated_total_amount * 100,
                'currency' => CURRENCY,
                'payment_capture' => 1
            ]);
            
            $real_razorpay_order_id = $razorpay_order['id'];

            // Store order details in session for verification
            $_SESSION['razorpay_order'] = [
                'order_id' => $order_id,
                'razorpay_order_id' => $real_razorpay_order_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone_number' => $phone_number,
                'shipping_address' => $shipping_address,
                'pincode' => $pincode,
                'payment_method' => $payment_method,
                'total_amount' => $calculated_total_amount,
                'items' => []
            ];

            // Store all cart items with product details
            foreach ($cart_items as $item) {
                $price_to_use = ($item['discount_price'] < $item['original_price'] && $item['discount_price'] > 0) 
                               ? $item['discount_price'] 
                               : $item['original_price'];
                
                $_SESSION['razorpay_order']['items'][] = [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_image' => $item['image'],
                    'quantity' => $item['quantity'],
                    'price' => $price_to_use,
                    'size' => $item['size']
                ];
            }

            // Return JSON response for Razorpay checkout
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'razorpay',
                'order_id' => $real_razorpay_order_id,
                'amount' => $calculated_total_amount * 100,
                'currency' => CURRENCY,
                'key' => RAZORPAY_KEY_ID,
                'name' => 'Your Store Name',
                'description' => 'Order Payment',
                'prefill' => [
                    'name' => $first_name . ' ' . $last_name,
                    'email' => '',
                    'contact' => $phone_number
                ],
                'notes' => [
                    'address' => $shipping_address,
                    'pincode' => $pincode,
                    'merchant_order_id' => $order_id
                ],
                'theme' => [
                    'color' => '#3399cc'
                ]
            ]);
            exit();
            
        } catch (Exception $e) {
            error_log("Razorpay Order Error: " . $e->getMessage());
            $_SESSION['message'] = "Error processing payment. Please try again or contact support.";
            $_SESSION['message_type'] = "error";
            header("Location: checkout.php");
            exit();
        }
    }

    // Handle COD payment
    $conn->begin_transaction();
    
    try {
        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (order_id, first_name, last_name, phone_number, shipping_address, pincode, payment_method, total_amount, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("sssssssd", $order_id, $first_name, $last_name, $phone_number, $shipping_address, $pincode, $payment_method, $calculated_total_amount);
        
        if (!$stmt->execute()) {
            throw new Exception("Error saving order: " . $stmt->error);
        }
        
        $last_order_id = $conn->insert_id;
        $stmt->close();

        // Insert order items
        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($cart_items as $item) {
            $price_to_use = ($item['discount_price'] < $item['original_price'] && $item['discount_price'] > 0) 
                           ? $item['discount_price'] 
                           : $item['original_price'];
            
            $stmt_items->bind_param("iiids", $last_order_id, $item['product_id'], $item['quantity'], $price_to_use, $item['size']);
            
            if (!$stmt_items->execute()) {
                throw new Exception("Error saving order items: " . $stmt_items->error);
            }
        }
        $stmt_items->close();

        // Clear cart
        $clear_cart_stmt = $conn->prepare("DELETE FROM cart WHERE user_session_id = ?");
        $clear_cart_stmt->bind_param("s", $user_session_id);
        
        if (!$clear_cart_stmt->execute()) {
            throw new Exception("Error clearing cart: " . $clear_cart_stmt->error);
        }
        $clear_cart_stmt->close();

        $conn->commit();

        $_SESSION['message'] = "Order placed successfully! Your Order ID: " . $order_id;
        $_SESSION['message_type'] = "success";
        header("Location: thank_you.php?order_id=" . urlencode($order_id));
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Order Processing Error: " . $e->getMessage());
        $_SESSION['message'] = "Error placing order. Please try again or contact support.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }
}

// Get cart items for display
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
        $price_to_use = ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0) 
                        ? $row['discount_price'] 
                        : $row['original_price'];
        $row['price'] = $price_to_use;
        $checkout_items[] = $row;
        $total_checkout_amount += ($price_to_use * $row['quantity']);
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
    <meta name="description" content="Secure checkout for your order">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" href="../images/Pyaara Circle.png">
    <link rel="apple-touch-icon" href="../images/Pyaara Circle.png">
    <title>Secure Checkout | Your Store Name</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="checkout.css?v=<?= filemtime('checkout.css') ?>">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-<?= $message_type ?>">
                <?= htmlspecialchars($message) ?>
                <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
            </div>
        <?php endif; ?>
        
        <header class="checkout-header">
            <div class="logo-container">
                <a href="index.php">
                    <img src="../images/Pyaara Circle.png" alt="Store Logo" class="logo">
                </a>
            </div>
            <div class="checkout-progress">
                <div class="step active"><span>1</span> Cart</div>
                <div class="step active"><span>2</span> Details</div>
                <div class="step"><span>3</span> Payment</div>
                <div class="step"><span>4</span> Complete</div>
            </div>
        </header>

        <main class="checkout-main">
            <div class="checkout-container">
                <div class="checkout-form-container">
                    <h1 class="form-title"><i class="fas fa-shipping-fast"></i> Shipping & Payment Details</h1>
                    <form action="checkout.php" method="post" id="checkoutForm" class="checkout-form">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        
                        <section class="form-section">
                            <h2 class="section-title"><i class="fas fa-user"></i> Personal Information</h2>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first_name">First Name*</label>
                                    <input type="text" id="first_name" name="first_name" required 
                                           value="<?= isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '' ?>">
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name*</label>
                                    <input type="text" id="last_name" name="last_name" required 
                                           value="<?= isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '' ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Phone Number*</label>
                                <input type="tel" id="phone_number" name="phone_number" required 
                                       pattern="[0-9]{10}" title="10-digit number without spaces or special characters"
                                       value="<?= isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : '' ?>">
                            </div>
                        </section>

                        <section class="form-section">
                            <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Shipping Address</h2>
                            <div class="form-group">
                                <label for="shipping_address">Full Address*</label>
                                <textarea id="shipping_address" name="shipping_address" required><?= isset($_POST['shipping_address']) ? htmlspecialchars($_POST['shipping_address']) : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="pincode">Pincode*</label>
                                <input type="text" id="pincode" name="pincode" required 
                                       pattern="[0-9]{6}" title="6-digit postal code"
                                       value="<?= isset($_POST['pincode']) ? htmlspecialchars($_POST['pincode']) : '' ?>">
                            </div>
                        </section>

                        <section class="form-section">
                            <h2 class="section-title"><i class="fas fa-credit-card"></i> Payment Method</h2>
                            <div class="payment-methods">
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="COD" required checked>
                                    <div class="payment-content">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span>Cash on Delivery (COD)</span>
                                        <small>Additional ₹<?= COD_FEE ?> fee</small>
                                    </div>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="Razorpay" required>
                                    <div class="payment-content">
                                        <i class="fas fa-wallet"></i>
                                        <span>Razorpay</span>
                                        <small>Card/UPI/NetBanking</small>
                                    </div>
                                </label>
                            </div>
                        </section>

                        <input type="hidden" name="total_amount" value="<?= htmlspecialchars(number_format($total_checkout_amount + COD_FEE, 2, '.', '')) ?>" id="totalAmountInput">

                        <div class="form-actions">
                            <a href="cart.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Cart</a>
                            <button type="submit" class="btn-submit" id="placeOrderBtn">
                                <span class="btn-text">Place Order</span>
                                <span class="btn-icon"><i class="fas fa-lock"></i></span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="order-summary-container">
                    <div class="order-summary">
                        <h2 class="summary-title"><i class="fas fa-receipt"></i> Order Summary</h2>
                        
                        <?php if (!empty($checkout_items)): ?>
                            <div class="order-items">
                                <?php foreach ($checkout_items as $item): ?>
                                    <div class="order-item">
                                        <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="item-image">
                                        <div class="item-details">
                                            <h3 class="item-name"><?= htmlspecialchars($item['name']) ?></h3>
                                            <div class="item-meta">
                                                <span class="item-size">Size: <?= htmlspecialchars($item['size'] ?? 'N/A') ?></span>
                                                <span class="item-qty">Qty: <?= htmlspecialchars($item['quantity']) ?></span>
                                            </div>
                                        </div>
                                        <div class="item-price">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="order-totals">
                                <div class="total-row subtotal">
                                    <span>Subtotal:</span>
                                    <span>₹<?= number_format($total_checkout_amount, 2) ?></span>
                                </div>
                                
                                <div class="total-row cod-fee" id="codFee">
                                    <span>COD Fee:</span>
                                    <span>₹<?= COD_FEE ?></span>
                                </div>
                                
                                <div class="total-row grand-total">
                                    <span>Total:</span>
                                    <span id="totalAmount">₹<?= number_format($total_checkout_amount + COD_FEE, 2) ?></span>
                                </div>
                            </div>
                            
                            <div class="payment-security">
                                <div class="security-badge">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Secure Payment Processing</span>
                                </div>
                                <div class="payment-icons">
                                    <i class="fab fa-cc-visa"></i>
                                    <i class="fab fa-cc-mastercard"></i>
                                    <i class="fab fa-cc-amex"></i>
                                    <i class="fab fa-cc-discover"></i>
                                    <i class="fas fa-rupee-sign"></i>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="empty-cart">
                                <i class="fas fa-shopping-cart"></i>
                                <p>No items in your cart</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>

        <footer class="checkout-footer">
            <div class="footer-links">
                <a href="#">Terms of Service</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Refund Policy</a>
                <a href="#">Contact Us</a>
            </div>
            <div class="copyright">
                &copy; <?= date('Y') ?> Your Store Name. All rights reserved.
            </div>
        </footer>
    </div>

    <!-- Payment Processing Modal -->
    <div class="modal-overlay" id="paymentProcessing">
        <div class="modal-container">
            <div class="modal-content">
                <div class="spinner-container">
                    <div class="spinner"></div>
                </div>
                <h3>Processing Payment</h3>
                <p>Please wait while we process your payment securely.</p>
                <p class="small-text">Do not refresh or close this window.</p>
            </div>
        </div>
    </div>

    <script>
        // Constants
        const baseTotal = <?= $total_checkout_amount ?>;
        const codFee = <?= COD_FEE ?>;
        
        // Update totals based on payment method
        function updateTotals() {
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
        
        // Payment method change event
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', updateTotals);
        });
        
        // Initialize totals
        updateTotals();

        // Form submission handler
        document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const processingModal = document.getElementById('paymentProcessing');
            
            // Show processing modal
            processingModal.style.display = 'flex';
            
            try {
                if (paymentMethod === 'Razorpay') {
                    // Submit form via AJAX to get Razorpay order details
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    
                    const data = await response.json();
                    
                    if (data.status === 'razorpay') {
                        // Initialize Razorpay checkout
                        const options = {
                            key: data.key,
                            amount: data.amount,
                            currency: data.currency,
                            name: data.name,
                            description: data.description,
                            order_id: data.order_id,
                            handler: function(response) {
                                // Create form to submit verification data
                                const verifyForm = document.createElement('form');
                                verifyForm.method = 'POST';
                                verifyForm.action = 'verify_razorpay.php';
                                
                                // Add response fields
                                const fields = {
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_order_id: response.razorpay_order_id,
                                    razorpay_signature: response.razorpay_signature,
                                    csrf_token: '<?= $_SESSION['csrf_token'] ?>'
                                };
                                
                                for (const [name, value] of Object.entries(fields)) {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = name;
                                    input.value = value;
                                    verifyForm.appendChild(input);
                                }
                                
                                document.body.appendChild(verifyForm);
                                verifyForm.submit();
                            },
                            prefill: data.prefill,
                            notes: data.notes,
                            theme: data.theme,
                            modal: {
                                ondismiss: function() {
                                    processingModal.style.display = 'none';
                                    window.location.href = 'checkout.php?payment_cancelled=1';
                                }
                            }
                        };
                        
                        const rzp = new Razorpay(options);
                        
                        // Hide modal when Razorpay opens
                        rzp.on('modal.opened', function() {
                            processingModal.style.display = 'none';
                        });
                        
                        rzp.open();
                    } else {
                        throw new Error('Unexpected response from server');
                    }
                } else {
                    // For COD, submit normally
                    form.submit();
                }
            } catch (error) {
                console.error('Payment Error:', error);
                processingModal.style.display = 'none';
                
                // Show error message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-error';
                alertDiv.innerHTML = `
                    <p>Error processing payment. Please try again.</p>
                    <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                `;
                
                document.querySelector('.container').prepend(alertDiv);
                
                // Scroll to top
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    </script>
</body>
</html>