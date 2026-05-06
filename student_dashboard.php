<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') { 
    header("Location: index.php"); 
    exit(); 
}
require_once 'config.php';

$student_id = $_SESSION['id'];

// Get student's class
$class_query = $conn->query("
    SELECT c.class_name 
    FROM student_classes sc 
    JOIN classes c ON sc.class_id = c.id 
    WHERE sc.student_id = $student_id
");
$student_class = $class_query && $class_query->num_rows > 0 ? $class_query->fetch_assoc()['class_name'] : 'Not Assigned';

// Get pending assignments
$pending = $conn->query("
    SELECT COUNT(*) as count 
    FROM assignments a 
    JOIN student_classes sc ON a.class_id = sc.class_id 
    WHERE sc.student_id = $student_id AND a.due_date >= CURDATE()
")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="sec.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Student Panel</h2>
            <ul>
                <li><a href="student_dashboard.php"> Dashboard</a></li>
                <li><a href="student_profile.php"> My Profile</a></li>
                <li><a href="student_assignments.php">Assignments</a></li>
                <li><a href="student_results.php">Exam Results</a></li>
                <li><a href="logout.php" style="color: #ff6b6b;">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
            
            <div class="card">
                <h3> Your Class</h3>
                <p style="font-size: 24px; font-weight: bold; color: #0A2342;">
                    <?php echo htmlspecialchars($student_class); ?>
                </p>
            </div>

            <div class="card">
                <h3> Quick Overview</h3>
                <p><strong>Pending Assignments:</strong> <?php echo $pending; ?></p>
                <p><strong>Status:</strong> <span style="color: green;">Active</span></p>
            </div>

            <div class="card">
                <h3> Announcements</h3>
                <p>Welcome to Solstice Secondary School! Check your assignments regularly and stay updated.</p>
            </div>
        </div>
    </div>
</body>
</html>