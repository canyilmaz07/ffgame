<?php
// admin/api/toggle_featured.php
header('Content-Type: application/json');
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $featured = intval($_POST['featured']);
    
    $query = "UPDATE products SET is_featured = $featured WHERE id = $id";
    
    if ($conn->query($query)) {
        echo json_encode(['success' => true, 'message' => 'Öne çıkan durumu güncellendi']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek metodu']);
}
?>