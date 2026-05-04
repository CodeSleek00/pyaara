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
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">

    <?php
    include 'temp_db.php';

    /* Fetch 4 random exclusive products */
    $exclusiveProducts = $conn->query("
        SELECT * FROM products 
        WHERE page = 'exclusive.php'
        ORDER BY RAND()
        LIMIT 4
    ");

    if ($exclusiveProducts && $exclusiveProducts->num_rows > 0):
      while ($row = $exclusiveProducts->fetch_assoc()):
    ?>

      <!-- Card -->
      <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300">

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
      <!-- Card End -->

    <?php 
      endwhile;
    else:
    ?>

      <p class="text-gray-500 text-center col-span-4">
        No exclusive products available right now.
      </p>

    <?php endif; ?>

  </div>

</section>
<script>
  window.addEventListener('DOMContentLoaded', function()) {
    constproductCards = document.querySelectorAll('.product');
    productCards.forEach(card => {
      card.addEventListener('click', function() {
        const productId = this.getAttribute('data-id');
        window.location.href = `orders/product_detail.php?id=${productId}`;
      });
    });
      function chatbot () {
        window.open('chatbot.php', AbortController.signal);
        
      }
  }
</script>