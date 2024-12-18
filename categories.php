<?php
session_start();
require_once 'config/database.php';

error_reporting(0);
ini_set('display_errors', 0);

// Get category ID from URL
$category_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Get category details
$category_query = "SELECT * FROM categories WHERE id = ?";
$stmt = $conn->prepare($category_query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$category_result = $stmt->get_result();
$category = $category_result->fetch_assoc();

// Get games in this category
$games_query = "SELECT p.*, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = ? 
                ORDER BY p.created_at DESC";
$stmt = $conn->prepare($games_query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$games_result = $stmt->get_result();

// Get all categories for sidebar
$all_categories_query = "SELECT * FROM categories ORDER BY name ASC";
$all_categories = $conn->query($all_categories_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - FF Game Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=DM+Sans:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="sources/css/main.css">
    <style>
        .category-header {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                url('uploads/categories/<?php echo htmlspecialchars($category['image']); ?>');
            background-size: cover;
            background-position: center;
            height: 300px;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            margin-bottom: 40px;
        }

        .category-header h1 {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .category-description {
            font-size: 18px;
            max-width: 600px;
            opacity: 0.8;
        }

        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: rgb(71 71 71 / 10%);
            padding: 20px;
            border-radius: 12px;
        }

        .filter-options {
            display: flex;
            gap: 20px;
        }

        .filter-button {
            background: none;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-button:hover,
        .filter-button.active {
            background: var(--primary-yellow);
            color: var(--primary-black);
        }

        .sort-select {
            background: rgb(71 71 71 / 20%);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        .sort-select option {
            background: var(--primary-black);
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
        <div class="sidebar">
            <a href="index.php" style="text-decoration: none;">
                <div class="store-title">FF STORE</div>
            </a>

            <div class="menu-items">
                <!-- Category List in Sidebar -->
                <div class="subcategories mt-4">
                    <div class="menu-subtitle mb-2 ps-3" style="color: #666;">Kategoriler</div>
                    <?php while ($cat = $all_categories->fetch_assoc()): ?>
                        <a href="categories.php?id=<?php echo $cat['id']; ?>"
                            class="menu-item <?php echo ($cat['id'] == $category_id) ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="help-card">
                <img src="sources/icons/bulk/message-question.svg" alt="Help" width="24" height="24">
                <div class="mt-2">Yardıma mı ihtiyacınız var?</div>
            </div>

            <div class="user-profile">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <img src="image.png" alt="User" class="rounded-circle me-2">
                    <span><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                    <img src="sources/icons/bulk/setting-2.svg" alt="Settings" class="ms-auto" width="24" height="24"
                        style="filter: brightness(0) invert(1); cursor: pointer;">
                    <a href="api/logout.php" class="ms-2" style="text-decoration: none;">
                        <img src="sources/icons/bulk/logout.svg" alt="Logout" width="24" height="24"
                            style="filter: brightness(0) invert(1); cursor: pointer;">
                    </a>
                <?php else: ?>
                    <a href="auth/login.php" class="auth-button" style="text-decoration: none; color: inherit;">
                        <button class="slide-button" style="width: 100%; margin-top: 10px;">
                            Giriş Yap
                        </button>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="main-content">
            <div class="header">
                <div class="welcome-text">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        Hoşgeldin, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!
                    <?php else: ?>
                        FF Store'a Hoşgeldin!
                    <?php endif; ?>
                </div>

                <div class="header-right">
                    <div class="search-container">
                        <img src="sources/icons/bulk/search-normal.svg" alt="Search" class="search-icon">
                        <input type="text" class="search-input" placeholder="Ara...">
                    </div>

                    <div class="header-actions">
                        <img src="sources/icons/bulk/shopping-cart.svg" alt="Cart" class="header-icon cart-icon">
                    </div>
                </div>
            </div>

            <div class="category-header">
                <h1><?php echo htmlspecialchars($category['name']); ?></h1>
                <div class="category-description">
                    <?php echo htmlspecialchars($category['description'] ?? 'Explore amazing games in this category'); ?>
                </div>
            </div>

            <!-- Games Grid -->
            <div class="games-grid">
                <?php while ($game = $games_result->fetch_assoc()): ?>
                    <div class="game-card" data-product-id="<?php echo htmlspecialchars($game['id']); ?>">
                        <div class="game-image">
                            <img src="uploads/products/<?php echo htmlspecialchars($game['image']); ?>"
                                alt="<?php echo htmlspecialchars($game['name']); ?>">
                        </div>
                        <div class="game-info">
                            <div class="game-title"><?php echo htmlspecialchars($game['name']); ?></div>
                            <div class="game-price">₺<?php echo number_format($game['price'], 2); ?></div>
                            <div class="add-to-cart">Sepete Ekle</div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Cart Alert -->
    <div id="cartAlert" class="alert">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 12l2 2 4-4"></path>
            <circle cx="12" cy="12" r="10"></circle>
        </svg>
        <span>Ürün sepete eklendi!</span>
    </div>

    <!-- Cart Dropdown -->
    <div class="cart-dropdown" id="cartDropdown">
        <div id="cartItems"></div>
        <div class="cart-total">
            <span>Toplam:</span>
            <span id="cartTotal">₺0.00</span>
        </div>
        <a href="cart.php" class="cart-button">Sepete Git</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="sources/scripts/main.js"></script>
    <script>
        // Cart state management
        let cart = [];
        let cartOpen = false;

        // Initialize cart functionality when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            setupCartIcon();
            setupAddToCartButtons();
            setupClickOutside();
            loadCart();
        });

        // Load cart from server
        async function loadCart() {
            try {
                const response = await fetch('/api/cart_operations.php');
                const data = await response.json();
                if (data.success) {
                    cart = data.cart;
                    updateCartDropdown();
                    updateCartButtonStates();
                }
            } catch (error) {
                console.error('Failed to load cart:', error);
            }
        }

        // Setup cart icon click handler
        function setupCartIcon() {
            const cartIcon = document.querySelector('.cart-icon');
            if (!cartIcon) return;

            cartIcon.addEventListener('click', (e) => {
                e.stopPropagation();
                cartOpen = !cartOpen;
                const cartDropdown = document.getElementById('cartDropdown');
                if (cartDropdown) {
                    cartDropdown.classList.toggle('active', cartOpen);
                }
            });
        }

        // Setup add to cart buttons
        function setupAddToCartButtons() {
            const addToCartButtons = document.querySelectorAll('.add-to-cart');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', handleAddToCart);
            });
        }

        // Setup click outside to close cart
        function setupClickOutside() {
            document.addEventListener('click', (e) => {
                const cartDropdown = document.getElementById('cartDropdown');
                if (cartOpen && cartDropdown && !cartDropdown.contains(e.target) && !e.target.classList.contains('cart-icon')) {
                    cartOpen = false;
                    cartDropdown.classList.remove('active');
                }
            });
        }

        // Handle add to cart button click
        async function handleAddToCart(e) {
            e.preventDefault();
            e.stopPropagation();

            if (this.classList.contains('added')) {
                return;
            }

            const gameCard = this.closest('.game-card');
            const imageUrl = new URL(gameCard.querySelector('.game-image img').src);

            const productData = {
                product_id: gameCard.dataset.productId,
                title: gameCard.querySelector('.game-title').textContent,
                price: parseFloat(gameCard.querySelector('.game-price').textContent.replace('₺', '')),
                image: imageUrl.pathname,
            };

            try {
                const response = await fetch('/api/cart_operations.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(productData)
                });

                const data = await response.json();
                if (data.success) {
                    cart = data.cart;
                    updateCartDropdown();

                    this.textContent = 'Sepete Eklendi';
                    this.classList.add('added');

                    showAlert();
                }
            } catch (error) {
                console.error('Failed to add item to cart:', error);
            }
        }

        // Remove item from cart
        async function removeFromCart(itemId, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

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
                    cart = data.cart;
                    updateCartDropdown();
                    updateCartButtonStates();
                }
            } catch (error) {
                console.error('Failed to remove item from cart:', error);
            }
        }

        // Update cart dropdown content
        function updateCartDropdown() {
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');

            if (!cartItems || !cartTotal) return;

            if (!cart || cart.length === 0) {
                cartItems.innerHTML = '<div class="empty-cart">Sepetiniz boş</div>';
                cartTotal.textContent = '₺0.00';
                return;
            }

            cartItems.innerHTML = cart.map(item => `
            <div class="cart-item">
                <img src="${item.image}" alt="${item.title}">
                <div class="cart-item-info">
                    <div class="cart-item-title">${item.title}</div>
                    <div class="cart-item-price">₺${parseFloat(item.price).toFixed(2)}</div>
                </div>
                <button class="remove-from-cart" onclick="removeFromCart('${item.id}', event)" title="Sepetten Kaldır">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `).join('');

            const total = cart.reduce((sum, item) => sum + parseFloat(item.price), 0);
            cartTotal.textContent = `₺${total.toFixed(2)}`;
        }

        // Update all "Add to Cart" button states
        function updateCartButtonStates() {
            const gameCards = document.querySelectorAll('.game-card');
            gameCards.forEach(card => {
                const productId = card.dataset.productId;
                const addButton = card.querySelector('.add-to-cart');
                if (!addButton) return;

                const isInCart = cart.some(item => item.product_id === productId);
                if (isInCart) {
                    addButton.textContent = 'Sepete Eklendi';
                    addButton.classList.add('added');
                } else {
                    addButton.textContent = 'Sepete Ekle';
                    addButton.classList.remove('added');
                }
            });
        }

        // Show alert when item is added to cart
        function showAlert() {
            const alert = document.getElementById('cartAlert');
            if (!alert) return;

            alert.classList.add('show');
            setTimeout(() => {
                alert.classList.remove('show');
            }, 3000);
        }

        function handleCartButtonClick() {
            window.location.href = 'cart.php';
        }
    </script>
</body>

</html>