<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbcon.php';

    // Collect and sanitize inputs
    $category = trim($_POST["artist_category"] ?? '');
    $name = trim($_POST["artist_name"] ?? '');
    $email = trim($_POST["artist_email"] ?? '');
    $phone = trim($_POST["artist_phone"] ?? '');
    $perform_count = trim($_POST["perform_count"] ?? '');
    $offstage_count = trim($_POST["offstage_count"] ?? '');
    $language = trim($_POST["artist_language"] ?? '');
    $pwd = $_POST["artist_pwd"] ?? '';
    $re_pwd = $_POST["artist_re_pwd"] ?? '';
    $agree = isset($_POST["agree"]);

    // Error handling
    if (
        empty($category) || empty($name) || empty($email) || empty($phone) ||
        empty($perform_count) || empty($offstage_count) || empty($language) ||
        empty($pwd) || empty($re_pwd)
    ) {
        $_SESSION['signup_error'] = '*Please fill in all required fields.';
        header('Location:../login signup/artistsignup.php');
        exit();
    }
    if (!$agree) {
        $_SESSION['signup_error'] = '*You must agree to the terms and conditions.';
        header('Location:../login signup/artistsignup.php');
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['signup_error'] = '*Please enter a valid email address.';
        header('Location:../login signup/artistsignup.php');
        exit();
    }
    if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        $_SESSION['signup_error'] = '*Please enter a valid phone number (10-15 digits).';
        header('Location:../login signup/artistsignup.php');
        exit();
    }
    if (strlen($pwd) < 6) {
        $_SESSION['signup_error'] = '*Password must be at least 6 characters.';
        header('Location:../login signup/artistsignup.php');
        exit();
    }
    if ($pwd !== $re_pwd) {
        $_SESSION['signup_error'] = '*Passwords do not match.';
        header('Location:../login signup/artistsignup.php');
        exit();
    }

    // Check for duplicate email or phone
    $stmt = $link->prepare("SELECT artist_id FROM artist WHERE artist_email = ? OR artist_phone = ?");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $_SESSION['signup_error'] = '*An account with this email or phone already exists.';
        header('Location:../login signup/artistsignup.php');
        exit();
    }
    $stmt->close();

    // Handle file upload (if you add file upload in the form)
    $photo_path = ''; // Set this if you implement file upload
    $description = '';

    // Hash password
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $link->prepare("INSERT INTO artist (artist_category, artist_name, artist_email, artist_phone, perform_count, offstage_count, artist_language, artist_pwd, artist_re_pwd, artist_photo, artist_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $category, $name, $email, $phone, $perform_count, $offstage_count, $language, $hashedPwd, $hashedPwd, $photo_path, $description);

    if ($stmt->execute()) {
        $new_artist_id = $link->insert_id;
        $_SESSION['artist_id'] = $new_artist_id;
        $_SESSION['artist_name'] = $name;
        $stmt->close();
        header('Location:../artist/artistprofile.php');
        exit();
    } else {
        $stmt->close();
        $_SESSION['signup_error'] = '*A server error occurred. Please try again later.';
        header('Location:../login signup/artistsignup.php');
        exit();
    }
} else {
    $_SESSION['signup_error'] = '*Access denied.';
    header('Location:../login signup/artistsignup.php');
    exit();
}
?>