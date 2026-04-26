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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Exclusive Collection</title>

<!-- Fonts optimized -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Bangers&family=Noto+Sans+JP:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ===== RESET & VARIABLES ===== */
:root {
  --ink: #080810;
  --ink2: #0f0f1a;
  --ink3: #1a1a2e;
  --yellow: #ffe600;
  --yellow2: #ffc200;
  --red: #e8003d;
  --red2: #ff1a4f;
  --white: #f0ede8;
  --white2: #ffffff;
  --scene-bg: radial-gradient(circle at 20% 30%, #0d0718, #020008);
  --scene-bg-solid: #020008;
  --card-w: 210px;
  --card-h: 295px;
  --transition-smooth: cubic-bezier(0.23, 1, 0.32, 1);
  
  /* Section colors */
  --section-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  --section-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  --section-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  --section-4: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
  --section-5: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html,
body {
  scroll-behavior: smooth;
}

body {
  font-family: 'Rajdhani', sans-serif;
  background: var(--scene-bg-solid);
  color: var(--white2);
  overflow-x: hidden;
  cursor: none;

}

/* ===== CUSTOM CURSOR ===== */
#cursor {
  width: 16px;
  height: 16px;
  background: var(--yellow);
  border-radius: 50%;
  position: fixed;
  pointer-events: none;
  z-index: 99999;
  transform: translate(-50%, -50%);
  transition: all 0.1s ease;
  mix-blend-mode: difference;
  will-change: transform;
}

#cursor.big {
  width: 40px;
  height: 40px;
  background: var(--red);
}

/* ===== LOADER ===== */
#loader {
  position: fixed;
  inset: 0;
  background: var(--ink);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  gap: 18px;
  z-index: 9999;
  transition: opacity 0.5s var(--transition-smooth);
}

.loader-kanji {
  font-family: 'Noto Sans JP', sans-serif;
  font-size: 60px;
  font-weight: 900;
  color: var(--yellow);
  animation: kanjiPop 0.6s ease forwards;
  text-shadow: 0 0 40px rgba(255, 230, 0, 0.6);
}

@keyframes kanjiPop {
  0% { opacity: 0; transform: scale(0.4) rotate(-10deg); }
  100% { opacity: 1; transform: scale(1) rotate(0); }
}

.loader-text {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 14px;
  letter-spacing: 8px;
  color: var(--white);
  animation: fadeUp 0.5s ease 0.3s forwards;
  opacity: 0;
}

.loader-bar {
  width: 200px;
  height: 3px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 2px;
  overflow: hidden;
  animation: fadeUp 0.3s ease 0.6s forwards;
  opacity: 0;
}

.loader-fill {
  height: 100%;
  background: linear-gradient(90deg, var(--red), var(--yellow));
  width: 0%;
  animation: fillBar 1s ease 0.8s forwards;
}

@keyframes fadeUp {
  to { opacity: 1; transform: translateY(0); }
  from { opacity: 0; transform: translateY(10px); }
}

@keyframes fillBar {
  to { width: 100%; }
}

/* ===== HERO SECTION ===== */
.hero {
  position: sticky;
  top: 0;
  height: 100vh;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background: var(--scene-bg);
}

.hero::after {
  content: '';
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  height: 200px;
  background: linear-gradient(to bottom, rgba(2, 0, 8, 0) 0%, var(--scene-bg-solid) 85%);
  pointer-events: none;
  z-index: 4;
}

/* Background Effects */
.speed-lines {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 200vmax;
  height: 200vmax;
  opacity: 0.085;
  background: repeating-conic-gradient(
    from 0deg at 50% 50%,
    rgba(255, 255, 255, 0.9) 0deg 1.2deg,
    transparent 1.2deg 4.8deg
  );
  transform-origin: center;
  animation: rotateSlow 60s linear infinite;
  pointer-events: none;
}

@keyframes rotateSlow {
  from { transform: translate(-50%, -50%) rotate(0deg); }
  to { transform: translate(-50%, -50%) rotate(360deg); }
}

.halftone {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle, rgba(255, 230, 0, 0.08) 1px, transparent 1px);
  background-size: 24px 24px;
  pointer-events: none;
}

.slash {
  position: absolute;
  width: 4px;
  height: 140%;
  background: linear-gradient(to bottom, transparent, var(--red), transparent);
  opacity: 0.55;
  animation: slashFlicker 3s ease-in-out infinite;
}

.slash-1 { left: 18%; transform: rotate(-12deg); }
.slash-2 { right: 22%; transform: rotate(10deg); animation-delay: 1.4s; }

@keyframes slashFlicker {
  0%, 100% { opacity: 0.55; }
  50% { opacity: 0.15; }
}

.corner {
  position: absolute;
  width: 60px;
  height: 60px;
  pointer-events: none;
}

.corner::before,
.corner::after {
  content: '';
  position: absolute;
  background: var(--yellow);
}

.corner::before {
  width: 100%;
  height: 3px;
  top: 0;
  left: 0;
}

.corner::after {
  width: 3px;
  height: 100%;
  top: 0;
  left: 0;
}

.corner-tl { top: 20px; left: 20px; }
.corner-tr { top: 20px; right: 20px; transform: rotate(90deg); }
.corner-br { bottom: 20px; right: 20px; transform: rotate(180deg); }

/* Hero Text */
.hero-head {
  position: relative;
  z-index: 5;
  text-align: center;
  margin-bottom: 44px;
}

.hero-jp {
  font-family: 'Noto Sans JP', sans-serif;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 8px;
  color: var(--yellow);
  margin-bottom: 6px;
  transform: translateY(16px);
  opacity: 0;
  transition: all 0.7s ease 0.2s;
}

.hero-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(52px, 9vw, 110px);
  line-height: 0.9;
  color: var(--white2);
  letter-spacing: 4px;
  position: relative;
  transform: translateY(30px);
  opacity: 0;
  transition: all 0.8s var(--transition-smooth) 0.35s;
}

.hero-title::before,
.hero-title::after {
  content: attr(data-text);
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  font-family: inherit;
  font-size: inherit;
  letter-spacing: inherit;
  opacity: 0;
}

.hero-title::before {
  color: var(--red);
  clip-path: polygon(0 25%, 100% 25%, 100% 50%, 0 50%);
  animation: glitch1 4s infinite;
}

.hero-title::after {
  color: var(--yellow);
  clip-path: polygon(0 60%, 100% 60%, 100% 80%, 0 80%);
  animation: glitch2 4s infinite;
}

@keyframes glitch1 {
  0%, 90%, 100% { transform: translateX(0); opacity: 0; }
  92% { transform: translateX(-4px); opacity: 0.7; }
  94% { transform: translateX(4px); opacity: 0.7; }
  96% { transform: translateX(0); opacity: 0; }
}

@keyframes glitch2 {
  0%, 88%, 100% { transform: translateX(0); opacity: 0; }
  90% { transform: translateX(4px); opacity: 0.6; }
  93% { transform: translateX(-4px); opacity: 0.6; }
  95% { transform: translateX(0); opacity: 0; }
}

.hero-title span { color: var(--yellow); }
.hero-title .stroke { color: transparent; -webkit-text-stroke: 2px var(--red); }

.hero-sub {
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 5px;
  color: rgba(240, 237, 232, 0.45);
  margin-top: 10px;
  transform: translateY(16px);
  opacity: 0;
  transition: all 0.7s ease 0.55s;
}

.hero.loaded .hero-jp,
.hero.loaded .hero-title,
.hero.loaded .hero-sub {
  opacity: 1;
  transform: translateY(0);
}

.hero.loaded .hero-title::before,
.hero.loaded .hero-title::after {
  opacity: 1;
}

/* ===== CARD STAGE ===== */
.stage {
  position: relative;
  width: 100%;
  max-width: 1300px;
  height: var(--card-h);
  display: flex;
  align-items: center;
  justify-content: center;
  perspective: 1400px;
  z-index: 5;
}

.card-wrap {
  position: absolute;
  width: var(--card-w);
  height: var(--card-h);
  transform-style: preserve-3d;
  cursor: pointer;
  transition: opacity 0.6s ease, transform 0.35s ease, filter 0.35s ease, box-shadow 0.35s ease;
  will-change: transform;
}

.card-front,
.card-back {
  position: absolute;
  inset: 0;
  border-radius: 6px;
  backface-visibility: hidden;
  overflow: hidden;
 
}

/* Card Front */
.card-front {
  background: var(--ink2);
  border: 1px solid rgba(255, 230, 0, 0.12);
}

.card-front img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: all 0.35s ease;
  opacity: 1;
  filter: none;
}

.card-wrap:hover .card-front img {
  opacity: 1;
  transform: scale(1.08);
}

.card-dot {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle, rgba(0, 0, 0, 0.45) 1px, transparent 1px);
  background-size: 6px 6px;
  mix-blend-mode: multiply;
  opacity: 0.6;
  pointer-events: none;
  display: none;
}

.card-stripe {
  position: absolute;
  top: -10px;
  right: -10px;
  width: 70px;
  height: 70px;
  overflow: hidden;
}

.card-stripe::after {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  border-style: solid;
  border-width: 0 60px 60px 0;
  border-color: transparent var(--red) transparent transparent;
}

.card-front::after {
  content: '';
  position: absolute;
  inset: 0;
  background: repeating-linear-gradient(0deg,
    transparent,
    transparent 3px,
    rgba(0, 0, 0, 0.08) 3px,
    rgba(0, 0, 0, 0.08) 4px
  );
  pointer-events: none;
}

.card-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top,
    rgba(8, 8, 16, 0.65) 0%,
    rgba(8, 8, 16, 0.35) 40%,
    rgba(8, 8, 16, 0.0) 100%
  );
}

/* Badge */
.badge {
  position: absolute;
  top: 10px;
  left: -3px;
  background: var(--red);
  color: var(--white2);
  font-family: 'Bebas Neue', sans-serif;
  font-size: 13px;
  letter-spacing: 1px;
  padding: 3px 12px 3px 10px;
  clip-path: polygon(0 0, 100% 0, 93% 100%, 0 100%);
}

/* Card Info */
.card-info {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 20px 14px 14px;
}

.prod-name {
  font-family: 'Bangers', sans-serif;
  font-size: 18px;
  letter-spacing: 1.5px;
  color: var(--white2);
  margin-bottom: 10px;
  line-height: 1.1;
  text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.8);
}

.price-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.price-new {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 20px;
  color: var(--yellow);
  letter-spacing: 1px;
}
.card-wrap.show {
  box-shadow: 0 10px 36px rgba(255, 230, 0, 0.12);
}

.card-wrap.active {
  box-shadow:
    0 16px 50px rgba(255, 230, 0, 0.2),
    0 0 20px rgba(232, 0, 61, 0.28);
  filter: drop-shadow(0 0 12px rgba(255, 230, 0, 0.25));
}
.price-old {
  font-size: 11px;
  text-decoration: line-through;
  color: rgba(240, 237, 232, 0.38);
  margin-left: 4px;
}

.buy-btn {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 13px;
  letter-spacing: 2px;
  color: var(--ink);
  background: var(--yellow);
  padding: 5px 14px;
  clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%);
  text-decoration: none;
  transition: all 0.2s;
}

.buy-btn:hover {
  background: var(--white2);
  color: var(--red);
}

/* Card Back */
.card-back {
  background: var(--ink2);
  transform: rotateX(180deg);
  border: 1px solid rgba(255, 230, 0, 0.25);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 22px 18px;
  gap: 10px;
  position: relative;
}

.card-back::before {
  content: '';
  position: absolute;
  inset: 6px;
  border: 1px solid rgba(255, 230, 0, 0.1);
  border-radius: 3px;
  pointer-events: none;
}

.back-label {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 10px;
  letter-spacing: 4px;
  color: var(--red);
  background: rgba(232, 0, 61, 0.1);
  padding: 4px 12px;
  border: 1px solid rgba(232, 0, 61, 0.3);
}

.back-name {
  font-family: 'Bangers', sans-serif;
  font-size: 20px;
  letter-spacing: 2px;
  color: var(--yellow);
  text-align: center;
  text-shadow: 0 0 20px rgba(255, 230, 0, 0.4);
}

.back-line {
  width: 50px;
  height: 2px;
  background: var(--red);
}

.back-price-big {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 30px;
  letter-spacing: 2px;
  color: var(--white2);
}

.back-old-price {
  font-size: 12px;
  text-decoration: line-through;
  color: rgba(240, 237, 232, 0.35);
  margin-top: -6px;
}

.back-cta {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 14px;
  letter-spacing: 3px;
  color: var(--ink);
  background: var(--yellow);
  padding: 8px 22px;
  clip-path: polygon(10px 0%, 100% 0%, calc(100% - 10px) 100%, 0% 100%);
  text-decoration: none;
  margin-top: 6px;
  transition: all 0.2s;
}

.back-cta:hover {
  background: var(--red);
  color: var(--white2);
}

.energy-num {
  position: absolute;
  bottom: 12px;
  right: 14px;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 52px;
  color: rgba(255, 230, 0, 0.06);
  line-height: 1;
  pointer-events: none;
}

/* ===== SCROLL HINT ===== */
.scroll-hint {
  position: absolute;
  bottom: 28px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  z-index: 10;
  transition: opacity 0.4s;
}

.scroll-hint-text {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 11px;
  letter-spacing: 5px;
  color: rgba(255, 230, 0, 0.6);
}

.scroll-chevrons {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
}

.chev {
  width: 14px;
  height: 14px;
  border-right: 2px solid var(--yellow);
  border-bottom: 2px solid var(--yellow);
  transform: rotate(45deg);
  animation: chevDrop 1.6s ease-in-out infinite;
}

.chev:nth-child(2) { animation-delay: 0.2s; opacity: 0.6; }
.chev:nth-child(3) { animation-delay: 0.4s; opacity: 0.3; }

@keyframes chevDrop {
  0%, 100% { transform: rotate(45deg) translate(0, 0); }
  50% { transform: rotate(45deg) translate(3px, 3px); }
}

/* ===== MODULAR SECTIONS ===== */
.sections-container {
  position: relative;
  z-index: 1;
  background: var(--scene-bg);
}

.sections-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 220px;
  background: linear-gradient(to bottom, var(--scene-bg-solid) 0%, rgba(2, 0, 8, 0) 100%);
  pointer-events: none;
  z-index: 0;
}

.content-section {
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.5s ease;
  position: relative;
  z-index: 1;
  overflow: hidden;
  content-visibility: auto;
  contain-intrinsic-size: 900px 1000px;
}

/* Section Backgrounds with Dynamic Effects */
.section-bg {
  position: absolute;
  inset: 0;
  z-index: 0;
  transition: all 0.5s ease;
  background: var(--scene-bg);
}

.section-bg::before {
  content: '';
  position: absolute;
  inset: 0;
  background: inherit;
  filter: blur(0px);
  transition: filter 0.5s ease;
}

.section-bg.active::before {
  filter: blur(10px) brightness(1.2);
}

/* Section Particles */
.section-particles {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 1;
}

.particle {
  position: absolute;
  background: rgba(255, 255, 255, 0.5);
  border-radius: 50%;
  pointer-events: none;
  animation: float 6s ease-in-out infinite;
}

@keyframes float {
  0%, 100% { transform: translateY(0) translateX(0); }
  25% { transform: translateY(-20px) translateX(10px); }
  50% { transform: translateY(0) translateX(20px); }
  75% { transform: translateY(20px) translateX(10px); }
}

  @media (max-width: 768px) {
  body.mobile-scroll-cards .hero {
    min-height: 100svh;
  }

  body.mobile-scroll-cards .hero-head {
    text-align: center;
  }

  body.mobile-scroll-cards .hero-sub {
    opacity: 0.8;
  }

  body.mobile-scroll-cards .stage {
    position: sticky;
    top: 120px;
    height: var(--card-h);
    display: block;
    overflow: visible;
  }

  body.mobile-scroll-cards .card-wrap {
    left: 50%;
    opacity: 0;
    --card-scale: 0.94;
    transform: translate3d(-50%, 0, 0) scale(var(--card-scale)) !important;
    transition: opacity 0.5s ease, transform 0.5s ease, box-shadow 0.5s ease;
  }

  body.mobile-scroll-cards .card-wrap.show {
    opacity: 1;
  }
}

body.scroll-lock {
  height: 100%;
  overflow: hidden;
}
/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  :root {
    --card-w: 82vw;
    --card-h: 320px;
  }

  body {
    cursor: auto;
  }

  #cursor {
    display: none;
  }

  .hero {
    position: relative;
    height: auto;
    min-height: 100svh;
    padding: 60px 0 80px;
  }


  .slash,
  .speed-lines,
  .corner {
    display: none;
  }

  .ac-grid {
    display: none;
  }

  .ac-carousel {
    display: flex;
  }

  .ac-dots {
    display: flex;
  }
  .card-front{
     height: 125%;
  }
  .featured-grid,
  .category-grid,
  .testimonials-grid {
    grid-template-columns: 1fr;
  }

  .newsletter-form {
    flex-direction: column;
  }
}

 

    :root {
      --transition-smooth: cubic-bezier(0.25, 0.46, 0.45, 0.94);
      --glass-border: rgba(255, 255, 255, 0.12);
    }

    /* sections container global */
    .sections-container {
     
    }

    .content-section {
      position: relative;
     
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .section-bg {
      position: absolute;
      inset: 0;
      z-index: 0;
      opacity: 0.9;
    }

    .section-content {
      position: relative;
      z-index: 2;
      width: 100%;
    }

    /* ========== SECTION 1 – ANIME CARDS (REFINED MOBILE + DESKTOP) ========== */
    .ac-section {
      width: 100%;
     background: linear-gradient(to bottom, #000000 0%, #110921 95%);
     
      padding: 2rem 1.2rem 2.5rem;
      box-shadow: 0 30px 40px -20px rgba(0,0,0,0.8), 0 0 0 1px rgba(168, 85, 247, 0.25);
      backdrop-filter: blur(2px);
    }

    .ac-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .ac-header h1 {
      font-family: 'Bebas Neue', sans-serif;
      font-size: clamp(2.4rem, 7vw, 4rem);
      letter-spacing: 6px;
      background: linear-gradient(135deg, #fff 20%, #c28aff 70%, #ffbefd 100%);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      text-shadow: 0 2px 10px rgba(160, 120, 255, 0.3);
    }

    .ac-header p {
      color: #b69ed4;
      font-size: 0.75rem;
      letter-spacing: 5px;
      margin-top: 0.5rem;
      font-weight: 500;
    }

    /* desktop grid: 4 columns but responsive */
    .ac-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
      max-width: 1200px;
      margin: 0 auto;
      perspective: 1400px;
    }

    .ac-card {
      position: relative;
    
      overflow: visible;
      cursor: pointer;
      height: 400px;
      width: 100%;
      transition: transform 0.4s var(--transition-smooth);
      transform-style: preserve-3d;
    }

    .ac-card .ac-front {
      position: absolute;
      inset: 0;
      z-index: 5;
      border-radius: 24px;
      overflow: hidden;
      background: #15121f;
      border: 1px solid rgba(188, 148, 255, 0.25);
      transition: all 0.45s var(--transition-smooth);
      transform-origin: center bottom;
      box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.6);
    }

    /* desktop hover */
    @media (min-width: 769px) {
      .ac-card:hover .ac-front {
        transform: translateY(260px) scale(0.82) rotateX(8deg);
        opacity: 0.35;
      }
      .ac-card:hover .ac-character {
        opacity: 1;
        transform: translateY(-20px) scale(1.08);
      }
      .ac-card:hover .ac-info {
        transform: translateZ(40px) rotateX(5deg) rotateY(-5deg);
      }
      .ac-card:hover .ac-desc {
        max-height: 90px;
        opacity: 1;
      }
    }

    .ac-character {
      position: absolute;
      inset: 0;
      z-index: 20;
      opacity: 0;
      transition: all 0.55s var(--transition-smooth);
      transform: translateY(30px) scale(0.92);
      background-size: cover;
      background-position: center 15%;
      border-radius: 24px;
      display: flex;
      align-items: flex-end;
      padding: 1.5rem;
      filter: drop-shadow(0 20px 18px rgba(0, 0, 0, 0.7));
    }

    .ac-character::before {
      content: '';
      position: absolute;
      bottom: -20px;
      left: 10%;
      width: 80%;
      height: 30px;
      background: rgba(0, 0, 0, 0.6);
      border-radius: 50%;
      filter: blur(15px);
      z-index: -1;
    }

    .ac-character::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, #0b0712 0%, transparent 65%);
      border-radius: 24px;
      z-index: 1;
    }

    .ac-quote {
      position: relative;
      z-index: 5;
      color: white;
      font-family: 'Bebas Neue', sans-serif;
      font-size: 1.9rem;
      letter-spacing: 2px;
      background: linear-gradient(135deg, #fff8e7, #e2c5ff);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      line-height: 1.2;
      text-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }

    .ac-quote small {
      font-size: 0.7rem;
      display: block;
      font-family: 'Noto Sans JP', sans-serif;
      -webkit-text-fill-color: #cfb3ff;
      letter-spacing: 1px;
    }

    .ac-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(0.78) contrast(1.05);
      transition: transform 0.3s;
    }

    .ac-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, #0f0b1a 0%, rgba(30, 15, 40, 0.5) 40%, transparent 75%);
      z-index: 2;
    }

    .ac-badge {
      position: absolute;
      top: 16px;
      right: 16px;
      z-index: 10;
      background: rgba(120, 90, 210, 0.4);
      backdrop-filter: blur(10px);
      border-radius: 40px;
      padding: 4px 12px;
      font-size: 0.65rem;
      font-weight: 700;
      color: #f0e4ff;
      border: 1px solid rgba(210, 170, 255, 0.6);
      letter-spacing: 0.5px;
    }

    .ac-info {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 6;
      padding: 1.2rem;
      transition: transform 0.4s var(--transition-smooth);
    }

    .ac-genre {
      font-size: 0.6rem;
      letter-spacing: 2px;
      color: #cfb9ff;
      font-weight: 500;
    }

    .ac-title {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 1.6rem;
      color: #fff;
      letter-spacing: 2px;
      margin: 6px 0 6px;
      line-height: 1.2;
    }

    .ac-desc {
      font-size: 0.7rem;
      color: #ccbbe9;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.45s ease, opacity 0.3s;
      opacity: 0;
      margin-top: 6px;
    }

    .ac-meta {
      display: flex;
      gap: 14px;
      margin-top: 10px;
      font-size: 0.7rem;
      font-weight: 600;
    }

    .ac-rating { color: #facc15; }
    .ac-episodes { color: #a38bd6; }

    /* mobile carousel section */
    .ac-carousel {
      display: none;
      overflow-x: auto;
      scroll-snap-type: x mandatory;
      -webkit-overflow-scrolling: touch;
      gap: 1rem;
      padding: 0.8rem 0.8rem 1.8rem;
      scrollbar-width: thin;
      scrollbar-color: #b77df2 #2a1f3a;
    }
    .ac-carousel::-webkit-scrollbar {
      height: 4px;
    }
    .ac-carousel::-webkit-scrollbar-track {
      background: #2a1f3a;
      border-radius: 10px;
    }
    .ac-carousel::-webkit-scrollbar-thumb {
      background: #b77df2;
      border-radius: 10px;
    }

    .ac-carousel .ac-card {
      flex: 0 0 78vw;
      max-width: 280px;
      scroll-snap-align: center;
      height: 390px;
      transform: scale(0.98);
      transition: transform 0.2s ease;
    }
    .ac-carousel .ac-card:active {
      transform: scale(0.97);
    }

    /* touch / mobile specific: we disable hover effects, but keep 3d tilt? we add subtle active */
    @media (max-width: 768px) {
      .ac-card .ac-character {
        opacity: 0;
        pointer-events: none;
      }
      /* special: on tap we show a little quote? but we keep clean: mobile only shows standard card-front and on click we could toggle? 
         But to match smooth design, we preserve original style, but the hover effect on desktop transforms.
         For better UX, on mobile we don't show 3D popup, but we keep full card interaction with clean swipe.
      */
      .ac-card .ac-front {
        transform: none !important;
      }
      .ac-card:hover .ac-front {
        transform: none !important;
        opacity: 1;
      }
      .ac-grid {
        display: none;
      }
      .ac-carousel {
        display: flex;
      }
      .ac-dots {
        display: flex;
      }
      .ac-desc {
        max-height: 65px;
        opacity: 1;
        margin-top: 6px;
      }
      .ac-info {
        transform: none;
      }
      .ac-card .ac-front {
        transition: box-shadow 0.2s;
      }
      .ac-card:active .ac-front {
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        transform: scale(0.99);
      }
    }

    .ac-dots {
      display: none;
      justify-content: center;
      gap: 10px;
      margin-top: 0.5rem;
      margin-bottom: 0.5rem;
    }

    .ac-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #5b4c7c;
      transition: all 0.25s;
      cursor: pointer;
      border: 0;
      padding: 0;
    }

    .ac-dot.active {
      background: #dbbaff;
      width: 22px;
      border-radius: 8px;
    }

    /* ========== OTHER SECTIONS - modern & cohesive ========== */
    .featured-section {
      background: linear-gradient(125deg, #0f172a 0%, #1e1b4b 100%);
      border-radius: 2rem;
      padding: 2.5rem 2rem;
      margin-top: 1rem;
      box-shadow: 0 15px 35px rgba(0,0,0,0.2);
      border: 1px solid rgba(139, 92, 246, 0.2);
    }

    .section-title {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 2rem;
      letter-spacing: 3px;
      background: linear-gradient(135deg, #e9d5ff, #b4adff);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      margin-bottom: 1rem;
    }

    .featured-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 1.8rem;
    }

    .featured-item {
      background: rgba(255, 255, 255, 0.07);
      backdrop-filter: blur(12px);
      border-radius: 1.5rem;
      padding: 1.8rem;
      text-align: center;
      transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
      border: 1px solid rgba(255,255,240,0.12);
    }
    .featured-item:hover {
      transform: translateY(-8px);
      background: rgba(139, 92, 246, 0.2);
      border-color: rgba(188, 148, 255, 0.5);
    }
    .featured-item h3 {
      font-size: 1.5rem;
      margin: 0.7rem 0;
      color: white;
    }
    .featured-item p { color: #cfc6ff; font-size: 0.85rem; }

    .category-section {
      background: radial-gradient(circle at 10% 30%, #2b1b3a, #0b0720);
      border-radius: 2rem;
      padding: 2.5rem 2rem;
      border: 1px solid rgba(168, 85, 247, 0.3);
    }
    .category-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.8rem;
    }
    .category-card {
      background: rgba(0,0,0,0.5);
      backdrop-filter: blur(6px);
      border-radius: 1.5rem;
      padding: 1.8rem;
      text-align: center;
      transition: all 0.2s ease;
      border: 1px solid rgba(210, 170, 255, 0.2);
    }
    .category-card:hover { transform: scale(1.02); background: rgba(100, 70, 160, 0.5);}
    .category-card h3 { color: #f0d9ff; font-size: 1.5rem; margin-bottom: 0.5rem; font-weight: 700;}
    .category-card p { color: #b9a5e6; }

    .newsletter-section {
      background: linear-gradient(115deg, #0f2b3d, #1e1a4a);
      border-radius: 2rem;
      padding: 2.8rem 2rem;
      text-align: center;
    }
    .newsletter-form {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      justify-content: center;
      max-width: 520px;
      margin: 1.5rem auto 0;
    }
    .newsletter-input {
      flex: 2;
      min-width: 200px;
      padding: 0.9rem 1.3rem;
      border-radius: 60px;
      border: none;
      background: #ffffffdd;
      font-size: 0.9rem;
      outline: none;
      transition: 0.2s;
    }
    .newsletter-input:focus { background: white; box-shadow: 0 0 0 3px #b77df2; }
    .newsletter-btn {
      background: #7c3aed;
      padding: 0.9rem 1.8rem;
      border-radius: 60px;
      font-weight: bold;
      border: none;
      color: white;
      cursor: pointer;
      transition: all 0.2s;
    }
    .newsletter-btn:hover { background: #a855f7; transform: scale(0.97);}

    .testimonials-section {
      background: linear-gradient(125deg, #101728, #261c3a);
      border-radius: 2rem;
      padding: 2.5rem 2rem;
    }
    .testimonials-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 1.8rem;
    }
    .testimonial-card {
      background: rgba(20, 12, 36, 0.7);
      backdrop-filter: blur(8px);
      border-radius: 1.5rem;
      padding: 1.7rem;
      border-left: 4px solid #c084fc;
    }
    .testimonial-card p { color: #ddd6fe; font-style: italic; }
    .testimonial-card h4 { margin-top: 1rem; color: #e9d5ff; }

    @media (max-width: 550px) {
      .ac-section { padding: 1.5rem 0.8rem 1.8rem; }
      .ac-carousel .ac-card { max-width: 260px; height: 380px; }
      .featured-section, .category-section, .newsletter-section, .testimonials-section { padding: 1.8rem; }
      .newsletter-form { flex-direction: column; }
      .newsletter-btn { width: 100%; }
      .section-title { font-size: 1.8rem; text-align: center; }
    }
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


<script>
(function() {
  // Cache DOM elements
  const cursor = document.getElementById('cursor');
  const loader = document.getElementById('loader');
  const main = document.getElementById('main');
  const hero = document.getElementById('hero');
  const stage = document.getElementById('stage');
  const scrollHint = document.getElementById('scrollHint');
  const sections = document.querySelectorAll('.content-section');
  const isMobile = window.matchMedia('(max-width: 768px)').matches;
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (isMobile) document.body.classList.add('mobile-scroll-cards');

  // Failsafe: if JS runs but the load event never fires, show the page anyway.
  const failSafeTimer = setTimeout(() => {
    if (loader) loader.style.display = 'none';
    if (main) main.style.display = 'block';
  }, 3500);
  
  // ===== CURSOR =====
  if (cursor) {
    document.addEventListener('mousemove', (e) => {
      cursor.style.left = e.clientX + 'px';
      cursor.style.top = e.clientY + 'px';
    });

    document.querySelectorAll('a, .card-wrap, .buy-btn, .back-cta, .featured-item, .category-card').forEach(el => {
      el.addEventListener('mouseenter', () => cursor.classList.add('big'));
      el.addEventListener('mouseleave', () => cursor.classList.remove('big'));
    });
  }

  // ===== LOADER =====
  window.addEventListener('load', () => {
    setTimeout(() => {
      loader.style.opacity = '0';
      loader.style.transform = 'scale(1.05)';
      
      setTimeout(() => {
        loader.style.display = 'none';
        main.style.display = 'block';
        clearTimeout(failSafeTimer);
        
        // Animate hero
        setTimeout(() => hero.classList.add('loaded'), 100);
        
        // Animate cards
        const cards = Array.from({length: 5}, (_, i) => document.getElementById(`c${i}`)).filter(Boolean);
        if (!isMobile) {
          cards.forEach((card, i) => {
            setTimeout(() => { card.style.opacity = '1'; }, 200 + i * 100);
          });
        } else if (cards[0]) {
          cards[0].style.opacity = '1';
        }

        // Initialize based on device
        if (!isMobile) {
          initScrollFlip(cards);
        } else {
          initMobileStackReveal(cards);
        }
        
        // Initialize section effects (defer for perf)
        if (!isMobile && !prefersReducedMotion) {
          if ('requestIdleCallback' in window) {
            requestIdleCallback(() => initSectionEffects(), { timeout: 1500 });
          } else {
            setTimeout(() => initSectionEffects(), 800);
          }
        }
      }, 500);
    }, 500);
  });

  // ===== SCROLL FLIP EFFECT =====
  function initScrollFlip(cards) {
    if (!cards.length) return;
    
    const positions = [-480, -240, 0, 240, 480];
    const scales = [0.87, 0.93, 1.03, 0.93, 0.87];
    const zIndexes = [1, 2, 3, 2, 1];
    const focusIndex = 2;
    
    let ticking = false;
    
    function updateCards() {
      const sy = window.scrollY;
      const maxScroll = document.body.scrollHeight - window.innerHeight;
      const progress = Math.min(sy / (maxScroll * 0.6), 1);
      const deg = progress * 180;
      const pulse = 0.018 * Math.sin(progress * Math.PI);
      
      cards.forEach((card, i) => {
        if (!card) return;
        const scale = scales[i] + pulse + (i === focusIndex ? 0.02 : 0);
        card.style.transform = `translate3d(${positions[i]}px, 0, 0) scale(${scale}) rotateX(${deg}deg)`;
        card.style.zIndex = zIndexes[i];
        card.classList.toggle('active', i === focusIndex);
      });
      
      if (scrollHint) {
        scrollHint.style.opacity = sy > 40 ? '0' : '1';
      }
      
      ticking = false;
    }
    
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(updateCards);
        ticking = true;
      }
    }, { passive: true });
  }

  // ===== SECTION BACKGROUND EFFECTS =====
  function initSectionEffects() {
    if (!sections.length) return;
    
    // Create particles for each section
    sections.forEach((section, index) => {
      createParticles(section, index + 1);
    });
    
    // Track active section for background effects
    let ticking = false;
    
    function updateActiveSection() {
      const scrollY = window.scrollY;
      const windowHeight = window.innerHeight;
      
      sections.forEach((section) => {
        const rect = section.getBoundingClientRect();
        const sectionTop = rect.top + scrollY;
        const sectionBottom = sectionTop + rect.height;
        const viewportCenter = scrollY + windowHeight / 2;
        
        const bg = section.querySelector('.section-bg');
        if (!bg) return;
        
        // Check if section is in view
        if (viewportCenter >= sectionTop && viewportCenter <= sectionBottom) {
          bg.classList.add('active');
          
          // Add glow effect based on scroll position
          const progress = (viewportCenter - sectionTop) / rect.height;
          const scale = 1 + Math.sin(progress * Math.PI) * 0.1;
          bg.style.transform = `scale(${scale})`;
        } else {
          bg.classList.remove('active');
          bg.style.transform = 'scale(1)';
        }
      });
      
      ticking = false;
    }
    
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(updateActiveSection);
        ticking = true;
      }
    }, { passive: true });
    
    // Initial update
    updateActiveSection();
  }

  // ===== CREATE PARTICLES =====
  function createParticles(section, sectionId) {
    const container = section.querySelector('.section-particles');
    if (!container) return;
    
    const colors = [
      'rgba(255, 255, 255, 0.5)',
      'rgba(255, 230, 0, 0.3)',
      'rgba(232, 0, 61, 0.3)',
      'rgba(168, 85, 247, 0.3)'
    ];
    
    for (let i = 0; i < 20; i++) {
      const particle = document.createElement('div');
      particle.className = 'particle';
      
      const size = Math.random() * 10 + 5;
      const left = Math.random() * 100;
      const top = Math.random() * 100;
      const delay = Math.random() * 5;
      const duration = Math.random() * 10 + 10;
      const color = colors[Math.floor(Math.random() * colors.length)];
      
      particle.style.width = size + 'px';
      particle.style.height = size + 'px';
      particle.style.left = left + '%';
      particle.style.top = top + '%';
      particle.style.background = color;
      particle.style.animationDelay = delay + 's';
      particle.style.animationDuration = duration + 's';
      
      container.appendChild(particle);
    }
  }

  // ===== CARD CLICK HANDLER =====
  document.querySelectorAll('.card-wrap').forEach(card => {
    card.addEventListener('click', (e) => {
      if (e.target.closest('a')) return;
      const href = card.getAttribute('data-href');
      if (href) window.location.href = href;
    });
  });

  // ===== 3D TILT EFFECT =====
  const desktopCards = document.querySelectorAll('#desktopGrid .ac-card');
  desktopCards.forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      const cx = rect.width / 2;
      const cy = rect.height / 2;
      const rotY = ((x - cx) / cx) * 5;
      const rotX = -((y - cy) / cy) * 4;
      card.style.transform = `perspective(800px) rotateX(${rotX}deg) rotateY(${rotY}deg)`;
    });
    
    card.addEventListener('mouseleave', () => {
      card.style.transform = 'perspective(800px) rotateX(0) rotateY(0)';
    });
  });

  // ===== MOBILE CAROUSEL SETUP =====
  const mobileCarousel = document.getElementById('mobileCarousel');
  const dotsWrap = document.getElementById('dotsWrap');
  
  if (mobileCarousel && dotsWrap && desktopCards.length) {
    mobileCarousel.innerHTML = '';
    dotsWrap.innerHTML = '';

    desktopCards.forEach((card, idx) => {
      const clone = card.cloneNode(true);
      clone.style.transform = '';
      mobileCarousel.appendChild(clone);

      const dot = document.createElement('button');
      dot.type = 'button';
      dot.className = `ac-dot${idx === 0 ? ' active' : ''}`;
      dot.setAttribute('aria-label', `Go to card ${idx + 1}`);
      dot.addEventListener('click', () => {
        const cards = mobileCarousel.querySelectorAll('.ac-card');
        const target = cards[idx];
        if (target) target.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
      });
      dotsWrap.appendChild(dot);
    });

    const updateDots = () => {
      const cards = Array.from(mobileCarousel.querySelectorAll('.ac-card'));
      if (!cards.length) return;

      const center = mobileCarousel.scrollLeft + mobileCarousel.clientWidth / 2;
      let activeIndex = 0;
      let best = Infinity;

      cards.forEach((card, i) => {
        const cardCenter = card.offsetLeft + card.offsetWidth / 2;
        const diff = Math.abs(cardCenter - center);
        if (diff < best) {
          best = diff;
          activeIndex = i;
        }
      });

      dotsWrap.querySelectorAll('.ac-dot').forEach((dot, i) => {
        dot.classList.toggle('active', i === activeIndex);
      });
    };

    mobileCarousel.addEventListener('scroll', () => {
      requestAnimationFrame(updateDots);
    }, { passive: true });

    updateDots();
  }
})();
function initMobileStackReveal(cards) {
  if (!cards.length || !hero) return;
  const cardList = Array.from(cards);
  hero.style.minHeight = '100svh';
  document.body.classList.add('scroll-lock');

  cardList.forEach((card) => {
    card.classList.remove('show');
    card.style.transitionDelay = '0ms';
  });

  let progress = 0;
  let ticking = false;
  let touchY = null;
  const clamp = (v) => Math.min(Math.max(v, 0), 1);
  const setScrollLock = (locked) => {
    document.body.classList.toggle('scroll-lock', locked);
  };

  const isHeroActive = () => {
    const rect = hero.getBoundingClientRect();
    return rect.top <= 0 && rect.bottom >= window.innerHeight * 0.6;
  };

  function updateCards() {
    const exactIndex = progress * cardList.length;
    const activeIndex = Math.min(Math.floor(exactIndex), cardList.length - 1);
    const stepProgress = exactIndex - activeIndex;
    const nextReveal = prefersReducedMotion ? (stepProgress > 0.05 ? 1 : 0) : Math.max(0, (stepProgress - 0.12) / 0.5);

    cardList.forEach((card, i) => {
      const isActive = i === activeIndex;
      const isNext = i === activeIndex + 1;
      const baseScale = isActive ? 1 : 0.94;
      const scale = baseScale + (isActive ? 0.015 : 0);
      const opacity = isActive ? 1 - nextReveal : (isNext ? nextReveal : 0);

      card.style.setProperty('--card-scale', scale.toFixed(3));
      card.classList.toggle('show', isActive || (isNext && opacity > 0));
      card.classList.toggle('active', isActive);
      card.style.opacity = opacity.toFixed(3);
      card.style.transform = `translate3d(-50%, ${isActive ? 0 : 18}px, 0) scale(${scale})`;
    });

    ticking = false;
  }

  function onWheel(e) {
    const inHero = isHeroActive();
    if (!inHero) {
      setScrollLock(false);
      return;
    }

    const delta = e.deltaY;
    if ((delta > 0 && progress >= 1) || (delta < 0 && progress <= 0)) {
      setScrollLock(false);
      return;
    }

    setScrollLock(true);
    e.preventDefault();
    progress = clamp(progress + delta * 0.0009);
    if (!ticking) {
      requestAnimationFrame(updateCards);
      ticking = true;
    }
  }

  function onTouchStart(e) {
    if (e.touches && e.touches.length) touchY = e.touches[0].clientY;
  }

  function onTouchMove(e) {
    if (touchY === null || !e.touches || !e.touches.length) return;
    const currentY = e.touches[0].clientY;
    const delta = touchY - currentY;
    touchY = currentY;
    const inHero = isHeroActive();
    if (!inHero) {
      setScrollLock(false);
      return;
    }
    if ((delta > 0 && progress >= 1) || (delta < 0 && progress <= 0)) {
      setScrollLock(false);
      return;
    }

    setScrollLock(true);
    e.preventDefault();
    progress = clamp(progress + delta * 0.0022);
    if (!ticking) {
      requestAnimationFrame(updateCards);
      ticking = true;
    }
  }

  window.addEventListener('wheel', onWheel, { passive: false });
  window.addEventListener('touchstart', onTouchStart, { passive: false });
  window.addEventListener('touchmove', onTouchMove, { passive: false });

  updateCards();
}
</script>

</body>
</html>
