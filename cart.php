<?php
session_start();
require_once 'config/database.php';

// Session kontrolü...
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

// Cart verilerini çek...
$cart_query = "SELECT cart_data FROM cart_items WHERE user_id = ?";
$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$cart_data = [];

if ($result->num_rows > 0) {
    $cart_row = $result->fetch_assoc();
    $cart_data = json_decode($cart_row['cart_data'], true) ?? [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim - FF Game Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=DM+Sans:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="sources/css/main.css">
    <style>
        .cart-page {
            min-height: calc(100vh - 80px);
            padding: 20px 40px;
            margin-top: 20px;
        }

        .cart-container {
            background: #1a1a1a;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        .cart-list {
            padding: 20px;
        }

        .cart-page-item {
            background: #242424;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            position: relative;
            transition: transform 0.3s ease;
        }

        .cart-page-item:hover {
            transform: translateY(-2px);
        }

        .cart-page-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }

        .cart-item-info {
            flex-grow: 1;
        }

        .cart-item-title {
            font-size: 18px;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
        }

        .cart-item-price {
            font-size: 20px;
            color: var(--primary-yellow);
            font-weight: bold;
            position: absolute;
            bottom: 20px;
            left: 160px;
        }

        .cart-summary {
            background: #1a1a1a;
            border-radius: 16px;
            padding: 25px;
            position: sticky;
            top: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: #999;
            font-size: 16px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #333;
            font-size: 22px;
            font-weight: bold;
            color: var(--primary-yellow);
        }

        .checkout-button {
            background: var(--primary-yellow);
            color: var(--primary-black);
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            width: 100%;
            margin-top: 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .checkout-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 174, 0, 0.3);
        }

        .empty-cart-message {
            text-align: center;
            padding: 50px;
            color: #999;
        }

        .empty-cart-message h3 {
            color: white;
            margin-bottom: 15px;
        }

        .continue-shopping {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-yellow);
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
            background: #1a1a1a;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .continue-shopping:hover {
            color: white;
            background: #242424;
            transform: translateX(-5px);
        }

        .continue-shopping svg {
            transition: transform 0.3s ease;
        }

        .continue-shopping:hover svg {
            transform: translateX(-5px);
        }

        .remove-from-cart {
            background: none;
            border: none;
            padding: 10px;
            color: #ff4444;
            cursor: pointer;
            position: absolute;
            top: 20px;
            right: 20px;
            transition: all 0.3s ease;
            border-radius: 50%;
        }

        .remove-from-cart:hover {
            background: rgba(255, 68, 68, 0.1);
            transform: scale(1.1);
        }

        .remove-from-cart svg {
            width: 20px;
            height: 20px;
        }

        .cart-title {
            font-size: 28px;
            margin-bottom: 30px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="video-container">
        <video autoplay muted loop>
            <source src="/sources/video/bg.mp4" type="video/mp4">
        </video>
    </div>
    <div class="overlay"></div>

    <div class="d-flex">

        <div class="main-content">
            <div class="cart-page">
                <a href="/" class="continue-shopping">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Alışverişe Devam Et
                </a>

                <h1 class="cart-title">Sepetim</h1>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="cart-container">
                            <?php if (empty($cart_data)): ?>
                                <div class="empty-cart-message">
                                    <h3>Sepetiniz Boş</h3>
                                    <p>Hemen alışverişe başlayın!</p>
                                </div>
                            <?php else: ?>
                                <div class="cart-list">
                                    <?php foreach ($cart_data as $item): ?>
                                        <div class="cart-page-item">
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                            <div class="cart-item-info">
                                                <div class="cart-item-title"><?php echo htmlspecialchars($item['title']); ?></div>
                                                <div class="cart-item-price">₺<?php echo number_format($item['price'], 2); ?></div>
                                            </div>
                                            <button class="remove-from-cart" onclick="removeFromCart('<?php echo $item['id']; ?>')" title="Sepetten Kaldır">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="cart-summary">
                            <h3>Sipariş Özeti</h3>
                            
                            <?php if (!empty($cart_data)): ?>
                                <?php
                                $subtotal = array_reduce($cart_data, function($carry, $item) {
                                    return $carry + $item['price'];
                                }, 0);
                                $tax = $subtotal * 0.18;
                                $total = $subtotal + $tax;
                                ?>
                                
                                <div class="summary-item">
                                    <span>Ara Toplam</span>
                                    <span>₺<?php echo number_format($subtotal, 2); ?></span>
                                </div>
                                <div class="summary-item">
                                    <span>KDV (%18)</span>
                                    <span>₺<?php echo number_format($tax, 2); ?></span>
                                </div>
                                <div class="summary-total">
                                    <span>Toplam</span>
                                    <span>₺<?php echo number_format($total, 2); ?></span>
                                </div>
                                
                                <button onclick="window.location.href='checkout.php'" class="checkout-button">
                                    Ödeme İşlemine Geç
                                </button>
                            <?php else: ?>
                                <div class="summary-item">
                                    <span>Toplam</span>
                                    <span>₺0.00</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    async function removeFromCart(itemId) {
        try {
            const response = await fetch('/api/cart_operations.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    item_id: itemId
                })
            });

            const data = await response.json();
            if (data.success) {
                window.location.reload();
            }
        } catch (error) {
            console.error('Failed to remove item from cart:', error);
        }
    }
    </script>
</body>
</html>