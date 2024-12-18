<?php
session_start();
require_once 'config/database.php';

error_reporting(0);
ini_set('display_errors', 0);

// Check if user is logged in and session is valid
if (isset($_SESSION['session_token'])) {
    $session_token = $conn->real_escape_string($_SESSION['session_token']);
    $query = "SELECT s.*, u.full_name 
              FROM sessions s
              JOIN users u ON s.user_id = u.id 
              WHERE s.session_token = '$session_token' AND s.expires_at > NOW()";
    $result = $conn->query($query);

    if ($result->num_rows === 0) {
        // Session expired or invalid
        session_destroy();
        header('Location: auth/login.php');
        exit;
    } else {
        // User data'sını session'a kaydet
        $user_data = $result->fetch_assoc();
        $_SESSION['full_name'] = $user_data['full_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FF Game Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=DM+Sans:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="sources/css/main.css">
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
            <div class="store-title">FF STORE</div>

            <div class="menu-items">
                <a href="#" class="menu-item active">
                    <img src="sources/icons/bulk/home-2.svg" alt="Home">
                    Ana Sayfa
                </a>
                <a href="#" class="menu-item">
                    <img src="sources/icons/bulk/category.svg" alt="Categories">
                    Kategoriler
                </a>
                <a href="#" class="menu-item">
                    <img src="sources/icons/bulk/book.svg" alt="Library">
                    Kütüphane
                </a>
                <a href="#" class="menu-item">
                    <img src="sources/icons/bulk/discount-shape.svg" alt="Discounts">
                    İndirimler
                </a>
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

            <div class="content-area">
                <!-- Ana sayfa içeriği -->
                <div class="homepage-content">
                    <div class="slider-container">
                        <div class="slide active"
                            style="background: linear-gradient(to right, rgba(0,0,0,0.7), transparent), url('images.jpeg') center/cover;">
                            <div class="popular-tag">
                                <img src="sources/icons/bulk/crown.svg" alt="Popular">
                                Popüler
                            </div>
                            <div class="slide-content">
                                <div class="slide-title">Cyberpunk 2077</div>
                                <div class="slide-text">Night City'nin tehlikeli sokaklarında kendi hikayenizi yazın.
                                </div>
                                <button class="slide-button">Şimdi Satın Al</button>
                            </div>
                        </div>
                        <div class="slide"
                            style="background: linear-gradient(to right, rgba(0,0,0,0.7), transparent), url('images.jpeg') center/cover;">
                            <div class="popular-tag">
                                <img src="sources/icons/bulk/crown.svg" alt="Popular">
                                Popüler
                            </div>
                            <div class="slide-content">
                                <div class="slide-title">Red Dead Redemption 2</div>
                                <div class="slide-text">Vahşi Batı'nın son günlerinde epik bir maceraya atılın.</div>
                                <button class="slide-button">Şimdi Satın Al</button>
                            </div>
                        </div>
                        <div class="slide"
                            style="background: linear-gradient(to right, rgba(0,0,0,0.7), transparent), url('images.jpeg') center/cover;">
                            <div class="popular-tag">
                                <img src="sources/icons/bulk/crown.svg" alt="Popular">
                                Popüler
                            </div>
                            <div class="slide-content">
                                <div class="slide-title">The Witcher 3</div>
                                <div class="slide-text">Fantastik dünyada bir canavar avcısının destansı yolculuğu.
                                </div>
                                <button class="slide-button">Şimdi Satın Al</button>
                            </div>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar active"></div>
                            <div class="progress-bar"></div>
                            <div class="progress-bar"></div>
                        </div>
                    </div>

                    <?php
                    // Öne çıkan ürünleri getir
                    $featured_products_query = "SELECT p.*, c.name as category_name 
                          FROM products p 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          WHERE p.is_featured = 1 
                          ORDER BY p.created_at DESC 
                          LIMIT 8";
                    $featured_products = $conn->query($featured_products_query);
                    ?>

                    <!-- Öne Çıkan Oyunlar -->
                    <div class="section-title">Öne Çıkan Oyunlar</div>
                    <div class="games-grid">
                        <?php while ($product = $featured_products->fetch_assoc()): ?>
                            <div class="game-card" data-product-id="<?php echo htmlspecialchars($product['id']); ?>">
                                <div class="game-image">
                                    <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </div>
                                <div class="game-info">
                                    <div class="game-title"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <div class="game-price">₺<?php echo number_format($product['price'], 2); ?></div>
                                    <div class="add-to-cart">Sepete Ekle</div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <?php
                $categories_query = "SELECT * FROM categories ORDER BY name ASC";
                $categories = $conn->query($categories_query);
                ?>
                <div class="categories-container">
                    <div class="section-title">Kategoriler</div>
                    <div class="categories-grid">
                        <?php while ($category = $categories->fetch_assoc()): ?>
                            <a href="categories.php?id=<?php echo htmlspecialchars($category['id']); ?>"
                                class="category-card">
                                <div class="category-image">
                                    <img src="uploads/categories/<?php echo htmlspecialchars($category['image']); ?>"
                                        alt="<?php echo htmlspecialchars($category['name']); ?>">
                                    <div class="category-overlay">
                                        <div class="category-title"><?php echo htmlspecialchars($category['name']); ?></div>
                                        <div class="category-count"><?php echo $category['game_count']; ?> Oyun</div>
                                    </div>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Kütüphane Container'ı -->
                <div class="library-container">
                    <div class="section-title">Kütüphanem</div>
                    <div class="library-grid">
                        <div class="library-card">
                            <div class="library-image">
                                <img src="images.jpeg" alt="Game">
                            </div>
                            <div class="library-info">
                                <div class="library-title">Red Dead Redemption 2</div>
                                <button class="explore-button">İncele</button>
                            </div>
                        </div>
                        <div class="library-card">
                            <div class="library-image">
                                <img src="images.jpeg" alt="Game">
                            </div>
                            <div class="library-info">
                                <div class="library-title">Cyberpunk 2077</div>
                                <button class="explore-button">İncele</button>
                            </div>
                        </div>

                        <div class="library-card">
                            <div class="library-image">
                                <img src="images.jpeg" alt="Game">
                            </div>
                            <div class="library-info">
                                <div class="library-title">God of War Ragnarök</div>
                                <button class="explore-button">İncele</button>
                            </div>
                        </div>

                        <div class="library-card">
                            <div class="library-image">
                                <img src="images.jpeg" alt="Game">
                            </div>
                            <div class="library-info">
                                <div class="library-title">Hogwarts Legacy</div>
                                <button class="explore-button">İncele</button>
                            </div>
                        </div>

                        <div class="library-card">
                            <div class="library-image">
                                <img src="images.jpeg" alt="Game">
                            </div>
                            <div class="library-info">
                                <div class="library-title">Elden Ring</div>
                                <button class="explore-button">İncele</button>
                            </div>
                        </div>

                        <div class="library-card">
                            <div class="library-image">
                                <img src="images.jpeg" alt="Game">
                            </div>
                            <div class="library-info">
                                <div class="library-title">Baldur's Gate 3</div>
                                <button class="explore-button">İncele</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- İndirimler Container'ı -->
                <div class="discounts-container">
                    <div class="section-title">İndirimler</div>
                    <div class="discounts-grid">
                        <div class="discount-card">
                            <div class="discount-image">
                                <img src="images.jpeg" alt="Game">
                                <div class="discount-badge">-75%</div>
                            </div>
                            <div class="discount-info">
                                <div class="discount-title">The Witcher 3: Wild Hunt</div>
                                <div class="discount-prices">
                                    <span class="original-price">₺299.99</span>
                                    <span class="discounted-price">₺74.99</span>
                                </div>
                                <div class="discount-timer">
                                    <img src="sources/icons/bulk/timer.svg" alt="Timer">
                                    2 gün 14 saat kaldı
                                </div>
                            </div>
                        </div>
                        <div class="discount-card">
                            <div class="discount-image">
                                <img src="images.jpeg" alt="Game">
                                <div class="discount-badge">-60%</div>
                            </div>
                            <div class="discount-info">
                                <div class="discount-title">Red Dead Redemption 2</div>
                                <div class="discount-prices">
                                    <span class="original-price">₺699.99</span>
                                    <span class="discounted-price">₺279.99</span>
                                </div>
                                <div class="discount-timer">
                                    <img src="sources/icons/bulk/timer.svg" alt="Timer">
                                    1 gün 8 saat kaldı
                                </div>
                            </div>
                        </div>

                        <div class="discount-card">
                            <div class="discount-image">
                                <img src="images.jpeg" alt="Game">
                                <div class="discount-badge">-40%</div>
                            </div>
                            <div class="discount-info">
                                <div class="discount-title">Assassin's Creed Valhalla</div>
                                <div class="discount-prices">
                                    <span class="original-price">₺499.99</span>
                                    <span class="discounted-price">₺299.99</span>
                                </div>
                                <div class="discount-timer">
                                    <img src="sources/icons/bulk/timer.svg" alt="Timer">
                                    3 gün 22 saat kaldı
                                </div>
                            </div>
                        </div>

                        <div class="discount-card">
                            <div class="discount-image">
                                <img src="images.jpeg" alt="Game">
                                <div class="discount-badge">-85%</div>
                            </div>
                            <div class="discount-info">
                                <div class="discount-title">GTA V Premium Edition</div>
                                <div class="discount-prices">
                                    <span class="original-price">₺399.99</span>
                                    <span class="discounted-price">₺59.99</span>
                                </div>
                                <div class="discount-timer">
                                    <img src="sources/icons/bulk/timer.svg" alt="Timer">
                                    16 saat kaldı
                                </div>
                            </div>
                        </div>

                        <div class="discount-card">
                            <div class="discount-image">
                                <img src="images.jpeg" alt="Game">
                                <div class="discount-badge">-50%</div>
                            </div>
                            <div class="discount-info">
                                <div class="discount-title">Sekiro: Shadows Die Twice</div>
                                <div class="discount-prices">
                                    <span class="original-price">₺599.99</span>
                                    <span class="discounted-price">₺299.99</span>
                                </div>
                                <div class="discount-timer">
                                    <img src="sources/icons/bulk/timer.svg" alt="Timer">
                                    4 gün 6 saat kaldı
                                </div>
                            </div>
                        </div>

                        <div class="discount-card">
                            <div class="discount-image">
                                <img src="images.jpeg" alt="Game">
                                <div class="discount-badge">-70%</div>
                            </div>
                            <div class="discount-info">
                                <div class="discount-title">Resident Evil Village</div>
                                <div class="discount-prices">
                                    <span class="original-price">₺499.99</span>
                                    <span class="discounted-price">₺149.99</span>
                                </div>
                                <div class="discount-timer">
                                    <img src="sources/icons/bulk/timer.svg" alt="Timer">
                                    2 gün 18 saat kaldı
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert için -->
    <div id="cartAlert" class="alert">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 12l2 2 4-4"></path>
            <circle cx="12" cy="12" r="10"></circle>
        </svg>
        <span>Ürün sepete eklendi!</span>
    </div>

    <!-- Cart dropdown için -->
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