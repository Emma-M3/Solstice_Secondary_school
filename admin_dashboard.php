<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: portal.php"); 
    exit(); 
}
require_once 'config.php';

// Get statistics
$total_students = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role='student'")->fetch_assoc()['count'];
$total_teachers = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role='teacher'")->fetch_assoc()['count'];
$total_subjects = $conn->query("SELECT COUNT(*) AS count FROM subjects")->fetch_assoc()['count'];

// Get class enrollment
$class_stats = $conn->query("
    SELECT c.class_name, COUNT(sc.student_id) as enrolled
    FROM classes c
    LEFT JOIN student_classes sc ON c.id = sc.class_id
    GROUP BY c.id
    ORDER BY c.id
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="sec.css">
    <style>
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 10px 0;
            text-align: center;
        }
        .stat-card h2 { margin: 0; font-size: 48px; }
        .stat-card p { margin: 5px 0 0 0; font-size: 16px; opacity: 0.9; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php"> Dashboard Home</a></li>
                <li><a href="admin_manage_students.php"> Manage Students</a></li>
                <li><a href="admin_manage_teachers.php"> Manage Teachers</a></li>
                <li><a href="admin_manage_subjects.php"> Manage Subjects</a></li>
                <li><a href="admin_system_reports.php">System Reports</a></li>
                <li><a href="logout.php" style="color: #ff6b6b;"> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin: 20px 0;">
                <div class="stat-card">
                    <h2><?php echo $total_students; ?></h2>
                    <p>Total Students</p>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <h2><?php echo $total_teachers; ?></h2>
                    <p>Total Teachers</p>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h2><?php echo $total_subjects; ?></h2>
                    <p>Total Subjects</p>
                </div>
            </div>

            <div class="card">
                <h3>Class Enrollment Overview</h3>
                <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #0A2342; color: white;">
                        <th>Class</th>
                        <th>Students Enrolled</th>
                        <th>Status</th>
                    </tr>
                    <?php while($row = $class_stats->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['class_name']); ?></strong></td>
                        <td><?php echo $row['enrolled']; ?></td>
                        <td>
                            <?php if($row['enrolled'] < 20): ?>
                                <span style="color: red;"> Need <?php echo 20 - $row['enrolled']; ?> more</span>
                            <?php else: ?>
                                <span style="color: green;"> Requirement Met</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>