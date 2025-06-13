<?php
session_start();
include_once '../include/dbcon.php';

// Check if user is logged in
if (!isset($_SESSION['organizer_id'])) {
    header("Location: login.php");
    exit();
}

$artist = null;
if (isset($_GET['artist_id'])) {
    $artist_id = intval($_GET['artist_id']);
    $stmt = $link->prepare("SELECT artist_id, artist_name, artist_photo FROM artist WHERE artist_id = ?");
    $stmt->bind_param("i", $artist_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        $artist = $row;
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $organizer_id = $_SESSION['organizer_id'];
    $artist_id = intval($_POST['artist_id']);
    $package_type = $_POST['package_type'];
    $occasion = $_POST['occasion'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $participant_count = intval($_POST['participant_count']);
    $event_description = $_POST['event_description'];
    
    // Get package_id and price from artist package table
    $stmt = $link->prepare("SELECT package_id, price FROM `artist package` WHERE artist_id = ? AND package_type = ?");
    $stmt->bind_param("is", $artist_id, $package_type);
    $stmt->execute();
    $result = $stmt->get_result();
    $package_data = $result->fetch_assoc();
    $stmt->close();
    
    if ($package_data) {
        $package_id = $package_data['package_id'];
        $total_amount = $package_data['price'];
        
        // Insert into booking table
        $status = 'pending'; // Initial status
        $paid_amount = 0; // Initial paid amount
        $due_amount = $total_amount; // Initial due amount
        
        $stmt = $link->prepare("INSERT INTO booking (organizer_id, artist_id, package_id, occasion, event_date, event_time, event_location, participant_count, event_description, status, total_amount, paid_amount, due_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("iiissssissddd", 
            $organizer_id,
            $artist_id,
            $package_id,
            $occasion,
            $event_date,
            $event_time,
            $event_location,
            $participant_count,
            $event_description,
            $status,
            $total_amount,
            $paid_amount,
            $due_amount
        );
        
        if ($stmt->execute()) {
            // Redirect to payment page with booking_id
            $booking_id = $link->insert_id;
            header("Location: ../organizer/organizerrequests.php");
            exit();
        } else {
            $error_message = "Error creating booking: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Invalid package selected";
    }
}

// Fetch available packages for this artist
$packages = [];
if (!empty($artist['artist_id'])) {
    $stmt = $link->prepare("SELECT package_id, package_type, price FROM `artist package` WHERE artist_id = ?");
    $stmt->bind_param("i", $artist['artist_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Artist Booking | EventsTune.lk</title>
  <link rel="stylesheet" href="../css/booking.css">
  <link rel="stylesheet" href="../organizer/navbar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="logo">
      <a href="../html/home.html" style="text-decoration: none;">
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

  <!-- Back Arrow Button -->
  <a href="#" class="back-arrow-btn" title="Back to Artist Details">
    <i class="fa fa-arrow-left"></i>
  </a>
 
  <div class="steps-bar">
    <div class="step"><span>1</span><div>Artist Details</div></div>
    <div class="step active"><span>2</span><div>Artist Booking</div></div>
  </div>
  <main class="booking-main-container">
    <div class="booking-title-block">
      <h2 class="booking-title">Woohoo! You made a great choice</h2>
      <div class="booking-subtitle">Just a step away from booking your dream star</div>
    </div>
    <div class="booking-content-row">
      <div class="booking-artist-col">
        <img src="<?php
            if (!empty($artist['artist_id'])) {
                echo '../include/artist_image.php?id=' . $artist['artist_id'] . '&t=' . time();
            } else {
                echo '../Image/profile-picture.jpg';
            }
        ?>" alt="<?php echo htmlspecialchars($artist['artist_name'] ?? 'Artist'); ?>" class="booking-artist-img">
        <div class="booking-artist-name"><?php echo htmlspecialchars($artist['artist_name'] ?? 'Artist Name'); ?></div>
      </div>
      <form class="booking-form" method="POST" action="">
        <input type="hidden" name="artist_id" value="<?php echo htmlspecialchars($artist['artist_id']); ?>">
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <div class="booking-form-row">
          <div class="booking-form-group">
            <label>Package Type</label>
            <select name="package_type" class="package-select" required>
              <option value="">Select a package</option>
              <?php foreach ($packages as $package): ?>
                  <option value="<?php echo htmlspecialchars($package['package_type']); ?>">
                      <?php echo htmlspecialchars(ucfirst($package['package_type'])) . ' Package - Rs. ' . htmlspecialchars($package['price']); ?>
                  </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="booking-form-row">
          <div class="booking-form-group">
            <label>Occasion</label>
            <input type="text" name="occasion" placeholder="Enter the occasion" required>
          </div>
          <div class="booking-form-group">
            <label>Event Date</label>
            <input type="date" name="event_date" placeholder="Select date" required>
          </div>
        </div>
        <div class="booking-form-row">
          <div class="booking-form-group">
            <label>Event Time</label>
            <input type="time" name="event_time" placeholder="Select time" required>
          </div>
          <div class="booking-form-group">
            <label>Event Location</label>
            <input type="text" name="event_location" placeholder="Enter event location" required>
          </div>
        </div>
        <div class="booking-form-row">
          <div class="booking-form-group">
            <label>How many people will attend?</label>
            <input type="number" name="participant_count" placeholder="Enter number of attendees" required>
          </div>
        </div>
        <div class="booking-form-row">
          <div class="booking-form-group" style="flex:1;">
            <label>Event Description</label>
            <textarea name="event_description" placeholder="Describe your event details" rows="4" required></textarea>
          </div>
        </div>
        <div class="booking-form-row booking-checkbox-row">
          <label class="booking-checkbox-label">
            <input type="checkbox" required> I accept the terms and conditions
          </label>
        </div>
        <button type="submit" class="booking-submit-btn">Send Request</button>
      </form>
    </div>
  </main>

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
  </script>
</body>
</html>