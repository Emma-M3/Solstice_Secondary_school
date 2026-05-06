<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: index.php"); 
    exit(); 
}

$total_students = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role='student'")->fetch_assoc()['count'];
$total_teachers = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role='teacher'")->fetch_assoc()['count'];
$total_subjects = $conn->query("SELECT COUNT(*) AS count FROM subjects")->fetch_assoc()['count'];

$class_breakdown = $conn->query("
    SELECT c.class_name, c.class_level, COUNT(sc.student_id) as enrolled
    FROM classes c
    LEFT JOIN student_classes sc ON c.id = sc.class_id
    GROUP BY c.id
    ORDER BY c.id
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>System Reports</title>
    <link rel="stylesheet" href="sec.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard Home</a></li>
                <li><a href="admin_manage_students.php"> Manage Students</a></li>
                <li><a href="admin_manage_teachers.php"> Manage Teachers</a></li>
                <li><a href="admin_manage_subjects.php"> Manage Subjects</a></li>
                <li><a href="admin_system_reports.php"> System Reports</a></li>
                <li><a href="logout.php" style="color: #ff6b6b;"> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>System Reports & Analytics</h1>
            
            <div class="card">
                <h3>Database Overview</h3>
                <ul style="list-style-type: none; padding-left: 0; font-size: 18px; line-height: 2;">
                    <li><strong>Total Students Enrolled:</strong> <?php echo $total_students; ?></li>
                    <li><strong>Total Teachers Registered:</strong> <?php echo $total_teachers; ?></li>
                    <li><strong>Total Subjects Offered:</strong> <?php echo $total_subjects; ?></li>
                    <li><strong>Total Classes:</strong> 4 (Forms 1-4)</li>
                </ul>
            </div>

            <div class="card">
                <h3>Class Enrollment Breakdown</h3>
                <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #0A2342; color: white;">
                        <th>Class</th>
                        <th>Level</th>
                        <th>Students Enrolled</th>
                        <th>Status</th>
                    </tr>
                    <?php while($row = $class_breakdown->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['class_name']); ?></strong></td>
                        <td><?php echo ucfirst($row['class_level']); ?> Secondary</td>
                        <td><?php echo $row['enrolled']; ?></td>
                        <td>
                            <?php if($row['enrolled'] >= 20): ?>
                                <span style="color: green; font-weight: bold;">✓ Active</span>
                            <?php else: ?>
                                <span style="color: orange; font-weight: bold;"> Below Capacity</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
                <br>
                <button onclick="window.print()" class="btn" style="width: auto;"> Print Report</button>
            </div>
        </div>
    </div>
</body>
</html>