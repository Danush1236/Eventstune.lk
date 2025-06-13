<?php
include 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$artist_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_SESSION['artist_id']) ? intval($_SESSION['artist_id']) : 0);

if ($artist_id) {
    $stmt = $link->prepare("SELECT artist_photo FROM artist WHERE artist_id = ?");
    $stmt->bind_param("i", $artist_id);
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
readfile("../artist/IMG_3493.JPG");
exit; 