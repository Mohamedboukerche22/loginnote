<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SESSION['user_type'] !== 'teacher') {
    die("Access denied. This page is for teachers only.");
}

// Fetch teacher's courses from database
$stmt = $db->prepare("SELECT * FROM courses WHERE teacher_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, Teacher!</h1>
        <p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        
        <div class="dashboard-content">
            <h2>Your Classes</h2>
            <ul>
                <?php foreach ($courses as $course): ?>
                <li><?= htmlspecialchars($course['title']) ?> - 
                    <?= $course['student_count'] ?? 0 ?> students</li>
                <?php endforeach; ?>
            </ul>
            
            <h2>Teaching Tools</h2>
            <div class="teacher-tools">
                <a href="create_assignment.php" class="tool-btn">Create Assignment</a>
                <a href="grade_submissions.php" class="tool-btn">Grade Submissions</a>
                <a href="student_progress.php" class="tool-btn">View Student Progress</a>
            </div>
        </div>
        
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>