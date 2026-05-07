<?php
session_start();
require_once 'config.php';
require_once 'grading_system.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') { 
    header("Location: index.php"); 
    exit(); 
}

$user_id = $_SESSION['id'];

// Get student info
$student_info = $conn->query("
    SELECT u.name, u.email, c.class_name, c.class_level
    FROM users u
    LEFT JOIN student_classes sc ON u.id = sc.student_id
    LEFT JOIN classes c ON sc.class_id = c.id
    WHERE u.id = $user_id
")->fetch_assoc();

// Get results
$query = "SELECT m.marks, m.grade, s.subject_name, m.uploaded_at
          FROM marks m 
          LEFT JOIN subjects s ON m.subject_id = s.id 
          WHERE m.student_id = ?
          ORDER BY s.subject_name ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$results = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Results</title>
    <link rel="stylesheet" href="sec.css">
    <style>
        @media print {
            .sidebar, .btn, h1 { display: none; }
            .card { box-shadow: none; border: 1px solid #000; }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Student Panel</h2>
            <ul>
                <li><a href="student_dashboard.php">Dashboard</a></li>
                <li><a href="student_profile.php"> My Profile</a></li>
                <li><a href="student_assignments.php"> Assignments</a></li>
                <li><a href="student_results.php"> Exam Results</a></li>
                <li><a href="logout.php" style="color: #ff6b6b;"> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>My Exam Results</h1>
            
            <div class="card">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h2 style="margin: 0; color: #0A2342;">SOLSTICE SECONDARY SCHOOL</h2>
                    <h3 style="margin: 5px 0;">Official Report Card</h3>
                </div>
                
                <div style="margin: 20px 0; padding: 15px; background: #f4f4f4; border-radius: 5px;">
                    <p><strong>Student Name:</strong> <?php echo htmlspecialchars($student_info['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($student_info['email']); ?></p>
                    <p><strong>Class:</strong> <?php echo htmlspecialchars($student_info['class_name'] ?? 'Not Assigned'); ?></p>
                    <p><strong>Level:</strong> <?php echo $student_info['class_level'] ? ucfirst($student_info['class_level']) . ' Secondary' : '-'; ?></p>
                    <p><strong>Report Date:</strong> <?php echo date('d M Y'); ?></p>
                </div>

                <?php if ($results && $results->num_rows > 0): ?>
                    <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                        <tr style="background-color: #0A2342; color: white;">
                            <th>Subject</th>
                            <th>Score (%)</th>
                            <th>Grade</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                        <?php 
                        $total_marks = 0;
                        $count = 0;
                        while($row = $results->fetch_assoc()): 
                            $total_marks += $row['marks'];
                            $count++;
                            $grade_desc = getGradeDescription($row['grade'], $student_info['class_level']);
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['subject_name'] ?? 'Unknown'); ?></td>
                            <td style="text-align: center; font-weight: bold;"><?php echo $row['marks']; ?>%</td>
                            <td style="text-align: center; font-size: 18px; font-weight: bold; color: #0A2342;">
                                <?php echo htmlspecialchars($row['grade']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($grade_desc); ?></td>
                            <td><?php echo date('d M Y', strtotime($row['uploaded_at'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <tr style="background: #f4f4f4; font-weight: bold;">
                            <td>AVERAGE</td>
                            <td style="text-align: center;"><?php echo $count > 0 ? round($total_marks / $count, 2) : 0; ?>%</td>
                            <td colspan="3"></td>
                        </tr>
                    </table>
                    
                    <div style="text-align: center; margin-top: 30px;">
                        <button onclick="window.print()" class="btn" style="width: auto; padding: 12px 30px;">
                             Download/Print Report Card
                        </button>
                    </div>
                <?php else: ?>
                    <p style="text-align: center; padding: 30px; color: #666;">
                        No exam results have been uploaded for you yet.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>