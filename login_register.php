<?php
session_start();
require_once 'config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    // ================= REGISTER =================
    if ($action == 'register') {
        $name = $conn->real_escape_string(trim($_POST['name']));
        $email = $conn->real_escape_string(trim(strtolower($_POST['email'])));
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $gender = $conn->real_escape_string($_POST['gender']);
        $district = $conn->real_escape_string($_POST['district']);

        // AUTO-DETECT ROLE FROM EMAIL
        if (strpos($email, 'student') === 0 && str_ends_with($email, '@solstice.com')) {
            $role = 'student';
        } elseif (strpos($email, 'teacher') === 0 && str_ends_with($email, '@solstice.com')) {
            $role = 'teacher';
        } elseif ($email === 'admin@solstice.com') {
            $role = 'admin';
        } else {
            $_SESSION['error'] = "Invalid email format! Use: studentXXX@solstice.com or teacherXXX@solstice.com";
            header("Location: index.php"); exit();
        }

        // Prevent public admin registration
        if ($role === 'admin') {
            $_SESSION['error'] = "Security Alert: Admin registration is disabled!";
            header("Location: index.php"); exit();
        }

        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, gender, district) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $password, $role, $gender, $district);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! You can now login as a " . ucfirst($role) . ".";
        } else {
            $_SESSION['error'] = "Registration Failed: That email already exists!";
        }
        header("Location: index.php");
        exit();
    }

    // ================= LOGIN =================
    if ($action == 'login') {
        $email = $conn->real_escape_string(trim(strtolower($_POST['email'])));
        $password = $_POST['password']; 

        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['role'] = $row['role'];

                if ($row['role'] == 'student') { header("Location: student_dashboard.php"); } 
                elseif ($row['role'] == 'teacher') { header("Location: teacher_dashboard.php"); } 
                elseif ($row['role'] == 'admin') { header("Location: admin_dashboard.php"); }
                exit();
            } else {
                $_SESSION['error'] = "Login Failed: Incorrect Password.";
                header("Location: index.php"); exit();
            }
        } else {
            $_SESSION['error'] = "Login Failed: Email not found.";
            header("Location: index.php"); exit();
        }
    }
}
?>