<?php
// admin/api/add_category.php
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

// Resim yükleme kontrolü
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Resim yüklenirken hata oluştu']);
    exit;
}

$name = $conn->real_escape_string($_POST['name']);
$image = $_FILES['image'];

// Resim doğrulama
$allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
if (!in_array($image['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Sadece JPG ve PNG dosyaları yüklenebilir']);
    exit;
}

// Resmi kaydet
$image_name = uniqid() . '_' . basename($image['name']);
$upload_dir = '../../uploads/categories/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (move_uploaded_file($image['tmp_name'], $upload_dir . $image_name)) {
    $query = "INSERT INTO categories (name, image) VALUES ('$name', '$image_name')";
    
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Resim yüklenirken hata oluştu']);
}
?>