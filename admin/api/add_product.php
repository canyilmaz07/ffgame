<?php
// admin/api/add_product.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Resim yükleme
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $new_name = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $upload_path = '../../uploads/products/' . $new_name;

        if (move_uploaded_file($image['tmp_name'], $upload_path)) {
            $query = "INSERT INTO products (name, description, price, category_id, image, is_featured) 
                     VALUES ('$name', '$description', $price, $category_id, '$new_name', $is_featured)";

            if ($conn->query($query)) {
                updateCategoryCount($conn, $category_id);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Image upload failed']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Image is required']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>