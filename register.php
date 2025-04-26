<?php
require 'db_config.php';

// Get form data
$user_type = $_POST['user_type'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$teacher_code = isset($_POST['teacher_code']) ? $_POST['teacher_code'] : '';

if ($password !== $confirm_password) {
    die("Passwords do not match.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}
if ($user_type === 'teacher') {
    $valid_teacher_code = "TEACHER123";
    if ($teacher_code !== $valid_teacher_code) {
        die("Invalid teacher registration code.");
    }
}


$stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    die("Email already registered. Please login instead.");
}

$stmt = $db->prepare("INSERT INTO users (email, password, user_type) VALUES (?, ?, ?)");
if ($stmt->execute([$email, $password, $user_type])) {
    echo "Registration successful! <a href='index.html'>Login here</a>";
} else {
    echo "Error: " . $stmt->errorInfo()[2];
}
?>