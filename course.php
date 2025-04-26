<?php
require 'db_config.php';
require 'functions.php';
require_login();

$course_id = $_GET['id'] ?? 0;

// Get course details
$stmt = $db->prepare("SELECT c.*, u.first_name, u.last_name 
                     FROM courses c JOIN users u ON c.teacher_id = u.id
                     WHERE c.id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    die("Course not found");
}

// Get enrolled students
$stmt = $db->prepare("SELECT u.id, u.first_name, u.last_name, u.email
                     FROM enrollments e JOIN users u ON e.student_id = u.id
                     WHERE e.course_id = ?");
$stmt->execute([$course_id]);
$students = $stmt->fetchAll();

// Get assignments
$stmt = $db->prepare("SELECT * FROM assignments WHERE course_id = ? ORDER BY due_date");
$stmt->execute([$course_id]);
$assignments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($course['title']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($course['title']) ?></h1>
        <p>Taught by: <?= htmlspecialchars($course['first_name'] . ' ' . $course['last_name']) ?></p>
        <p><?= htmlspecialchars($course['description']) ?></p>
        
        <h2>Students Enrolled (<?= count($students) ?>)</h2>
        <ul>
            <?php foreach ($students as $student): ?>
            <li><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></li>
            <?php endforeach; ?>
        </ul>
        
        <h2>Assignments</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($assignments as $assignment): ?>
            <tr>
                <td><?= htmlspecialchars($assignment['title']) ?></td>
                <td><?= date('M j, Y', strtotime($assignment['due_date'])) ?></td>
                <td>
                    <a href="assignment.php?id=<?= $assignment['id'] ?>">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <?php if (is_teacher() && $_SESSION['user_id'] == $course['teacher_id']): ?>
        <div class="teacher-actions">
            <a href="create_assignment.php?course=<?= $course_id ?>" class="btn">Add Assignment</a>
            <a href="edit_course.php?id=<?= $course_id ?>" class="btn">Edit Course</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>