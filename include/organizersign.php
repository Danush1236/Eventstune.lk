<?php
session_start();
if (isset($_POST["submit"])) {
    include 'dbcon.php';

    // Collect and sanitize inputs
    $name = trim($_POST["organizer_name"] ?? '');
    $company = trim($_POST["organizer_company"] ?? '');
    $phone = trim($_POST["organizer_phone"] ?? '');
    $email = trim($_POST["organizer_email"] ?? '');
    $pwd = $_POST["organizer_pwd"] ?? '';
    $re_pwd = $_POST["organizer_re_pwd"] ?? '';
    $agree = isset($_POST["agree"]);

    // Error handling
    if (empty($name) || empty($phone) || empty($email) || empty($pwd) || empty($re_pwd)) {
        $_SESSION['signup_error'] = '*Please fill in all required fields.';
        header('Location:../login signup/organizersignup.php');
        exit();
    }
    if (!$agree) {
        $_SESSION['signup_error'] = '*You must agree to the terms and conditions.';
        header('Location:../login signup/organizersignup.php');
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['signup_error'] = '*Please enter a valid email address.';
        header('Location:../login signup/organizersignup.php');
        exit();
    }
    if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        $_SESSION['signup_error'] = '*Please enter a valid phone number (10-15 digits).';
        header('Location:../login signup/organizersignup.php');
        exit();
    }
    if (strlen($pwd) < 6) {
        $_SESSION['signup_error'] = '*Password must be at least 6 characters.';
        header('Location:../login signup/organizersignup.php');
        exit();
    }
    if ($pwd !== $re_pwd) {
        $_SESSION['signup_error'] = '*Passwords do not match.';
        header('Location:../login signup/organizersignup.php');
        exit();
    }

    // Check for duplicate email or phone
    $stmt = $link->prepare("SELECT organizer_id FROM organizer WHERE organizer_email = ? OR organizer_phone = ?");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $_SESSION['signup_error'] = '*An account with this email or phone already exists.';
        header('Location:../login signup/organizersignup.php');
        exit();
    }
    $stmt->close();

    // Handle file upload
    $photo_path = '';

    // Hash password
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $link->prepare("INSERT INTO organizer (organizer_name, organizer_company, organizer_phone, organizer_email, organizer_pwd, organizer_re_pwd, organizer_photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $company, $phone, $email, $hashedPwd, $hashedPwd, $photo_path);
    if ($stmt->execute()) {
        // Get the new organizer's ID
        $new_organizer_id = $link->insert_id;
        $_SESSION['organizer_id'] = $new_organizer_id;
        $_SESSION['organizer_name'] = $name;
        $stmt->close();
        header('Location:../html/home2.php');
        exit();
    } else {
        $stmt->close();
        $_SESSION['signup_error'] = '*A server error occurred. Please try again later.';
        header('Location:../login signup/organizersignup.php');
        exit();
    }
} else {
    $_SESSION['signup_error'] = '*Access denied.';
    header('Location:../login signup/organizersignup.php');
    exit();
}

?>