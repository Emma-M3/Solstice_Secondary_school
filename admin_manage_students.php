<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: index.php"); 
    exit(); 
}

// Handle moving student to class
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['move_student'])) {
    $student_id = $_POST['student_id'];
    $class_id = $_POST['class_id'];
    
    // Delete existing class assignment
    $conn->query("DELETE FROM student_classes WHERE student_id = $student_id");
    
    // Assign new class
    $stmt = $conn->prepare("INSERT INTO student_classes (student_id, class_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $student_id, $class_id);
    
    if ($stmt->execute()) {
        $msg = "<p style='color:green; font-weight:bold;'>✓ Student moved successfully!</p>";
    } else {
        $msg = "<p style='color:red;'>Error moving student.</p>";
    }
}

// Get all students with their current class
$students = $conn->query("
    SELECT u.id, u.name, u.email, u.gender, u.district, c.class_name, c.id as class_id
    FROM users u
    LEFT JOIN student_classes sc ON u.id = sc.student_id
    LEFT JOIN classes c ON sc.class_id = c.id
    WHERE u.role = 'student'
    ORDER BY u.name ASC
");

// Get all classes for dropdown
$classes = $conn->query("SELECT id, class_name FROM classes ORDER BY id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <link rel="stylesheet" href="sec.css">
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
            <h1>Manage Students</h1>
            
            <?php if(isset($msg)) echo $msg; ?>
            
            <div class="card">
                <h3>All Registered Students</h3>
                <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #0A2342; color: white;">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>District</th>
                        <th>Current Class</th>
                        <th>Action</th>
                    </tr>
                    <?php if ($students && $students->num_rows > 0): ?>
                        <?php while($row = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['district'] ?? 'N/A'); ?></td>
                            <td>
                                <strong style="color: #0A2342;">
                                    <?php echo $row['class_name'] ? htmlspecialchars($row['class_name']) : '<span style="color:red;">Not Assigned</span>'; ?>
                                </strong>
                            </td>
                            <td>
                                <form method="POST" style="display: flex; gap: 5px; align-items: center;">
                                    <input type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                                    <select name="class_id" required style="padding: 5px;">
                                        <option value="">Select Class</option>
                                        <?php 
                                        $classes->data_seek(0); // Reset pointer
                                        while($c = $classes->fetch_assoc()): 
                                        ?>
                                            <option value="<?php echo $c['id']; ?>" 
                                                <?php echo ($c['id'] == $row['class_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($c['class_name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <button type="submit" name="move_student" class="btn" style="padding: 5px 10px; margin: 0;">Move</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align:center;">No students registered yet.</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>