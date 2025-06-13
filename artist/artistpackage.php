<?php
include_once '../include/session.php';
$response = include_once '../include/get_artist_profile.php';
$artist = null;
if ($response && $response['success'] && isset($response['data'])) {
    $artist = $response['data'];
}

$artist_id = $_SESSION['artist_id'] ?? null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $artist_id) {
    $package_type = $_POST['package_type'] ?? '';
    $price = $_POST['price'] ?? 0;
    $duration = $_POST['duration'] ?? 0;
    $count = $_POST['count'] ?? 0;

    // Check if package already exists for this artist and type
    $stmt = $link->prepare("SELECT package_id FROM `artist package` WHERE artist_id = ? AND package_type = ?");
    $stmt->bind_param("is", $artist_id, $package_type);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update
        $stmt->close();
        $stmt = $link->prepare("UPDATE `artist package` SET price=?, duration=?, count=? WHERE artist_id=? AND package_type=?");
        $stmt->bind_param("diiis", $price, $duration, $count, $artist_id, $package_type);
        $success = $stmt->execute();
        $message = $success ? "Package updated!" : "Update failed!";
    } else {
        // Insert
        $stmt->close();
        $stmt = $link->prepare("INSERT INTO `artist package` (artist_id, package_type, price, duration, count) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isdii", $artist_id, $package_type, $price, $duration, $count);
        $success = $stmt->execute();
        $message = $success ? "Package added!" : "Insert failed!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Package</title>
    <link rel="stylesheet" href="artistpackage.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="artistnav.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <h1>EventsTune.lk</h1>
        </div>
        
        <div class="nav-right">
            <div class="menu" id="menu">
                <a href="#">Singers</a>
                <a href="#">Music Bands</a>
                <a href="#">Dancing Teams</a>
            </div>
            
            <div class="navbar-profile">
                <img src="../include/artist_image.php?id=<?php echo $artist['artist_id']; ?>&t=<?php echo time(); ?>" alt="Profile Picture" class="navbar-profile-pic">
            </div>
        </div>
        
        <button class="mobile-toggle" id="mobile-toggle">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <div class="container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <h2>Manage My Account</h2>
            
            <div class="sidebar-link">
                <a href="artistprofile.php"><i class="fas fa-user"></i> My Profile</a>
            </div>
            <div class="sidebar-link">
                <a href="artistbankdetails.php"><i class="fas fa-university"></i> Bank Details</a>
            </div>
            <div class="sidebar-link active">
                <a href="artistpackage.php"><i class="fas fa-box"></i> My Package</a>
            </div>
            
            <h2>Activities</h2>
            
            <div class="sidebar-link">
                <a href="artistbookings.php"><i class="fas fa-calendar-check"></i> Bookings</a>
            </div>
            <div class="sidebar-link">
                <a href="artistrequests.php"><i class="fas fa-clipboard-list"></i> Requests</a>
            </div>
            <div class="sidebar-link">
                <a href="artistcancellations.php"><i class="fas fa-ban"></i> Cancellations</a>
            </div>
            <div class="sidebar-link">
                <a href="artistcalendar.php"><i class="fas fa-calendar-alt"></i> Calendar</a>
            </div>

            <div class="sidebar-link logout">
                <a href="../include/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <h1>My Package</h1>
            <div class="welcome-message">
                <p>Manage your package details and pricing</p>
            </div>
            
            <?php if ($message): ?>
                <div class="alert"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <!-- Package Management Section -->
            <div class="package-management">
                <!-- Silver Package -->
                <form method="POST">
                <div class="package-edit-card">
                    <div class="package-header">
                        <h2><i class="fas fa-medal"></i> Silver Package</h2>
                        <span class="package-badge">Basic</span>
                    </div>
                    <div class="package-form">
                        <input type="hidden" name="package_type" value="Silver">
                        <div class="form-group">
                            <label for="silver-price">Package Price (Rs.)</label>
                            <input type="number" name="price" id="silver-price" value="" min="0">
                        </div>
                        <div class="form-group">
                            <label for="silver-duration">Performance Duration (minutes)</label>
                            <input type="number" name="duration" id="silver-duration" value="" min="30">
                        </div>
                        <div class="form-group">
                            <label for="silver-songs">Number of Songs</label>
                            <input type="number" name="count" id="silver-songs" value="" min="1">
                        </div>
                        <div class="form-actions">
                            <button type="reset" class="btn-cancel">Cancel</button>
                            <button type="submit" class="btn-save">Save Changes</button>
                        </div>
                    </div>
                </div>
                </form>

                <!-- Gold Package -->
                <form method="POST">
                <div class="package-edit-card featured">
                    <div class="package-header">
                        <h2><i class="fas fa-medal gold"></i> Gold Package</h2>
                        <span class="package-badge">Popular</span>
                    </div>
                    <div class="package-form">
                        <input type="hidden" name="package_type" value="Gold">
                        <div class="form-group">
                            <label for="gold-price">Package Price (Rs.)</label>
                            <input type="number" name="price" id="gold-price" value="" min="0">
                        </div>
                        <div class="form-group">
                            <label for="gold-duration">Performance Duration (minutes)</label>
                            <input type="number" name="duration" id="gold-duration" value="" min="30">
                        </div>
                        <div class="form-group">
                            <label for="gold-songs">Number of Songs</label>
                            <input type="number" name="count" id="gold-songs" value="" min="1">
                        </div>
                        <div class="form-actions">
                            <button type="reset" class="btn-cancel">Cancel</button>
                            <button type="submit" class="btn-save">Save Changes</button>
                        </div>
                    </div>
                </div>
                </form>

                <!-- Platinum Package -->
                <form method="POST">
                <div class="package-edit-card">
                    <div class="package-header">
                        <h2><i class="fas fa-medal platinum"></i> Platinum Package</h2>
                        <span class="package-badge">Premium</span>
                    </div>
                    <div class="package-form">
                        <input type="hidden" name="package_type" value="Platinum">
                        <div class="form-group">
                            <label for="platinum-price">Package Price (Rs.)</label>
                            <input type="number" name="price" id="platinum-price" value="" min="0">
                        </div>
                        <div class="form-group">
                            <label for="platinum-duration">Performance Duration (minutes)</label>
                            <input type="number" name="duration" id="platinum-duration" value="" min="30">
                        </div>
                        <div class="form-group">
                            <label for="platinum-songs">Number of Songs</label>
                            <input type="number" name="count" id="platinum-songs" value="" min="1">
                        </div>
                        <div class="form-actions">
                            <button type="reset" class="btn-cancel">Cancel</button>
                            <button type="submit" class="btn-save">Save Changes</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script src="navbar.js"></script>

    <script>
        // Load saved profile picture on page load
        window.addEventListener('load', function() {
            const savedPicture = localStorage.getItem('profilePicture');
            if (savedPicture) {
                const navbarProfilePic = document.querySelector('.navbar-profile-pic');
                if (navbarProfilePic) {
                    navbarProfilePic.src = savedPicture;
                }
            }
        });
    </script>

    <link rel="stylesheet" href="footer.css">
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