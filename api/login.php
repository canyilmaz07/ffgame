<?php
// api/login.php
require_once '../config/database.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$email = $conn->real_escape_string($_POST['email']);
$password = $_POST['password'];

$query = "SELECT id, username, full_name, password FROM users WHERE email = '$email'";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'E-posta veya şifre hatalı']);
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'E-posta veya şifre hatalı']);
    exit;
}

// Generate session token
$session_token = bin2hex(random_bytes(32));
$user_id = $user['id'];
$expires_at = date('Y-m-d H:i:s', strtotime('+30 days'));

// Store session
$query = "INSERT INTO sessions (user_id, session_token, expires_at) VALUES ('$user_id', '$session_token', '$expires_at')";
$conn->query($query);

// Set session variables
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['session_token'] = $session_token;

echo json_encode(['success' => true]);
?>