<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Kullanıcı kontrolü
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Sepet verilerini getir veya yeni oluştur
function getOrCreateCart($conn, $user_id)
{
    $query = "SELECT cart_data FROM cart_items WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Yeni sepet oluştur
        $empty_cart = json_encode([]);
        $insert_query = "INSERT INTO cart_items (user_id, cart_data) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("is", $user_id, $empty_cart);
        $stmt->execute();
        return [];
    }

    $cart_row = $result->fetch_assoc();
    return json_decode($cart_row['cart_data'], true) ?? [];
}

// Sepeti güncelle
function updateCart($conn, $user_id, $cart_data)
{
    $cart_json = json_encode($cart_data);
    $query = "UPDATE cart_items SET cart_data = ? WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $cart_json, $user_id);
    return $stmt->execute();
}

// POST request için (Sepete ekleme)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['product_id']) || !isset($data['title']) || !isset($data['price']) || !isset($data['image'])) {
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    $cart_data = getOrCreateCart($conn, $user_id);

    // Yeni ürün için benzersiz ID oluştur
    $item_id = uniqid();

    // Yeni ürünü ekle
    $cart_data[] = [
        'id' => $item_id,
        'product_id' => $data['product_id'],
        'title' => $data['title'],
        'price' => $data['price'],
        'image' => $data['image'] // URL artık pathname olarak gelecek
    ];

    if (updateCart($conn, $user_id, $cart_data)) {
        echo json_encode(['success' => true, 'cart' => $cart_data]);
    } else {
        echo json_encode(['error' => 'Failed to add item to cart']);
    }
}

// DELETE request için (Sepetten silme)
else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['item_id'])) {
        echo json_encode(['error' => 'Missing item_id']);
        exit;
    }

    $cart_data = getOrCreateCart($conn, $user_id);

    // Silinecek ürünü bul ve kaldır
    $cart_data = array_filter($cart_data, function ($item) use ($data) {
        return $item['id'] !== $data['item_id'];
    });

    // Dizinin indekslerini yeniden düzenle
    $cart_data = array_values($cart_data);

    if (updateCart($conn, $user_id, $cart_data)) {
        echo json_encode(['success' => true, 'cart' => $cart_data]);
    } else {
        echo json_encode(['error' => 'Failed to remove item from cart']);
    }
}

// GET request için (Sepet içeriğini getirme)
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cart_data = getOrCreateCart($conn, $user_id);
    echo json_encode(['success' => true, 'cart' => $cart_data]);
}
?>