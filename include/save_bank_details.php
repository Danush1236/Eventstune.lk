<?php
include_once 'session.php';
include_once 'dbcon.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ['success' => false, 'message' => '', 'debug' => []];

// Check if user is logged in
if (!isset($_SESSION['artist_id'])) {
    $response['message'] = 'Not logged in';
    $response['debug'][] = 'No artist_id in session';
    echo json_encode($response);
    exit;
}

// Log POST data
$response['debug']['post_data'] = $_POST;

// Validate required fields
$required_fields = ['bankName', 'branchName', 'accountNumber', 'accountHolder'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        $response['message'] = 'All fields are required';
        $response['debug'][] = "Missing field: {$field}";
        echo json_encode($response);
        exit;
    }
}

// Get form data
$artist_id = $_SESSION['artist_id'];
$bank_name = mysqli_real_escape_string($link, $_POST['bankName']);
$branch_name = mysqli_real_escape_string($link, $_POST['branchName']);
$account_number = mysqli_real_escape_string($link, $_POST['accountNumber']);
$holder_name = mysqli_real_escape_string($link, $_POST['accountHolder']);
$bank_id = isset($_POST['bankId']) && !empty($_POST['bankId']) ? (int)$_POST['bankId'] : null;

try {
    // Check if artist already has bank details
    $check_query = "SELECT bank_id FROM `artist bank` WHERE artist_id = ?";
    $check_stmt = $link->prepare($check_query);
    $check_stmt->bind_param("i", $artist_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $existing_bank = $result->fetch_assoc();
    $check_stmt->close();

    if ($existing_bank && !$bank_id) {
        // Update existing record if no bank_id provided
        $bank_id = $existing_bank['bank_id'];
    }

    if ($bank_id) {
        // Update existing bank details
        $query = "UPDATE `artist bank` SET 
                 bank_name = ?, 
                 branch_name = ?, 
                 account_number = ?, 
                 holder_name = ? 
                 WHERE bank_id = ? AND artist_id = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("ssssii", $bank_name, $branch_name, $account_number, $holder_name, $bank_id, $artist_id);
        $response['debug'][] = "Executing update query";
    } else {
        // Insert new bank details
        $query = "INSERT INTO `artist bank` (artist_id, bank_name, branch_name, account_number, holder_name) 
                 VALUES (?, ?, ?, ?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bind_param("issss", $artist_id, $bank_name, $branch_name, $account_number, $holder_name);
        $response['debug'][] = "Executing insert query";
    }

    // Execute the query
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = $bank_id ? 'Bank details updated successfully' : 'Bank details saved successfully';
        $response['debug'][] = "Query executed successfully";
        $response['debug'][] = "Affected rows: " . $stmt->affected_rows;
        
        // Get the bank_id for new insertions
        if (!$bank_id) {
            $bank_id = $stmt->insert_id;
        }
        
        $response['bank_id'] = $bank_id;
    } else {
        $response['message'] = 'Failed to save bank details';
        $response['debug'][] = "Query execution failed";
        $response['debug'][] = "Error: " . $stmt->error;
    }
    
    $stmt->close();
} catch (Exception $e) {
    $response['message'] = 'Error saving bank details';
    $response['debug'][] = "Exception: " . $e->getMessage();
}

// Close database connection
$link->close();

echo json_encode($response);
?> 