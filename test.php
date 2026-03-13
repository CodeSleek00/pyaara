<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include 'db.php';

/* FETCH RANDOM 5 PRODUCTS */

$exclusiveProducts = $conn->query("
SELECT id,image,name,original_price,discount_price 
FROM products 
WHERE page='exclusive.php'
ORDER BY RAND()
LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Exclusive Products</title>

<style>

/* GLOBAL */

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
font-family:Arial, Helvetica, sans-serif;
background:#fafafa;
overflow-x:hidden;
}

/* LOADER */

#loader{
position:fixed;
width:100%;
height:100vh;
background:white;
display:flex;
align-items:center;
justify-content:center;
z-index:9999;
}

.spinner{
width:50px;
height:50px;
border:4px solid #e5e7eb;
border-top:4px solid #111827;
border-radius:50%;
animation:spin 1s linear infinite;
}

@keyframes spin{
100%{transform:rotate(360deg);}
}

/* MAIN */

#main{
display:none;
padding-top:60px;
}

/* CARD CONTAINER */

.cards{
position:relative;
width:100%;
height:340px;
display:flex;
align-items:center;
justify-content:center;
}

/* CARD */

.card{
position:absolute;
width:250px;
height:280px;
border-radius:10px;
overflow:hidden;

background:#fff;

transform:translateY(400px);
opacity:0;

transition:all 1s ease;
cursor:pointer;
}

.card img{
width:100%;
height:100%;
object-fit:cover;
}

/* PRICE OVERLAY */

.card-info{
position:absolute;
bottom:0;
left:0;
right:0;

padding:10px;

display:flex;
justify-content:space-between;
align-items:center;

background:linear-gradient(
to top,
rgba(0,0,0,0.75),
rgba(0,0,0,0.45),
rgba(0,0,0,0)
);

color:white;
}

.price{
font-weight:700;
font-size:14px;
}

.price .old{
text-decoration:line-through;
opacity:0.7;
margin-left:6px;
}

.buy-btn{
background:white;
color:black;
padding:5px 10px;
border-radius:4px;
font-size:12px;
font-weight:600;
}

/* STEP 1 */

.cards.show .card{
transform:translateY(0);
opacity:1;
}

/* STEP 2 DESKTOP SPREAD */

.cards.spread .card:nth-child(1){transform:translate(-520px,0);}
.cards.spread .card:nth-child(2){transform:translate(-260px,0);}
.cards.spread .card:nth-child(3){transform:translate(0,0);}
.cards.spread .card:nth-child(4){transform:translate(260px,0);}
.cards.spread .card:nth-child(5){transform:translate(520px,0);}

/* MOBILE */

@media(max-width:768px){

.cards{
display:flex;
overflow-x:auto;
scroll-snap-type:x mandatory;
gap:16px;
padding:0 16px;
height:auto;
}

.card{
position:relative;
flex:0 0 85%;
height:240px;

transform:none !important;
opacity:1 !important;

scroll-snap-align:center;
}

}

</style>
</head>

<body>

<!-- LOADER -->

<div id="loader">
<div class="spinner"></div>
</div>

<!-- MAIN -->

<div id="main">

<div class="cards" id="cards">

<?php

if($exclusiveProducts && $exclusiveProducts->num_rows>0){

while($row=$exclusiveProducts->fetch_assoc()){

$display_price = ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0)
? $row['discount_price']
: $row['original_price'];

echo '<a href="orders/product_detail.php?id='.$row['id'].'" class="card">

<img src="orders/uploads/'.htmlspecialchars($row['image']).'" alt="'.htmlspecialchars($row['name']).'">

<div class="card-info">

<div class="price">
₹'.number_format($display_price).'
'.($display_price < $row['original_price'] ? '<span class="old">₹'.number_format($row['original_price']).'</span>' : '').'
</div>

<span class="buy-btn">Buy</span>

</div>

</a>';

}

}

else{

for($i=1;$i<=5;$i++){

echo '<a href="#" class="card">

<img src="https://picsum.photos/300/400?random='.$i.'">

<div class="card-info">

<div class="price">₹499</div>

<span class="buy-btn">Buy</span>

</div>

</a>';

}

}

?>

</div>

</div>

<script>

/* LOADER */

document.addEventListener("DOMContentLoaded",function(){

setTimeout(function(){

document.getElementById("loader").style.display="none";
document.getElementById("main").style.display="block";

const cards=document.getElementById("cards");

/* STEP 1 */

setTimeout(()=>{
cards.classList.add("show");
},100);

/* STEP 2 */

setTimeout(()=>{
cards.classList.add("spread");
},1400);

},2000);

});

/* MOBILE AUTO CAROUSEL */

function startMobileCarousel(){

if(window.innerWidth <= 768){

const container=document.querySelector(".cards");

let scrollAmount=0;

setInterval(()=>{

scrollAmount+=container.offsetWidth*0.85+16;

if(scrollAmount>=container.scrollWidth){
scrollAmount=0;
}

container.scrollTo({
left:scrollAmount,
behavior:"smooth"
});

},3000);

}

}

startMobileCarousel();

</script>

</body>
</html>