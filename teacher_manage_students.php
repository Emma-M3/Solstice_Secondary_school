<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'teacher') { 
    header("Location: index.php"); 
    exit(); 
}

// Get students with their classes
$students = $conn->query("
    SELECT u.id, u.name, u.email, u.gender, u.district, c.class_name, c.class_level
    FROM users u
    LEFT JOIN student_classes sc ON u.id = sc.student_id
    LEFT JOIN classes c ON sc.class_id = c.id
    WHERE u.role = 'student'
    ORDER BY c.id, u.name ASC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
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
            <h1>Student Directory</h1>
            
            <div class="card">
                <h3>All Students</h3>
                <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #0A2342; color: white;">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>District</th>
                        <th>Class</th>
                        <th>Level</th>
                    </tr>
                    <?php if ($students && $students->num_rows > 0): ?>
                        <?php while($s = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $s['id']; ?></td>
                            <td><?php echo htmlspecialchars($s['name']); ?></td>
                            <td><?php echo htmlspecialchars($s['email']); ?></td>
                            <td><?php echo htmlspecialchars($s['gender'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($s['district'] ?? 'N/A'); ?></td>
                            <td>
                                <strong style="color: #0A2342;">
                                    <?php echo $s['class_name'] ? htmlspecialchars($s['class_name']) : '<span style="color:red;">Not Assigned</span>'; ?>
                                </strong>
                            </td>
                            <td><?php echo $s['class_level'] ? ucfirst($s['class_level']) : '-'; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align:center;">No students found.</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>