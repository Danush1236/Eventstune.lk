<?php
session_start();
include_once 'dbcon.php';

// Check if organizer is logged in
if (!isset($_SESSION['organizer_id'])) {
    header("Location: ../html/login.php");
    exit();
}

// Validate POST request
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../html/payment.php?error=invalid_request");
    exit();
}

// Get and validate form data
$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
$payment_amount = isset($_POST['payment_amount']) ? floatval($_POST['payment_amount']) : 0;

// Validate booking exists and belongs to logged-in organizer
$stmt = $link->prepare("
    SELECT booking_id, total_amount, paid_amount, due_amount 
    FROM booking 
    WHERE booking_id = ? AND organizer_id = ?
");
$stmt->bind_param("ii", $booking_id, $_SESSION['organizer_id']);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    header("Location: ../html/payment.php?error=invalid_booking");
    exit();
}

// Validate payment amount
if ($payment_amount <= 0 || $payment_amount > $booking['due_amount']) {
    header("Location: ../html/payment.php?booking_id=" . $booking_id . "&error=invalid_amount");
    exit();
}

try {
    // Calculate new amounts
    $new_paid_amount = $booking['paid_amount'] + $payment_amount;
    $new_due_amount = $booking['total_amount'] - $new_paid_amount;

    // Update only paid_amount and due_amount
    $stmt = $link->prepare("
        UPDATE booking 
        SET paid_amount = ?,
            due_amount = ?
        WHERE booking_id = ?
    ");
    $stmt->bind_param("ddi", $new_paid_amount, $new_due_amount, $booking_id);
    $stmt->execute();

    // Redirect with success message
    header("Location: ../organizer/organizerbookings.php?success=payment_processed");
    exit();

} catch (Exception $e) {
    // Log error (in a production environment)
    error_log("Payment processing error: " . $e->getMessage());
    
    // Redirect with error message
    header("Location: ../html/payment.php?booking_id=" . $booking_id . "&error=payment_failed");
    exit();
} 