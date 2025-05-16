<?php
include('db_connect.php');
session_start();

// Optional: Check if user is logged in
if (!isset($_SESSION['login_id'])) {
    exit('User not logged in');
}

// Fetch messages ordered by timestamp
$sql = "SELECT * FROM messages ORDER BY created_at ASC"; // Change this to your actual column name if it's not timestamp
$result = $conn->query($sql);

// Check for errors
if (!$result) {
    die("Query Error: " . $conn->error);
}

// Display messages
while ($row = $result->fetch_assoc()) {
    echo "<div><strong>" . htmlspecialchars($row['sender']) . ":</strong> " . htmlspecialchars($row['message']) . "</div>";
}
?>
Write to Rogelyn Ambrocio
