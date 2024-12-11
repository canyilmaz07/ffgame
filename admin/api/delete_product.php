<?php
// admin/api/delete_product.php
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
    $id = intval($_POST['id']);

    // Ürün resmini bul
    $query = "SELECT image FROM products WHERE id = $id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Ürünü sil
        $delete_query = "DELETE FROM products WHERE id = $id";
        if ($conn->query($delete_query)) {
            // Resmi sil
            if ($product['image']) {
                @unlink('../../uploads/products/' . $product['image']);
            }
            updateCategoryCount($conn, $product['category_id']);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>