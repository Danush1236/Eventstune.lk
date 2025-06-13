<?php
include_once '../include/session.php';
include_once '../include/dbcon.php';
$response = include_once '../include/get_artist_profile.php';
$artist = null;
if ($response && $response['success'] && isset($response['data'])) {
    $artist = $response['data'];
}

// Handle form submission
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $required_fields = ['bankName', 'branchName', 'accountNumber', 'accountHolder'];
    $is_valid = true;
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $is_valid = false;
            $message = 'All fields are required';
            $messageType = 'error';
            break;
        }
    }
    
    if ($is_valid) {
        $artist_id = $_SESSION['artist_id'];
        $bank_name = mysqli_real_escape_string($link, $_POST['bankName']);
        $branch_name = mysqli_real_escape_string($link, $_POST['branchName']);
        $account_number = mysqli_real_escape_string($link, $_POST['accountNumber']);
        $holder_name = mysqli_real_escape_string($link, $_POST['accountHolder']);
        $bank_id = isset($_POST['bankId']) && !empty($_POST['bankId']) ? (int)$_POST['bankId'] : null;

        try {
            // Check if artist already has bank details
            $check_query = "SELECT bank_id FROM `artist bank` WHERE artist_id = ?";
            $check_stmt = $link->prepare($check_query);
            $check_stmt->bind_param("i", $artist_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            $existing_bank = $result->fetch_assoc();
            $check_stmt->close();

            if ($existing_bank && !$bank_id) {
                $bank_id = $existing_bank['bank_id'];
            }

            if ($bank_id) {
                // Update existing bank details
                $query = "UPDATE `artist bank` SET 
                         bank_name = ?, 
                         branch_name = ?, 
                         account_number = ?, 
                         holder_name = ? 
                         WHERE bank_id = ? AND artist_id = ?";
                $stmt = $link->prepare($query);
                $stmt->bind_param("ssssii", $bank_name, $branch_name, $account_number, $holder_name, $bank_id, $artist_id);
            } else {
                // Insert new bank details
                $query = "INSERT INTO `artist bank` (artist_id, bank_name, branch_name, account_number, holder_name) 
                         VALUES (?, ?, ?, ?, ?)";
                $stmt = $link->prepare($query);
                $stmt->bind_param("issss", $artist_id, $bank_name, $branch_name, $account_number, $holder_name);
            }

            if ($stmt->execute()) {
                $message = 'Bank details saved successfully';
                $messageType = 'success';
            } else {
                $message = 'Failed to save bank details';
                $messageType = 'error';
            }
            $stmt->close();
        } catch (Exception $e) {
            $message = 'Error saving bank details';
            $messageType = 'error';
        }
    }
}

// Fetch existing bank details
$bank_details = null;
if (isset($_SESSION['artist_id'])) {
    $stmt = $link->prepare("SELECT * FROM `artist bank` WHERE artist_id = ?");
    $stmt->bind_param("i", $_SESSION['artist_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $bank_details = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Details - Artist Dashboard</title>
    <link rel="stylesheet" href="artistprofile.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="artistbankdetails.css">
    <link rel="stylesheet" href="artistnav.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .bank-details-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .current-details {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        .current-details h3 {
            margin-top: 0;
            color: #1976d2;
        }
        .current-details p {
            margin: 10px 0;
        }
        .current-details strong {
            min-width: 150px;
            display: inline-block;
            color: #1976d2;
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
            <div class="sidebar-link active">
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
            <h1>Bank Details</h1>
            <div class="welcome-message">
                <p>Manage your bank account information for receiving payments</p>
            </div>

            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Display current bank details if they exist -->
            <?php if ($bank_details): ?>
            <div class="current-details">
                <h3>Current Bank Details</h3>
                <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($bank_details['bank_name']); ?></p>
                <p><strong>Branch Name:</strong> <?php echo htmlspecialchars($bank_details['branch_name']); ?></p>
                <p><strong>Account Number:</strong> <?php echo htmlspecialchars($bank_details['account_number']); ?></p>
                <p><strong>Account Holder:</strong> <?php echo htmlspecialchars($bank_details['holder_name']); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Form for bank details -->
            <div class="bank-details-section">
                <form method="POST" action="artistbankdetails.php">
                    <div class="form-group">
                        <label for="bankName">Bank Name</label>
                        <select id="bankName" name="bankName" required>
                            <option value="">Select a bank</option>
                            <option value="Bank of Ceylon" <?php echo ($bank_details && $bank_details['bank_name'] == 'Bank of Ceylon') ? 'selected' : ''; ?>>Bank of Ceylon</option>
                            <option value="People's Bank" <?php echo ($bank_details && $bank_details['bank_name'] == "People's Bank") ? 'selected' : ''; ?>>People's Bank</option>
                            <option value="Commercial Bank" <?php echo ($bank_details && $bank_details['bank_name'] == 'Commercial Bank') ? 'selected' : ''; ?>>Commercial Bank</option>
                            <option value="Hatton National Bank" <?php echo ($bank_details && $bank_details['bank_name'] == 'Hatton National Bank') ? 'selected' : ''; ?>>Hatton National Bank</option>
                            <option value="Sampath Bank" <?php echo ($bank_details && $bank_details['bank_name'] == 'Sampath Bank') ? 'selected' : ''; ?>>Sampath Bank</option>
                            <option value="NDB Bank" <?php echo ($bank_details && $bank_details['bank_name'] == 'NDB Bank') ? 'selected' : ''; ?>>NDB Bank</option>
                            <option value="Seylan Bank" <?php echo ($bank_details && $bank_details['bank_name'] == 'Seylan Bank') ? 'selected' : ''; ?>>Seylan Bank</option>
                            <option value="DFCC Bank" <?php echo ($bank_details && $bank_details['bank_name'] == 'DFCC Bank') ? 'selected' : ''; ?>>DFCC Bank</option>
                            <option value="Pan Asia Bank" <?php echo ($bank_details && $bank_details['bank_name'] == 'Pan Asia Bank') ? 'selected' : ''; ?>>Pan Asia Bank</option>
                            <option value="Union Bank" <?php echo ($bank_details && $bank_details['bank_name'] == 'Union Bank') ? 'selected' : ''; ?>>Union Bank</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="branchName">Branch Name</label>
                        <select id="branchName" name="branchName" required>
                            <option value="">Select a branch</option>
                            <option value="Colombo Main" <?php echo ($bank_details && $bank_details['branch_name'] == 'Colombo Main') ? 'selected' : ''; ?>>Colombo Main</option>
                            <option value="Kandy" <?php echo ($bank_details && $bank_details['branch_name'] == 'Kandy') ? 'selected' : ''; ?>>Kandy</option>
                            <option value="Galle" <?php echo ($bank_details && $bank_details['branch_name'] == 'Galle') ? 'selected' : ''; ?>>Galle</option>
                            <option value="Jaffna" <?php echo ($bank_details && $bank_details['branch_name'] == 'Jaffna') ? 'selected' : ''; ?>>Jaffna</option>
                            <option value="Matara" <?php echo ($bank_details && $bank_details['branch_name'] == 'Matara') ? 'selected' : ''; ?>>Matara</option>
                            <option value="Negombo" <?php echo ($bank_details && $bank_details['branch_name'] == 'Negombo') ? 'selected' : ''; ?>>Negombo</option>
                            <option value="Kurunegala" <?php echo ($bank_details && $bank_details['branch_name'] == 'Kurunegala') ? 'selected' : ''; ?>>Kurunegala</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="accountNumber">Account Number</label>
                        <input type="text" id="accountNumber" name="accountNumber" 
                               pattern="[0-9]{8,}" required
                               value="<?php echo $bank_details ? htmlspecialchars($bank_details['account_number']) : ''; ?>"
                               placeholder="Enter your account number">
                        <small class="form-text">Account number must be at least 8 digits</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="accountHolder">Account Holder Name</label>
                        <input type="text" id="accountHolder" name="accountHolder" 
                               required
                               value="<?php echo $bank_details ? htmlspecialchars($bank_details['holder_name']) : ''; ?>"
                               placeholder="Enter account holder name">
                    </div>

                    <?php if ($bank_details): ?>
                        <input type="hidden" name="bankId" value="<?php echo $bank_details['bank_id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> <?php echo $bank_details ? 'Update' : 'Save'; ?> Bank Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="navbar.js"></script>
    <script src="artistnav.js"></script>
    <script src="artistbankdetails.js"></script>

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