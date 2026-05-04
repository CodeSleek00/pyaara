<?php
include 'temp_db.php'; // your database connection file

// Random 5 Exclusive Products
$sql = "SELECT * FROM products 
        WHERE category = 'exclusive' 
        ORDER BY RAND() 
        LIMIT 5";

$exclusiveProducts = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Exclusive Collection</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com?plugins=line-clamp"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-50">

<!-- ================= Exclusive Section ================= -->
<section class="max-w-[1400px] mx-auto px-4 mt-16">

  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl md:text-3xl font-bold">
      Exclusive Collection
    </h2>

    <a href="orders/exclusive.php"
       class="text-red-600 font-semibold flex items-center gap-2 hover:underline">
      More Products <i class="fa fa-arrow-right text-sm"></i>
    </a>
  </div>

  <!-- Products Wrapper -->
  <div class="flex gap-4 overflow-x-auto pb-4 scroll-smooth snap-x snap-mandatory
              md:grid md:grid-cols-4 md:gap-6 md:overflow-visible">

    <?php if ($exclusiveProducts && $exclusiveProducts->num_rows > 0): ?>
      
      <?php while ($row = $exclusiveProducts->fetch_assoc()): ?>

        <!-- Card -->
        <div class="min-w-[75%] sm:min-w-[55%] md:min-w-0
                    bg-white rounded-2xl shadow-sm
                    hover:shadow-lg hover:-translate-y-1
                    transition duration-300 snap-start">

          <!-- Image -->
          <div class="relative aspect-[4/5] bg-gray-100 rounded-t-2xl overflow-hidden">

            <a href="orders/product_detail.php?id=<?php echo $row['id']; ?>">
              <img
                src="orders/uploads/<?php echo htmlspecialchars($row['image']); ?>"
                alt="<?php echo htmlspecialchars($row['name']); ?>"
                class="w-full h-full object-cover hover:scale-105 transition duration-300">
            </a>

            <!-- Discount Badge -->
            <?php if (!empty($row['discount_price']) && $row['discount_price'] < $row['original_price']): ?>
              <span class="absolute top-3 right-3
                           bg-red-500 text-white
                           text-xs font-semibold
                           px-3 py-1 rounded-full shadow">
                <?php echo (int)$row['discount_percent']; ?>% OFF
              </span>
            <?php endif; ?>

          </div>

          <!-- Info -->
          <div class="p-4">

            <h3 class="text-sm font-semibold line-clamp-2 mb-2">
              <?php echo htmlspecialchars($row['name']); ?>
            </h3>

            <div class="mb-3">
              <?php if (!empty($row['discount_price']) && $row['discount_price'] < $row['original_price']): ?>
                
                <span class="text-red-600 font-bold text-lg">
                  ₹<?php echo number_format($row['discount_price']); ?>
                </span>

                <span class="text-gray-400 text-sm line-through ml-1">
                  ₹<?php echo number_format($row['original_price']); ?>
                </span>

              <?php else: ?>

                <span class="text-red-600 font-bold text-lg">
                  ₹<?php echo number_format($row['original_price']); ?>
                </span>

              <?php endif; ?>
            </div>

            <a href="orders/product_detail.php?id=<?php echo $row['id']; ?>"
               class="block text-center
                      bg-red-600 hover:bg-red-700
                      text-white py-2 rounded-lg
                      text-sm font-semibold transition">
              Buy Now
            </a>

          </div>

        </div>

      <?php endwhile; ?>

    <?php else: ?>

      <p class="text-gray-500 text-center col-span-4">
        No exclusive products available right now.
      </p>

    <?php endif; ?>

  </div>

</section>
<!-- ================= Section End ================= -->

</body>
</html>