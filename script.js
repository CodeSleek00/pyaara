const newsItems = [
    "Fresh:  Freshly stocked items!",
    "New Arrival: Check out our latest collection!",
    "Sale: Up to 60% off on selected items!",
    "Delivery: Free shipping on razorpay orders!",
];

let currentIndex = 0;

function showNews() {
    const newsItemElement = document.getElementById('news-item');
    newsItemElement.style.opacity = 0; // Fade out

    setTimeout(() => {
        newsItemElement.textContent = newsItems[currentIndex];
        newsItemElement.style.opacity = 1; // Fade in

        currentIndex = (currentIndex + 1) % newsItems.length; // Loop through news items
    }, 500); // Wait for fade out to complete

    setTimeout(showNews, 3000); // Change news every 3 seconds
}

// Start the news ticker
showNews();
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const searchButton = document.querySelector('.search-button');
    const searchInput = document.querySelector('.search input[type="search"]');
    const cartIcon = document.querySelector('.cart-icon-container');
    const userIcon = document.querySelector('.user-icon-container');
    const logo = document.querySelector('.logo');
    
    // Cart items count (simulated)
    let cartItemsCount = 3;
    updateCartBadge();
    
    // Search functionality
    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') performSearch();
    });
    
    function performSearch() {
        const searchTerm = searchInput.value.trim();
        if (searchTerm) {
            console.log(`Searching for: ${searchTerm}`);
            // Show loading state
            searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            searchButton.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                alert(`Showing results for: ${searchTerm}`);
                // Reset search button
                searchButton.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i>';
                searchButton.disabled = false;
            }, 800);
        }
    }
    
    // Cart icon functionality
    cartIcon.addEventListener('click', function() {
        this.classList.add('clicked');
        setTimeout(() => {
            this.classList.remove('clicked');
            window.location.href = '/cart';
        }, 200);
    });
    
    // User icon functionality
    userIcon.addEventListener('click', function() {
        // Toggle user dropdown (simulated)
        const dropdown = document.createElement('div');
        dropdown.className = 'user-dropdown';
        dropdown.innerHTML = `
            <a href="/account">My Account</a>
            <a href="/orders">My Orders</a>
            <a href="/logout">Logout</a>
        `;
        document.body.appendChild(dropdown);
        
        // Position dropdown
        const rect = this.getBoundingClientRect();
        dropdown.style.top = `${rect.bottom + 5}px`;
        dropdown.style.right = `${window.innerWidth - rect.right}px`;
        
        // Close dropdown when clicking outside
        setTimeout(() => {
            document.addEventListener('click', function closeDropdown(e) {
                if (!dropdown.contains(e.target) && e.target !== userIcon) {
                    dropdown.remove();
                    document.removeEventListener('click', closeDropdown);
                }
            });
        }, 0);
    });
    
    
    // Simulate adding to cart
    window.addToCart = function() {
        cartItemsCount++;
        updateCartBadge();
        
        // Show animation
        const cartIconImg = document.querySelector('img[alt="shopping-cart"]');
        cartIconImg.classList.add('pulse');
        setTimeout(() => {
            cartIconImg.classList.remove('pulse');
        }, 500);
    }
});




 document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const mobileNav = document.querySelector('.mobile-nav');
            const overlay = document.querySelector('.overlay');
            const mobileDropdowns = document.querySelectorAll('.mobile-dropdown');

            // Toggle mobile menu
            menuToggle.addEventListener('click', function() {
                this.classList.toggle('active');
                mobileNav.classList.toggle('active');
                overlay.classList.toggle('active');
            });

            // Close menu when clicking overlay
            overlay.addEventListener('click', function() {
                menuToggle.classList.remove('active');
                mobileNav.classList.remove('active');
                this.classList.remove('active');
            });

            // Mobile dropdown functionality
            mobileDropdowns.forEach(dropdown => {
                const link = dropdown.querySelector('a');
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdown.classList.toggle('active');
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle (Header)
    const menuToggle = document.querySelector('.menu-toggle');
    const mobileNav = document.querySelector('.mobile-nav');
    const overlay = document.createElement('div');
    overlay.className = 'overlay';
    document.body.appendChild(overlay);

    // Mobile Footer Menu Toggle
    const footerToggle = document.querySelector('.footer-toggle');
    
    // Toggle mobile menu
    function toggleMobileMenu() {
        menuToggle.classList.toggle('active');
        mobileNav.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.classList.toggle('no-scroll');
    }

    // Setup event listeners for both toggles
    menuToggle.addEventListener('click', toggleMobileMenu);
    if (footerToggle) {
        footerToggle.addEventListener('click', toggleMobileMenu);
    }

    // Close mobile menu when clicking overlay
    overlay.addEventListener('click', toggleMobileMenu);

    // Mobile dropdown functionality
    const mobileDropdowns = document.querySelectorAll('.mobile-dropdown > a');
    mobileDropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            parent.classList.toggle('active');
            
            // Close other open dropdowns
            mobileDropdowns.forEach(otherDropdown => {
                if (otherDropdown !== dropdown) {
                    otherDropdown.parentElement.classList.remove('active');
                }
            });
        });
    });

    // Prevent body scroll when mobile menu is open
    document.body.classList.add('scroll-enabled');
    const noScrollClass = 'no-scroll';
    
    // Add CSS for no-scroll class
    const style = document.createElement('style');
    style.textContent = `
        .no-scroll {
            overflow: hidden;
            position: fixed;
            width: 100%;
            height: 100%;
        }
    `;
    document.head.appendChild(style);

    // Highlight active footer menu item
    const footerMenuItems = document.querySelectorAll('.footer-menu-item');
    footerMenuItems.forEach(item => {
        item.addEventListener('click', function() {
            footerMenuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Close mobile menu when clicking a link (optional)
    const mobileNavLinks = document.querySelectorAll('.mobile-nav a:not(.mobile-dropdown > a)');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (mobileNav.classList.contains('active')) {
                toggleMobileMenu();
            }
        });
    });
});
 // ==== Desktop Carousel Logic ====
    const desktopCarousel = document.getElementById("desktop-carousel");
    const desktopImages = desktopCarousel.children;
    let desktopIndex = 0;

    function updateDesktopSlide() {
      desktopCarousel.style.transform = `translateX(-${desktopIndex * 100}%)`;
    }

    function nextDesktopSlide() {
      desktopIndex = (desktopIndex + 1) % desktopImages.length;
      updateDesktopSlide();
    }

    function prevDesktopSlide() {
      desktopIndex = (desktopIndex - 1 + desktopImages.length) % desktopImages.length;
      updateDesktopSlide();
    }

    setInterval(nextDesktopSlide, 4000);

    // Manual desktop click
    const desktopOverlay = document.getElementById("desktop-overlay");
    desktopOverlay.addEventListener("click", (e) => {
      const clickX = e.clientX;
      const screenWidth = window.innerWidth;
      if (clickX < screenWidth / 2) {
        prevDesktopSlide();
      } else {
        nextDesktopSlide();
      }
    });

    // ==== Mobile Carousel Logic ====
    const mobileCarousel = document.getElementById("mobile-carousel");
    const mobileImages = mobileCarousel.children;
    let mobileIndex = 0;

    function updateMobileSlide() {
      mobileCarousel.style.transform = `translateX(-${mobileIndex * 100}%)`;
    }

    function nextMobileSlide() {
      mobileIndex = (mobileIndex + 1) % mobileImages.length;
      updateMobileSlide();
    }

    function prevMobileSlide() {
      mobileIndex = (mobileIndex - 1 + mobileImages.length) % mobileImages.length;
      updateMobileSlide();
    }

    setInterval(nextMobileSlide, 4000);

    // Manual mobile click
    const mobileOverlay = document.getElementById("mobile-overlay");
    mobileOverlay.addEventListener("click", (e) => {
      const clickX = e.clientX;
      const screenWidth = window.innerWidth;
      if (clickX < screenWidth / 2) {
        prevMobileSlide();
      } else {
        nextMobileSlide();
      }
    });
 // Tab functionality
        const tabs = document.querySelectorAll('.tab');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                tab.classList.add('active');
                
                // In a real implementation, you would filter products here
                // For this demo, we'll just log the selected category
                console.log(`Filtering by: ${tab.textContent}`);
            });
        });
        
        // Add to cart functionality
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productCard = this.closest('.product-card');
                const productTitle = productCard.querySelector('.product-title').textContent;
                const productPrice = productCard.querySelector('.current-price').textContent;
                
                // In a real implementation, you would add to cart here
                // For this demo, we'll just show an alert
                alert(`Added to cart: ${productTitle} - ${productPrice}`);
                
                // Animation feedback
                this.innerHTML = '<i class="fas fa-check"></i> Added!';
                this.style.backgroundColor = '#4CAF50';
                
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
                    this.style.backgroundColor = '';
                }, 2000);
            });
        });
        
        // Wishlist functionality
        document.addEventListener('DOMContentLoaded', function() {
            const wishlistButtons = document.querySelectorAll('.action-btn:nth-child(1)');
            
            wishlistButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    
                    if (icon.classList.contains('far')) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        icon.style.color = 'var(--primary-red)';
                        // In real implementation, add to wishlist
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        icon.style.color = '';
                        // In real implementation, remove from wishlist
                    }
                });
            });
            
            // Infinite scroll effect for mobile
            const scrollerInner = document.querySelector('.scroller-inner');
            if (scrollerInner) {
                // Clone the first few items and append to end for seamless looping
                const items = scrollerInner.querySelectorAll('.product-card');
                const firstFewItems = Array.from(items).slice(0, 4);
                
                firstFewItems.forEach(item => {
                    const clone = item.cloneNode(true);
                    scrollerInner.appendChild(clone);
                });
                
                // Reset position when animation completes
                scrollerInner.addEventListener('animationiteration', () => {
                    scrollerInner.style.animation = 'none';
                    scrollerInner.offsetHeight; // Trigger reflow
                    scrollerInner.style.animation = 'scroll 20s linear infinite';
                });
            }
        });
         // Accordion functionality
    document.querySelectorAll(".accordion-item > button").forEach((btn) => {
      btn.addEventListener("click", () => {
        const expanded = btn.getAttribute("aria-expanded") === "true";
        const panelId = btn.getAttribute("aria-controls");
        const panel = document.getElementById(panelId);
        const icon = btn.querySelector("i.fas");

        if (expanded) {
          btn.setAttribute("aria-expanded", "false");
          if (panel) {
            panel.style.maxHeight = "0";
            panel.style.opacity = "0";
          }
          if (icon) {
            icon.classList.remove("fa-chevron-up");
            icon.classList.add("fa-chevron-down");
          }
        } else {
          // Close all other panels
          document.querySelectorAll(".accordion-item > button").forEach((otherBtn) => {
            if (otherBtn !== btn) {
              otherBtn.setAttribute("aria-expanded", "false");
              const otherPanelId = otherBtn.getAttribute("aria-controls");
              const otherPanel = document.getElementById(otherPanelId);
              const otherIcon = otherBtn.querySelector("i.fas");
              if (otherPanel) {
                otherPanel.style.maxHeight = "0";
                otherPanel.style.opacity = "0";
              }
              if (otherIcon) {
                otherIcon.classList.remove("fa-chevron-up");
                otherIcon.classList.add("fa-chevron-down");
              }
            }
          });

          // Open clicked panel
          btn.setAttribute("aria-expanded", "true");
          if (panel) {
            panel.style.maxHeight = panel.scrollHeight + "px";
            panel.style.opacity = "1";
          }
          if (icon) {
            icon.classList.remove("fa-chevron-down");
            icon.classList.add("fa-chevron-up");
          }
        }
      });
    });

    // Scroll to top button
    const scrollToTopBtn = document.getElementById("scrollToTop");
    
    window.addEventListener("scroll", () => {
      if (window.pageYOffset > 300) {
        scrollToTopBtn.classList.remove("hidden");
      } else {
        scrollToTopBtn.classList.add("hidden");
      }
    });

    scrollToTopBtn.addEventListener("click", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    });