<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'dbcon.php';

if (!isset($_SESSION['artist_id'])) {
    header("Location: ../artist/artistprofile.php?imgerr=Not+logged+in");
    exit;
}

if (isset($_POST['upload_image']) && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profile_image']['tmp_name'];
    $fileType = mime_content_type($fileTmpPath);

    // Only allow JPEG and PNG
    if ($fileType !== 'image/jpeg' && $fileType !== 'image/png') {
        header("Location: ../artist/artistprofile.php?imgerr=Only+JPEG+and+PNG+images+are+allowed");
        exit;
    }

    $imgData = file_get_contents($fileTmpPath);

    $stmt = $link->prepare("UPDATE artist SET artist_photo = ? WHERE artist_id = ?");
    $stmt->bind_param("si", $imgData, $_SESSION['artist_id']);
    if ($stmt->execute()) {
        header("Location: ../artist/artistprofile.php?imgmsg=Profile+image+updated+successfully");
        exit;
    } else {
        header("Location: ../artist/artistprofile.php?imgerr=Failed+to+update+image");
        exit;
    }
} else {
    header("Location: ../artist/artistprofile.php?imgerr=No+file+uploaded");
    exit;
} 