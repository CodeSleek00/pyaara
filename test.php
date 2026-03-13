<?php
include 'db.php';

/* ================= FETCH RANDOM 5 PRODUCTS ================= */

$exclusiveProducts = $conn->query("
SELECT id,image,name 
FROM products 
WHERE page='exclusive'
ORDER BY RAND()
LIMIT 5
");

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Exclusive Collection</title>

<style>

/* ================= GLOBAL ================= */

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial;
}

body{
background:#f5f7fb;
overflow:hidden;
}

/* ================= LOADER ================= */

#loader{
position:fixed;
width:100%;
height:100vh;
background:white;
display:flex;
align-items:center;
justify-content:center;
z-index:999;
}

.spinner{
width:50px;
height:50px;
border:5px solid #eee;
border-top:5px solid #2563eb;
border-radius:50%;
animation:spin 1s linear infinite;
}

@keyframes spin{
100%{transform:rotate(360deg);}
}

/* ================= MAIN ================= */

#main{
display:none;
height:100vh;
display:flex;
align-items:center;
justify-content:center;
}

/* ================= CARD CONTAINER ================= */

.cards{
position:relative;
width:90%;
height:320px;
display:flex;
align-items:center;
justify-content:center;
}

/* ================= PRODUCT CARD ================= */

.card{
position:absolute;
width:220px;
height:280px;
border-radius:12px;
overflow:hidden;
box-shadow:0 10px 25px rgba(0,0,0,0.15);

transform:translateY(400px);
opacity:0;

transition:transform 1s ease, opacity 1s ease;
}

.card img{
width:100%;
height:100%;
object-fit:cover;
}

/* ================= STEP 1: COME FROM BOTTOM ================= */

.cards.show .card{
transform:translateY(0);
opacity:1;
}

/* ================= STEP 2: SPREAD HORIZONTALLY ================= */

.cards.spread .card:nth-child(1){transform:translate(-450px,0);}
.cards.spread .card:nth-child(2){transform:translate(-220px,0);}
.cards.spread .card:nth-child(3){transform:translate(0,0);}
.cards.spread .card:nth-child(4){transform:translate(220px,0);}
.cards.spread .card:nth-child(5){transform:translate(450px,0);}

/* ================= RESPONSIVE ================= */

@media(max-width:768px){

.card{
width:150px;
height:200px;
}

.cards.spread .card:nth-child(1){transform:translate(-200px,0);}
.cards.spread .card:nth-child(2){transform:translate(-100px,0);}
.cards.spread .card:nth-child(3){transform:translate(0,0);}
.cards.spread .card:nth-child(4){transform:translate(100px,0);}
.cards.spread .card:nth-child(5){transform:translate(200px,0);}

}

</style>
</head>

<body>

<!-- ================= LOADER ================= -->

<div id="loader">
<div class="spinner"></div>
</div>

<!-- ================= MAIN ================= -->

<div id="main">

<div class="cards" id="cards">

<?php if ($exclusiveProducts && $exclusiveProducts->num_rows > 0): ?>

<?php while ($row = $exclusiveProducts->fetch_assoc()): ?>

<a href="orders/product_detail.php?id=<?php echo $row['id']; ?>" class="card">

<img
src="orders/uploads/<?php echo htmlspecialchars($row['image']); ?>"
alt="<?php echo htmlspecialchars($row['name']); ?>"
loading="lazy"
>

</a>

<?php endwhile; ?>

<?php endif; ?>

</div>

</div>

<script>

/* ================= LOADER ================= */

window.onload=function(){

setTimeout(function(){

document.getElementById("loader").style.display="none";
document.getElementById("main").style.display="flex";

const cards=document.getElementById("cards");

/* cards rise from bottom */

setTimeout(()=>{
cards.classList.add("show");
},200);

/* cards spread horizontally */

setTimeout(()=>{
cards.classList.add("spread");
},1500);

},2000);

}

</script>

</body>
</html>
```
