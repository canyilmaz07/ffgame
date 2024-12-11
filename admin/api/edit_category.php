<?php
// admin/api/edit_category.php
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
$name = $conn->real_escape_string($_POST['name']);

// Mevcut kategoriyi kontrol et
$check_query = "SELECT image FROM categories WHERE id = '$id'";
$result = $conn->query($check_query);

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Kategori bulunamadı']);
    exit;
}

$category = $result->fetch_assoc();
$image_name = $category['image'];

// Yeni resim yüklendiyse
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image = $_FILES['image'];
    
    // Resim doğrulama
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($image['type'], $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Sadece JPG ve PNG dosyaları yüklenebilir']);
        exit;
    }

    // Eski resmi sil
    $old_image_path = '../../uploads/categories/' . $image_name;
    if (file_exists($old_image_path)) {
        unlink($old_image_path);
    }

    // Yeni resmi kaydet
    $image_name = uniqid() . '_' . basename($image['name']);
    $upload_dir = '../../uploads/categories/';

    if (!move_uploaded_file($image['tmp_name'], $upload_dir . $image_name)) {
        echo json_encode(['success' => false, 'message' => 'Resim yüklenirken hata oluştu']);
        exit;
    }
}

// Kategoriyi güncelle
$query = "UPDATE categories SET name = '$name', image = '$image_name' WHERE id = '$id'";

if ($conn->query($query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
}
?>