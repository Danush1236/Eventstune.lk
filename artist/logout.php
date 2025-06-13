<?php
include_once '../include/session.php';

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: ../login signup/artistlogin.php');
exit();
?> 