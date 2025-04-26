<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SESSION['user_type'] !== 'student') {
    die("Access denied. This page is for students only.");
}

// Fetch student's courses
$stmt = $db->prepare("SELECT c.title FROM enrollments e 
                     JOIN courses c ON e.course_id = c.id 
                     WHERE e.student_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll();

// Fetch assignments
$stmt = $db->prepare("SELECT a.title, a.due_date FROM assignments a
                     JOIN enrollments e ON a.course_id = e.course_id
                     WHERE e.student_id = ? AND a.due_date > NOW()
                     ORDER BY a.due_date ASC");
$stmt->execute([$_SESSION['user_id']]);
$assignments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, Student!</h1>
        <p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        
        <div class="dashboard-content">
            <h2>Your Courses</h2>
            <ul>
                <?php foreach ($courses as $course): ?>
                <li><?= htmlspecialchars($course['title']) ?></li>
                <?php endforeach; ?>
            </ul>
            
            <h2>Upcoming Assignments</h2>
            <ul>
                <?php foreach ($assignments as $assignment): ?>
                <li><?= htmlspecialchars($assignment['title']) ?> - 
                    Due <?= date('F j', strtotime($assignment['due_date'])) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>