<?php
// admin/login_process.php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$username = $conn->real_escape_string($_POST['username']);
$password = $_POST['password'];

$query = "SELECT id, password FROM admin_users WHERE username = '$username'";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Kullanıcı adı veya şifre hatalı']);
    exit;
}

$admin = $result->fetch_assoc();

if (password_verify($password, $admin['password'])) {
    $_SESSION['admin_id'] = $admin['id'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Kullanıcı adı veya şifre hatalı']);
}
?>