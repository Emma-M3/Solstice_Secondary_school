<?php
$host = 'localhost';
$db_user = 'root';
$db_pass = ''; // Add your MySQL password if you have one
$db_name = 'school_system';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");
?>