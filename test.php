<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'temp_db.php';

// Optimized query with caching headers
$exclusiveProducts = $conn->query("
  SELECT id, image, name, original_price, discount_price 
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

html {
  scroll-behavior: smooth;
}

body {
  font-family: 'Rajdhani', sans-serif;
  background: var(--white2);
  color: var(--ink);
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
  background: var(--ink);
}

/* Background Effects */
.speed-lines {
  position: absolute;
  inset: 0;
  opacity: 0.04;
  background: repeating-conic-gradient(from 0deg at 50% 50%, #fff 0deg 1deg, transparent 1deg 6deg);
  animation: rotateSlow 60s linear infinite;
}

@keyframes rotateSlow {
  to { transform: rotate(360deg); }
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
  transition: opacity 0.6s ease, transform 0.1s linear;
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
  opacity: 0.75;
  transition: all 0.35s ease;
  filter: saturate(1.1) contrast(1.05);
}

.card-wrap:hover .card-front img {
  opacity: 0.92;
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
    rgba(8, 8, 16, 0.95) 0%,
    rgba(8, 8, 16, 0.55) 40%,
    rgba(8, 8, 16, 0.05) 100%
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
}

.content-section {
  min-height: 100vh;
  padding: 4rem 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.5s ease;
  position: relative;
  overflow: hidden;
}

/* Section Backgrounds with Dynamic Effects */
.section-bg {
  position: absolute;
  inset: 0;
  z-index: 0;
  transition: all 0.5s ease;
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

/* Section Content */
.section-content {
  position: relative;
  z-index: 2;
  max-width: 1300px;
  width: 100%;
  margin: 0 auto;
}

/* Section 1 - Anime Cards (Original) */
.anime-section {
  width: 100%;
  background: radial-gradient(ellipse at 30% 40%, #1e102a 0%, #08060c 90%);
  border-radius: 3rem;
  padding: 2.5rem 1.5rem 3rem;
  box-shadow: 0 30px 40px -20px black, 0 0 0 1px rgba(168, 85, 247, 0.2);
}

.section-header {
  text-align: center;
  margin-bottom: 2.5rem;
}

.section-header h1 {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(2.5rem, 6vw, 4rem);
  letter-spacing: 6px;
  background: linear-gradient(135deg, #fff 20%, #b77df2 70%, #ffb3fe 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.section-header p {
  color: #8f7aa8;
  font-size: 0.8rem;
  letter-spacing: 4px;
  margin-top: 0.5rem;
}

.cards-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  max-width: 1100px;
  margin: 0 auto;
  perspective: 1400px;
}

.anime-card {
  position: relative;
  border-radius: 24px;
  overflow: visible;
  cursor: pointer;
  height: 400px;
  width: 100%;
  transition: transform 0.4s var(--transition-smooth);
  transform-style: preserve-3d;
}

.anime-card .card-front {
  position: absolute;
  inset: 0;
  z-index: 5;
  border-radius: 24px;
  overflow: hidden;
  background: #15121f;
  border: 1px solid rgba(188, 148, 255, 0.2);
  transition: all 0.4s var(--transition-smooth);
  transform-origin: center bottom;
  box-shadow: 0 15px 25px -10px rgba(0, 0, 0, 0.7);
}

.anime-card:hover .card-front {
  transform: translateY(280px) scale(0.8) rotateX(8deg);
  opacity: 0.4;
}

.character-3d {
  position: absolute;
  inset: 0;
  z-index: 20;
  opacity: 0;
  transition: all 0.5s var(--transition-smooth);
  transform: translateY(20px) scale(0.9);
  background-size: cover;
  background-position: center 20%;
  border-radius: 24px;
  display: flex;
  align-items: flex-end;
  padding: 1.5rem;
  filter: drop-shadow(0 20px 15px rgba(0, 0, 0, 0.6));
}

.character-3d::before {
  content: '';
  position: absolute;
  bottom: -20px;
  left: 10%;
  width: 80%;
  height: 30px;
  background: rgba(0, 0, 0, 0.5);
  border-radius: 50%;
  filter: blur(12px);
  z-index: -1;
}

.character-3d::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, #0b0712 0%, transparent 60%);
  border-radius: 24px;
  z-index: 1;
}

.anime-card:hover .character-3d {
  opacity: 1;
  transform: translateY(-15px) scale(1.1);
}

.char-quote {
  position: relative;
  z-index: 5;
  color: white;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 2rem;
  letter-spacing: 2px;
  transition: all 0.45s var(--transition-smooth);
  background: linear-gradient(135deg, #fff8e7, #e4c4ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  line-height: 1.2;
}

.char-quote small {
  font-size: 0.75rem;
  display: block;
  font-family: 'Noto Sans JP', sans-serif;
  -webkit-text-fill-color: #bfa8e6;
}

.card-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: brightness(0.75);
}

.card-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, #0f0b1a 0%, rgba(30, 15, 40, 0.5) 40%, transparent 70%);
  z-index: 2;
}

.card-badge {
  position: absolute;
  top: 16px;
  right: 16px;
  z-index: 10;
  background: rgba(150, 120, 255, 0.25);
  backdrop-filter: blur(8px);
  border-radius: 30px;
  padding: 5px 12px;
  font-size: 0.7rem;
  color: #e6d0ff;
  border: 1px solid rgba(220, 190, 255, 0.5);
  font-weight: 700;
}

.card-info {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 6;
  padding: 1.5rem;
  transform-style: preserve-3d;
  transition: transform 0.45s var(--transition-smooth);
}

.card-genre {
  font-size: 0.65rem;
  letter-spacing: 2.5px;
  color: #c7adff;
}

.card-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 1.8rem;
  color: #fff;
  letter-spacing: 2px;
  margin: 5px 0 8px;
}

.card-desc {
  font-size: 0.72rem;
  color: #a694c2;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.4s;
  opacity: 0;
}

.anime-card:hover .card-desc {
  max-height: 80px;
  opacity: 1;
}

.card-meta {
  display: flex;
  gap: 12px;
  margin-top: 12px;
  font-size: 0.75rem;
  font-weight: 700;
}

.card-rating { color: #facc15; }
.card-episodes { color: #7b6b96; }

.anime-card:hover .card-info {
  transform: translateZ(45px) rotateX(6deg) rotateY(-6deg);
}

.anime-card:hover .char-quote {
  transform: translateZ(35px) rotateX(5deg) rotateY(5deg);
  text-shadow: 0 8px 25px rgba(183, 125, 242, 0.6);
}

/* Mobile Carousel */
.cards-carousel {
  display: none;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
  gap: 1.2rem;
  padding: 1rem 1.5rem 2rem;
  scrollbar-width: none;
}

.cards-carousel::-webkit-scrollbar {
  display: none;
}

.cards-carousel .anime-card {
  flex: 0 0 75vw;
  max-width: 280px;
  scroll-snap-align: center;
}

.carousel-dots {
  display: none;
  justify-content: center;
  gap: 8px;
  margin-top: 1.2rem;
}

.dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #423b5c;
  transition: all 0.2s;
}

.dot.active {
  background: #c7a2ff;
  width: 20px;
}

/* ===== SECTION 2 - Featured Products ===== */
.featured-section {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 3rem;
  padding: 3rem;
}

.featured-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
  margin-top: 2rem;
}

.featured-item {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 2rem;
  text-align: center;
  color: white;
  transition: transform 0.3s ease;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.featured-item:hover {
  transform: translateY(-10px);
  background: rgba(255, 255, 255, 0.2);
}

/* ===== SECTION 3 - Category Showcase ===== */
.category-section {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  border-radius: 3rem;
  padding: 3rem;
}

.category-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
  margin-top: 2rem;
}

.category-card {
  background: white;
  border-radius: 20px;
  padding: 2rem;
  text-align: center;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

/* ===== SECTION 4 - Newsletter ===== */
.newsletter-section {
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  border-radius: 3rem;
  padding: 4rem;
  text-align: center;
  color: white;
}

.newsletter-form {
  max-width: 500px;
  margin: 2rem auto 0;
  display: flex;
  gap: 1rem;
}

.newsletter-input {
  flex: 1;
  padding: 1rem;
  border: none;
  border-radius: 50px;
  font-size: 1rem;
}

.newsletter-btn {
  padding: 1rem 2rem;
  background: #333;
  color: white;
  border: none;
  border-radius: 50px;
  cursor: pointer;
  transition: background 0.3s;
}

.newsletter-btn:hover {
  background: #000;
}

/* ===== SECTION 5 - Testimonials ===== */
.testimonials-section {
  background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
  border-radius: 3rem;
  padding: 3rem;
}

.testimonials-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2rem;
  margin-top: 2rem;
}

.testimonial-card {
  background: white;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  :root {
    --card-w: 75vw;
    --card-h: 270px;
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

  .stage {
    position: static;
    height: auto;
    display: flex;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    gap: 18px;
    padding: 0 20px;
    -webkit-overflow-scrolling: touch;
  }

  .card-wrap {
    position: relative;
    flex: 0 0 var(--card-w);
    transform: none !important;
    opacity: 1 !important;
    scroll-snap-align: center;
  }

  .slash,
  .speed-lines,
  .corner {
    display: none;
  }

  .cards-grid {
    display: none;
  }

  .cards-carousel {
    display: flex;
  }

  .carousel-dots {
    display: flex;
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
</style>
</head>
<body>

<div id="cursor"></div>

<!-- LOADER -->
<div id="loader">
  <div class="loader-kanji">専</div>
  <div class="loader-text">Initializing Exclusive Mode</div>
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
        <span class="stroke">EXCL</span><span>USIVE</span><br>
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
      ?>
      <div class="card-wrap" id="c<?= $i ?>" data-href="orders/product_detail.php?id=<?= $id ?>"
           style="transform: translate(<?= $positions[$i] ?>px, 0) scale(<?= $scales[$i] ?>);
                  z-index: <?= $zIndexes[$i] ?>; opacity: 0;">
        <div class="card-front">
          <img src="orders/uploads/<?= $img ?>" alt="<?= $name ?>" loading="lazy">
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

  <!-- MODULAR SECTIONS CONTAINER -->
  <div class="sections-container" id="sectionsContainer">
    
    <!-- SECTION 1 - Anime Cards (Original) -->
    <section class="content-section" data-section="1">
      <div class="section-bg" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);"></div>
      <div class="section-particles" id="particles-1"></div>
      <div class="section-content">
        <div class="anime-section">
          <div class="section-header">
            <h1>Coming Soon</h1>
            <p>3D stands</p>
          </div>

          <!-- Desktop Grid -->
          <div class="cards-grid" id="desktopGrid">
            <!-- Demon Slayer -->
            <div class="anime-card">
              <div class="card-front">
                <img class="card-img" src="https://i.imgur.com/0y8Ftya.jpg" onerror="this.style.background='#130726'" alt="Demon Slayer" loading="lazy">
                <div class="card-overlay"></div>
                <div class="card-badge">🔥 Trending</div>
                <div class="card-info">
                  <div class="card-genre">Action · Supernatural</div>
                  <div class="card-title">DEMON SLAYER</div>
                  <div class="card-desc">Tanjiro fights to save Nezuko. Flames, water, unwavering bonds.</div>
                  <div class="card-meta"><span class="card-rating">★ 9.4</span><span class="card-episodes">44 eps</span></div>
                </div>
              </div>
              <div class="character-3d" style="background-image: url('1.png'); background-position: center 10%;">
                <div class="char-quote">TANJIRO <small>水の呼吸</small></div>
              </div>
            </div>

            <!-- Attack on Titan -->
            <div class="anime-card">
              <div class="card-front">
                <img class="card-img" src="https://i.imgur.com/kBSWcR9.jpg" onerror="this.style.background='#09253a'" alt="Attack on Titan" loading="lazy">
                <div class="card-overlay"></div>
                <div class="card-badge">⚔️ Epic</div>
                <div class="card-info">
                  <div class="card-genre">Dark Fantasy · War</div>
                  <div class="card-title">ATTACK ON TITAN</div>
                  <div class="card-desc">Humanity's last stand. The truth will shatter everything.</div>
                  <div class="card-meta"><span class="card-rating">★ 9.9</span><span class="card-episodes">87 eps</span></div>
                </div>
              </div>
              <div class="character-3d" style="background-image: url('https://i.imgur.com/hHRs5A8.jpg'); background-position: center 20%;">
                <div class="char-quote">EREN <small>進撃</small></div>
              </div>
            </div>

            <!-- Jujutsu Kaisen -->
            <div class="anime-card">
              <div class="card-front">
                <img class="card-img" src="https://i.imgur.com/LwzrxvJ.jpg" onerror="this.style.background='#172012'" alt="Jujutsu Kaisen" loading="lazy">
                <div class="card-overlay"></div>
                <div class="card-badge">💀 Dark</div>
                <div class="card-info">
                  <div class="card-genre">Occult · Shonen</div>
                  <div class="card-title">JUJUTSU KAISEN</div>
                  <div class="card-desc">Cursed energy, fingers, and the will to fight for the right death.</div>
                  <div class="card-meta"><span class="card-rating">★ 9.3</span><span class="card-episodes">48 eps</span></div>
                </div>
              </div>
              <div class="character-3d" style="background-image: url('https://i.imgur.com/tfcn45t.jpg'); background-position: center 30%;">
                <div class="char-quote">YUJI <small>呪術</small></div>
              </div>
            </div>

            <!-- One Piece -->
            <div class="anime-card">
              <div class="card-front">
                <img class="card-img" src="https://i.imgur.com/G4yOd9c.jpg" onerror="this.style.background='#231d09'" alt="One Piece" loading="lazy">
                <div class="card-overlay"></div>
                <div class="card-badge">🌊 Legend</div>
                <div class="card-info">
                  <div class="card-genre">Adventure · Pirate</div>
                  <div class="card-title">ONE PIECE</div>
                  <div class="card-desc">The king of pirates, a rubber boy, and the great treasure.</div>
                  <div class="card-meta"><span class="card-rating">★ 9.7</span><span class="card-episodes">1000+ eps</span></div>
                </div>
              </div>
              <div class="character-3d" style="background-image: url('https://i.imgur.com/qhPqk8d.jpg'); background-position: center 25%;">
                <div class="char-quote">LUFFY <small>ギア5</small></div>
              </div>
            </div>
          </div>

          <!-- Mobile Carousel -->
          <div class="cards-carousel" id="mobileCarousel"></div>
          <div class="carousel-dots" id="dotsWrap">
            <div class="dot active"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

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
        cards.forEach((card, i) => {
          setTimeout(() => { card.style.opacity = '1'; }, 200 + i * 100);
        });

        // Initialize based on device
        if (window.innerWidth > 768) {
          initScrollFlip(cards);
        } else {
          initMobileCarousel();
        }
        
        // Initialize section effects
        initSectionEffects();
      }, 500);
    }, 1900);
  });

  // ===== SCROLL FLIP EFFECT =====
  function initScrollFlip(cards) {
    if (!cards.length) return;
    
    const positions = [-480, -240, 0, 240, 480];
    const scales = [0.87, 0.93, 1.03, 0.93, 0.87];
    const zIndexes = [1, 2, 3, 2, 1];
    
    let ticking = false;
    
    function updateCards() {
      const sy = window.scrollY;
      const maxScroll = document.body.scrollHeight - window.innerHeight;
      const progress = Math.min(sy / (maxScroll * 0.6), 1);
      const deg = progress * 180;
      
      cards.forEach((card, i) => {
        if (!card) return;
        card.style.transform = `translate(${positions[i]}px, 0) scale(${scales[i]}) rotateX(${deg}deg)`;
        card.style.zIndex = zIndexes[i];
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

  // ===== MOBILE CAROUSEL =====
  function initMobileCarousel() {
    if (!stage) return;
    
    const firstCard = stage.querySelector('.card-wrap');
    if (!firstCard) return;
    
    const step = firstCard.offsetWidth + 18;
    let pos = 0;
    
    setInterval(() => {
      pos += step;
      if (pos >= stage.scrollWidth - stage.offsetWidth + 10) pos = 0;
      stage.scrollTo({ left: pos, behavior: 'smooth' });
    }, 3000);
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
  const desktopCards = document.querySelectorAll('#desktopGrid .anime-card');
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
  const dots = document.querySelectorAll('.dot');
  
  if (mobileCarousel && desktopCards.length) {
    desktopCards.forEach(card => {
      const clone = card.cloneNode(true);
      mobileCarousel.appendChild(clone);
    });
    
    // Scroll dots
    mobileCarousel.addEventListener('scroll', () => {
      const idx = Math.round(mobileCarousel.scrollLeft / (mobileCarousel.offsetWidth * 0.75));
      dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === idx);
      });
    });
  }
})();
</script>

</body>
</html>
