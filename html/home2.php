<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="stylesheet" href="../css/home.css">
        <link rel="stylesheet" href="../css/home2.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <title>EvntsTunes.lk</title>
    </head>
    <body>
    <div class="banner">
        <video autoplay loop muted plays-inline>
            <source src="../Image/Back_in_Belgium_for_@tomorrowland_2024_This_time_with_@swedishhousemafia._Let_s_get_it.mp4" type="video/mp4">
        </video>
        <nav class="navbar">
            <div class="logo">
                <a href="../html/home2.php" style="text-decoration: none;">
                    <h1>EvntsTunes.lk</h1>
                </a>
            </div>
            
            <div class="menu" id="menu">
                <a href="../html/singer.php">Singers</a>
                <a href="../html/musicband.php">Music Bands</a>
                <a href="../html/dance.php">Dancing Teams</a>
                <div class="user-actions">
                    <a href="#" class="notification-icon">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </a>
                    <a href="<?php echo isset($_SESSION['organizer_id']) ? '../organizer/organizerprofile.php' : '#'; ?>" class="profile-picture">
                        <img src="<?php
                            if (isset($_SESSION['organizer_id'])) {
                                echo '../include/organizer_image.php?id=' . $_SESSION['organizer_id'];
                            } else {
                                echo '../Image/profile-picture.jpg';
                            }
                        ?>" alt="Profile Picture" class="navbar-profile-pic">
                    </a>
                </div>
            </div>
            
            <button class="mobile-toggle" id="mobile-toggle">
                ☰
            </button>
        </nav>

        <div class="banner-content">
            <div class="search-wrapper">
                <div class="section-title">Let's Book Your Artist</div>
                <div class="section-subtitle">Seamless Booking for Unforgettable Events</div>
                <form class="search-container">
                    <div class="search-input-wrapper">
                        <input class="search-input" type="text" placeholder="Search Artists......." id="artistSearch" autocomplete="off" />
                        <div class="search-suggestions" id="searchSuggestions"></div>
                    </div>
                    <button class="search-btn" type="submit">
                        Search
                        <svg viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7" stroke="white" stroke-width="2" fill="none"/>
                            <line x1="16.5" y1="16.5" x2="21" y2="21" stroke="white" stroke-width="2"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
        <div class="singer-grid">
            <!-- First Section -->
            <div class="section-group">
                <div class="section-header">
                    <h2 class="section-title">Trending Singers</h2>
                </div>
                <!-- Singer 1 -->
                <div class="singer-card">
                    <div class="favorite-btn-wrapper">
                      <button class="btn favorite-btn" type="button" aria-label="Favorite">
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                      </button>
                    </div>
                    <img src="../Image/chamara-weerasinghe-1.png" alt="Chamara Weerasinghe" class="singer-image">
                    
                    <div class="singer-info">
                        <div class="singer-name">CHAMARA WEERASINGHE</div>
                        <div class="singer-description">
                            Known for his unique voice and hit songs like "Rosa pethi athurata", "Mathakayan obe" and "oya susum pawan".
                        </div>
                        <div class="performance-info">
                            Performs at concerts, weddings, and special events
                        </div>
                        <div class="book-now">
                            <a href="../html/artist.php"><button>Book Now</button></a>
                          

  
                        </div>
                    </div>
                </div>
                
                <!-- Singer 2 -->
                <div class="singer-card">
                    <div class="favorite-btn-wrapper">
                      <button class="btn favorite-btn" type="button" aria-label="Favorite">
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                      </button>
                    </div>
                    <img src="../Image/IMG_0647.JPG" alt="Anushka Udana" class="singer-image">
        
                    <div class="singer-info">
                        <div class="singer-name">ANUSHKA UDANA</div>
                        <div class="singer-description">
                           Talented Sri Lankan singer and performer.Known for hit songs like "Mandaram kathave" and "Marunu hithe".
                        </div>
                        <div class="performance-info">
                            Performs at weddings, concerts, and special events
                        </div>
                        <div class="book-now">
                            <button>Book Now</button>
      
                        </div>
                    </div>
                </div>
                
                <!-- Singer 3 -->
                <div class="singer-card">
                    <div class="favorite-btn-wrapper">
                      <button class="btn favorite-btn" type="button" aria-label="Favorite">
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                      </button>
                    </div>
                    <img src="../Image/IMG_0687.JPG" alt="Hana Shafa" class="singer-image">
                    <div class="singer-info">
                        <div class="singer-name">HANA SHAFA</div>
                        <div class="singer-description">
                           Popular Sri Lankan performer known for her melodious voice and captivating stage presence.
                        </div>
                        <div class="performance-info">
                            Performs at concerts, weddings, and corporate events
                        </div>
                        <div class="book-now">
                            <button>Book Now</button>
      
                        </div>
                    </div>
                </div>

                <div class="see-more-container">
                    <a href="../html/singer.php" class="see-more-btn" style="text-decoration: none;">
                        <span class="button-text">See more</span>
                        <span class="arrow">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>

            <!-- Second Section -->
            <div class="section-group">
                <div class="section-header">
                    <h2 class="section-title">Trending Music Bands</h2>
                </div>
                <!-- Singer 1 -->
                <div class="singer-card">
                    <div class="favorite-btn-wrapper">
                      <button class="btn favorite-btn" type="button" aria-label="Favorite">
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                      </button>
                    </div>
                    <img src="../Image/IMG_0708.JPG" alt="Chamara Weerasinghe" class="singer-image">
                    
                    <div class="singer-info">
                        <div class="singer-name">NEWS</div>
                        <div class="singer-description">
                           Specializes in Sinhalese pop,rock, and fusion music.Popular Sri Lankan band known for energetic performances
                        </div>
                        <div class="performance-info">
                            Performs at concerts, weddings, and special events
                        </div>
                        <div class="book-now">
                            <button>Book Now </button>
                          

  
                        </div>
                    </div>
                </div>
                
                <!-- Singer 2 -->
                <div class="singer-card">
                    <div class="favorite-btn-wrapper">
                      <button class="btn favorite-btn" type="button" aria-label="Favorite">
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                      </button>
                    </div>
                    <img src="../Image/IMG_0722.JPG" alt="Anushka Udana" class="singer-image">
        
                    <div class="singer-info">
                        <div class="singer-name">INFINITY</div>
                        <div class="singer-description">
                          Specializes in Sinhalese pop,rock, and fusion music.Popular Sri Lankan band known for energetic performances
                        </div>
                        <div class="performance-info">
                            Performs at weddings, concerts, and special events
                        </div>
                        <div class="book-now">
                            <button>Book Now</button>
      
                        </div>
                    </div>
                </div>
                
                <!-- Singer 3 -->
                <div class="singer-card">
                    <div class="favorite-btn-wrapper">
                      <button class="btn favorite-btn" type="button" aria-label="Favorite">
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                      </button>
                    </div>
                    <img src="../Image/IMG_0758.JPG" alt="Hana Shafa" class="singer-image">
                    <div class="singer-info">
                        <div class="singer-name">FLASHBACK</div>
                        <div class="singer-description">
                           Popular Sri Lankan performer known for her melodious voice and captivating stage presence.
                        </div>
                        <div class="performance-info">
                            Performs at concerts, weddings, and corporate events
                        </div>
                        <div class="book-now">
                            <button>Book Now</button>
      
                        </div>
                    </div>
                </div>

                <div class="see-more-container">
                    <a href="../html/musicband.php" class="see-more-btn" style="text-decoration: none;">
                        <span class="button-text">See more</span>
                        <span class="arrow">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>

            <!-- Third Section -->
            <div class="section-group">
                <div class="section-header">
                    <h2 class="section-title">Trending Dancing Teams</h2>
                </div>
                <!-- Singer 1 -->
                <div class="singer-card">
                    <div class="favorite-btn-wrapper">
                      <button class="btn favorite-btn" type="button" aria-label="Favorite">
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                      </button>
                    </div>
                    <img src="../Image/1.jpg" alt="Chamara Weerasinghe" class="singer-image">
                    
                    <div class="singer-info">
                        <div class="singer-name">RaMoD with COOL STEPS</div>
                        <div class="singer-description">
                            Beloved Sri Lankan artist known for her unique vocal style and emotional performances.
                        </div>
                        <div class="performance-info">
                            Performs at concerts, weddings, and special events
                        </div>
                        <div class="book-now">
                            <button>Book Now </button>
                        </div>
                    </div>
                </div>
                
                <!-- Singer 2 -->
                <div class="singer-card">
                    <div class="favorite-btn-wrapper">
                      <button class="btn favorite-btn" type="button" aria-label="Favorite">
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                      </button>
                    </div>
                    <img src="../Image/IMG_0772.JPG" alt="Anushka Udana" class="singer-image">
        
                    <div class="singer-info">
                        <div class="singer-name">BUDAWATTA DANCE TROUPE</div>
                        <div class="singer-description">
                           Beloved Sri Lankan artist known for her unique vocal style and emotional performances.
                        </div>
                        <div class="performance-info">
                            Performs at weddings, concerts, and special events
                        </div>
                        <div class="book-now">
                            <button>Book Now</button>
                        </div>
                    </div>
                </div>
                
                <!-- Singer 3 -->
                <div class="singer-card">
                    <div class="favorite-btn-wrapper">
                      <button class="btn favorite-btn" type="button" aria-label="Favorite">
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                      </button>
                    </div>
                    <img src="../Image/IMG_0773.JPG" alt="Hana Shafa" class="singer-image">
                    <div class="singer-info">
                        <div class="singer-name">CHANNA-UPULI PERFORMING ARTS FOUNDATION</div>
                        <div class="singer-description">
                             Beloved Sri Lankan artist known for her unique vocal style and emotional performances.
                        </div>
                        <div class="performance-info">
                            Performs at concerts, weddings, and corporate events
                        </div>
                        <div class="book-now">
                            <button>Book Now</button>
                        </div>
                    </div>
                </div>

                <div class="see-more-container">
                    <a href="../html/dance.php" class="see-more-btn" style="text-decoration: none;">
                        <span class="button-text">See more</span>
                        <span class="arrow">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
           
        <div class="pagination-wrapper">
            <div class="pagination">
              <a href="#" class="prev page-numbers">prev</a>
              <a href="#" class="page-numbers active">1</a>
              <a href="#" class="page-numbers">2</a>
              <a href="#" class="page-numbers">3</a>
              <a href="#" class="page-numbers">4</a>
              <a href="#" class="page-numbers">5</a>
              <a href="#" class="page-numbers">6</a>
              <a href="#" class="page-numbers">7</a>
              <a href="#" class="page-numbers">8</a>
              <a href="#" class="page-numbers">9</a>
              <a href="#" class="page-numbers">10</a>
              <a href="#" class="next page-numbers">next</a>
            </div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
          const input = document.querySelector('.modern-search-input');
          const clearBtn = document.querySelector('.modern-clear-btn');
          const form = document.querySelector('.modern-search-form');

          if(input && clearBtn && form) {
            input.addEventListener('input', function() {
              if (input.value.length > 0) {
                clearBtn.classList.add('show');
              } else {
                clearBtn.classList.remove('show');
              }
            });

            clearBtn.addEventListener('click', function() {
              input.value = '';
              input.focus();
              clearBtn.classList.remove('show');
            });

            form.addEventListener('submit', function(e) {
              if (!input.value.trim()) {
                e.preventDefault();
                form.classList.add('shake');
                setTimeout(() => form.classList.remove('shake'), 400);
              }
            });
          }
          
          // Removed navbar toggle functionality
          
        });
        </script>
        <script>
        // Toggle mobile menu
        const mobileToggle = document.getElementById('mobile-toggle');
        const menu = document.getElementById('menu');
        
        mobileToggle.addEventListener('click', () => {
            menu.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && !mobileToggle.contains(e.target) && menu.classList.contains('active')) {
                menu.classList.remove('active');
            }
        });

        // Add hover effects for the logo and menu items
        const menuItems = document.querySelectorAll('.menu a');

        menuItems.forEach(item => {
            item.addEventListener('mouseover', () => {
                item.style.letterSpacing = '1px';
            });
            
            item.addEventListener('mouseout', () => {
                item.style.letterSpacing = '0';
            });
        });

        // Add pagination functionality
        document.addEventListener('DOMContentLoaded', function() {
            const pageNumbers = document.querySelectorAll('.page-numbers');
            
            pageNumbers.forEach(page => {
                page.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all pages
                    pageNumbers.forEach(p => p.classList.remove('active'));
                    
                    // Add active class to clicked page
                    this.classList.add('active');
                });
            });
        });

        // Add search suggestions functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('artistSearch');
            const suggestionsContainer = document.getElementById('searchSuggestions');
            
            // Sample artist data - you can replace this with your actual data
            const artists = [
                'Chamara Weerasinghe',
                'Anushka Udana',
                'Hana Shafa',
                'Chamara Perera',
                'Chamika Silva',
                'Chathura Perera',
                'Chaminda Silva',
                'Chamara Fernando',
                'Chamara Jayasinghe',
                'Chamara Rathnayake'
            ];

            searchInput.addEventListener('input', function() {
                const value = this.value.toLowerCase();
                suggestionsContainer.innerHTML = '';
                
                if (value.length < 2) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }

                const matches = artists.filter(artist => 
                    artist.toLowerCase().includes(value)
                );

                if (matches.length > 0) {
                    suggestionsContainer.style.display = 'block';
                    matches.forEach(match => {
                        const div = document.createElement('div');
                        div.className = 'suggestion-item';
                        div.textContent = match;
                        div.addEventListener('click', function() {
                            searchInput.value = match;
                            suggestionsContainer.style.display = 'none';
                        });
                        suggestionsContainer.appendChild(div);
                    });
                } else {
                    suggestionsContainer.style.display = 'none';
                }
            });

            // Close suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                    suggestionsContainer.style.display = 'none';
                }
            });

            // Handle form submission
            const searchForm = document.querySelector('.search-container');
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // Add your search logic here
                console.log('Searching for:', searchInput.value);
            });
        });
    </script>
        <!-- Footer -->
        <footer class="footer">
          <div class="footer-main">
              <div class="footer-left">
                  <div class="footer-logo">
                      <span>EventsTune.lk</span>
                  </div>
                  <p class="footer-info">
                      Your premier destination for booking talented singers and performers. 
                      We connect artists with opportunities, making entertainment accessible to all.
                  </p>
                  <div class="footer-socials">
                      <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                      <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                      <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                      <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                      <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                      <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                  </div>
              </div>
              
              <div class="footer-links">
                  <div class="footer-section">
                      <h4><i class="fas fa-sitemap"></i> Site Map</h4>
                      <ul>
                          <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
                          <li><a href="#"><i class="fas fa-info-circle"></i> About Us</a></li>
                          <li><a href="#"><i class="fas fa-envelope"></i> Contact</a></li>
                      </ul>
                  </div>
                  
                  <div class="footer-section">
                      <h4><i class="fas fa-question-circle"></i> FAQ & Support</h4>
                      <ul>
                          <li><a href="#"><i class="fas fa-book"></i> How to Book</a></li>
                          <li><a href="#"><i class="fas fa-credit-card"></i> Payment Methods</a></li>
                          <li><a href="#"><i class="fas fa-ban"></i> Cancellation Policy</a></li>
                          <li><a href="#"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
                          <li><a href="#"><i class="fas fa-file-contract"></i> Terms of Service</a></li>
                      </ul>
                  </div>
                  
                  <div class="footer-section">
                      <h4><i class="fas fa-address-book"></i> Contact Info</h4>
                      <ul>
                          <li><a href="tel:+1234567890"><i class="fas fa-phone-alt"></i> +123 456 7890</a></li>
                          <li><a href="mailto:info@eventstune.lk"><i class="fas fa-envelope"></i> info@eventstune.lk</a></li>
                          <li><a href="#"><i class="fas fa-map-marker-alt"></i> 123 Event Street, Colombo</a></li>
                          <li><a href="#"><i class="fas fa-clock"></i> Mon - Sat: 9:00 AM - 6:00 PM</a></li>
                          <li><a href="#"><i class="fas fa-globe"></i> www.eventstune.lk</a></li>
                      </ul>
                  </div>
              </div>
          </div>
          
          <div class="footer-bottom">
              <div class="footer-bottom-links">
                  <a href="#"><i class="fas fa-shield-alt"></i> Privacy Policy</a>
                  <a href="#"><i class="fas fa-file-contract"></i> Terms of Service</a>
                  <a href="#"><i class="fas fa-cookie"></i> Cookie Policy</a>
              </div>
              <span>Copyright 2025 © EventsTune.lk | All Rights Reserved</span>
          </div>
      </footer>
    </body>
</html>
<script src="../artist/artistnav.js"></script>