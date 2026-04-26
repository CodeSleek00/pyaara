<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'temp_db.php';

// Optimized random selection without ORDER BY RAND()
$page = 'exclusive.php';
$limit = 5;
$offset = 0;

$countRes = $conn->query("SELECT COUNT(*) AS cnt FROM products WHERE page='{$page}'");
if ($countRes && ($countRow = $countRes->fetch_assoc())) {
  $count = (int)$countRow['cnt'];
  if ($count > $limit) {
    $offset = random_int(0, $count - $limit);
  }
}

$stmt = $conn->prepare("
  SELECT id, image, name, original_price, discount_price
  FROM products
  WHERE page = ?
  ORDER BY id DESC
  LIMIT ? OFFSET ?
");
$stmt->bind_param("sii", $page, $limit, $offset);
$stmt->execute();
$exclusiveProducts = $stmt->get_result();

// Anime products (limit)
$animeProducts = [];
$animePageA = 'anime.php';
$animePageB = 'orders/anime.php';
$animeStmt = $conn->prepare("
  SELECT id, image, name, original_price, discount_price
  FROM products
  WHERE page IN (?, ?)
  ORDER BY id DESC
  LIMIT 8
");
if ($animeStmt) {
  $animeStmt->bind_param("ss", $animePageA, $animePageB);
  if ($animeStmt->execute()) {
    $animeRes = $animeStmt->get_result();
    if ($animeRes) {
      while ($row = $animeRes->fetch_assoc()) {
        $animeProducts[] = $row;
      }
    } else {
      // Fallback for environments without mysqlnd (no get_result()).
      $id = null;
      $image = null;
      $name = null;
      $original = null;
      $discount = null;
      if ($animeStmt->bind_result($id, $image, $name, $original, $discount)) {
        while ($animeStmt->fetch()) {
          $animeProducts[] = [
            'id' => $id,
            'image' => $image,
            'name' => $name,
            'original_price' => $original,
            'discount_price' => $discount,
          ];
        }
      }
    }
  }
  $animeStmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Exclusive Collection</title>

<!-- Fonts optimized -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="design/style.css">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Bangers&family=Noto+Sans+JP:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>

</style>
</head>
<body>

<div id="cursor"></div>

<!-- LOADER -->
<div id="loader">
  <div class="loader-kanji">専</div>
  <div class="loader-text">Pyaara Store</div>
  <div class="loader-bar"><div class="loader-fill"></div></div>
</div>

<!-- MAIN CONTENT -->
<div id="main" style="display: none;">
  <!-- HERO SECTION -->
  <div class="hero" id="hero">
    <div class="speed-lines"></div>
    <div class="halftone"></div>
    <div class="slash slash-1"></div>
    <div class="slash slash-2"></div>
    <div class="corner corner-tl"></div>
    <div class="corner corner-tr"></div>
    <div class="corner corner-br"></div>

    <div class="hero-head">
      <p class="hero-jp">専用コレクション — Exclusive</p>
      <h1 class="hero-title" data-text="EXCLUSIVE DROP">
        <span class="stroke">PY</span><span>AARA</span><br>
        <span>DR</span><span class="stroke">OP</span>
      </h1>
      <p class="hero-sub">Limited Season · Scroll to Reveal</p>
    </div>

    <!-- Card Stage -->
    <div class="stage" id="stage">
      <?php
      $positions = [-480, -240, 0, 240, 480];
      $scales = [0.87, 0.93, 1.03, 0.93, 0.87];
      $zIndexes = [1, 2, 3, 2, 1];
      $labels = ['Arc Flash', 'Soul Blade', 'Ultra Rare', 'Phantom', 'Oni Strike'];
      $i = 0;

      if ($exclusiveProducts && $exclusiveProducts->num_rows > 0):
        while ($row = $exclusiveProducts->fetch_assoc()):
          $dp = ($row['discount_price'] > 0 && $row['discount_price'] < $row['original_price'])
                ? $row['discount_price'] : $row['original_price'];
          $disc = ($row['discount_price'] > 0 && $row['original_price'] > 0)
                  ? round((($row['original_price'] - $row['discount_price']) / $row['original_price']) * 100) : 0;
          $name = htmlspecialchars($row['name']);
          $img = htmlspecialchars($row['image']);
          $id = (int)$row['id'];
          $num = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
          $loadingAttr = ($i === 0) ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"';
      ?>
      <div class="card-wrap" id="c<?= $i ?>" data-href="orders/product_detail.php?id=<?= $id ?>"
           style="transform: translate(<?= $positions[$i] ?>px, 0) scale(<?= $scales[$i] ?>);
                  z-index: <?= $zIndexes[$i] ?>; opacity: 0;">
        <div class="card-front">
          <img src="orders/uploads/<?= $img ?>" alt="<?= $name ?>" width="210" height="295" decoding="async" <?= $loadingAttr ?>>
          <div class="card-dot"></div>
          <div class="card-stripe"></div>
          <div class="card-overlay"></div>
          <?php if ($disc > 0): ?>
            <div class="badge"><?= $disc ?>% OFF</div>
          <?php endif; ?>
          <div class="card-info">
            <div class="prod-name"><?= $name ?></div>
            <div class="price-row">
              <div>
                <span class="price-new">₹<?= number_format($dp) ?></span>
                <?php if ($dp < $row['original_price']): ?>
                  <span class="price-old">₹<?= number_format($row['original_price']) ?></span>
                <?php endif; ?>
              </div>
              <a href="orders/product_detail.php?id=<?= $id ?>" class="buy-btn">Buy</a>
            </div>
          </div>
          <div class="energy-num"><?= $num ?></div>
        </div>

        <div class="card-back">
          <span class="back-label"><?= $labels[$i % 5] ?></span>
          <div class="back-name"><?= $name ?></div>
          <div class="back-line"></div>
          <div class="back-price-big">₹<?= number_format($dp) ?></div>
          <?php if ($dp < $row['original_price']): ?>
            <div class="back-old-price">₹<?= number_format($row['original_price']) ?></div>
          <?php endif; ?>
          <a href="orders/product_detail.php?id=<?= $id ?>" class="back-cta">View Item</a>
          <div class="energy-num"><?= $num ?></div>
        </div>
      </div>
      <?php $i++; endwhile; endif; ?>
    </div>

    <div class="scroll-hint" id="scrollHint">
      <span class="scroll-hint-text">Scroll Down</span>
      <div class="scroll-chevrons">
        <div class="chev"></div>
        <div class="chev"></div>
        <div class="chev"></div>
      </div>
    </div>
  </div>
  </div>
  <div class="sections-container" id="sectionsContainer">
  
  <!-- SECTION 1 - Anime Cards (Enhanced Mobile Swipe) -->
  <section class="content-section" data-section="1">
    <div class="section-bg"></div>
    <div class="section-content">
      <div class="ac-section">
        <div class="ac-header">
          <h1>Coming Soon</h1>
          <p>3D STANDS // ICONIC MOMENTS</p>
        </div>

        <!-- Desktop Grid -->
        <div class="ac-grid" id="desktopGrid">
          <!-- card 1 -->
          <div class="ac-card" data-tilt data-name="tanjiro">
            <div class="ac-front"><img class="ac-img" src="https://i.imgur.com/0y8Ftya.jpg" alt="Demon Slayer" loading="lazy"><div class="ac-overlay"></div><div class="ac-badge">🔥 Trending</div><div class="ac-info"><div class="ac-genre">Action · Supernatural</div><div class="ac-title">DEMON SLAYER</div><div class="ac-desc">Tanjiro fights to save Nezuko. Flames, water, unwavering bonds.</div><div class="ac-meta"><span class="ac-rating">★ 9.4</span><span class="ac-episodes">44 eps</span></div></div></div>
            <div class="ac-character" style="background-image: url('https://i.imgur.com/6f8u0yA.png'); background-position: center 12%; background-size: cover;"><div class="ac-quote">TANJIRO <small>水の呼吸</small></div></div>
          </div>
          <div class="ac-card">
            <div class="ac-front"><img class="ac-img" src="https://i.imgur.com/kBSWcR9.jpg" alt="Attack on Titan" loading="lazy"><div class="ac-overlay"></div><div class="ac-badge">⚔️ Epic</div><div class="ac-info"><div class="ac-genre">Dark Fantasy · War</div><div class="ac-title">ATTACK ON TITAN</div><div class="ac-desc">Humanity's last stand. The truth will shatter everything.</div><div class="ac-meta"><span class="ac-rating">★ 9.9</span><span class="ac-episodes">87 eps</span></div></div></div>
            <div class="ac-character" style="background-image: url('https://i.imgur.com/hHRs5A8.jpg'); background-position: center 20%; background-size: cover;"><div class="ac-quote">EREN <small>進撃</small></div></div>
          </div>
          <div class="ac-card">
            <div class="ac-front"><img class="ac-img" src="https://i.imgur.com/LwzrxvJ.jpg" alt="Jujutsu Kaisen" loading="lazy"><div class="ac-overlay"></div><div class="ac-badge">💀 Dark</div><div class="ac-info"><div class="ac-genre">Occult · Shonen</div><div class="ac-title">JUJUTSU KAISEN</div><div class="ac-desc">Cursed energy, fingers, and the will to fight for the right death.</div><div class="ac-meta"><span class="ac-rating">★ 9.3</span><span class="ac-episodes">48 eps</span></div></div></div>
            <div class="ac-character" style="background-image: url('https://i.imgur.com/tfcn45t.jpg'); background-position: center 30%; background-size: cover;"><div class="ac-quote">YUJI <small>呪術</small></div></div>
          </div>
          <div class="ac-card">
            <div class="ac-front"><img class="ac-img" src="https://i.imgur.com/G4yOd9c.jpg" alt="One Piece" loading="lazy"><div class="ac-overlay"></div><div class="ac-badge">🌊 Legend</div><div class="ac-info"><div class="ac-genre">Adventure · Pirate</div><div class="ac-title">ONE PIECE</div><div class="ac-desc">The king of pirates, a rubber boy, and the great treasure.</div><div class="ac-meta"><span class="ac-rating">★ 9.7</span><span class="ac-episodes">1000+ eps</span></div></div></div>
            <div class="ac-character" style="background-image: url('https://i.imgur.com/qhPqk8d.jpg'); background-position: center 25%; background-size: cover;"><div class="ac-quote">LUFFY <small>ギア5</small></div></div>
          </div>
        </div>

        <!-- Mobile Carousel (dynamic clone) -->
        <div class="ac-carousel" id="mobileCarousel"></div>
        <div class="ac-dots" id="dotsWrap"></div>
      </div>
    </div>
  </section>

  <!-- SECTION 2 - Anime Products (from DB) -->
  <section class="content-section" data-section="2">
    <div class="section-bg"></div>
    <div class="section-content">
      <div class="ap-section">
        <div class="ap-header">
          <div class="ap-title">ANIME</div>
          <div class="ap-jp">アニメ・スタイル</div>
        </div>

        <div class="ap-grid">
          <?php if (empty($animeProducts)): ?>
            <div class="ap-empty">
              <strong>Coming Soon.</strong><br>
              Add products with <code>page = anime.php</code> in DB.
            </div>
          <?php else: ?>
            <?php foreach ($animeProducts as $row): ?>
              <?php
                if (!is_array($row)) continue;
                $id = (int)($row['id'] ?? 0);
                $name = htmlspecialchars((string)($row['name'] ?? 'Unnamed'), ENT_QUOTES, 'UTF-8');
                $img = htmlspecialchars((string)($row['image'] ?? ''), ENT_QUOTES, 'UTF-8');
                $o = (float)($row['original_price'] ?? 0);
                $d = (float)($row['discount_price'] ?? 0);
                $final = ($d > 0 && $d < $o) ? $d : $o;
              ?>
              <div class="ap-card">
                <div class="ap-thumb">
                  <?php if ($img !== ''): ?>
                    <img loading="lazy" src="orders/uploads/<?= $img ?>" alt="<?= $name ?>" onerror="this.style.display='none'">
                  <?php else: ?>
                    <span style="color:rgba(255,255,255,0.55); font-size:1.6rem;">🎴</span>
                  <?php endif; ?>
                </div>
                <div class="ap-name"><?= $name ?></div>
                <div class="ap-price">₹<?= number_format($final) ?></div>
                <a class="ap-btn" href="orders/product_detail.php?id=<?= $id ?>">OPEN</a>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>


<script src="design/script.js"></script>


</body>
</html>
