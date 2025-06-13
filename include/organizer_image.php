<?php
include 'dbcon.php';
session_start();

$organizer_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_SESSION['organizer_id']) ? intval($_SESSION['organizer_id']) : 0);

if ($organizer_id) {
    $stmt = $link->prepare("SELECT organizer_photo FROM organizer WHERE organizer_id = ?");
    $stmt->bind_param("i", $organizer_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($photo);
        $stmt->fetch();
        if ($photo) {
            header("Content-Type: image/jpeg"); // Change to image/png if needed
            echo $photo;
            exit;
        }
    }
}
// fallback image
header("Content-Type: image/jpeg");
readfile("../profile/IMG_3493.JPG");
exit; 