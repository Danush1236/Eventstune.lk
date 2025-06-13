<?php
include_once '../include/session.php';
include_once '../include/dbcon.php';
$response = include_once '../include/get_artist_profile.php';
$artist = null;
if ($response && $response['success'] && isset($response['data'])) {
    $artist = $response['data'];
}

// Fetch rejected bookings for the logged-in artist
$cancellations = [];
if (isset($artist['artist_id'])) {
    $stmt = $link->prepare("
        SELECT b.*, o.organizer_name, ap.package_type 
        FROM booking b
        JOIN organizer o ON b.organizer_id = o.organizer_id
        JOIN `artist package` ap ON b.package_id = ap.package_id
        WHERE b.artist_id = ? AND b.status = 'rejected'
        ORDER BY b.event_date ASC
    ");
    $stmt->bind_param("i", $artist['artist_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cancellations[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cancellations</title>
    <link rel="stylesheet" href="artistcancellations.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="artistnav.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <h1>EvntsTunes.lk</h1>
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
            ☰
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
            <div class="sidebar-link">
                <a href="artistpackage.php"><i class="fas fa-box"></i> My Package</a>
            </div>
            
            <h2>Activities</h2>
            
            <div class="sidebar-link">
                <a href="artistbookings.php"><i class="fas fa-calendar-check"></i> Bookings</a>
            </div>
            <div class="sidebar-link">
                <a href="artistrequests.php"><i class="fas fa-clipboard-list"></i> Requests</a>
            </div>
            <div class="sidebar-link active">
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
            <h1>My Cancellations</h1>
            <div class="welcome-message">
                <p>View and manage your cancelled service requests</p>
            </div>
            
            <!-- Content will be added here later -->
            <div class="cancellations-container">
                <table class="cancellations-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Organizer Name</th>
                            <th>Location</th>
                            <th>Event Name</th>
                            <th>Time</th>
                            <th>Date</th>
                            <th>People Attend</th>
                            <th>Package</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($cancellations)): ?>
                        <tr>
                            <td colspan="10" class="no-cancellations">No rejected bookings found</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($cancellations as $cancellation): ?>
                            <tr>
                                <td>#BK<?php echo str_pad($cancellation['booking_id'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($cancellation['organizer_name']); ?></td>
                                <td><?php echo htmlspecialchars($cancellation['event_location']); ?></td>
                                <td><?php echo htmlspecialchars($cancellation['occasion']); ?></td>
                                <td><?php echo htmlspecialchars($cancellation['event_time']); ?></td>
                                <td><?php echo htmlspecialchars($cancellation['event_date']); ?></td>
                                <td><?php echo htmlspecialchars($cancellation['participant_count']); ?></td>
                                <td><?php echo htmlspecialchars($cancellation['package_type']); ?></td>
                                <td>Rs. <?php echo number_format($cancellation['total_amount'], 2); ?></td>
                                <td class="status canceled">Rejected</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="navbar.js"></script>
    <script src="artistnav.js"></script>

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