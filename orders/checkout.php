<?php
ob_start();
session_start();
include 'db_connect.php';
error_reporting(0);

$user_session_id = session_id();
$cod_fee = 49;
$checkout_items = [];
$total_checkout_amount = 0;

// Fetch cart items
$stmt = $conn->prepare("SELECT c.product_id, c.quantity, c.size, p.name, p.image, p.original_price, p.discount_price
                       FROM cart c JOIN products p ON c.product_id = p.id
                       WHERE c.user_session_id = ?");
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
    $price = ($row['discount_price'] > 0 && $row['discount_price'] < $row['original_price']) 
           ? $row['discount_price'] 
           : $row['original_price'];
    $row['price'] = $price;
    $checkout_items[] = $row;
    $total_checkout_amount += $price * $row['quantity'];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Pyaara</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }
        .container {
            max-width: 1140px;
            margin: auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        .checkout-form, .summary-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            padding: 25px;
        }
        .checkout-form {
            flex: 2;
        }
        .summary-box {
            flex: 1;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .radio-group {
            margin-top: 10px;
        }
        .btn {
            margin-top: 20px;
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn:hover {
            background: #1e40af;
        }
        .item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #ddd;
            padding: 10px 0;
        }
        .item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 5px;
        }
        .item-name {
            flex-grow: 1;
        }
        .total {
            font-size: 18px;
            font-weight: 600;
            margin-top: 15px;
            border-top: 2px solid #2563eb;
            padding-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="checkout-form">
        <h1>Shipping & Payment</h1>
        <form id="checkoutForm">
            <label>First Name</label>
            <input type="text" name="first_name" required>
            
            <label>Last Name</label>
            <input type="text" name="last_name" required>
            
            <label>Phone Number</label>
            <input type="tel" name="phone_number" pattern="[0-9]{10}" required>
            
            <label>Address</label>
            <textarea name="shipping_address" required></textarea>

            <label>Payment Method</label>
            <div class="radio-group">
                <input type="radio" name="payment_method" value="COD" checked> Cash on Delivery (₹<?= $cod_fee ?>)<br>
                <input type="radio" name="payment_method" value="Razorpay"> Razorpay
            </div>

            <input type="hidden" name="total_amount" id="totalAmountInput" value="<?= $total_checkout_amount + $cod_fee ?>">
            <button type="submit" class="btn" id="checkoutBtn">Place Order</button>
        </form>
    </div>

    <div class="summary-box">
        <h1>Order Summary</h1>
        <?php foreach ($checkout_items as $item): ?>
            <div class="item">
                <div><img src="uploads/<?= $item['image'] ?>"></div>
                <div class="item-name"><?= $item['name'] ?><br>Qty: <?= $item['quantity'] ?></div>
                <div>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
            </div>
        <?php endforeach; ?>
        <div class="item">
            <div class="item-name">Subtotal</div>
            <div>₹<?= number_format($total_checkout_amount, 2) ?></div>
        </div>
        <div class="item" id="codFee">
            <div class="item-name">COD Fee</div>
            <div>₹<?= $cod_fee ?></div>
        </div>
        <div class="total" id="totalAmount">Total: ₹<?= number_format($total_checkout_amount + $cod_fee, 2) ?></div>
    </div>
</div>
<script>
    const baseTotal = <?= $total_checkout_amount ?>;
    const codFee = <?= $cod_fee ?>;

    function updateTotal() {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const totalElement = document.getElementById("totalAmount");
        const totalInput = document.getElementById("totalAmountInput");
        const codFeeRow = document.getElementById("codFee");

        if (paymentMethod === 'COD') {
            codFeeRow.style.display = 'flex';
            totalElement.textContent = "Total: ₹" + (baseTotal + codFee).toFixed(2);
            totalInput.value = (baseTotal + codFee).toFixed(2);
        } else {
            codFeeRow.style.display = 'none';
            totalElement.textContent = "Total: ₹" + baseTotal.toFixed(2);
            totalInput.value = baseTotal.toFixed(2);
        }
    }

    document.querySelectorAll('input[name="payment_method"]').forEach(input => {
        input.addEventListener('change', updateTotal);
    });

    updateTotal();

    document.getElementById("checkoutForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const form = new FormData(this);
        const method = form.get("payment_method");

        if (method === "Razorpay") {
            fetch("razorpay_api.php", {
                method: "POST",
                body: form
            })
            .then(res => res.json())
            .then(data => {
                const options = {
                    key: data.key,
                    amount: data.amount,
                    currency: data.currency,
                    name: data.name,
                    description: data.description,
                    order_id: data.order_id,
                    handler: function (response) {
                        const verifyForm = document.createElement("form");
                        verifyForm.method = "POST";
                        verifyForm.action = "verify_razorpay.php";
                        for (const key in response) {
                            const input = document.createElement("input");
                            input.type = "hidden";
                            input.name = key;
                            input.value = response[key];
                            verifyForm.appendChild(input);
                        }
                        document.body.appendChild(verifyForm);
                        verifyForm.submit();
                    }
                };
                const rzp = new Razorpay(options);
                rzp.open();
            })
            .catch(err => {
                alert("Payment failed: " + err.message);
            });
        } else {
            this.submit(); // COD
        }
    });
</script>
</body>
</html>
