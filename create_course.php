<?php
require 'db_config.php';
require 'functions.php';
require_login();

if (!is_teacher()) {
    die("Access denied. Teachers only.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $teacher_id = $_SESSION['user_id'];
    
    $stmt = $db->prepare("INSERT INTO courses (title, description, teacher_id) VALUES (?, ?, ?)");
    if ($stmt->execute([$title, $description, $teacher_id])) {
        $course_id = $db->lastInsertId();
        redirect("course.php?id=$course_id");
    } else {
        $error = "Failed to create course";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Course</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Create New Course</h1>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Course Title:</label>
                <input type="text" name="title" required>
            </div>
            
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="4"></textarea>
            </div>
            
            <button type="submit">Create Course</button>
        </form>
    </div>
</body>
</html>