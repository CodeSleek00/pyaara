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

// --- 1. FETCH CART DATA ---
$checkout_items = [];
$total_checkout_amount = 0;

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
                        ? $row['discount_price'] : $row['original_price'];
        $row['price'] = $price_to_use;
        $checkout_items[] = $row;
        $total_checkout_amount += ($price_to_use * $row['quantity']);
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['message'] = "Your cart is empty.";
        header("Location: index.php");
        exit();
    }
}
$stmt->close();

// --- 2. PROCESS POST REQUEST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Security token mismatch.']);
        exit();
    }

    $payment_method = $_POST['payment_method'];
    $calculated_total = $total_checkout_amount + ($payment_method === 'COD' ? COD_FEE : 0);
    
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
                'amount' => round($calculated_total * 100),
                'currency' => CURRENCY,
                'payment_capture' => 1
            ]);

            $_SESSION['razorpay_order'] = [
                'order_id' => $order_id,
                'razorpay_order_id' => $razorpay_order['id'],
                'post_data' => $_POST,
                'items' => $checkout_items,
                'total_amount' => $calculated_total
            ];

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'razorpay',
                'order_id' => $razorpay_order['id'],
                'amount' => round($calculated_total * 100),
                'currency' => CURRENCY,
                'key' => RAZORPAY_KEY_ID,
                'name' => 'Pyaara',
                'prefill' => ['name' => $first_name . ' ' . $last_name, 'contact' => $phone_number]
            ]);
            exit(); 
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit();
        }
    }

    /* // --- COD PROCESSING BLOCK (UNCOMMENT TO ENABLE) ---
    if ($payment_method === 'COD') {
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO orders (user_id, order_id, first_name, last_name, phone_number, shipping_address, pincode, payment_method, total_amount, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("isssssssd", $_SESSION['user_id'], $order_id, $first_name, $last_name, $phone_number, $shipping_address, $pincode, $payment_method, $calculated_total);
            $stmt->execute();
            $last_id = $conn->insert_id;

            $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");
            foreach ($checkout_items as $item) {
                $stmt_items->bind_param("iiids", $last_id, $item['product_id'], $item['quantity'], $item['price'], $item['size']);
                $stmt_items->execute();
            }

            $conn->prepare("DELETE FROM cart WHERE user_session_id = ?")->execute([$user_session_id]);
            $conn->commit();
            header("Location: thank_you.php?order_id=" . urlencode($order_id));
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['message'] = "Order Failed: " . $e->getMessage();
            header("Location: checkout.php");
            exit();
        }
    }
    */
}

$message = $_SESSION['message'] ?? '';
$message_type = $_SESSION['message_type'] ?? 'success';
unset($_SESSION['message'], $_SESSION['message_type']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout | Pyaara</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="checkout.css?v=<?= time() ?>">
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
                <a href="../index.php"><img src="../images/Pyaara Circle.png" alt="Logo" class="logo"></a>
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
                    <h1 class="form-title"><i class="fas fa-shipping-fast"></i> Shipping & Payment</h1>
                    <form action="checkout.php" method="post" id="checkoutForm" class="checkout-form">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        
                        <section class="form-section">
                            <h2 class="section-title"><i class="fas fa-user"></i> Personal Information</h2>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>First Name*</label>
                                    <input type="text" name="first_name" required>
                                </div>
                                <div class="form-group">
                                    <label>Last Name*</label>
                                    <input type="text" name="last_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Phone Number*</label>
                                <input type="tel" name="phone_number" required pattern="[0-9]{10}">
                            </div>
                        </section>

                        <section class="form-section">
                            <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Shipping Address</h2>
                            <div class="form-group">
                                <label>Full Address*</label>
                                <textarea name="shipping_address" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Pincode*</label>
                                <input type="text" name="pincode" required pattern="[0-9]{6}">
                            </div>
                        </section>

                        <section class="form-section">
                            <h2 class="section-title"><i class="fas fa-credit-card"></i> Payment Method</h2>
                            <div class="payment-methods">
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="Razorpay" checked>
                                    <div class="payment-content">
                                        <i class="fas fa-wallet"></i>
                                        <span>Online Payment</span>
                                        <small>Card/UPI/NetBanking</small>
                                    </div>
                                </label>
                            </div>
                        </section>

                        <div class="form-actions">
                            <a href="cart.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back</a>
                            <button type="submit" class="btn-submit" id="placeOrderBtn">
                                <span class="btn-text">Pay Now</span>
                                <span class="btn-icon"><i class="fas fa-lock"></i></span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="order-summary-container">
                    <div class="order-summary">
                        <h2 class="summary-title"><i class="fas fa-receipt"></i> Summary</h2>
                        <div class="order-items">
                            <?php foreach ($checkout_items as $item): ?>
                            <div class="order-item">
                                <img src="uploads/<?= htmlspecialchars($item['image']) ?>" class="item-image">
                                <div class="item-details">
                                    <h3 class="item-name"><?= htmlspecialchars($item['name']) ?></h3>
                                    <small>Size: <?= htmlspecialchars($item['size'] ?? 'N/A') ?> | Qty: <?= $item['quantity'] ?></small>
                                </div>
                                <div class="item-price">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="order-totals">
                            <div class="total-row"><span>Subtotal:</span><span>₹<?= number_format($total_checkout_amount, 2) ?></span></div>
                            <div class="total-row" id="codFeeRow" style="display:none;"><span>COD Fee:</span><span>₹<?= COD_FEE ?></span></div>
                            <div class="total-row grand-total"><span>Total:</span><span id="totalDisplay">₹<?= number_format($total_checkout_amount, 2) ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="modal-overlay" id="paymentProcessing" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; justify-content:center; align-items:center; color:white; flex-direction:column;">
        <div class="spinner" style="border:4px solid #f3f3f3; border-top:4px solid #3498db; border-radius:50%; width:40px; height:40px; animation:spin 1s linear infinite;"></div>
        <p style="margin-top:15px;">Securely processing payment...</p>
    </div>

    <style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>

    <script>
        const baseTotal = <?= $total_checkout_amount ?>;
        const codFee = <?= COD_FEE ?>;
        const form = document.getElementById('checkoutForm');
        const processingModal = document.getElementById('paymentProcessing');

        function updateDisplay() {
            const selected = document.querySelector('input[name="payment_method"]:checked');
            if(!selected) return;
            
            const method = selected.value;
            const feeRow = document.getElementById('codFeeRow');
            const totalDisp = document.getElementById('totalDisplay');
            
            if (method === 'COD') {
                feeRow.style.display = 'flex';
                totalDisp.textContent = '₹' + (baseTotal + codFee).toFixed(2);
            } else {
                feeRow.style.display = 'none';
                totalDisp.textContent = '₹' + baseTotal.toFixed(2);
            }
        }

        document.querySelectorAll('input[name="payment_method"]').forEach(r => r.addEventListener('change', updateDisplay));
        updateDisplay();

        form.addEventListener('submit', async function(e) {
            const selected = document.querySelector('input[name="payment_method"]:checked');
            if(!selected) { alert("Please select a payment method"); e.preventDefault(); return; }

            const method = selected.value;
            if (method === 'COD') return; // Submit normally if uncommented in HTML

            e.preventDefault();
            processingModal.style.display = 'flex';

            try {
                const response = await fetch('checkout.php', {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {'Accept': 'application/json'}
                });
                
                const data = await response.json();
                if (data.status === 'razorpay') {
                    const options = {
                        key: data.key,
                        amount: data.amount,
                        order_id: data.order_id,
                        name: "Pyaara",
                        handler: function(res) {
                            window.location.href = `verify_razorpay.php?razorpay_payment_id=${res.razorpay_payment_id}&razorpay_order_id=${res.razorpay_order_id}&razorpay_signature=${res.razorpay_signature}`;
                        },
                        modal: { ondismiss: function() { processingModal.style.display = 'none'; } }
                    };
                    new Razorpay(options).open();
                } else {
                    alert(data.message || "Error starting payment");
                    processingModal.style.display = 'none';
                }
            } catch (err) {
                console.error(err);
                processingModal.style.display = 'none';
            }
        });
    </script>
</body>
</html>