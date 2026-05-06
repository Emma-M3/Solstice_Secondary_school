<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: index.php"); 
    exit(); 
}

// Handle adding new teacher
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_teacher'])) {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim(strtolower($_POST['email'])));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Validate teacher email format
    if (strpos($email, 'teacher') !== 0 || !str_ends_with($email, '@solstice.com')) {
        $msg = "<p style='color:red;'> Teacher email must be in format: teacherXXX@solstice.com</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'teacher')");
        $stmt->bind_param("sss", $name, $email, $password);
        
        if ($stmt->execute()) {
            $msg = "<p style='color:green; font-weight:bold;'>✓ Teacher added successfully!</p>";
        } else {
            $msg = "<p style='color:red;'>Error: Email already exists!</p>";
        }
    }
}

$teachers = $conn->query("SELECT id, name, email, created_at FROM users WHERE role = 'teacher' ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Teachers</title>
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
            <h1>Manage Teachers</h1>
            
            <div class="card" style="margin-bottom: 20px;">
                <h3>Add New Teacher</h3>
                <?php if(isset($msg)) echo $msg; ?>
                <form method="POST">
                    <input type="text" name="name" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email (teacher001@solstice.com)" required>
                    <input type="password" name="password" placeholder="Password" required minlength="6">
                    <button type="submit" name="add_teacher" class="btn">Add Teacher</button>
                </form>
            </div>

            <div class="card">
                <h3>Registered Teachers</h3>
                <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #0A2342; color: white;">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date Registered</th>
                    </tr>
                    <?php if ($teachers && $teachers->num_rows > 0): ?>
                        <?php while($row = $teachers->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center;">No teachers found.</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>