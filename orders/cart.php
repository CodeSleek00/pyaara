<?php
include 'db_connect.php';

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

$user_session_id = session_id();
$cart_items = [];
$total_cart_amount = 0;

// Handle quantity update or item removal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $cart_id = (int)$_POST['cart_id'];
        $new_quantity = (int)$_POST['quantity'];
        if ($new_quantity > 0) {
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_session_id = ?");
            $stmt->bind_param("iis", $new_quantity, $cart_id, $user_session_id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['message'] = "Cart updated successfully!";
        } else {
            // If quantity is 0 or less, remove the item
            $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_session_id = ?");
            $stmt->bind_param("is", $cart_id, $user_session_id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['message'] = "Item removed from cart!";
        }
    } elseif (isset($_POST['remove_item'])) {
        $cart_id = (int)$_POST['cart_id'];
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_session_id = ?");
        $stmt->bind_param("is", $cart_id, $user_session_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Item removed from cart!";
    }
    header("Location: cart.php"); // Redirect to prevent re-submission
    exit();
}

// Fetch cart items
$sql = "SELECT c.id as cart_id, c.quantity, c.size, p.name, p.image, p.original_price, p.discount_price
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_session_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_session_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $price_to_use = ($row['discount_price'] < $row['original_price']) ? $row['discount_price'] : $row['original_price'];
        $row['current_price'] = $price_to_use;
        $cart_items[] = $row;
        $total_cart_amount += ($price_to_use * $row['quantity']);
    }
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="images/Pyaara Circle.png">
    <link rel="apple-touch-icon" href="images/Pyaara Circle.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8f8f8;
        }
        .price {
            color: #d10000;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #d10000;
            color: white;
        }
        .btn-primary:hover {
            background-color: #b00000;
        }
        .btn-remove {
            color: #d10000;
        }
        .message.success {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        .cart-item {
            border-bottom: 1px solid #ffc107;
        }
        .cart-summary {
            background-color: #fff3cd;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Mobile Header -->
    <header class="md:hidden bg-white shadow-sm py-4 px-4 sticky top-0 z-10">
        <div class="flex items-center justify-between">
            <a href="javascript:history.back()" class="text-gray-700">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Your Cart</h1>
            <div class="w-6"></div> <!-- Spacer for alignment -->
        </div>
    </header>

    <!-- Desktop Header -->
    <header class="hidden md:block bg-white shadow-sm py-6 px-8">
         <div class="flex items-center justify-between">
            <a href="javascript:history.back()" class="text-gray-700">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Your Cart</h1>
            <div class="w-6"></div> <!-- Spacer for alignment -->
       
    </header>

    <div class="container mx-auto px-4 py-6 max-w-6xl">
        <?php if ($message): ?>
            <div class="message success mb-6 p-4 rounded">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($cart_items)): ?>
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Cart Items -->
                <div class="md:w-2/3 bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="divide-y divide-yellow-200">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="cart-item p-4 flex flex-col sm:flex-row gap-4">
                                <div class="sm:w-1/4">
                                    <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                         class="w-full h-auto rounded-md object-cover">
                                </div>
                                <div class="sm:w-2/4 flex flex-col justify-between">
                                    <div>
                                        <h4 class="font-bold text-lg text-gray-800"><?php echo htmlspecialchars($item['name']); ?></h4>
                                        <p class="text-gray-600">Price: <span class="price">₹<?php echo htmlspecialchars(number_format($item['current_price'], 2)); ?></span></p>
                                        <?php if ($item['size']): ?>
                                            <p class="text-gray-600">Size: <?php echo htmlspecialchars($item['size']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="cart-item-actions mt-4 flex flex-col sm:flex-row gap-2">
                                        <form action="cart.php" method="post" class="flex items-center gap-2">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                            <label class="text-gray-600">Qty:</label>
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" 
                                                   class="w-16 p-1 border border-gray-300 rounded text-center"
                                                   onchange="this.form.submit()">
                                            <input type="hidden" name="update_quantity" value="1">
                                        </form>
                                        <form action="cart.php" method="post">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                            <button type="submit" name="remove_item" 
                                                    class="btn-remove text-sm text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash-alt mr-1"></i>Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="sm:w-1/4 flex sm:flex-col justify-between items-end">
                                    <p class="font-bold text-lg price">
                                        ₹<?php echo htmlspecialchars(number_format($item['current_price'] * $item['quantity'], 2)); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="md:w-1/3">
                    <div class="cart-summary p-6 rounded-lg shadow-sm sticky top-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h3>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">₹<?php echo htmlspecialchars(number_format($total_cart_amount, 2)); ?></span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Shipping (If You chose Razorpay)</span>
                            <span class="font-medium">Free</span>
                        </div>
                        <div class="border-t border-yellow-200 my-4"></div>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-lg font-bold text-gray-800">Total</span>
                            <span class="text-lg font-bold price">₹<?php echo htmlspecialchars(number_format($total_cart_amount, 2)); ?></span>
                        </div>
                        <a href="checkout.php" class="btn btn-primary w-full py-3 rounded-lg font-bold text-center block hover:bg-red-700 transition">
                            Proceed to Checkout
                        </a>
                        <a href="../index.php" class="block text-center mt-4 text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-shopping-cart text-yellow-500 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Your cart is empty</h3>
                <p class="text-gray-600 mb-6">Looks like you haven't added any items to your cart yet.</p>
                <a href="../index.php" class="btn-primary inline-block px-8 py-3 rounded-lg font-bold hover:bg-red-700 transition">
                    Start Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // You can add any JavaScript functionality here if needed
    </script>
</body>
</html>
<?php $conn->close(); ?>