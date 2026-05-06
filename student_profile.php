<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') { 
    header("Location: index.php"); 
    exit(); 
}

$user_id = $_SESSION['id'];
$stmt = $conn->prepare("
    SELECT u.name, u.email, u.role, u.gender, u.district, u.created_at, c.class_name, c.class_level
    FROM users u
    LEFT JOIN student_classes sc ON u.id = sc.student_id
    LEFT JOIN classes c ON sc.class_id = c.id
    WHERE u.id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="sec.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Student Panel</h2>
            <ul>
                <li><a href="student_dashboard.php"> Dashboard</a></li>
                <li><a href="student_profile.php"> My Profile</a></li>
                <li><a href="student_assignments.php"> Assignments</a></li>
                <li><a href="student_results.php"> Exam Results</a></li>
                <li><a href="logout.php" style="color: #ff6b6b;"> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>My Profile</h1>
            
            <div class="card">
                <h3>Personal Information</h3>
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="padding: 10px; border: none;"><strong>Full Name:</strong></td>
                        <td style="padding: 10px; border: none;"><?php echo htmlspecialchars($profile['name']); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: none;"><strong>Email Address:</strong></td>
                        <td style="padding: 10px; border: none;"><?php echo htmlspecialchars($profile['email']); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: none;"><strong>Gender:</strong></td>
                        <td style="padding: 10px; border: none;"><?php echo htmlspecialchars($profile['gender'] ?? 'Not Specified'); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: none;"><strong>District:</strong></td>
                        <td style="padding: 10px; border: none;"><?php echo htmlspecialchars($profile['district'] ?? 'Not Specified'); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: none;"><strong>Current Class:</strong></td>
                        <td style="padding: 10px; border: none; font-size: 18px; color: #0A2342; font-weight: bold;">
                            <?php echo $profile['class_name'] ? htmlspecialchars($profile['class_name']) : '<span style="color:red;">Not Assigned</span>'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: none;"><strong>Level:</strong></td>
                        <td style="padding: 10px; border: none;">
                            <?php echo $profile['class_level'] ? ucfirst($profile['class_level']) . ' Secondary' : '-'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: none;"><strong>Account Created:</strong></td>
                        <td style="padding: 10px; border: none;"><?php echo date('d M Y', strtotime($profile['created_at'])); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>