<?php
session_start();
require 'db_config.php';

$user_type = $_POST['user_type'];
$email = $_POST['email'];
$password = $_POST['password'];
$teacher_code = isset($_POST['teacher_code']) ? $_POST['teacher_code'] : '';

if ($user_type === 'teacher') {
    $valid_teacher_code = "TEACHER123"; 
    
    if ($teacher_code !== $valid_teacher_code) {
        die("Invalid teacher code. Please try again.");
    }
}
$stmt = $db->prepare("SELECT id, password FROM users WHERE email = ? AND user_type = ?");
$stmt->execute([$email, $user_type]);
$user = $stmt->fetch();

if ($user) {
    if ($password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user_type;
        $_SESSION['email'] = $email;
        header("Location: " . ($user_type === 'student' ? 'student.php' : 'teacher.php'));
        exit();
    } else {
        die("Invalid password. Please try again.");
    }
} else {
    die("User not found. Please register first.");
}
?>
