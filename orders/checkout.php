<?php
session_start();
ob_start();

// Connect to DB
include 'db_connect.php';

// Replace with your Razorpay credentials
$razorpay_key = "rzp_live_pA6jgjncp78sq7";
$razorpay_secret = "YOUR_SECRET_KEY"; // NEVER expose this in frontend

// Simulate total amount from cart/session (in paisa)
$total_amount = 44900; // ₹449.00

// Create Razorpay Order via API
$orderData = [
    'receipt' => 'RCPT_' . rand(1000, 99999),
    'amount' => $total_amount,
    'currency' => 'INR',
    'payment_capture' => 1
];

$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$razorpay_key:$razorpay_secret");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
$order = json_decode($response, true);
curl_close($ch);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout | Pyaara</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      margin: 0;
      background: #f4f4f8;
      color: #333;
    }
    .container {
      max-width: 600px;
      margin: 3rem auto;
      padding: 2rem;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
    }
    label {
      display: block;
      margin-top: 1rem;
      font-weight: 600;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    .btn {
      width: 100%;
      margin-top: 2rem;
      padding: 12px;
      background: #f37254;
      border: none;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .btn:hover {
      background: #d95b3f;
    }
    .cod-btn {
      background: #555;
      margin-top: 1rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Checkout</h2>
    <form id="checkout-form">
      <label>Full Name</label>
      <input type="text" id="name" required>

      <label>Email</label>
      <input type="email" id="email" required>

      <label>Phone Number</label>
      <input type="tel" id="contact" required>

      <label>Amount</label>
      <input type="text" value="₹<?php echo $total_amount / 100; ?>" disabled>

      <button type="button" class="btn" onclick="payNow()">Pay with Razorpay</button>
      <button type="submit" class="btn cod-btn">Cash on Delivery</button>
    </form>
  </div>

  <script>
    function payNow() {
      var options = {
        "key": "<?php echo $razorpay_key; ?>",
        "amount": "<?php echo $total_amount; ?>",
        "currency": "INR",
        "name": "Pyaara",
        "description": "T-Shirt Purchase",
        "image": "https://yourdomain.com/logo.png",
        "order_id": "<?php echo $order['id']; ?>",
        "handler": function (response) {
          // Submit to PHP for verification
          var form = document.createElement('form');
          form.method = 'POST';
          form.action = 'verify_razorpay.php';

          var fields = {
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_order_id: response.razorpay_order_id,
            razorpay_signature: response.razorpay_signature,
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            contact: document.getElementById('contact').value
          };

          for (var key in fields) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = fields[key];
            form.appendChild(input);
          }

          document.body.appendChild(form);
          form.submit();
        },
        "prefill": {
          "name": document.getElementById('name').value,
          "email": document.getElementById('email').value,
          "contact": document.getElementById('contact').value
        },
        "theme": {
          "color": "#F37254"
        }
      };
      var rzp = new Razorpay(options);
      rzp.open();
    }

    // COD form submit
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
      e.preventDefault();
      // You can handle COD here (e.g., send details to backend PHP script)
      alert("COD order placed! You can now process it from the backend.");
    });
  </script>
</body>
</html>
