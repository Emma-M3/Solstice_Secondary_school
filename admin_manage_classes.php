<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') { header("Location: index.php"); exit(); }

// Handle adding a new class
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_class'])) {
    $class_name = $conn->real_escape_string($_POST['class_name']);
    $stmt = $conn->prepare("INSERT INTO classes (class_name) VALUES (?)");
    $stmt->bind_param("s", $class_name);
    if ($stmt->execute()) {
        $msg = "<p style='color:green; font-weight:bold;'>Class added successfully!</p>";
    } else {
        $msg = "<p style='color:red;'>Error adding class.</p>";
    }
}

$classes = $conn->query("SELECT * FROM classes ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Classes</title><link rel="stylesheet" href="sec.css"></head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard Home</a></li>
                <li><a href="admin_manage_students.php">Manage Students</a></li>
                <li><a href="admin_manage_teachers.php">Manage Teachers</a></li>
                <li><a href="admin_manage_classes.php">Manage Classes (Form 1-4)</a></li>
                <li><a href="admin_manage_subjects.php">Manage Subjects</a></li>
                <li><a href="admin_system_reports.php">System Reports</a></li>
                <li><a href="logout.php" style="color: #ff6b6b;">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Manage Classes</h1>
            
            <div class="card" style="margin-bottom: 20px;">
                <h3>Add New Class (e.g., Form 1)</h3>
                <?php if(isset($msg)) echo $msg; ?>
                <form method="POST">
                    <input type="text" name="class_name" placeholder="Enter Class Name" required>
                    <button type="submit" name="add_class" class="btn">Add Class</button>
                </form>
            </div>

            <div class="card">
                <h3>Current Classes</h3>
                <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #0A2342; color: white;">
                        <th>Class ID</th>
                        <th>Class Name</th>
                    </tr>
                    <?php if ($classes && $classes->num_rows > 0): while($row = $classes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="2" style="text-align:center;">No classes created yet.</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>