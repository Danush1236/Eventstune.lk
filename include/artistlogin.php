<?php
session_start();
if (isset($_POST["artist_email"]) && isset($_POST["artist_pwd"])) {
    include 'dbcon.php';

    $email = trim($_POST["artist_email"]);
    $pwd = $_POST["artist_pwd"];

    // Validation
    if (empty($email) || empty($pwd)) {
        $_SESSION['login_error'] = '*Please fill in all required fields.';
        header('Location:../login signup/artistlogin.php');
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['login_error'] = '*Please enter a valid email address.';
        header('Location:../login signup/artistlogin.php');
        exit();
    }
    if (strlen($pwd) < 6) {
        $_SESSION['login_error'] = '*Password must be at least 6 characters.';
        header('Location:../login signup/artistlogin.php');
        exit();
    }

    $stmt = $link->prepare("SELECT * FROM artist WHERE artist_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($pwd, $row['artist_pwd'])) {
            $_SESSION['artist_id'] = $row['artist_id'];
            $_SESSION['artist_name'] = $row['artist_name'];
            header("Location:../artist/artistprofile.php");
            exit();
        } else {
            $_SESSION['login_error'] = '*Incorrect password.';
            header("Location:../login signup/artistlogin.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = '*No account found with this email.';
        header("Location:../login signup/artistlogin.php");
        exit();
    }
    $stmt->close();
} else {
    $_SESSION['login_error'] = '*Access denied.';
    header('Location:../login signup/artistlogin.php');
    exit();
}

?>