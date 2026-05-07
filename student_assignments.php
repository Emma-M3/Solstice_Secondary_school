<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') { 
    header("Location: index.php"); 
    exit(); 
}

$student_id = $_SESSION['id'];

// Get assignments for student's class
$query = "SELECT a.title, a.description, a.due_date, u.name as teacher_name, c.class_name
          FROM assignments a 
          LEFT JOIN users u ON a.teacher_id = u.id 
          LEFT JOIN classes c ON a.class_id = c.id
          LEFT JOIN student_classes sc ON c.id = sc.class_id
          WHERE sc.student_id = ?
          ORDER BY a.due_date ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$assignments = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Assignments</title>
    <link rel="stylesheet" href="sec.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Student Panel</h2>
            <ul>
                <li><a href="student_dashboard.php">Dashboard</a></li>
                <li><a href="student_profile.php"> My Profile</a></li>
                <li><a href="student_assignments.php"> Assignments</a></li>
                <li><a href="student_results.php">Exam Results</a></li>
                <li><a href="logout.php" style="color: #ff6b6b;"> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>My Assignments</h1>
            
            <div class="card">
                <h3>Pending Tasks</h3>
                <?php if ($assignments && $assignments->num_rows > 0): ?>
                    <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
                        <tr style="background-color: #0A2342; color: white;">
                            <th>Assignment Title</th>
                            <th>Description</th>
                            <th>Teacher</th>
                            <th>Class</th>
                            <th>Due Date</th>
                        </tr>
                        <?php while($row = $assignments->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                            <td style="color: red; font-weight: bold;"><?php echo date('d M Y', strtotime($row['due_date'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>✓ No pending assignments at the moment. Great job staying on top of your work!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>