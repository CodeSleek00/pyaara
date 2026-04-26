<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = null;

// Prefer the same DB connection used by `orders/anime.php`.
if (file_exists(__DIR__ . '/orders/db_connect.php')) {
  require_once __DIR__ . '/orders/db_connect.php';
} else {
  require_once __DIR__ . '/temp_db.php';
}

if (!($conn instanceof mysqli)) {
  die('Database connection not available.');
}

$page = 'anime.php';
$pageAlt = 'orders/anime.php';
$products = [];

$stmt = $conn->prepare("
  SELECT id, image, name, original_price, discount_price
  FROM products
  WHERE page IN (?, ?)
  ORDER BY id DESC
");
if ($stmt) {
  $stmt->bind_param('ss', $page, $pageAlt);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    if ($res) {
      while ($row = $res->fetch_assoc()) {
        $products[] = $row;
      }
    }
  } else {
    die('Query failed.');
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <title>GenZ Scroll Vibe | Hype Drop</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,500;14..32,700;14..32,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Inter', sans-serif;
      background: #010101;
      overflow-x: hidden;
      scroll-behavior: smooth;
    }

    /* custom scrollbar for extra sauce */
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: #0a0a0a; }
    ::-webkit-scrollbar-thumb { background: #ff3b6f; border-radius: 10px; }

    /* === HERO SECTION — FRESH GLOW === */
    .hero {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 10;
      pointer-events: none;
      text-align: center;
      background: radial-gradient(circle at 30% 10%, rgba(255,59,111,0.15), rgba(0,0,0,0) 70%);
    }

    .hero .main-title {
      font-size: clamp(2.8rem, 10vw, 6rem);
      font-weight: 800;
      background: linear-gradient(135deg, #fff 20%, #ff3b6f 60%, #ffb347);
      background-clip: text;
      -webkit-background-clip: text;
      color: transparent;
      letter-spacing: -0.02em;
      filter: drop-shadow(0 0 15px rgba(255,59,111,0.4));
      animation: subtleGlow 3s infinite alternate;
    }

    @keyframes subtleGlow {
      0% { filter: drop-shadow(0 0 5px rgba(255,59,111,0.3)); }
      100% { filter: drop-shadow(0 0 25px rgba(255,59,111,0.7)); }
    }

    .hero .scroll-indicator {
      margin-top: 2rem;
      font-size: 0.9rem;
      font-weight: 500;
      letter-spacing: 2px;
      color: #aaa;
      background: rgba(255,255,255,0.05);
      backdrop-filter: blur(12px);
      padding: 0.6rem 1.5rem;
      border-radius: 40px;
      pointer-events: auto;
      border: 0.5px solid rgba(255,255,255,0.2);
    }

    .hero .scroll-indicator i {
      margin-right: 8px;
      font-size: 0.8rem;
      animation: bounceY 1.2s infinite;
    }

    @keyframes bounceY {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(4px); }
    }

    /* product container — fixed layer with depth */
    .products {
      position: sticky;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 40px;
      flex-wrap: wrap;
      z-index: 2;
      padding: 110px 20px 80px;
      pointer-events: none;
    }

    /* CARD MODERN — GLASS CORE + VISUAL UPGRADE */
    .card {
      width: 280px;
      background: rgba(15, 15, 25, 0.65);
      backdrop-filter: blur(18px);
      border-radius: 42px;
      padding: 1.5rem 1.2rem 1.8rem;
      text-align: center;
      border: 1px solid rgba(255,255,255,0.2);
      box-shadow: 0 25px 40px rgba(0,0,0,0.4);
      transition: all 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1);
      transform: translateY(120px) scale(0.88);
      opacity: 0;
      cursor: pointer;
      pointer-events: auto;
      position: relative;
      overflow: hidden;
      will-change: transform, opacity;
    }

    /* glow gradient border on hover */
    .card::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: 42px;
      padding: 2px;
      background: linear-gradient(145deg, rgba(255,59,111,0.5), rgba(255,180,71,0.3));
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      opacity: 0;
      transition: opacity 0.4s;
      pointer-events: none;
    }

    .card:hover::before { opacity: 1; }

    .card.show {
      transform: translateY(0) scale(1);
      opacity: 1;
    }

    .card a {
      color: inherit;
      text-decoration: none;
      display: block;
    }

    /* product image creative container */
    .card .img-wrapper {
      width: 100%;
      height: 200px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(0,0,0,0.2);
      border-radius: 32px;
      margin-bottom: 20px;
      transition: transform 0.3s ease;
    }

    .card:hover .img-wrapper { transform: scale(1.02); }

    .card img {
      width: 92%;
      height: auto;
      max-height: 185px;
      object-fit: contain;
      filter: drop-shadow(0 8px 12px rgba(0,0,0,0.3));
      transition: filter 0.3s;
    }

    .card:hover img { filter: drop-shadow(0 0 12px rgba(255,59,111,0.5)); }

    .card h2 {
      font-size: 1.3rem;
      font-weight: 800;
      letter-spacing: -0.3px;
      margin-top: 4px;
      background: linear-gradient(to right, #f0f0f0, #ffe6c7);
      background-clip: text;
      -webkit-background-clip: text;
      color: transparent;
    }

    .card p {
      font-size: 0.8rem;
      color: #bdbdd6;
      margin-top: 6px;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .card .price-tag {
      display: inline-block;
      margin-top: 16px;
      background: rgba(255,59,111,0.2);
      padding: 4px 16px;
      border-radius: 40px;
      font-size: 0.85rem;
      font-weight: 700;
      color: #ff7b9c;
      backdrop-filter: blur(4px);
      border: 0.5px solid rgba(255,255,255,0.15);
    }

    /* interactive badge for genz */
    .trend-badge {
      position: absolute;
      top: 15px;
      right: 15px;
      background: #ff3b6f;
      color: white;
      font-size: 0.7rem;
      font-weight: 800;
      padding: 4px 10px;
      border-radius: 30px;
      box-shadow: 0 0 8px #ff3b6f;
      z-index: 2;
      backdrop-filter: blur(2px);
      pointer-events: none;
    }

    /* SCROLL PROGRESS BAR */
    .progress-bar {
      position: fixed;
      top: 0;
      left: 0;
      height: 4px;
      background: linear-gradient(90deg, #ff3b6f, #ffb347);
      width: 0%;
      z-index: 100;
      transition: width 0.1s ease;
      box-shadow: 0 0 6px #ff3b6f;
    }

    /* floating instruction pill */
    .hint-pill {
      position: fixed;
      bottom: 28px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(0,0,0,0.6);
      backdrop-filter: blur(20px);
      border-radius: 60px;
      padding: 10px 22px;
      font-size: 0.8rem;
      font-weight: 500;
      color: #f0f0f0;
      z-index: 99;
      border: 1px solid rgba(255,255,255,0.2);
      display: flex;
      gap: 12px;
      letter-spacing: 0.5px;
      pointer-events: none;
      white-space: nowrap;
      font-family: monospace;
    }

    .hint-pill i { font-size: 0.9rem; color: #ffb347; }

    /* responsive adjustments */
    @media (max-width: 780px) {
      .products { gap: 20px; }
      .card { width: 240px; padding: 1.2rem; }
      .card .img-wrapper { height: 170px; }
      .card h2 { font-size: 1.1rem; }
      .hint-pill { font-size: 0.7rem; padding: 8px 16px; }
    }

    @media (max-width: 550px) {
      .products { gap: 15px; padding-top: 90px; }
      .card { width: 200px; height: auto; }
      .card .img-wrapper { height: 140px; }
      .trend-badge { font-size: 0.6rem; top: 8px; right: 8px; }
    }

    /* === SCROLL ZONES (make products appear/disappear by viewport) === */
    .scroll-stack {
      position: relative;
      z-index: 1;
      min-height: 340vh;
    }

    .spacer {
      min-height: 120vh;
      display: grid;
      place-items: center;
      color: rgba(255, 255, 255, 0.2);
      text-align: center;
      padding: 0 16px;
    }

    .spacer h2 {
      font-size: clamp(1.2rem, 3.5vw, 2rem);
      letter-spacing: 0.08em;
      text-transform: uppercase;
      font-weight: 800;
    }

    .drop-zone {
      position: relative;
      min-height: 180vh;
      z-index: 2;
    }

    .drop-zone::before {
      content: '';
      position: absolute;
      inset: -10vh 0 -10vh;
      background: radial-gradient(circle at 50% 30%, rgba(255,59,111,0.10), rgba(0,0,0,0) 62%);
      pointer-events: none;
      z-index: 0;
    }

    .drop-zone .products {
      opacity: 0;
      transition: opacity 0.35s ease;
    }

    .drop-zone.is-active .products { opacity: 1; }
  </style>
</head>
<body>

<div class="progress-bar" id="progressBar"></div>

<div class="hero">
  <div class="main-title">⚡ DROP ZONE ⚡</div>
  <div class="scroll-indicator">
    <i class="fas fa-arrow-down"></i> SCROLL TO REVEAL
  </div>
</div>

<main class="scroll-stack">
  <section class="spacer spacer-top" aria-hidden="true">
    <h2>Scroll down</h2>
  </section>

  <section class="drop-zone" id="dropZone">
    <div class="products" id="products">
      <?php if (!empty($products)): ?>
        <?php foreach ($products as $idx => $row): ?>
          <?php
            $id = (int)($row['id'] ?? 0);
            $name = htmlspecialchars((string)($row['name'] ?? ''), ENT_QUOTES, 'UTF-8');
            $image = htmlspecialchars((string)($row['image'] ?? ''), ENT_QUOTES, 'UTF-8');
            $original = (float)($row['original_price'] ?? 0);
            $discount = (float)($row['discount_price'] ?? 0);
            $final = ($discount > 0 && $discount < $original) ? $discount : $original;
            $percent = ($discount > 0 && $discount < $original && $original > 0)
              ? (int)round((($original - $discount) / $original) * 100)
              : 0;
            $badge = $percent > 0 ? ($percent . '% OFF') : '#HYPE';
          ?>
          <div class="card" data-product="<?= $id ?>">
            <div class="trend-badge"><?= htmlspecialchars($badge, ENT_QUOTES, 'UTF-8') ?></div>
            <a href="orders/product_detail.php?id=<?= $id ?>">
              <div class="img-wrapper">
                <img loading="lazy" src="orders/uploads/<?= $image ?>" alt="<?= $name ?>">
              </div>
              <h2><?= $name ?></h2>
              <p>anime drop · limited</p>
              <div class="price-tag">₹<?= number_format($final) ?></div>
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="card show" style="transform:none; opacity:1;">
          <div class="trend-badge">SOON</div>
          <div class="img-wrapper"></div>
          <h2>Coming Soon</h2>
          <p>no anime products found</p>
          <div class="price-tag">—</div>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="spacer spacer-bottom" aria-hidden="true">
    <h2>More drops soon</h2>
  </section>
</main>

<div class="hint-pill">
  <i class="fas fa-hand-peace"></i> <?= count($products) ?> items loaded &nbsp;&nbsp;|&nbsp;&nbsp; scroll → products pop in &nbsp;&nbsp;|&nbsp;&nbsp; scroll past → fade out
</div>

<script>
  (function() {
    const zone = document.getElementById('dropZone');
    const cards = Array.from(document.querySelectorAll('#products .card'));
    const prefersReducedMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches ?? false;
    let isShowing = false;

    function updateProgressBar() {
      const winScroll = window.scrollY;
      const height = document.documentElement.scrollHeight - window.innerHeight;
      const scrolled = height > 0 ? (winScroll / height) * 100 : 0;
      const progressBar = document.getElementById('progressBar');
      if (progressBar) progressBar.style.width = scrolled + '%';
    }

    function showProducts() {
      if (isShowing) return;
      isShowing = true;
      cards.forEach((card, idx) => {
        const delay = prefersReducedMotion ? 0 : idx * 45;
        window.setTimeout(() => card.classList.add('show'), delay);
      });
    }

    function hideProducts() {
      if (!isShowing) return;
      isShowing = false;
      cards.slice().reverse().forEach((card, idx) => {
        const delay = prefersReducedMotion ? 0 : idx * 25;
        window.setTimeout(() => card.classList.remove('show'), delay);
      });
    }

    if (zone) {
      const onIntersect = (entries) => {
        const entry = entries[0];
        const active = !!entry?.isIntersecting;
        zone.classList.toggle('is-active', active);
        if (active) showProducts();
        else hideProducts();
      };

      const observer = new IntersectionObserver(onIntersect, {
        root: null,
        threshold: 0.35,
        rootMargin: '-15% 0px -25% 0px',
      });

      observer.observe(zone);
    }

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(() => {
        updateProgressBar();
        ticking = false;
      });
    }, { passive: true });

    window.addEventListener('load', () => {
      updateProgressBar();
      const heroTitle = document.querySelector('.main-title');
      if (heroTitle) heroTitle.style.animation = 'subtleGlow 3s infinite alternate';
    });

    window.addEventListener('resize', updateProgressBar);
  })();
</script>
</body>
</html>
