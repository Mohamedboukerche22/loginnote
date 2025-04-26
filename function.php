<?php
function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        redirect('index.html');
    }
}

function is_teacher() {
    return $_SESSION['user_type'] === 'teacher';
}

function is_student() {
    return $_SESSION['user_type'] === 'student';
}

function validate_password($password) {
    return strlen($password) >= 8;
}
?>