<?php
session_start();
include 'db_connect.php';

$key_id     = "rzp_live_pA6jgjncp78sq7";
$key_secret = "YOUR_SECRET_KEY"; // 🔐 Replace with your Razorpay Secret Key

$cod_fee = 49;
$user_sid = session_id();
$cart_items = [];
$total_amount = 0;

// Fetch cart
$sql = "SELECT c.quantity,c.size,p.name,p.image,p.original_price,p.discount_price,p.id as pid
        FROM cart c JOIN products p ON c.product_id=p.id
        WHERE c.user_session_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_sid);
$stmt->execute();
$res = $stmt->get_result();

while($r = $res->fetch_assoc()){
  $price = ($r['discount_price'] < $r['original_price'] && $r['discount_price']>0)
           ? $r['discount_price'] : $r['original_price'];
  $r['price'] = $price;
  $cart_items[] = $r;
  $total_amount += $price*$r['quantity'];
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $fn = $_POST['first_name'];
  $ln = $_POST['last_name'];
  $phone = $_POST['phone_number'];
  $addr = $_POST['shipping_address'];
  $pm = $_POST['payment_method'];
  $receipt = uniqid('ORDER_');

  if($pm==='COD'){
    $fa = $total_amount + $cod_fee;
    $stmt = $conn->prepare("INSERT INTO orders(order_id,first_name,last_name,phone_number,shipping_address,payment_method,total_amount)
                             VALUES(?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssd",$receipt,$fn,$ln,$phone,$addr,$pm,$fa);
    $stmt->execute();
    $order_db = $stmt->insert_id;

    foreach($cart_items as $item){
      $s = $item['size']?:'N/A';
      $stmt2 = $conn->prepare("INSERT INTO order_items(order_id,product_id,quantity,price,size)
                               VALUES(?,?, ?, ?, ?)");
      $stmt2->bind_param("iiids",$order_db,$item['pid'],$item['quantity'],$item['price'],$s);
      $stmt2->execute();
    }
    $conn->query("DELETE FROM cart WHERE user_session_id='$user_sid'");
    header("Location: thank_you.php?order_id=$receipt");
    exit();
  }

  // Razorpay -> create order via cURL
  $amt = $total_amount*100;
  $ord = ['receipt'=>$receipt,'amount'=>$amt,'currency'=>'INR','payment_capture'=>1];
  $ch = curl_init('https://api.razorpay.com/v1/orders');
  curl_setopt($ch, CURLOPT_USERPWD, "$key_id:$key_secret");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ord));
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
  $resp = curl_exec($ch); curl_close($ch);
  $data = json_decode($resp,true);
  if(!isset($data['id'])) die("Razorpay order creation failed");

  $_SESSION['rzp'] = ['receipt'=>$receipt,'rzp_order_id'=>$data['id']];

  // Razorpay Checkout
  echo "<script src='https://checkout.razorpay.com/v1/checkout.js'
    data-key='$key_id'
    data-amount='$amt'
    data-currency='INR'
    data-order_id='{$data['id']}'
    data-name='Pyaara Store'
    data-prefill.name='$fn $ln'
    data-prefill.contact='$phone'
    data-prefill.email=''
    data-notes.shopping_order_id='$receipt'
    data-theme.color='#3399cc'></script>";
  exit();
}
?>
<!DOCTYPE html><html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial‑scale=1.0">
<title>Checkout</title>
<style>
body { font-family: Arial, sans‑serif; background:#f5f5f5; margin:0;padding:20px; }
.container { max‑width:900px; margin:auto; background:#fff; padding:20px; border‑radius:8px; box‑shadow:0 2px 6px rgba(0,0,0,0.1); }
h2{text-align:center;color:#333;}
.form‑group{margin-bottom:15px;}
label{display:block;margin-bottom:5px;font-weight:bold;}
input[type=text], input[type=tel], textarea{width:100%;padding:10px;border:1px solid #ccc;border‑radius:4px;}
.order‑summary{background:#fafafa;padding:15px;margin-top:20px;border:1px solid #eee;border‑radius:4px;}
.order-item{display:flex;align-items:center;margin-bottom:10px;}
.order-item img{width:50px;height:50px;object-fit:cover;border-radius:4px;margin-right:10px;}
.total{font-weight:bold;font-size:1.2em;margin-top:10px;}
button{padding:12px 20px;background:#3399cc;color:#fff;border:none;border‑radius:4px;cursor:pointer;font-size:1em;}
button:hover{background:#287b9b;}
</style>
</head><body>
<div class="container">
  <h2>Checkout</h2>
  <form method="POST">
    <div class="form-group"><label>First Name</label><input name="first_name" type="text" required></div>
    <div class="form-group"><label>Last Name</label><input name="last_name" type="text" required></div>
    <div class="form-group"><label>Phone</label><input name="phone_number" type="tel" pattern="[0-9]{10}" required></div>
    <div class="form-group"><label>Shipping Address</label><textarea name="shipping_address" rows="3" required></textarea></div>

    <div class="form-group">
      <label><input type="radio" name="payment_method" value="COD" checked> Cash on Delivery (₹<?php echo $cod_fee;?> extra)</label><br>
      <label><input type="radio" name="payment_method" value="Razorpay"> Razorpay</label>
    </div>
    <button type="submit">Place Order</button>
  </form>

  <div class="order-summary">
    <h3>Your Order</h3>
    <?php foreach($cart_items as $it): ?>
      <div class="order-item">
        <img src="uploads/<?php echo htmlspecialchars($it['image']);?>" alt="">
        <div><?php echo htmlspecialchars($it['name']);?> × <?php echo $it['quantity'];?><br>₹<?php echo number_format($it['price']*$it['quantity'],2);?></div>
      </div>
    <?php endforeach; ?>
    <div class="total">Subtotal: ₹<?php echo number_format($total_amount,2);?></div>
    <?php if(count($cart_items)>0): ?>
      <div class="total">Total: ₹<?php echo number_format($total_amount + $cod_fee * (isset($_POST['payment_method']) && $_POST['payment_method']==='COD'),2);?></div>
    <?php endif; ?>
  </div>
</div>
</body></html>
