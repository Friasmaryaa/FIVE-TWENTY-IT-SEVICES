<?php
// Start session if not already started
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include 'db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['login_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Get user ID to check status
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

if($user_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
    exit;
}

// Query to get user information
$query = "SELECT id, CONCAT(firstname, ' ', lastname) as name, last_login 
          FROM users WHERE id = $user_id";
$result = $conn->query($query);

if($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Check if user was active within the last 5 minutes (300 seconds)
    $last_login = strtotime($user['last_login']);
    $current_time = time();
    $is_online = ($current_time - $last_login) <= 300;
    
    echo json_encode([
        'status' => 'success',
        'id' => $user['id'],
        'name' => $user['name'],
        'online' => $is_online,
        'last_active' => date('Y-m-d H:i:s', $last_login)
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
}
?>