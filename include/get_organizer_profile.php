<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'dbcon.php';

$response = ["success" => false, "data" => null, "error" => null];

if (!isset($_SESSION['organizer_id'])) {
    $response["error"] = "Not logged in.";
    if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
    return $response;
}

$organizer_id = $_SESSION['organizer_id'];
$stmt = $link->prepare("SELECT organizer_id, organizer_name, organizer_company, organizer_phone, organizer_email, organizer_photo FROM organizer WHERE organizer_id = ?");
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $response["success"] = true;
    $response["data"] = $row;
} else {
    $response["error"] = "Organizer not found.";
}
$stmt->close();

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

return $response; 