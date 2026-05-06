<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: index.php"); 
    exit(); 
}

// Handle adding subject
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subject'])) {
    $subject_name = $conn->real_escape_string(trim($_POST['subject_name']));
    
    $stmt = $conn->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
    $stmt->bind_param("s", $subject_name);
    
    if ($stmt->execute()) {
        $msg = "<p style='color:green; font-weight:bold;'>✓ Subject added successfully!</p>";
    } else {
        $msg = "<p style='color:red;'>Error adding subject.</p>";
    }
}

$subjects = $conn->query("SELECT * FROM subjects ORDER BY subject_name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Subjects</title>
    <link rel="stylesheet" href="sec.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard Home</a></li>
                <li><a href="admin_manage_students.php">Manage Students</a></li>
                <li><a href="admin_manage_teachers.php"> Manage Teachers</a></li>
                <li><a href="admin_manage_subjects.php"> Manage Subjects</a></li>
                <li><a href="admin_system_reports.php"> System Reports</a></li>
                <li><a href="logout.php" style="color: #ff6b6b;"> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Manage Subjects</h1>
            
            <div class="card" style="margin-bottom: 20px;">
                <h3>Add New Subject</h3>
                <?php if(isset($msg)) echo $msg; ?>
                <form method="POST">
                    <input type="text" name="subject_name" placeholder="Subject Name (e.g., Mathematics)" required>
                    <button type="submit" name="add_subject" class="btn">Add Subject</button>
                </form>
            </div>

            <div class="card">
                <h3>All Subjects</h3>
                <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #0A2342; color: white;">
                        <th>Subject ID</th>
                        <th>Subject Name</th>
                    </tr>
                    <?php if ($subjects && $subjects->num_rows > 0): ?>
                        <?php while($row = $subjects->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="2" style="text-align:center;">No subjects created yet.</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>