<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FF Game Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=DM+Sans:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-black: #121212;
            --primary-yellow: #ffae00;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--primary-black);
            color: white;
            min-height: 100vh;
        }

        .sidebar {
            background-color: var(--primary-black);
            width: 380px;
            position: fixed;
            height: 100vh;
            padding: 40px;
            display: flex;
            flex-direction: column;
            opacity: 0;
            transform: translateY(-20px);
            /* Initial position for bounce */
        }

        .store-title {
            font-family: 'Anton', sans-serif;
            font-size: 22px;
            text-transform: uppercase;
            color: var(--primary-yellow);
            margin: 30px;
            text-align: center;
            opacity: 0;
            transform: translateY(20px);
            letter-spacing: 4px;
        }

        .menu-items {
            flex-grow: 1;
            margin-top: 20px;
        }

        .menu-item {
            font-size: 16px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            border-radius: 12px;
            opacity: 0;
            transform: translateY(20px);
        }

        .menu-item img {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            filter: brightness(0) invert(1);
            transition: filter 0.3s ease;
        }

        .menu-item:hover {
            background-color: rgb(71 71 71 / 10%);
            color: var(--primary-yellow);
        }

        .menu-item:hover img {
            filter: brightness(0) saturate(100%) invert(71%) sepia(89%) saturate(1161%) hue-rotate(360deg) brightness(103%) contrast(104%);
        }

        .menu-item.active {
            background-color: rgb(71 71 71 / 10%);
            color: var(--primary-yellow);
        }

        .menu-item.active img {
            filter: brightness(0) saturate(100%) invert(71%) sepia(89%) saturate(1161%) hue-rotate(360deg) brightness(103%) contrast(104%);
        }

        .help-card {
            background: linear-gradient(45deg, #312100, #ffa700);
            border-radius: 16px;
            padding: 30px;
            margin: 20px 0;
            opacity: 0;
            transform: translateY(20px);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .help-card:hover {
            transform: translateY(-5px);
        }

        .help-card img {
            filter: brightness(0) invert(1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            padding: 12px;
            opacity: 0;
            transform: translateY(20px);
        }

        .user-profile img {
            width: 24px;
            height: 24px;
        }

        .main-content {
            margin-left: 380px;
            padding: 40px;
            background-color: var(--primary-black);
            min-height: 100vh;
            width: 100%;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .welcome-text {
            font-size: 24px;
            font-weight: bold;
            flex: 1;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-container {
            position: relative;
            width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 12px 20px 12px 45px;
            border: none;
            border-radius: 12px;
            background-color: rgb(71 71 71 / 10%);
            color: white;
        }

        .search-input:focus {
            outline: none;
            background-color: rgb(71 71 71 / 20%);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            filter: brightness(0) invert(0.7);
        }

        .header-actions {
            display: flex;
            gap: 20px;
        }

        .header-icon {
            width: 24px;
            height: 24px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .header-icon:hover {
            transform: scale(1.1);
        }

        .heart-icon {
            filter: invert(15%) sepia(95%) saturate(6932%) hue-rotate(358deg) brightness(95%) contrast(112%);
        }

        .cart-icon {
            filter: brightness(0) saturate(100%) invert(71%) sepia(89%) saturate(1161%) hue-rotate(360deg) brightness(103%) contrast(104%);
        }

        .slider-container {
            position: relative;
            margin-bottom: 40px;
            height: 400px;
            overflow: hidden;
            border-radius: 20px;
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            box-shadow: 0px 22px 40px rgb(255, 174, 0, 20%);
            transition: opacity 0.5s ease;
        }

        .slide.active {
            opacity: 1;
        }

        .slide-content {
            position: absolute;
            bottom: 40px;
            left: 40px;
            z-index: 2;
        }

        .slide-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .slide-text {
            font-size: 16px;
            margin-bottom: 20px;
            max-width: 500px;
        }

        .slide-button {
            background-color: var(--primary-yellow);
            color: var(--primary-black);
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .slide-button:hover {
            transform: translateY(-2px);
        }

        .progress-container {
            position: absolute;
            bottom: 20px;
            right: 40px;
            display: flex;
            gap: 10px;
        }

        .progress-bar {
            width: 50px;
            height: 4px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
            cursor: pointer;
        }

        .progress-bar.active {
            background-color: var(--primary-yellow);
        }

        .popular-tag {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: var(--primary-yellow);
            color: var(--primary-black);
            padding: 8px 16px;
            border-radius: 30px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .popular-tag img {
            width: 20px;
            height: 20px;
            filter: brightness(0);
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .games-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            margin-top: 40px;
            align-items: start;
            /* This ensures top alignment */
        }

        .game-card {
            width: 100%;
            position: relative;
            transition: transform 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            background: var(--primary-black);
            border-radius: 12px;
        }

        .game-card:hover {
            transform: translateY(-5px);
        }

        .game-image {
            width: 100%;
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 12px;
            position: relative;
        }

        .game-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .game-card:hover .game-image img {
            transform: scale(1.1);
            filter: grayscale(100%);
        }

        .game-info {
            padding: 10px;
            position: relative;
            height: 80px;
            /* Fixed height for consistent card sizes */
        }

        .game-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
            color: white;
        }

        .game-price {
            color: var(--primary-yellow);
            font-weight: bold;
            transition: transform 0.3s ease, opacity 0.3s ease;
            position: absolute;
            bottom: 10px;
            left: 10px;
        }

        .add-to-cart {
            position: absolute;
            bottom: -30px;
            left: 10px;
            color: var(--primary-yellow);
            font-weight: bold;
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .game-card:hover .game-price {
            transform: translateY(-30px);
            opacity: 0;
        }

        .game-card:hover .add-to-cart {
            transform: translateY(-30px);
            opacity: 1;
        }

        .favorite-icon {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 24px;
            height: 24px;
            z-index: 2;
            filter: brightness(0) saturate(100%) invert(15%) sepia(95%) saturate(6932%) hue-rotate(358deg) brightness(95%) contrast(112%);
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .game-card:hover .favorite-icon {
            opacity: 1;
        }

        .game-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.5);
            border-radius: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .game-card:hover .game-image::after {
            opacity: 1;
        }

        .welcome-text,
        .search-container,
        .header-actions,
        .slider-container,
        .section-title,
        .game-card {
            opacity: 0;
            transform: translateY(-20px);
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
        }

        .category-card {
            position: relative;
            height: 180px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
            background-color: rgb(71 71 71 / 10%);
            opacity: 0;
            transform: translateY(20px);
        }

        .category-card:hover {
            transform: translateY(-5px);
        }

        .category-image {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .category-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .category-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: white;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .category-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .category-count {
            font-size: 14px;
            opacity: 0.8;
        }

        .category-card:hover .category-image img {
            transform: scale(1.1);
            filter: brightness(0.7);
        }

        .category-card:hover .category-overlay {
            background: linear-gradient(transparent, var(--primary-yellow));
            transform: translateY(0);
        }

        .categories-container {
            display: none;
            opacity: 0;
            transform: translateY(-20px);
        }

        .library-container {
            display: none;
            opacity: 0;
            transform: translateY(-20px);
        }

        .library-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            margin-top: 40px;
        }

        .library-card {
            width: 100%;
            position: relative;
            transition: transform 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            background: var(--primary-black);
            border-radius: 12px;
        }

        .library-card:hover {
            transform: translateY(-5px);
        }

        .library-image {
            width: 100%;
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 12px;
            position: relative;
        }

        .library-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .library-card:hover .library-image img {
            transform: scale(1.1);
            filter: grayscale(100%);
        }

        .library-info {
            padding: 10px;
            position: relative;
        }

        .library-title {
            font-weight: bold;
            font-size: 14px;
            color: white;
            margin-bottom: 15px;
        }

        .explore-button {
            background-color: rgb(71 71 71 / 10%);
            color: var(--primary-yellow);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .explore-button:hover {
            background-color: var(--primary-yellow);
            color: var(--primary-black);
        }

        .library-stats {
            font-size: 14px;
            color: #888;
            display: flex;
            gap: 15px;
        }

        .library-hours {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .library-hours img {
            width: 16px;
            height: 16px;
            filter: brightness(0) invert(0.6);
        }

        .library-achievements {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .library-achievements img {
            width: 16px;
            height: 16px;
            filter: brightness(0) invert(0.6);
        }

        .play-button {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: var(--primary-yellow);
            color: var(--primary-black);
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .play-button:hover {
            transform: translateY(-2px);
        }

        /* İndirimler Sayfası Stilleri */
        .discounts-container {
            display: none;
            opacity: 0;
            transform: translateY(-20px);
        }

        .discounts-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            margin-top: 40px;
        }

        .discount-card {
            width: 100%;
            position: relative;
            transition: transform 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            background: var(--primary-black);
            border-radius: 12px;
        }

        .discount-card:hover {
            transform: translateY(-5px);
        }

        .discount-image {
            width: 100%;
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 12px;
            position: relative;
        }

        .discount-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .discount-card:hover .discount-image img {
            transform: scale(1.1);
            filter: grayscale(100%);
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--primary-yellow);
            color: var(--primary-black);
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
        }

        .discount-info {
            padding: 10px;
        }

        .discount-title {
            font-size: 14px;
            font-weight: bold;
            color: white;
            margin-bottom: 8px;
        }

        .discount-prices {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .original-price {
            color: #888;
            text-decoration: line-through;
            font-size: 14px;
        }

        .discounted-price {
            color: var(--primary-yellow);
            font-weight: bold;
        }

        .discount-timer {
            font-size: 14px;
            color: #888;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .discount-timer img {
            width: 16px;
            height: 16px;
            filter: brightness(0) invert(0.6);
        }
    </style>
</head>

<body>
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
                <img src="image.png" alt="User" class="rounded-circle me-2">
                <span>Emir Paytar</span>
                <img src="sources/icons/bulk/setting-2.svg" alt="Settings" class="ms-auto" width="24" height="24"
                    style="filter: brightness(0) invert(1); cursor: pointer;">
            </div>
        </div>

        <div class="main-content">
            <div class="header">
                <div class="welcome-text">Hoşgeldin, Emir!</div>

                <div class="header-right">
                    <div class="search-container">
                        <img src="sources/icons/bulk/search-normal.svg" alt="Search" class="search-icon">
                        <input type="text" class="search-input" placeholder="Ara...">
                    </div>

                    <div class="header-actions">
                        <img src="sources/icons/bulk/heart.svg" alt="Favorites" class="header-icon heart-icon">
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

                    <div class="section-title">Öne Çıkan Oyunlar</div>

                    <div class="games-grid">
                        <div class="game-card">
                            <img src="sources/icons/bulk/heart.svg" alt="Favorite" class="favorite-icon">
                            <div class="game-image">
                                <img src="images.jpeg" alt="God of War">
                            </div>
                            <div class="game-info">
                                <div class="game-title">God of War Ragnarök</div>
                                <div class="game-price">₺999.99</div>
                                <div class="add-to-cart">Sepete Ekle</div>
                            </div>
                        </div>

                        <div class="game-card">
                            <img src="sources/icons/bulk/heart.svg" alt="Favorite" class="favorite-icon">
                            <div class="game-image">
                                <img src="images.jpeg" alt="Elden Ring">
                            </div>
                            <div class="game-info">
                                <div class="game-title">Elden Ring</div>
                                <div class="game-price">₺899.99</div>
                                <div class="add-to-cart">Sepete Ekle</div>
                            </div>
                        </div>

                        <div class="game-card">
                            <img src="sources/icons/bulk/heart.svg" alt="Favorite" class="favorite-icon">
                            <div class="game-image">
                                <img src="images.jpeg" alt="Spider-Man 2">
                            </div>
                            <div class="game-info">
                                <div class="game-title">Marvel's Spider-Man 2</div>
                                <div class="game-price">₺1299.99</div>
                                <div class="add-to-cart">Sepete Ekle</div>
                            </div>
                        </div>

                        <div class="game-card">
                            <img src="sources/icons/bulk/heart.svg" alt="Favorite" class="favorite-icon">
                            <div class="game-image">
                                <img src="images.jpeg" alt="Baldur's Gate 3">
                            </div>
                            <div class="game-info">
                                <div class="game-title">Baldur's Gate 3</div>
                                <div class="game-price">₺799.99</div>
                                <div class="add-to-cart">Sepete Ekle</div>
                            </div>
                        </div>

                        <div class="game-card">
                            <img src="sources/icons/bulk/heart.svg" alt="Favorite" class="favorite-icon">
                            <div class="game-image">
                                <img src="images.jpeg" alt="Final Fantasy XVI">
                            </div>
                            <div class="game-info">
                                <div class="game-title">Final Fantasy XVI</div>
                                <div class="game-price">₺899.99</div>
                                <div class="add-to-cart">Sepete Ekle</div>
                            </div>
                        </div>

                        <div class="game-card">
                            <img src="sources/icons/bulk/heart.svg" alt="Favorite" class="favorite-icon">
                            <div class="game-image">
                                <img src="images.jpeg" alt="Resident Evil 4">
                            </div>
                            <div class="game-info">
                                <div class="game-title">Resident Evil 4 Remake</div>
                                <div class="game-price">₺699.99</div>
                                <div class="add-to-cart">Sepete Ekle</div>
                            </div>
                        </div>

                        <div class="game-card">
                            <img src="sources/icons/bulk/heart.svg" alt="Favorite" class="favorite-icon">
                            <div class="game-image">
                                <img src="images.jpeg" alt="Starfield">
                            </div>
                            <div class="game-info">
                                <div class="game-title">Starfield</div>
                                <div class="game-price">₺999.99</div>
                                <div class="add-to-cart">Sepete Ekle</div>
                            </div>
                        </div>

                        <div class="game-card">
                            <img src="sources/icons/bulk/heart.svg" alt="Favorite" class="favorite-icon">
                            <div class="game-image">
                                <img src="images.jpeg" alt="Horizon">
                            </div>
                            <div class="game-info">
                                <div class="game-title">Horizon Forbidden West</div>
                                <div class="game-price">₺799.99</div>
                                <div class="add-to-cart">Sepete Ekle</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="categories-container">
                    <div class="section-title">Kategoriler</div>
                    <div class="categories-grid">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="images.jpeg" alt="Action">
                                <div class="category-overlay">
                                    <div class="category-title">Aksiyon</div>
                                    <div class="category-count">156 Oyun</div>
                                </div>
                            </div>
                        </div>
                        <div class="category-card">
                            <div class="category-image">
                                <img src="images.jpeg" alt="Adventure">
                                <div class="category-overlay">
                                    <div class="category-title">Macera</div>
                                    <div class="category-count">145 Oyun</div>
                                </div>
                            </div>
                        </div>
                        <div class="category-card">
                            <div class="category-image">
                                <img src="images.jpeg" alt="RPG">
                                <div class="category-overlay">
                                    <div class="category-title">RPG</div>
                                    <div class="category-count">178 Oyun</div>
                                </div>
                            </div>
                        </div>
                        <div class="category-card">
                            <div class="category-image">
                                <img src="images.jpeg" alt="Strategy">
                                <div class="category-overlay">
                                    <div class="category-title">Strateji</div>
                                    <div class="category-count">92 Oyun</div>
                                </div>
                            </div>
                        </div>
                        <div class="category-card">
                            <div class="category-image">
                                <img src="images.jpeg" alt="Simulation">
                                <div class="category-overlay">
                                    <div class="category-title">Simülasyon</div>
                                    <div class="category-count">89 Oyun</div>
                                </div>
                            </div>
                        </div>
                        <div class="category-card">
                            <div class="category-image">
                                <img src="images.jpeg" alt="Sports">
                                <div class="category-overlay">
                                    <div class="category-title">Spor</div>
                                    <div class="category-count">67 Oyun</div>
                                </div>
                            </div>
                        </div>
                        <div class="category-card">
                            <div class="category-image">
                                <img src="images.jpeg" alt="Racing">
                                <div class="category-overlay">
                                    <div class="category-title">Yarış</div>
                                    <div class="category-count">94 Oyun</div>
                                </div>
                            </div>
                        </div>
                        <div class="category-card">
                            <div class="category-image">
                                <img src="images.jpeg" alt="Horror">
                                <div class="category-overlay">
                                    <div class="category-title">Korku</div>
                                    <div class="category-count">83 Oyun</div>
                                </div>
                            </div>
                        </div>
                        <div class="category-card">
                            <div class="category-image">
                                <img src="images.jpeg" alt="Platformer">
                                <div class="category-overlay">
                                    <div class="category-title">Platform</div>
                                    <div class="category-count">112 Oyun</div>
                                </div>
                            </div>
                        </div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize main timeline
            const tl = gsap.timeline();

            // Sidebar animations
            tl.to('.sidebar', {
                opacity: 1,
                duration: 0.5
            })
                .to('.store-title', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3
                })
                .to('.menu-item', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3,
                    stagger: 0.1
                })
                .to('.help-card', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3
                })
                .to('.user-profile', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3
                })

                // Main content animations - starting after sidebar
                .to('.welcome-text', {
                    opacity: 0,
                    y: -20,
                    duration: 0,
                })
                .to('.welcome-text', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3
                })
                .to('.search-container', {
                    opacity: 0,
                    y: -20,
                    duration: 0
                })
                .to('.search-container', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3
                })
                .to('.header-actions', {
                    opacity: 0,
                    y: -20,
                    duration: 0
                })
                .to('.header-actions', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3
                })
                .to('.slider-container', {
                    opacity: 0,
                    y: -20,
                    duration: 0
                })
                .to('.slider-container', {
                    opacity: 1,
                    y: 0,
                    duration: 0.5
                })
                .to('.section-title', {
                    opacity: 0,
                    y: -20,
                    duration: 0
                })
                .to('.section-title', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3
                })
                .to('.game-card', {
                    opacity: 0,
                    y: -20,
                    duration: 0
                })
                .to('.game-card', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3,
                    stagger: 0.1
                });

            // Initialize slider variables
            let currentSlide = 0;
            const slides = document.querySelectorAll('.slide');
            const progressBars = document.querySelectorAll('.progress-bar');
            const slideCount = slides.length;

            // Function to handle slide transitions
            function nextSlide() {
                // Fade out current slide
                gsap.to(slides[currentSlide], {
                    opacity: 0,
                    duration: 0.5
                });
                progressBars[currentSlide].classList.remove('active');

                // Update current slide index
                currentSlide = (currentSlide + 1) % slideCount;

                // Fade in next slide
                gsap.to(slides[currentSlide], {
                    opacity: 1,
                    duration: 0.5
                });
                progressBars[currentSlide].classList.add('active');
            }

            // Add click events to progress bars
            progressBars.forEach((bar, index) => {
                bar.addEventListener('click', () => {
                    if (currentSlide !== index) {
                        // Fade out current slide
                        gsap.to(slides[currentSlide], {
                            opacity: 0,
                            duration: 0.5
                        });
                        progressBars[currentSlide].classList.remove('active');

                        // Update current slide
                        currentSlide = index;

                        // Fade in selected slide
                        gsap.to(slides[currentSlide], {
                            opacity: 1,
                            duration: 0.5
                        });
                        progressBars[currentSlide].classList.add('active');
                    }
                });
            });

            // Start automatic slider
            setInterval(nextSlide, 5000);
        });
    </script>
    <script>
        // Kategorilere tıklama işleyicisi
        document.querySelector('.menu-items .menu-item:nth-child(2)').addEventListener('click', function (e) {
            e.preventDefault();

            // Menu item active state
            document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
            this.classList.add('active');

            const tl = gsap.timeline();

            // Ana sayfa içeriğini gizle
            tl.to('.homepage-content', {
                opacity: 0,
                y: -20,
                duration: 0.3,
                onComplete: () => {
                    document.querySelector('.homepage-content').style.display = 'none';
                    document.querySelector('.categories-container').style.display = 'block';
                    // Display'i block yaptıktan sonra opacity'yi 0'a set et
                    gsap.set('.categories-container', { opacity: 0 });
                    gsap.set('.category-card', { opacity: 0, y: 20 });
                }
            })
                // Kategori container'ı göster
                .to('.categories-container', {
                    opacity: 1,
                    duration: 0.3
                })
                // Kategori kartlarını sırayla göster
                .to('.category-card', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3,
                    stagger: 0.05 // Kartlar arası süreyi azalttık
                });
        });

        // Ana sayfaya dönüş işleyicisi
        document.querySelector('.menu-items .menu-item:first-child').addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
            this.classList.add('active');

            const tl = gsap.timeline();

            // Tüm containerları kontrol et ve görüneni bul
            const containers = ['.homepage-content', '.categories-container', '.library-container', '.discounts-container'];
            const visibleContainer = containers.find(container =>
                document.querySelector(container).style.display !== 'none'
            ) || '.homepage-content';

            tl.to(visibleContainer, {
                opacity: 0,
                y: -20,
                duration: 0.3,
                onComplete: () => {
                    // Tüm containerları gizle
                    containers.forEach(container => {
                        document.querySelector(container).style.display = 'none';
                    });
                    document.querySelector('.homepage-content').style.display = 'block';
                    gsap.set('.homepage-content', { opacity: 0 });
                    gsap.set('.game-card', { opacity: 0, y: 20 });
                }
            })
                .to('.homepage-content', {
                    opacity: 1,
                    duration: 0.3
                })
                .to('.slider-container', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3
                })
                .to('.game-card', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3,
                    stagger: 0.05
                });
        });

        // Kütüphane sayfasına geçiş
        document.querySelector('.menu-items .menu-item:nth-child(3)').addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
            this.classList.add('active');

            const tl = gsap.timeline();

            // Tüm containerları kontrol et ve görüneni bul
            const containers = ['.homepage-content', '.categories-container', '.library-container', '.discounts-container'];
            const visibleContainer = containers.find(container =>
                document.querySelector(container).style.display !== 'none'
            ) || '.homepage-content';

            tl.to(visibleContainer, {
                opacity: 0,
                y: -20,
                duration: 0.3,
                onComplete: () => {
                    // Tüm containerları gizle
                    containers.forEach(container => {
                        document.querySelector(container).style.display = 'none';
                    });
                    document.querySelector('.library-container').style.display = 'block';
                    gsap.set('.library-container', { opacity: 0 });
                    gsap.set('.library-card', { opacity: 0, y: 20 });
                }
            })
                .to('.library-container', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3
                })
                .to('.library-card', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3,
                    stagger: 0.05
                });
        });

        // İndirimler sayfasına geçiş
        document.querySelector('.menu-items .menu-item:nth-child(4)').addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
            this.classList.add('active');

            const tl = gsap.timeline();

            // Tüm containerları kontrol et ve görüneni bul
            const containers = ['.homepage-content', '.categories-container', '.library-container', '.discounts-container'];
            const visibleContainer = containers.find(container =>
                document.querySelector(container).style.display !== 'none'
            ) || '.homepage-content';

            tl.to(visibleContainer, {
                opacity: 0,
                y: -20,
                duration: 0.3,
                onComplete: () => {
                    // Tüm containerları gizle
                    containers.forEach(container => {
                        document.querySelector(container).style.display = 'none';
                    });
                    document.querySelector('.discounts-container').style.display = 'block';
                    gsap.set('.discounts-container', { opacity: 0 });
                    gsap.set('.discount-card', { opacity: 0, y: 20 });
                }
            })
                .to('.discounts-container', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3
                })
                .to('.discount-card', {
                    opacity: 1,
                    y: 0,
                    duration: 0.3,
                    stagger: 0.05
                });
        });
    </script>
</body>

</html>