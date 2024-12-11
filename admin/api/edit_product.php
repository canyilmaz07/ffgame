<?php
// admin/api/edit_product.php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Mevcut ürünü kontrol et
    $check_query = "SELECT image FROM products WHERE id = $id";
    $result = $conn->query($check_query);

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    $current_product = $result->fetch_assoc();
    $image_query = "";

    function updateCategoryCount($conn, $category_id)
    {
        $query = "UPDATE categories 
                  SET game_count = (
                      SELECT COUNT(*) 
                      FROM products 
                      WHERE category_id = ?
                  ) 
                  WHERE id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $category_id, $category_id);
        $stmt->execute();
    }

    // Yeni resim yüklendiyse
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $new_name = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $upload_path = '../../uploads/products/' . $new_name;

        if (move_uploaded_file($image['tmp_name'], $upload_path)) {
            // Eski resmi sil
            if ($current_product['image']) {
                @unlink('../../uploads/products/' . $current_product['image']);
            }
            $image_query = ", image = '$new_name'";
        } else {
            echo json_encode(['success' => false, 'message' => 'Image upload failed']);
            exit;
        }
    }

    $query = "UPDATE products 
             SET name = '$name', 
                 description = '$description', 
                 price = $price, 
                 category_id = $category_id, 
                 is_featured = $is_featured" .
        $image_query .
        " WHERE id = $id";

    if ($conn->query($query)) {
        // Eğer kategori değiştiyse eski ve yeni kategorinin sayısını güncelle
        if ($old_category_id != $category_id) {
            updateCategoryCount($conn, $old_category_id);
            updateCategoryCount($conn, $category_id);
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>