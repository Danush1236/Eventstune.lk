<?php
session_start();
include 'dbcon.php';

if (!isset($_SESSION['organizer_id'])) {
    header("Location: ../profile/organizerprofile.php?updateerr=Not+logged+in");
    exit;
}

$organizer_id = $_SESSION['organizer_id'];

// Sanitize and validate input
$name = trim($_POST['name'] ?? '');
$company = trim($_POST['company'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$email = trim($_POST['email'] ?? '');

if (empty($name) || empty($mobile) || empty($email)) {
    header("Location: ../profile/organizerprofile.php?updateerr=Please+fill+in+all+required+fields");
    exit;
}

// Update personal info
$stmt = $link->prepare("UPDATE organizer SET organizer_name=?, organizer_company=?, organizer_phone=?, organizer_email=? WHERE organizer_id=?");
$stmt->bind_param("ssssi", $name, $company, $mobile, $email, $organizer_id);
if (!$stmt->execute()) {
    header("Location: ../profile/organizerprofile.php?updateerr=Failed+to+update+profile+info");
    exit;
}
$stmt->close();

// Handle password change if fields are filled
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if ($current_password || $new_password || $confirm_password) {
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        header("Location: ../profile/organizerprofile.php?updateerr=Please+fill+all+password+fields");
        exit;
    }
    if ($new_password !== $confirm_password) {
        header("Location: ../profile/organizerprofile.php?updateerr=New+passwords+do+not+match");
        exit;
    }
    // Check current password
    $stmt = $link->prepare("SELECT organizer_pwd FROM organizer WHERE organizer_id=?");
    $stmt->bind_param("i", $organizer_id);
    $stmt->execute();
    $stmt->bind_result($db_pwd);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_password, $db_pwd)) {
        header("Location: ../profile/organizerprofile.php?updateerr=Current+password+is+incorrect");
        exit;
    }
    // Update password
    $hashedPwd = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $link->prepare("UPDATE organizer SET organizer_pwd=?, organizer_re_pwd=? WHERE organizer_id=?");
    $stmt->bind_param("ssi", $hashedPwd, $hashedPwd, $organizer_id);
    if (!$stmt->execute()) {
        header("Location: ../profile/organizerprofile.php?updateerr=Failed+to+update+password");
        exit;
    }
    $stmt->close();
}

header("Location: ../organizer/organizerprofile.php?updatemsg=Profile+updated+successfully");
exit; 