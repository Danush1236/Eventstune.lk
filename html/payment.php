<?php
session_start();
include_once '../include/dbcon.php';
include_once '../include/get_organizer_profile.php';

// Add this after the session_start() at the top
$success_message = '';
$error_message = '';

if (isset($_GET['success']) && $_GET['success'] == 'payment_processed') {
    $success_message = "Payment processed successfully!";
}

if (isset($_GET['error'])) {
    switch($_GET['error']) {
        case 'invalid_request':
            $error_message = "Invalid request method.";
            break;
        case 'invalid_booking':
            $error_message = "Invalid booking details.";
            break;
        case 'invalid_amount':
            $error_message = "Invalid payment amount.";
            break;
        case 'invalid_card':
            $error_message = "Please fill in all card details.";
            break;
        case 'invalid_card_number':
            $error_message = "Please enter a valid 16-digit card number.";
            break;
        case 'invalid_expiry':
            $error_message = "Please enter a valid expiry date (MM/YY).";
            break;
        case 'invalid_cvv':
            $error_message = "Please enter a valid CVV number.";
            break;
        case 'payment_failed':
            $error_message = "Payment processing failed. Please try again.";
            break;
        default:
            $error_message = "An error occurred. Please try again.";
    }
}

// Check if organizer is logged in
if (!isset($_SESSION['organizer_id'])) {
    header("Location: login.php");
    exit();
}

// Get booking details if booking_id is provided
$booking = null;
$error = null;
if (isset($_GET['booking_id'])) {
    $stmt = $link->prepare("
        SELECT b.*, a.artist_name, 
               o.organizer_name, o.organizer_email, o.organizer_phone as organizer_contact 
        FROM booking b
        JOIN artist a ON b.artist_id = a.artist_id
        JOIN organizer o ON b.organizer_id = o.organizer_id
        WHERE b.booking_id = ? AND b.organizer_id = ?
    ");
    $stmt->bind_param("ii", $_GET['booking_id'], $_SESSION['organizer_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $booking = $row;
    } else {
        $error = "Invalid booking ID or unauthorized access.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment | EventsTune.lk</title>
  <link rel="stylesheet" href="../css/payment.css">
  <link rel="stylesheet" href="../organizer/navbar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <header class="navbar">
    <div class="navbar-logo">
      <span>EventsTune.lk</span>
    </div>
    <nav class="navbar-links">
      <a href="#singers">Singers</a>
      <a href="#bands">Music Bands</a>
      <a href="#dancing">Dancing Groups</a>
    </nav>
    <div class="navbar-auth">
      <?php if (isset($_SESSION['organizer_id'])): ?>
        <a href="../organizer/organizerprofile.php">
          <img src="../include/organizer_image.php?id=<?php echo $_SESSION['organizer_id']; ?>&t=<?php echo time(); ?>" 
               alt="Profile Picture" class="navbar-profile-pic">
        </a>
      <?php else: ?>
        <a href="signup.php"><button class="signup-btn">Sign Up</button></a>
        <a href="login.php"><button class="login-btn">Login</button></a>
      <?php endif; ?>
    </div>
  </header>
  <!-- Back Arrow Button -->
  <a href="../organizer/organizerpending.php" class="back-arrow-btn" title="Back to Bookings">
    <i class="fa fa-arrow-left"></i>
  </a>
  <div class="breadcrumb-bar">
    <span>Home</span> / <span>Bookings</span> / <span>Payment</span>
  </div>
  <div class="steps-bar">
    <div class="step"><span>1</span><div>Artist Details</div></div>
    <div class="step"><span>2</span><div>Artist Booking</div></div>
    <div class="step active"><span>3</span><div>Payment</div></div>
  </div>
  <?php if ($error): ?>
    <div class="error-message">
      <?php echo htmlspecialchars($error); ?>
    </div>
  <?php elseif ($booking): ?>
    <main class="payment-main-container">
      <div class="payment-content-row">
        <form class="payment-billing-form">
          <h2 class="payment-section-title">Billing Details</h2>
          <hr class="payment-divider">
          <div class="payment-form-group">
            <label>Name :</label>
            <input type="text" value="<?php echo htmlspecialchars($booking['organizer_name']); ?>" readonly>
          </div>
          <div class="payment-form-group">
            <label>Email :</label>
            <input type="email" value="<?php echo htmlspecialchars($booking['organizer_email']); ?>" readonly>
          </div>
          <div class="payment-form-group">
            <label>Phone No :</label>
            <input type="text" value="<?php echo htmlspecialchars($booking['organizer_contact']); ?>" readonly>
          </div>
        </form>
        <div class="payment-summary-col" id="invoiceSection">
          <h2 class="payment-section-title">Invoice Details</h2>
          <hr class="payment-divider">
          <div class="payment-summary-list">
            <div class="payment-summary-row">
              <span>Booking ID</span>
              <span>#BK<?php echo str_pad($booking['booking_id'], 3, '0', STR_PAD_LEFT); ?></span>
            </div>
            <div class="payment-summary-row">
              <span>Artist Name</span>
              <span><?php echo htmlspecialchars($booking['artist_name']); ?></span>
            </div>
            <div class="payment-summary-row">
              <span>Total Amount</span>
              <span><?php echo number_format($booking['total_amount'], 2); ?> LKR</span>
            </div>
            <div class="payment-summary-row">
              <span>Due Amount</span>
              <span><?php echo number_format($booking['due_amount'], 2); ?> LKR</span>
            </div>
          </div>
          <hr class="payment-divider">
          <div class="payment-summary-total">
            <span>Amount to Pay</span>
            <span><?php echo number_format($booking['due_amount'], 2); ?> LKR</span>
          </div>
          <div class="payment-card-icons">
            <img src="../image/visa.jpg" alt="Visa" class="card-icon">
            <img src="../image/mastercard.jpg" alt="Mastercard" class="card-icon">
            <img src="../image/unionpay.jpg" alt="UnionPay" class="card-icon">
          </div>
          <div class="payment-checkbox-row">
            <label class="payment-checkbox-label">
              <input type="checkbox" id="termsCheckbox"> I accept and agree to <a href="#" class="payment-terms-link">Terms and Conditions</a>
            </label>
          </div>
          <div class="payment-error-msg" id="termsError" style="display: none;">
            * In order to proceed, you should agree to T & C by clicking the above box
          </div>
          <button class="payment-submit-btn" id="showCardSection">Proceed to pay &nbsp; <i class="fa fa-arrow-right"></i></button>
        </div>
        <!-- Card Payment Section (hidden by default) -->
        <div class="card-payment-section" id="cardSection" style="display:none;">
          <h2 class="payment-section-title">Payment</h2>
          <hr class="payment-divider">
          <form class="card-payment-form" method="POST" action="../include/process_payment.php" id="paymentForm">
            <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
            <div class="payment-form-group">
              <label class="card-pay-label">Enter Payment Amount (LKR):</label>
              <input type="number" name="payment_amount" class="card-input" 
                     min="1" max="<?php echo $booking['due_amount']; ?>" 
                     value="<?php echo $booking['due_amount']; ?>" required>
              <small class="payment-hint">Maximum amount: Rs. <?php echo number_format($booking['due_amount'], 2); ?></small>
            </div>
            <button type="submit" class="card-pay-btn">Pay Now</button>
          </form>
        </div>
      </div>
    </main>
    <?php if ($success_message): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
  <?php endif; ?>
  <footer class="footer">
    <div class="footer-main">
      <div class="footer-info">
        <div class="footer-logo">
          <span>EventsTune.lk</span>
        </div>
        <p>EventsTune.lk, Sri Lanka's premier and most trusted online platform for booking artists, music bands, and dance groups, serves as the official marketplace, providing a secure and seamless experience for organizing unforgettable musical events and functions.</p>
        <div class="footer-socials">
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-facebook"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
          <a href="#"><i class="fab fa-tiktok"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
          <a href="#"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>
      <div class="footer-links">
        <div>
          <h4>Site Map</h4>
          <ul>
            <li><a href="#singers">Singers</a></li>
            <li><a href="#bands">Music Bands</a></li>
            <li><a href="#dancing">Dancing Groups</a></li>
          </ul>
        </div>
        <div>
          <h4>FAQ</h4>
          <ul>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms & Conditions</a></li>
          </ul>
        </div>
        <div>
          <h4>Contact</h4>
          <ul>
            <li><a href="mailto:support@eventstune.lk">support@eventstune.lk</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-bottom-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Cookie Policy</a>
        <a href="#">Terms and Conditions</a>
      </div>
      <span>Copyright 2025 © EventsTune.lk | All Rights Reserved</span>
    </div>
  </footer>
  <script>
    document.getElementById('showCardSection').addEventListener('click', function(e) {
      e.preventDefault();
      
      // Check if terms are accepted
      if (!document.getElementById('termsCheckbox').checked) {
        document.getElementById('termsError').style.display = 'block';
        return;
      }
      
      document.getElementById('termsError').style.display = 'none';
      document.getElementById('invoiceSection').style.display = 'none';
      document.getElementById('cardSection').style.display = 'block';
    });

    // Add form validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const paymentAmount = document.querySelector('input[name="payment_amount"]');
      const maxAmount = parseFloat(paymentAmount.getAttribute('max'));
      const amount = parseFloat(paymentAmount.value);
      
      if (!amount || amount <= 0) {
        alert('Please enter a valid payment amount');
        return false;
      }
      
      if (amount > maxAmount) {
        alert('Payment amount cannot exceed the due amount');
        return false;
      }
      
      // If validation passes, submit the form
      this.submit();
    });
  </script>
</body>
</html> 