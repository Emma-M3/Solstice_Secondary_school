<?php
session_start();
require_once 'config.php';
require_once 'grading_system.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'teacher') { 
    header("Location: index.php"); 
    exit(); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_marks'])) {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $marks = intval($_POST['marks']);
    
    // Get student's class level for grading
    $class_query = $conn->query("
        SELECT c.class_level 
        FROM student_classes sc 
        JOIN classes c ON sc.class_id = c.id 
        WHERE sc.student_id = $student_id
    ");
    
    if ($class_query && $class_query->num_rows > 0) {
        $class_level = $class_query->fetch_assoc()['class_level'];
        $grade = calculateGrade($marks, $class_level);
        
        $stmt = $conn->prepare("INSERT INTO marks (student_id, subject_id, marks, grade) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $student_id, $subject_id, $marks, $grade);
        
        if ($stmt->execute()) {
            $grade_desc = getGradeDescription($grade, $class_level);
            $msg = "<p style='color:green; font-weight:bold;'>✓ Result uploaded! Grade: $grade ($grade_desc)</p>";
        } else {
            $msg = "<p style='color:red;'>Error uploading result.</p>";
        }
    } else {
        $msg = "<p style='color:red;'>Error: Student not assigned to any class!</p>";
    }
}

$students = $conn->query("
    SELECT u.id, u.name, c.class_name 
    FROM users u 
    LEFT JOIN student_classes sc ON u.id = sc.student_id 
    LEFT JOIN classes c ON sc.class_id = c.id 
    WHERE u.role = 'student' 
    ORDER BY u.name
");
$subjects = $conn->query("SELECT id, subject_name FROM subjects ORDER BY subject_name");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Results</title>
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
                <li><a href="teacher_track_attendance.php">Track Attendance</a></li>
                <li><a href="logout.php" style="color: #ff6b6b;">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Upload Exam Results</h1>
            
            <div class="card" style="background: #e8f4f8; border-left: 4px solid #0A2342; margin-bottom: 20px;">
                <h4 style="margin-top: 0;"> Grading System:</h4>
                <p><strong>Junior Secondary (Forms 1-2):</strong> Division 1 (75+), 2 (60+), 3 (50+), 4 (40+), F (below 40)</p>
                <p><strong>Senior Secondary (Forms 3-4):</strong> Grade 1 (80+), 2 (70+), 3 (65+), 4 (60+), 5 (55+), 6 (50+), 7 (45+), 8 (40+), 9 (35+), F (below 35)</p>
            </div>
            
            <div class="card">
                <h3>Enter Student Marks</h3>
                <?php if(isset($msg)) echo $msg; ?>
                <form method="POST">
                    <select name="student_id" required>
                        <option value="">Select Student</option>
                        <?php while($s = $students->fetch_assoc()): ?>
                            <option value="<?php echo $s['id']; ?>">
                                <?php echo htmlspecialchars($s['name']); ?> 
                                <?php if($s['class_name']): ?>
                                    (<?php echo htmlspecialchars($s['class_name']); ?>)
                                <?php endif; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    
                    <select name="subject_id" required>
                        <option value="">Select Subject</option>
                        <?php while($sub = $subjects->fetch_assoc()): ?>
                            <option value="<?php echo $sub['id']; ?>">
                                <?php echo htmlspecialchars($sub['subject_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    
                    <input type="number" name="marks" placeholder="Enter Marks (0-100)" min="0" max="100" required>
                    
                    <button type="submit" name="upload_marks" class="btn">Upload Result (Auto-Grade)</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>