<?php
// api/register.php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$username = $conn->real_escape_string($_POST['username']);
$full_name = $conn->real_escape_string($_POST['full_name']);
$email = $conn->real_escape_string($_POST['email']);
$birth_date = $conn->real_escape_string($_POST['birth_date']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Check if email already exists
$query = "SELECT id FROM users WHERE email = '$email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Bu e-posta adresi zaten kullanılıyor']);
    exit;
}

// Check if username already exists
$query = "SELECT id FROM users WHERE username = '$username'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Bu kullanıcı adı zaten kullanılıyor']);
    exit;
}

$query = "INSERT INTO users (username, full_name, email, password, birth_date) 
          VALUES ('$username', '$full_name', '$email', '$password', '$birth_date')";

if ($conn->query($query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Kayıt sırasında bir hata oluştu']);
}
?>