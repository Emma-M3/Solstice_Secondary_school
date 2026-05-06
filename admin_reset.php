<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
require_once 'config.php';

echo "<div style='font-family: Arial, sans-serif; text-align: center; margin-top: 50px;'>";

// Delete ALL existing admins
$conn->query("DELETE FROM users WHERE role = 'admin'");

// Set admin credentials
$admin_name = "System Administrator";
$admin_email = "admin@solstice.com";
$admin_password = "admin123";

// Hash password
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
$role = "admin";

// Insert fresh admin
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $admin_name, $admin_email, $hashed_password, $role);

if ($stmt->execute()) {
    echo "<h1 style='color: green;'> SUCCESS! Admin Account Reset.</h1>";
    echo "<div style='background: #f4f4f4; padding: 20px; display: inline-block; border-radius: 8px; border: 2px solid #0A2342; font-size: 18px; margin: 20px;'>";
    echo "<strong>Email:</strong> " . $admin_email . "<br><br>";
    echo "<strong>Password:</strong> " . $admin_password . "<br>";
    echo "</div><br><br>";
    echo "<a href='portal.php' style='padding: 15px 30px; background: #0A2342; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;'>Go to Login Page</a>";
} else {
    echo "<h1 style='color: red;'>Database Error</h1>";
    echo "<p>Error: " . $conn->error . "</p>";
}

echo "</div>";
?>