<?php
include 'db.php';

/* Fetch 4 random exclusive products */
$exclusiveProducts = $conn->query("
    SELECT * FROM products 
    WHERE page = 'exclusive.php'
    ORDER BY RAND()
    LIMIT 4
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pyaara Store | Exclusive Collection</title>

<link rel="icon" type="image/png" href="../images/Pyaara Circle.png">
<link rel="apple-touch-icon" href="../images/Pyaara Circle.png">

<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Font -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<script>
tailwind.config = {
  theme: {
    fontFamily: {
      outfit: ['Outfit', 'sans-serif'],
    },
    extend: {
      colors: {
        primary: '#E63946',
        accent: '#FFD166',
      }
    }
  }
}
</script>
</head>

<body class="font-outfit bg-white text-gray-800">

<!-- ================= Exclusive Section ================= -->
<section class="max-w-[1400px] mx-auto px-4 mt-16">

  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl md:text-3xl font-bold">
      Exclusive Collection
    </h2>

    <a href="exclusive.php"
       class="text-primary font-semibold flex items-center gap-2 hover:underline">
      More Products <i class="fa fa-arrow-right text-sm"></i>
    </a>
  </div>

  <!-- Products Wrapper -->
  <div
    class="flex gap-4 overflow-x-auto pb-3
           md:grid md:grid-cols-4 md:gap-6">

    <?php if ($exclusiveProducts && $exclusiveProducts->num_rows > 0): ?>
      <?php while ($row = $exclusiveProducts->fetch_assoc()): ?>

        <!-- Card -->
        <div
          class="min-w-[70%] sm:min-w-[55%] md:min-w-0
                 bg-white rounded-xl shadow-md
                 hover:-translate-y-1 transition">

          <!-- Image -->
          <div class="relative aspect-[4/5] bg-gray-100 rounded-t-xl overflow-hidden">
            <a href="orders/product_detail.php?id=<?php echo $row['id']; ?>">
              <img
                src="orders/uploads/<?php echo htmlspecialchars($row['image']); ?>"
                alt="<?php echo htmlspecialchars($row['name']); ?>"
                class="w-full h-full object-cover">
            </a>

            <?php if ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0): ?>
              <span
                class="absolute top-3 right-3
                       bg-accent text-gray-800
                       text-xs font-semibold
                       px-3 py-1 rounded-full">
                <?php echo $row['discount_percent']; ?>% OFF
              </span>
            <?php endif; ?>
          </div>

          <!-- Info -->
          <div class="p-4">
            <h3 class="text-sm font-semibold line-clamp-2 mb-2">
              <?php echo htmlspecialchars($row['name']); ?>
            </h3>

            <div class="mb-3">
              <?php if ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0): ?>
                <span class="text-primary font-bold text-lg">
                  ₹<?php echo number_format($row['discount_price']); ?>
                </span>
                <span class="text-gray-500 text-sm line-through ml-1">
                  ₹<?php echo number_format($row['original_price']); ?>
                </span>
              <?php else: ?>
                <span class="text-primary font-bold text-lg">
                  ₹<?php echo number_format($row['original_price']); ?>
                </span>
              <?php endif; ?>
            </div>

            <!-- Buy Button -->
            <a
              href="orders/product_detail.php?id=<?php echo $row['id']; ?>"
              class="block text-center
                     bg-primary text-white
                     py-2 rounded-lg
                     text-sm font-semibold">
              Buy Now
            </a>
          </div>

        </div>
        <!-- Card End -->

      <?php endwhile; ?>
    <?php endif; ?>

  </div>
</section>

</body>
</html>

<?php $conn->close(); ?>
