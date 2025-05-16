<?php
include('db_connect.php');
session_start();

$sender = $_SESSION['login_name'] ?? '';
$recipient = $_POST['receiver_id'] ?? '';
$message = trim($_POST['message'] ?? '');

if (empty($sender) || empty($recipient) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill all fields.']);
    exit;
}

$sql = "INSERT INTO messages (sender, recipient, message, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $sender, $recipient, $message);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Message sent.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error sending message: ' . $conn->error]);
}
?>
