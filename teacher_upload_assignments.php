<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'teacher') { 
    header("Location: index.php"); 
    exit(); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_assignment'])) {
    $class_id = $_POST['class_id'];
    $title = $conn->real_escape_string(trim($_POST['title']));
    $desc = $conn->real_escape_string(trim($_POST['description']));
    $due = $_POST['due_date'];
    $tid = $_SESSION['id'];

    $stmt = $conn->prepare("INSERT INTO assignments (teacher_id, class_id, title, description, due_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $tid, $class_id, $title, $desc, $due);
    
    if ($stmt->execute()) { 
        $msg = "<p style='color:green; font-weight:bold;'>✓ Assignment posted successfully!</p>"; 
    }
}

$classes = $conn->query("SELECT id, class_name FROM classes ORDER BY id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Assignment</title>
    <link rel="stylesheet" href="sec.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Teacher Panel</h2>
            <ul>
                <li><a href="teacher_dashboard.php">Dashboard Home</a></li>
                <li><a href="teacher_manage_students.php"> View Students</a></li>
                <li><a href="teacher_upload_results.php">Upload Results</a></li>
                <li><a href="teacher_upload_assignments.php"> Create Assignment</a></li>
                <li><a href="teacher_track_attendance.php">Track Attendance</a></li>
                <li><a href="logout.php" style="color: #ff6b6b;"> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Create New Assignment</h1>
            
            <div class="card">
                <?php if(isset($msg)) echo $msg; ?>
                <form method="POST">
                    <select name="class_id" required>
                        <option value="">Select Target Class</option>
                        <?php while($c = $classes->fetch_assoc()): ?>
                            <option value="<?php echo $c['id']; ?>">
                                <?php echo htmlspecialchars($c['class_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    
                    <input type="text" name="title" placeholder="Assignment Title" required>
                    
                    <textarea name="description" placeholder="Assignment Details and Instructions..." 
                              style="width:100%; padding:10px; margin:8px 0; border:1px solid #ddd; border-radius:6px; height:120px;" 
                              required></textarea>
                    
                    <input type="date" name="due_date" required min="<?php echo date('Y-m-d'); ?>">
                    
                    <button type="submit" name="upload_assignment" class="btn">Post Assignment</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>