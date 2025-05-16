<?php
// filepath: c:\xampp\htdocs\520\load_message.php
<?php
include('db_connect.php');
session_start();

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$current_user_id = $_SESSION['login_id'] ?? 0;

if ($user_id <= 0 || $current_user_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID.']);
    exit;
}

$sql = "SELECT * FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?) 
           OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $current_user_id, $user_id, $user_id, $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'message' => htmlspecialchars($row['message']),
        'is_sender' => $row['sender_id'] == $current_user_id
    ];
}

echo json_encode(['status' => 'success', 'messages' => $messages]);
?>