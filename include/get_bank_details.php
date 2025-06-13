<?php
include_once 'session.php';
include_once 'dbcon.php';

header('Content-Type: application/json');

$response = ['success' => false, 'data' => null, 'message' => ''];

if (!isset($_SESSION['artist_id'])) {
    $response['message'] = 'Not logged in';
    echo json_encode($response);
    exit;
}

$artist_id = $_SESSION['artist_id'];

try {
    $stmt = $link->prepare("SELECT * FROM `artist bank` WHERE artist_id = ?");
    $stmt->bind_param("i", $artist_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['success'] = true;
        $response['data'] = $result->fetch_assoc();
    } else {
        $response['message'] = 'No bank details found';
    }
} catch (Exception $e) {
    $response['message'] = 'Error fetching bank details';
}

echo json_encode($response);
?> 