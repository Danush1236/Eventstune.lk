<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'dbcon.php';

$response = ["success" => false, "data" => null, "error" => null];

if (!isset($_SESSION['artist_id'])) {
    $response["error"] = "Not logged in.";
    if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
    return $response;
}

$artist_id = $_SESSION['artist_id'];
$stmt = $link->prepare("SELECT artist_id, artist_category, artist_name, artist_email, artist_phone, perform_count, offstage_count, artist_language, artist_photo, artist_description FROM artist WHERE artist_id = ?");
$stmt->bind_param("i", $artist_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $response["success"] = true;
    $response["data"] = $row;
} else {
    $response["error"] = "Artist not found.";
}
$stmt->close();

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

return $response; 