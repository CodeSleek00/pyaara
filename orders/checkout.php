<?php
session_start();
require('../vendor/autoload.php');
use Razorpay\Api\Api;
include 'db_connect.php';

// Configuration
$cod_fee = 49;
$razorpay_key_id = 'rzp_test_Ox3tDG4PAJscLL'; // Replace with your key
$razorpay_key_secret = '8y5toKVa5TXJ2zfOUvXaZnPs'; // Replace with your secret

// Initialize Razorpay
$api = new Api($razorpay_key_id, $razorpay_key_secret);

// Get cart items
$user_session_id = session_id();
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

while ($row = $result->fetch_assoc()) {
    $price_to_use = ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0) 
                   ? $row['discount_price'] 
                   : $row['original_price'];
    $row['price'] = $price_to_use;
    $checkout_items[] = $row;
    $total_checkout_amount += ($price_to_use * $row['quantity']);
}
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $shipping_address = $conn->real_escape_string($_POST['shipping_address']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $total_amount_from_form = (float)$_POST['total_amount'];

    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($phone_number) || empty($shipping_address)) {
        $_SESSION['message'] = "All fields are required.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }

    // Calculate actual total
    $calculated_total_amount = $total_checkout_amount;
    if ($payment_method === 'COD') {
        $calculated_total_amount += $cod_fee;
    }

    // Security check
    if (abs($calculated_total_amount - $total_amount_from_form) > 0.01) {
        $_SESSION['message'] = "Total amount mismatch. Please try again.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }

    // Generate order ID
    $order_id = 'ORDER_' . uniqid();

    // For Razorpay payments
    if ($payment_method === 'Razorpay') {
        // Save order details in session
        $_SESSION['razorpay_order'] = [
            'order_id' => $order_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone_number' => $phone_number,
            'shipping_address' => $shipping_address,
            'payment_method' => $payment_method,
            'total_amount' => $calculated_total_amount,
            'items' => $checkout_items
        ];

        // Create Razorpay order
        $razorpay_order = $api->order->create([
            'receipt' => $order_id,
            'amount' => $calculated_total_amount * 100, // in paise
            'currency' => 'INR',
            'notes' => [
                'merchant_order_id' => $order_id,
                'user_session' => $user_session_id
            ]
        ]);

        // Return JSON response for AJAX handling
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'razorpay',
            'order_id' => $razorpay_order['id'],
            'amount' => $calculated_total_amount * 100,
            'key' => $razorpay_key_id,
            'name' => 'Your Store Name',
            'description' => 'Order Payment',
            'prefill' => [
                'name' => $first_name . ' ' . $last_name,
                'contact' => $phone_number
            ],
            'notes' => [
                'merchant_order_id' => $order_id
            ]
        ]);
        exit();
    } 
    // For COD payments
    else {
        // Process COD order directly
        $stmt = $conn->prepare("INSERT INTO orders (order_id, first_name, last_name, phone_number, shipping_address, payment_method, total_amount, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("ssssssd", $order_id, $first_name, $last_name, $phone_number, $shipping_address, $payment_method, $calculated_total_amount);

        if ($stmt->execute()) {
            $last_order_id = $conn->insert_id;
            
            // Insert order items
            $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");
            foreach ($checkout_items as $item) {
                $size = $item['size'] ?? '';
                $stmt_items->bind_param("iiids", $last_order_id, $item['product_id'], $item['quantity'], $item['price'], $size);
                $stmt_items->execute();
            }
            
            // Clear cart
            $clear_cart = $conn->prepare("DELETE FROM cart WHERE user_session_id = ?");
            $clear_cart->bind_param("s", $user_session_id);
            $clear_cart->execute();
            
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        .payment-method {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .payment-method.selected {
            border-color: #4CAF50;
            background-color: #f8f9fa;
        }
        .order-summary-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <h2 class="mb-4">Checkout</h2>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message_type'] === 'error' ? 'danger' : 'success'; ?>">
                        <?php echo $_SESSION['message']; unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                    </div>
                <?php endif; ?>
                
                <form id="checkoutForm" method="post">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5>Shipping Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Shipping Address</label>
                                <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5>Payment Method</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check payment-method mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked>
                                <label class="form-check-label" for="cod">
                                    <strong>Cash on Delivery (COD)</strong>
                                    <span class="d-block text-muted">Pay when you receive your order (₹<?php echo $cod_fee; ?> fee)</span>
                                </label>
                            </div>
                            <div class="form-check payment-method">
                                <input class="form-check-input" type="radio" name="payment_method" id="razorpay" value="Razorpay">
                                <label class="form-check-label" for="razorpay">
                                    <strong>Razorpay</strong>
                                    <span class="d-block text-muted">Pay securely with Credit/Debit Card, UPI, or Net Banking</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="total_amount" id="totalAmountInput" value="<?php echo $total_checkout_amount + $cod_fee; ?>">
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100" id="placeOrderBtn">Place Order</button>
                </form>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($checkout_items as $item): ?>
                            <div class="order-summary-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <small class="text-muted">Size: <?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?> | Qty: <?php echo $item['quantity']; ?></small>
                                    </div>
                                    <div>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="order-summary-item">
                            <div class="d-flex justify-content-between">
                                <div>Subtotal</div>
                                <div>₹<?php echo number_format($total_checkout_amount, 2); ?></div>
                            </div>
                        </div>
                        
                        <div class="order-summary-item" id="codFee">
                            <div class="d-flex justify-content-between">
                                <div>COD Fee</div>
                                <div>₹<?php echo $cod_fee; ?></div>
                            </div>
                        </div>
                        
                        <div class="order-summary-item pt-3">
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <div>Total</div>
                                <div id="totalAmount">₹<?php echo number_format($total_checkout_amount + $cod_fee, 2); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update total when payment method changes
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const codFee = <?php echo $cod_fee; ?>;
                const subtotal = <?php echo $total_checkout_amount; ?>;
                
                if (this.value === 'COD') {
                    document.getElementById('codFee').style.display = 'block';
                    document.getElementById('totalAmount').textContent = '₹' + (subtotal + codFee).toFixed(2);
                    document.getElementById('totalAmountInput').value = (subtotal + codFee).toFixed(2);
                } else {
                    document.getElementById('codFee').style.display = 'none';
                    document.getElementById('totalAmount').textContent = '₹' + subtotal.toFixed(2);
                    document.getElementById('totalAmountInput').value = subtotal.toFixed(2);
                }
            });
        });

        // Handle form submission
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (paymentMethod === 'Razorpay') {
                // Show loading state
                const btn = document.getElementById('placeOrderBtn');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                
                // Submit form via AJAX
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
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
                            handler: function(response) {
                                // Create a form to submit payment details
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = 'verify_razorpay.php';
                                
                                const fields = {
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_order_id: response.razorpay_order_id,
                                    razorpay_signature: response.razorpay_signature
                                };
                                
                                for (const [name, value] of Object.entries(fields)) {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = name;
                                    input.value = value;
                                    form.appendChild(input);
                                }
                                
                                document.body.appendChild(form);
                                form.submit();
                            },
                            prefill: data.prefill,
                            notes: data.notes,
                            theme: {
                                color: '#3399cc'
                            },
                            modal: {
                                ondismiss: function() {
                                    // Re-enable button if payment is cancelled
                                    btn.disabled = false;
                                    btn.textContent = 'Place Order';
                                }
                            }
                        };
                        
                        const rzp = new Razorpay(options);
                        rzp.open();
                    } else {
                        throw new Error('Unexpected response from server');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error processing payment. Please try again.');
                    btn.disabled = false;
                    btn.textContent = 'Place Order';
                });
            } else {
                // For COD, submit normally
                form.submit();
            }
        });
    </script>
</body>
</html>