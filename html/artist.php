<?php
session_start();
$response = include_once '../include/get_artist_profile.php';
$artist = null;
if (
    $response && $response['success'] && isset($response['data'])
) {
    $artist = $response['data'];
}

// Helper function to convert HH:MM:SS to minutes
function timeToMinutes($timeStr) {
    if (!$timeStr) return 'N/A';
    list($h, $m, $s) = explode(':', $timeStr);
    return ($h * 60) + $m + round($s / 60, 2); // returns minutes (with decimals)
}

// Fetch package details for this artist
$silver_package = null;
$gold_package = null;
$platinum_package = null;

if (!empty($artist['artist_id'])) {
    include_once '../include/dbcon.php';
    
    // Fetch Silver Package
    $stmt = $link->prepare("SELECT package_type, price, duration, count FROM `artist package` WHERE artist_id = ? AND LOWER(package_type) = 'silver'");
    $stmt->bind_param("i", $artist['artist_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $silver_package = $row;
    }
    $stmt->close();
    
    // Fetch Gold Package
    $stmt = $link->prepare("SELECT package_type, price, duration, count FROM `artist package` WHERE artist_id = ? AND LOWER(package_type) = 'gold'");
    $stmt->bind_param("i", $artist['artist_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $gold_package = $row;
    }
    $stmt->close();
    
    // Fetch Platinum Package
    $stmt = $link->prepare("SELECT package_type, price, duration, count FROM `artist package` WHERE artist_id = ? AND LOWER(package_type) = 'platinum'");
    $stmt->bind_param("i", $artist['artist_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $platinum_package = $row;
    }
    $stmt->close();
}

// Debug output

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="stylesheet" href="../css/singer.css">
        <link rel="stylesheet" href="../css/artist.css">
        <link rel="stylesheet" href="../organizer/navbar.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <title>EvntsTunes.lk</title>
       
    </head>
    <body>
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
            <?php if (isset($_SESSION['organizer_id'])): ?>
            <div class="user-actions">
                <a href="#" class="notification-icon">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </a>
                <a href="<?php echo '../organizer/organizerprofile.php'; ?>" class="profile-picture">
                    <img src="<?php echo '../include/organizer_image.php?id=' . $_SESSION['organizer_id']; ?>" alt="Profile Picture" class="navbar-profile-pic">
                </a>
            </div>
            <?php else: ?>
            <div class="auth-buttons">
                <button class="login-btn">Log In</button>
                <button class="signup-btn">Sign Up</button>
            </div>
            <?php endif; ?>
        </div>
        
        <button class="mobile-toggle" id="mobile-toggle">
            ☰
        </button>
    </nav>
    
        <!-- Stepper -->
        <div class="stepper">
            <div class="step active"><div class="step-circle">1</div><div class="step-label">Artist Details</div></div>
            <div class="step"><div class="step-circle">2</div><div class="step-label">Artist Booking</div></div>
        </div>

        <!-- Artist Card -->
        <div class="artist-card">
            <a href="home2.php" class="back-arrow-btn" aria-label="Back">
                <i class="fa fa-arrow-left"></i>
            </a>
            <img class="artist-card-img" src="<?php if (!empty($artist['artist_id'])) { echo '../include/artist_image.php?id=' . $artist['artist_id'] . '&t=' . time(); } else { echo '../Image/profile-picture.jpg'; } ?>" alt="<?php echo htmlspecialchars($artist['artist_name'] ?? 'Artist'); ?>">
            <div class="artist-card-content">
                <div class="artist-card-title">
                    <?php echo htmlspecialchars($artist['artist_name'] ?? 'Artist Name'); ?>
                </div>
                <div class="artist-card-desc">
                    <?php echo htmlspecialchars($artist['artist_description'] ?? 'No description available.'); ?>
                </div>
                <a href="booking.php?artist_id=<?php echo urlencode($artist['artist_id']); ?>" style="text-decoration: none;">
                    <button class="artist-card-btn">Go to Artist Booking <i class="fa fa-arrow-right"></i></button>
                </a>
                </div>
            </div>
            
        <!-- Performances Section -->
        <div class="performances-section">
            <h2>PERFORMANCES</h2>
            <div class="performance-stats">
                <div class="stat"><span><?php echo htmlspecialchars($artist['perform_count'] ?? '-'); ?></span><div>Performing<br>Members</div></div>
                <div class="stat"><span><?php echo htmlspecialchars($artist['perform_duration'] ?? '-'); ?></span><div>Performing Duration<br>(in Hours)</div></div>
                <div class="stat"><span>Nationwide</span><div>Can Travel</div></div>
                <div class="stat"><span><?php echo htmlspecialchars($artist['offstage_count'] ?? '-'); ?></span><div>Offstage<br>Members</div></div>
                <div class="stat"><span><?php echo htmlspecialchars($artist['artist_language'] ?? '-'); ?></span><div>Performing<br>Language</div></div>
                </div>
            </div>
            
        <!-- Package Cards Section -->
        <section class="package-management">
            <?php if ($silver_package): ?>
            <div class="package-edit-card">
                <div class="package-header">
                    <h2><i class="fas fa-medal"></i> Silver Package</h2>
                    <span class="package-badge">Basic</span>
                </div>
                <div class="package-form">
                    <div class="form-group">
                        <label>Package Price</label>
                        <div class="price">Rs. <?php echo htmlspecialchars($silver_package['price']); ?></div>
                    </div>
                    <div class="form-group">
                        <label>Performance Duration</label>
                        <div class="duration"><?php echo timeToMinutes($silver_package['duration']) . ' minutes'; ?></div>
                    </div>
                    <div class="form-group">
                        <label>Number of Songs</label>
                        <div class="songs"><?php echo htmlspecialchars($silver_package['count']); ?> songs</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($gold_package): ?>
            <div class="package-edit-card featured">
                <div class="package-header">
                    <h2><i class="fas fa-medal gold"></i> Gold Package</h2>
                    <span class="package-badge">Popular</span>
                </div>
                <div class="package-form">
                    <div class="form-group">
                        <label>Package Price</label>
                        <div class="price">Rs. <?php echo htmlspecialchars($gold_package['price']); ?></div>
                    </div>
                    <div class="form-group">
                        <label>Performance Duration</label>
                        <div class="duration"><?php echo timeToMinutes($gold_package['duration']) . ' minutes'; ?></div>
                    </div>
                    <div class="form-group">
                        <label>Number of Songs</label>
                        <div class="songs"><?php echo htmlspecialchars($gold_package['count']); ?> songs</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($platinum_package): ?>
            <div class="package-edit-card">
                <div class="package-header">
                    <h2><i class="fas fa-medal platinum"></i> Platinum Package</h2>
                    <span class="package-badge">Premium</span>
                </div>
                <div class="package-form">
                    <div class="form-group">
                        <label>Package Price</label>
                        <div class="price">Rs. <?php echo htmlspecialchars($platinum_package['price']); ?></div>
                    </div>
                    <div class="form-group">
                        <label>Performance Duration</label>
                        <div class="duration"><?php echo timeToMinutes($platinum_package['duration']) . ' minutes'; ?></div>
                    </div>
                    <div class="form-group">
                        <label>Number of Songs</label>
                        <div class="songs"><?php echo htmlspecialchars($platinum_package['count']); ?> songs</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </section>
            
        <!-- Gallery Section -->
        <section class="gallery-section">
            <div class="gallery-tabs">
                <button class="tab active" id="photosTab">Photos</button>
                <button class="tab" id="videosTab">Videos</button>
            </div>
            <div class="gallery-grid" id="photosGallery">
                <img src="../Image/IMG_0667.JPG" alt="Chamara Weerasinghe 1">
                <img src="../Image/IMG_0666.JPG" alt="Chamara Weerasinghe 2">
                <img src="../Image/IMG_0661.JPG" alt="Chamara Weerasinghe 3">
                <img src="../Image/IMG_0664.JPG" alt="Chamara Weerasinghe 4">
                <img src="../Image/IMG_0665.JPG" alt="Chamara Weerasinghe 5">
                <img src="../Image/IMG_0666.JPG" alt="Chamara Weerasinghe 6">
            </div>
            <div class="gallery-grid" id="videosGallery" style="display:none;">
                <div class="video-thumb">
                    <iframe src="https://www.youtube.com/embed/VKNaugqrJ74" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="video-thumb">
                    <iframe src="https://www.youtube.com/embed/VKNaugqrJ74" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="video-thumb">
                    <iframe src="https://www.youtube.com/embed/vXv4RDrlsyU" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="video-thumb">
                    <iframe src="https://www.youtube.com/embed/hd5sd-Ih_30" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="video-thumb">
                    <iframe src="https://www.youtube.com/embed/HvQZ3hUcsVw" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="video-thumb">
                    <iframe src="https://www.youtube.com/embed/FWfx_VAtZ2s" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </section>
            
        <!-- Reviews Section -->
        <section class="reviews-section">
            <div class="reviews-scroll">
                <div class="review-card">
                    <div class="review-header">
                        <div class="review-avatar"><i class="fa fa-user-circle"></i></div>
                        <div>
                            <div class="review-name">Yeshan</div>
                            <div class="review-date">January 2025</div>
                </div>
                    </div>
                    <div class="review-title">Colombo Music Fest 2024</div>
                    <div class="review-stars">
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    </div>
                    <div class="review-text">Sarith Surith and The News were professional, punctual, and delivered an electrifying performance. The crowd loved every moment! Highly Recommended!!</div>
                </div>
                <div class="review-card">
                    <div class="review-header">
                        <div class="review-avatar"><i class="fa fa-user-circle"></i></div>
                        <div>
                            <div class="review-name">Nimesha</div>
                            <div class="review-date">December 2024</div>
                </div>
                    </div>
                    <div class="review-title">Galle Music Night</div>
                    <div class="review-stars">
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    </div>
                    <div class="review-text">Amazing energy and great song selection! The band made our event unforgettable.</div>
                </div>
                <div class="review-card">
                    <div class="review-header">
                        <div class="review-avatar"><i class="fa fa-user-circle"></i></div>
                        <div>
                            <div class="review-name">Ruwan</div>
                            <div class="review-date">November 2024</div>
                </div>
                    </div>
                    <div class="review-title">Wedding Reception</div>
                    <div class="review-stars">
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    </div>
                    <div class="review-text">The best live band experience! Highly recommended for any event.</div>
                </div>
            </div>
        </section>

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

        // Gallery tabs functionality
        const photosTab = document.getElementById('photosTab');
        const videosTab = document.getElementById('videosTab');
        const photosGallery = document.getElementById('photosGallery');
        const videosGallery = document.getElementById('videosGallery');

        function switchTab(activeTab, inactiveTab, activeGallery, inactiveGallery) {
            // Remove active class from both tabs
            photosTab.classList.remove('active');
            videosTab.classList.remove('active');
            
            // Add active class to clicked tab
            activeTab.classList.add('active');
            
            // Hide both galleries
            photosGallery.style.display = 'none';
            videosGallery.style.display = 'none';
            
            // Show active gallery
            activeGallery.style.display = 'grid';
        }

        photosTab.addEventListener('click', () => {
            switchTab(photosTab, videosTab, photosGallery, videosGallery);
        });

        videosTab.addEventListener('click', () => {
            switchTab(videosTab, photosTab, videosGallery, photosGallery);
        });
        </script>
    </body>
</html>