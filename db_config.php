<?php
// db_config.php
$host = 'sql313.hstn.me';
$dbname = 'mseet_38835548_education_portal';
$username = 'mseet_38835548';
$password = 'adda28011968'; 

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>