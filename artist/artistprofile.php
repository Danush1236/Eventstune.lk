<?php
include_once '../include/session.php';
$response = include_once '../include/get_artist_profile.php';
$artist = null;
if ($response && $response['success'] && isset($response['data'])) {
    $artist = $response['data'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="artistprofile.css">
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
            
            <div class="sidebar-link active">
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
            <h1>My Profile</h1>
            <div class="welcome-message">
                <p>Hello <span class="user-name"><?php echo htmlspecialchars($artist['artist_name'] ?? 'Artist'); ?></span>, welcome to your profile!</p>
            </div>
            
            <!-- Profile Picture Section -->
            <div class="profile-picture-section">
                <div class="profile-picture-container">
                    <img src="../include/artist_image.php?id=<?php echo $artist['artist_id']; ?>&t=<?php echo time(); ?>" alt="Profile Picture" id="main-profile-picture">
                    <form id="profile-image-form" action="../include/upload_artist_image.php" method="post" enctype="multipart/form-data" style="position: absolute; bottom: 10px; right: 10px; margin: 0;">
                        <label for="profile_image" class="edit-overlay" title="Change profile picture">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" name="profile_image" id="profile_image" accept="image/*" onchange="document.getElementById('profile-image-form').submit();">
                        <input type="hidden" name="upload_image" value="1">
                    </form>
                </div>
                <?php
                if (isset($_GET['imgmsg'])) {
                    echo '<div style=\"color:green;\">' . htmlspecialchars($_GET['imgmsg']) . '</div>';
                }
                if (isset($_GET['imgerr'])) {
                    echo '<div style=\"color:red;\">' . htmlspecialchars($_GET['imgerr']) . '</div>';
                }
                ?>
            </div>
            
            <!-- Profile Information Form -->
            <div class="profile-form">
                <form action="../include/update_artist_profile.php" method="post" autocomplete="off">
                    <h2>Artist Information</h2>
                    <div class="form-group">
                        <label for="category">Artist Category</label>
                        <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($artist['artist_category'] ?? ''); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Your Full Name" value="<?php echo htmlspecialchars($artist['artist_name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($artist['artist_email'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile">Telephone Number</label>
                        <input type="tel" id="mobile" name="mobile" placeholder="Telephone Number" value="<?php echo htmlspecialchars($artist['artist_phone'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="performing-members">Number of Performing Members</label>
                        <input type="number" id="performing-members" name="perform_count" placeholder="Number of Performing Members" value="<?php echo htmlspecialchars($artist['perform_count'] ?? ''); ?>" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="offstage-members">Number of Offstage Members</label>
                        <input type="number" id="offstage-members" name="offstage_count" placeholder="Number of Offstage Members" value="<?php echo htmlspecialchars($artist['offstage_count'] ?? ''); ?>" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="languages">Language(s)</label>
                        <input type="text" id="languages" name="artist_language" placeholder="Language(s)" value="<?php echo htmlspecialchars($artist['artist_language'] ?? ''); ?>" required>
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

            <!-- Featured Photos Section -->
            <div class="featured-photos-section">
                <h2>Featured Photos</h2>
                <p class="section-description">Add up to 6 photos to showcase your best performances and moments</p>
                
                <div class="photo-gallery">
                    <div class="photo-grid">
                        <div class="photo-item add-photo">
                            <div class="photo-placeholder">
                                <i class="fas fa-plus"></i>
                                <span>Add Photos</span>
                            </div>
                            <input type="file" class="photo-input" accept="image/*" multiple>
                        </div>
                        <div class="photo-item">
                            <img src="../image/placeholder1.jpg" alt="Featured Photo 1">
                            <div class="photo-overlay">
                                <button class="btn-remove"><i class="fas fa-times"></i></button>
                                <button class="btn-move"><i class="fas fa-arrows-alt"></i></button>
                            </div>
                        </div>
                        <div class="photo-item">
                            <img src="../image/placeholder2.jpg" alt="Featured Photo 2">
                            <div class="photo-overlay">
                                <button class="btn-remove"><i class="fas fa-times"></i></button>
                                <button class="btn-move"><i class="fas fa-arrows-alt"></i></button>
                            </div>
                        </div>
                        <div class="photo-item">
                            <img src="../image/placeholder3.jpg" alt="Featured Photo 3">
                            <div class="photo-overlay">
                                <button class="btn-remove"><i class="fas fa-times"></i></button>
                                <button class="btn-move"><i class="fas fa-arrows-alt"></i></button>
                            </div>
                        </div>
                        <div class="photo-item">
                            <img src="../image/placeholder4.jpg" alt="Featured Photo 4">
                            <div class="photo-overlay">
                                <button class="btn-remove"><i class="fas fa-times"></i></button>
                                <button class="btn-move"><i class="fas fa-arrows-alt"></i></button>
                            </div>
                        </div>
                        <div class="photo-item">
                            <img src="../image/placeholder5.jpg" alt="Featured Photo 5">
                            <div class="photo-overlay">
                                <button class="btn-remove"><i class="fas fa-times"></i></button>
                                <button class="btn-move"><i class="fas fa-arrows-alt"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
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

        // Photo upload functionality
        const photoInput = document.querySelector('.photo-input');
        const photoGrid = document.querySelector('.photo-grid');
        let currentPhotos = 0;
        const maxPhotos = 6;

        photoInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const remainingSlots = maxPhotos - currentPhotos;
            
            if (files.length > remainingSlots) {
                alert(`You can only add ${remainingSlots} more photo(s).`);
                return;
            }

            files.forEach(file => {
                if (currentPhotos >= maxPhotos) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoItem = createPhotoElement(e.target.result);
                    photoGrid.insertBefore(photoItem, document.querySelector('.add-photo'));
                    currentPhotos++;

                    if (currentPhotos >= maxPhotos) {
                        document.querySelector('.add-photo').style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            });
        });

        function createPhotoElement(src) {
            const photoItem = document.createElement('div');
            photoItem.className = 'photo-item';
            photoItem.innerHTML = `
                 <img src="${src}" alt="Featured Photo">
                 <div class="photo-overlay">
                     <button class="btn-remove"><i class="fas fa-times"></i></button>
                     <button class="btn-move"><i class="fas fa-arrows-alt"></i></button>
                 </div>
             `;

            // Add remove functionality
            photoItem.querySelector('.btn-remove').addEventListener('click', function() {
                photoItem.remove();
                currentPhotos--;
                document.querySelector('.add-photo').style.display = 'flex';
            });

            // Add drag functionality
            photoItem.draggable = true;
            photoItem.addEventListener('dragstart', handleDragStart);
            photoItem.addEventListener('dragover', handleDragOver);
            photoItem.addEventListener('drop', handleDrop);

            return photoItem;
        }

        // Drag and drop functionality
        let draggedItem = null;

        function handleDragStart(e) {
            draggedItem = this;
            this.style.opacity = '0.5';
        }

        function handleDragOver(e) {
            e.preventDefault();
        }

        function handleDrop(e) {
            e.preventDefault();
            if (this !== draggedItem) {
                const allPhotos = [...this.parentNode.children];
                const draggedIndex = allPhotos.indexOf(draggedItem);
                const droppedIndex = allPhotos.indexOf(this);

                if (draggedIndex < droppedIndex) {
                    this.parentNode.insertBefore(draggedItem, this.nextSibling);
                } else {
                    this.parentNode.insertBefore(draggedItem, this);
                }
            }
            draggedItem.style.opacity = '1';
        }
    </script>

    <script src="navbar.js"></script>
    <script src="artistnav.js"></script>

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