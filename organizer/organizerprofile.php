<?php
// Fetch organizer profile data
include_once '../include/get_organizer_profile.php';
$organizer = null;
if (isset(
    $response) && $response['success'] && isset($response['data'])
) {
    $organizer = $response['data'];
} else {
    // Optionally redirect to login or show error
    // header('Location: ../login signup/organizerlogin.php');
    // exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="organizerprofile.css">
    <link rel="stylesheet" href="navbar.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .profile-picture-container {
            position: relative;
            display: inline-block;
        }
        .edit-overlay {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0,0,0,0.6);
            border-radius: 50%;
            padding: 10px;
            display: none;
            cursor: pointer;
            color: #fff;
            transition: background 0.2s;
        }
        .profile-picture-container:hover .edit-overlay {
            display: block;
        }
        #profile_image {
            display: none;
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
            
            <div class="sidebar-link active">
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
            <div class="sidebar-link">
                <a href="organizercancellations.php"><i class="fas fa-ban"></i> My Cancellations</a>
            </div>

            <div class="sidebar-link logout">
                <a href="../include/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <h1>My Profile</h1>
            <div class="welcome-message">
                <p>Hello <span class="user-name"><?php echo isset($organizer['organizer_name']) ? htmlspecialchars($organizer['organizer_name']) : 'Organizer'; ?></span>, welcome to your profile!</p>
            </div>
            
            <!-- Profile Picture Section -->
            <div class="profile-picture-section">
                <div class="profile-picture-container">
                    <img src="../include/organizer_image.php?id=<?php echo $organizer['organizer_id']; ?>&t=<?php echo time(); ?>" alt="Profile Picture" id="profile-picture">
                    <form id="profile-image-form" action="../include/upload_organizer_image.php" method="post" enctype="multipart/form-data" style="margin:0;">
                        <label for="profile_image" class="edit-overlay" title="Change profile picture">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" name="profile_image" id="profile_image" accept="image/*" onchange="document.getElementById('profile-image-form').submit();">
                        <input type="hidden" name="upload_image" value="1">
                    </form>
                </div>
                <?php
                if (isset($_GET['imgmsg'])) {
                    echo '<div style="color:green;">' . htmlspecialchars($_GET['imgmsg']) . '</div>';
                }
                if (isset($_GET['imgerr'])) {
                    echo '<div style="color:red;">' . htmlspecialchars($_GET['imgerr']) . '</div>';
                }
                ?>
            </div>
            
            <!-- Profile Information Form -->
            <div class="profile-form">
                <form action="../include/update_organizer_profile.php" method="post" autocomplete="off">
                    <h2>Personal Information</h2>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Your Full Name" value="<?php echo isset($organizer['organizer_name']) ? htmlspecialchars($organizer['organizer_name']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="company">Company Name (Optional)</label>
                        <input type="text" id="company" name="company" placeholder="Company Name" value="<?php echo isset($organizer['organizer_company']) ? htmlspecialchars($organizer['organizer_company']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile Number</label>
                        <input type="tel" id="mobile" name="mobile" placeholder="Mobile Number" value="<?php echo isset($organizer['organizer_phone']) ? htmlspecialchars($organizer['organizer_phone']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Email Address" value="<?php echo isset($organizer['organizer_email']) ? htmlspecialchars($organizer['organizer_email']) : ''; ?>" required>
                    </div>
                    <h2>Change Password</h2>
                    <div class="form-group">
                        <label for="current-password">Current Password</label>
                        <div class="password-input">
                            <input type="password" id="current-password" name="current_password" placeholder="Current Password">
                            <i class="fas fa-eye-slash toggle-password"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new-password">New Password</label>
                        <div class="password-input">
                            <input type="password" id="new-password" name="new_password" placeholder="New Password">
                            <i class="fas fa-eye-slash toggle-password"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm New Password</label>
                        <div class="password-input">
                            <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm New Password">
                            <i class="fas fa-eye-slash toggle-password"></i>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="reset" class="btn-cancel">Cancel</button>
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>
                </form>
                <?php
                if (isset($_GET['updatemsg'])) {
                    echo '<div style="color:green;">' . htmlspecialchars($_GET['updatemsg']) . '</div>';
                }
                if (isset($_GET['updateerr'])) {
                    echo '<div style="color:red;">' . htmlspecialchars($_GET['updateerr']) . '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        // Simple script to toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function() {
                const input = this.previousElementSibling;
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.replace('fa-eye-slash', 'fa-eye');
                } else {
                    input.type = 'password';
                    this.classList.replace('fa-eye', 'fa-eye-slash');
                }
            });
        });
    </script>

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