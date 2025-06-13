<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'dbcon.php';

if (!isset($_SESSION['artist_id'])) {
    header("Location: ../artist/artistprofile.php?updateerr=Not+logged+in");
    exit;
}

$artist_id = $_SESSION['artist_id'];

// Sanitize and validate input
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['mobile'] ?? '');
$perform_count = trim($_POST['perform_count'] ?? '');
$offstage_count = trim($_POST['offstage_count'] ?? '');
$artist_language = trim($_POST['artist_language'] ?? '');

if (empty($name) || empty($email) || empty($phone) || empty($perform_count) || empty($offstage_count) || empty($artist_language)) {
    header("Location: ../artist/artistprofile.php?updateerr=Please+fill+in+all+required+fields");
    exit;
}

// Update personal info
$stmt = $link->prepare("UPDATE artist SET artist_name=?, artist_email=?, artist_phone=?, perform_count=?, offstage_count=?, artist_language=? WHERE artist_id=?");
$stmt->bind_param("ssssssi", $name, $email, $phone, $perform_count, $offstage_count, $artist_language, $artist_id);
if (!$stmt->execute()) {
    header("Location: ../artist/artistprofile.php?updateerr=Failed+to+update+profile+info");
    exit;
}
$stmt->close();

// Handle password change if fields are filled
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if ($current_password || $new_password || $confirm_password) {
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        header("Location: ../artist/artistprofile.php?updateerr=Please+fill+all+password+fields");
        exit;
    }
    if ($new_password !== $confirm_password) {
        header("Location: ../artist/artistprofile.php?updateerr=New+passwords+do+not+match");
        exit;
    }
    // Check current password
    $stmt = $link->prepare("SELECT artist_pwd FROM artist WHERE artist_id=?");
    $stmt->bind_param("i", $artist_id);
    $stmt->execute();
    $stmt->bind_result($db_pwd);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_password, $db_pwd)) {
        header("Location: ../artist/artistprofile.php?updateerr=Current+password+is+incorrect");
        exit;
    }
    // Update password
    $hashedPwd = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $link->prepare("UPDATE artist SET artist_pwd=?, artist_re_pwd=? WHERE artist_id=?");
    $stmt->bind_param("ssi", $hashedPwd, $hashedPwd, $artist_id);
    if (!$stmt->execute()) {
        header("Location: ../artist/artistprofile.php?updateerr=Failed+to+update+password");
        exit;
    }
    $stmt->close();
}

header("Location: ../artist/artistprofile.php?updatemsg=Profile+updated+successfully");
exit; 