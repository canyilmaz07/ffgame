<?php
// api/logout.php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['session_token'])) {
    $session_token = $conn->real_escape_string($_SESSION['session_token']);
    $query = "DELETE FROM sessions WHERE session_token = '$session_token'";
    $conn->query($query);
}

session_destroy();
header('Location: ../index.php');
?>