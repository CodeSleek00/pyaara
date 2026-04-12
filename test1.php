
  <style>

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
  </style>
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