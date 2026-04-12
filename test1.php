<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes, viewport-fit=cover">
  <title>AnimeVerse | 3D Stands & Premium Showcase</title>
  <!-- Google Fonts + smooth reset -->
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
  
  </style>
</head>
<body>

  
<script>
  (function() {
    // ===== 1. Mobile Carousel Setup with smooth dots & cloning
    const desktopGrid = document.getElementById('desktopGrid');
    const mobileCarousel = document.getElementById('mobileCarousel');
    const dotsWrap = document.getElementById('dotsWrap');
    if (mobileCarousel && desktopGrid) {
      const originalCards = Array.from(desktopGrid.querySelectorAll('.anime-card'));
      mobileCarousel.innerHTML = '';
      // clone cards for carousel
      originalCards.forEach(card => {
        const cloneCard = card.cloneNode(true);
        // remove any event listener conflicts but keep structure
        cloneCard.classList.add('carousel-card-clone');
        mobileCarousel.appendChild(cloneCard);
      });
      
      // generate dots dynamically based on clone count
      const total = originalCards.length;
      dotsWrap.innerHTML = '';
      for (let i = 0; i < total; i++) {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (i === 0) dot.classList.add('active');
        dot.setAttribute('data-index', i);
        dot.addEventListener('click', () => {
          if (mobileCarousel) {
            const cardWidth = mobileCarousel.querySelector('.anime-card')?.offsetWidth || 260;
            const gap = 16;
            const scrollPosition = i * (cardWidth + gap);
            mobileCarousel.scrollTo({ left: scrollPosition, behavior: 'smooth' });
          }
        });
        dotsWrap.appendChild(dot);
      }
      
      // sync dots on scroll
      function updateDots() {
        const carouselRect = mobileCarousel;
        if (!carouselRect) return;
        const scrollLeft = carouselRect.scrollLeft;
        const cards = Array.from(mobileCarousel.querySelectorAll('.anime-card'));
        if (cards.length === 0) return;
        const cardWidth = cards[0].offsetWidth;
        const gap = parseFloat(getComputedStyle(mobileCarousel).gap) || 16;
        const itemWidth = cardWidth + gap;
        let activeIndex = Math.round(scrollLeft / itemWidth);
        activeIndex = Math.min(Math.max(activeIndex, 0), cards.length - 1);
        const dots = dotsWrap.querySelectorAll('.dot');
        dots.forEach((dot, idx) => {
          dot.classList.toggle('active', idx === activeIndex);
        });
      }
      
      mobileCarousel.addEventListener('scroll', () => { requestAnimationFrame(updateDots); });
      window.addEventListener('resize', () => { updateDots(); });
      setTimeout(updateDots, 100);
    }

    // ===== 2. DESKTOP 3D TILT (Only for desktop media)
    function initDesktopTilt() {
      const cards = document.querySelectorAll('#desktopGrid .anime-card');
      if (window.innerWidth <= 768) return;
      cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
          const rect = card.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          const cx = rect.width / 2;
          const cy = rect.height / 2;
          let rotY = ((x - cx) / cx) * 6;
          let rotX = -((y - cy) / cy) * 5;
          rotY = Math.min(Math.max(rotY, -8), 8);
          rotX = Math.min(Math.max(rotX, -6), 6);
          card.style.transform = `perspective(1000px) rotateX(${rotX}deg) rotateY(${rotY}deg)`;
        });
        card.addEventListener('mouseleave', () => {
          card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
        });
      });
    }
    initDesktopTilt();
    window.addEventListener('resize', () => { initDesktopTilt(); });
    
    // 3. small fix for character 3d images fallback
    const charDivs = document.querySelectorAll('.character-3d');
    charDivs.forEach(div => {
      const bgImg = div.style.backgroundImage;
      if (!bgImg || bgImg === 'none' || bgImg === '') {
        div.style.backgroundImage = "url('https://i.imgur.com/placeholder.png')";
        div.style.backgroundSize = 'cover';
      }
    });
    
    // 4. Mobile tap improvements: optional prevent double hover weirdness
    if ('ontouchstart' in window) {
      document.querySelectorAll('.anime-card').forEach(card => {
        card.addEventListener('touchstart', function(e) {
          // just light feedback no 3D transform
          this.style.transform = 'scale(0.98)';
          setTimeout(() => { this.style.transform = ''; }, 150);
        });
      });
    }
    
    // 5. smooth newsletter subscription simulation (just UI)
    const subBtn = document.querySelector('.newsletter-btn');
    if (subBtn) {
      subBtn.addEventListener('click', (e) => {
        const input = document.querySelector('.newsletter-input');
        if (input && input.value.trim() !== '') {
          alert('✨ Thanks! You’re now part of the inner circle.');
          input.value = '';
        } else {
          alert('Please enter a valid email to get epic updates.');
        }
      });
    }
  })();
</script>
</body>
</html>