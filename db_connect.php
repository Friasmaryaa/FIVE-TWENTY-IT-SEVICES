<?php
// filepath: c:\xampp\htdocs\520\db_connect.php
$conn = new mysqli('localhost', 'root', '', '520_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>