<?php
header('Content-Type: application/json');

// Secure session start (same as in send_message.php)
if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params([/* same as above */]);
    session_start();
}

include 'db_connect.php';

// Authentication check
if (!isset($_SESSION['login_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = (int)$_SESSION['login_id'];
$other_user_id = isset($_GET['other_user_id']) ? (int)$_GET['other_user_id'] : 0;
$last_message_id = isset($_GET['last_message_id']) ? (int)$_GET['last_message_id'] : 0;

if ($other_user_id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
    exit;
}

// Get messages with user details
$query = "SELECT 
            m.id, 
            m.sender_id, 
            m.receiver_id, 
            m.message, 
            m.timestamp, 
            m.is_read,
            u.username as sender_name,
            u2.username as receiver_name
          FROM messages m
          JOIN users u ON m.sender_id = u.id
          JOIN users u2 ON m.receiver_id = u2.id
          WHERE ((m.sender_id = ? AND m.receiver_id = ?) 
                 OR (m.sender_id = ? AND m.receiver_id = ?))
          AND m.id > ?
          ORDER BY m.timestamp ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("iiiii", $user_id, $other_user_id, $other_user_id, $user_id, $last_message_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'id' => $row['id'],
        'sender_id' => $row['sender_id'],
        'sender_name' => $row['sender_name'],
        'receiver_id' => $row['receiver_id'],
        'message' => htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8'),
        'timestamp' => $row['timestamp'],
        'is_read' => (bool)$row['is_read'],
        'is_me' => ($row['sender_id'] == $user_id)
    ];
}

// Mark messages as read
if (!empty($messages)) {
    $new_messages_from_other = array_filter($messages, function($msg) use ($user_id) {
        return !$msg['is_me'] && !$msg['is_read'];
    });
    
    if (!empty($new_messages_from_other)) {
        $ids_to_mark = implode(',', array_column($new_messages_from_other, 'id'));
        $conn->query("UPDATE messages SET is_read = TRUE WHERE id IN ($ids_to_mark)");
    }
}

echo json_encode([
    'status' => 'success',
    'messages' => $messages,
    'count' => count($messages)
]);

$stmt->close();
$conn->close();
?>