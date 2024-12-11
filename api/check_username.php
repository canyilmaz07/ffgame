<?php
// api/check_username.php
require_once '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$username = $conn->real_escape_string($_POST['username']);

$query = "SELECT id FROM users WHERE username = '$username'";
$result = $conn->query($query);

echo json_encode(['available' => $result->num_rows === 0]);

// api/check_email.php
require_once '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$email = $conn->real_escape_string($_POST['email']);

$query = "SELECT id FROM users WHERE email = '$email'";
$result = $conn->query($query);

echo json_encode(['available' => $result->num_rows === 0]);
?>