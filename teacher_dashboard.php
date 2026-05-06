<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'teacher') { 
    header("Location: portal.php"); 
    exit(); 
}
require_once 'config.php';

$teacher_id = $_SESSION['id'];

// Get quick stats
$total_students = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='student'")->fetch_assoc()['count'];
$pending_assignments = $conn->query("SELECT COUNT(*) as count FROM assignments WHERE teacher_id=$teacher_id AND due_date >= CURDATE()")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="sec.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Teacher Panel</h2>
            <ul>
                <li><a href="teacher_dashboard.php"> Dashboard Home</a></li>
                <li><a href="teacher_manage_students.php"> View Students</a></li>
                <li><a href="teacher_upload_results.php"> Upload Results</a></li>
                <li><a href="teacher_upload_assignments.php"> Create Assignment</a></li>
                <li><a href="teacher_track_attendance.php"> Track Attendance</a></li>
                <li><a href="logout.php" style="color: #ff6b6b;"> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Welcome, Teacher <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
            
            <div class="card">
                <h3>Quick Statistics</h3>
                <p><strong>Total Students in School:</strong> <?php echo $total_students; ?></p>
                <p><strong>Your Active Assignments:</strong> <?php echo $pending_assignments; ?></p>
            </div>

            <div class="card">
                <h3>⚡ Quick Actions</h3>
                <a href="teacher_upload_results.php"><button class="btn" style="width: auto; margin-right: 10px;">Upload Results</button></a>
                <a href="teacher_track_attendance.php"><button class="btn" style="width: auto;">Take Attendance</button></a>
            </div>
        </div>
    </div>
</body>
</html>