<?php
// admin/api/delete_category.php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = $conn->real_escape_string($_POST['id']);

// Kategori resmi bilgisini al
$query = "SELECT image FROM categories WHERE id = '$id'";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Kategori bulunamadı']);
    exit;
}

$category = $result->fetch_assoc();

// Resmi sil
$image_path = '../../uploads/categories/' . $category['image'];
if (file_exists($image_path)) {
    unlink($image_path);
}

// Kategoriyi sil
$query = "DELETE FROM categories WHERE id = '$id'";

if ($conn->query($query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
}
?>