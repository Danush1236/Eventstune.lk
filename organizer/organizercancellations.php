<?php
session_start();
include_once '../include/dbcon.php';
include_once '../include/get_organizer_profile.php';
$organizer = null;
if (isset($response) && $response['success'] && isset($response['data'])) {
    $organizer = $response['data'];
}

// Fetch cancelled bookings for the logged-in organizer
$cancellations = [];
if (isset($_SESSION['organizer_id'])) {
    $stmt = $link->prepare("
        SELECT b.*, a.artist_name, ap.package_type, ap.price 
        FROM booking b
        JOIN artist a ON b.artist_id = a.artist_id
        JOIN `artist package` ap ON b.package_id = ap.package_id
        WHERE b.organizer_id = ? AND b.status = 'cancelled'
        ORDER BY b.event_date DESC
    ");
    $stmt->bind_param("i", $_SESSION['organizer_id']);
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
    <link rel="stylesheet" href="organizercancellations.css">
    <link rel="stylesheet" href="navbar.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .cancellations-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .cancellations-table th,
        .cancellations-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .cancellations-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #1976d2;
        }

        .cancellations-table tr:hover {
            background-color: #f5f5f5;
        }

        .status {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
        }

        .status.cancelled {
            background-color: #ffebee;
            color: #c62828;
        }

        .no-cancellations {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }

        .reason {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
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
            <div class="user-actions">
                <a href="organizerprofile.php" class="profile-picture">
                    <img src="../include/organizer_image.php?id=<?php echo $organizer && isset($organizer['organizer_id']) ? $organizer['organizer_id'] : ''; ?>&t=<?php echo time(); ?>" alt="Profile Picture" class="navbar-profile-pic">
                </a>
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
                <a href="organizerprofile.php"><i class="fas fa-user"></i> My Profile</a>
            </div>
            
            <h2>Activities</h2>
            
            <div class="sidebar-link">
                <a href="organizerbookings.php"><i class="fas fa-calendar-check"></i> Bookings</a>
            </div>
            <div class="sidebar-link">
                <a href="organizerpending.php"><i class="fas fa-clock"></i> Pending</a>
            </div>
            <div class="sidebar-link">
                <a href="organizerrequests.php"><i class="fas fa-clipboard-list"></i> My Requests</a>
            </div>
            <div class="sidebar-link active">
                <a href="organizercancellations.php"><i class="fas fa-ban"></i> My Cancellations</a>
            </div>

            <div class="sidebar-link logout">
                <a href="../include/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <h1>My Cancellations</h1>
            <div class="welcome-message">
                <p>View your cancelled service requests</p>
            </div>
            
            <div class="cancellations-container">
                <?php if (empty($cancellations)): ?>
                    <div class="no-cancellations">
                        <p>You have no cancelled bookings.</p>
                    </div>
                <?php else: ?>
                    <table class="cancellations-table">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Artist Name</th>
                                <th>Event</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Location</th>
                                <th>Package</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cancellations as $cancellation): ?>
                                <tr>
                                    <td>#BK<?php echo str_pad($cancellation['booking_id'], 3, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo htmlspecialchars($cancellation['artist_name']); ?></td>
                                    <td><?php echo htmlspecialchars($cancellation['occasion']); ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($cancellation['event_date'])); ?></td>
                                    <td><?php echo date('h:i A', strtotime($cancellation['event_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($cancellation['event_location']); ?></td>
                                    <td><?php echo htmlspecialchars($cancellation['package_type']); ?></td>
                                    <td>Rs. <?php echo number_format($cancellation['total_amount'], 2); ?></td>
                                    <td><span class="status cancelled">Cancelled</span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="navbar.js"></script>

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