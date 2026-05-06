<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'teacher') { 
    header("Location: index.php"); 
    exit(); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_attendance'])) {
    $student_id = $_POST['student_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $student_id, $date, $status);
    
    if ($stmt->execute()) { 
        $msg = "<p style='color:green; font-weight:bold;'>✓ Attendance recorded!</p>"; 
    }
}

$students = $conn->query("
    SELECT u.id, u.name, c.class_name 
    FROM users u 
    LEFT JOIN student_classes sc ON u.id = sc.student_id 
    LEFT JOIN classes c ON sc.class_id = c.id 
    WHERE u.role = 'student' 
    ORDER BY c.id, u.name
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Track Attendance</title>
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
            <h1>Track Student Attendance</h1>
            
            <div class="card">
                <?php if(isset($msg)) echo $msg; ?>
                <form method="POST">
                    <select name="student_id" required>
                        <option value="">Select Student</option>
                        <?php while($s = $students->fetch_assoc()): ?>
                            <option value="<?php echo $s['id']; ?>">
                                <?php echo htmlspecialchars($s['name']); ?>
                                <?php if($s['class_name']): ?>
                                    - <?php echo htmlspecialchars($s['class_name']); ?>
                                <?php endif; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    
                    <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                    
                    <select name="status" required>
                        <option value="present"> Present</option>
                        <option value="absent"> Absent</option>
                        <option value="late"> Late</option>
                    </select>
                    
                    <button type="submit" name="mark_attendance" class="btn">Record Attendance</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>