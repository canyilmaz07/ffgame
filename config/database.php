<?php
date_default_timezone_set('Europe/Istanbul');

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ff_game_store';

// Mevcut mysqli bağlantısı
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// PDO bağlantısı
try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}
?>