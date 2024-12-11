<?php
// login.php
session_start();
require_once '../config/database.php';

// Kullanıcı zaten giriş yapmışsa ana sayfaya yönlendir
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FF Game Store - Giriş Yap</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=DM+Sans:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../sources/css/main.css">
    <style>
        body {
            background: var(--primary-black);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            padding: 40px;
            background: rgba(71, 71, 71, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .store-title {
            font-family: 'Anton', sans-serif;
            font-size: 32px;
            text-transform: uppercase;
            color: var(--primary-yellow);
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: 4px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            color: #ffffff;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid transparent;
            border-radius: 12px;
            color: #ffffff;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-yellow);
            box-shadow: none;
            color: #ffffff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: var(--primary-yellow);
            color: var(--primary-black);
            border: none;
            border-radius: 12px;
            font-weight: bold;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            background: #ffc107;
        }

        .auth-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .auth-link:hover {
            color: var(--primary-yellow);
        }

        .alert {
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            color: #51cf66;
        }

        .back-to-home {
            position: fixed;
            top: 20px;
            left: 20px;
            color: var(--primary-yellow);
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.3s ease;
        }

        .back-to-home:hover {
            transform: translateX(-5px);
            color: var(--primary-yellow);
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid var(--primary-black);
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <a href="../index.php" class="back-to-home">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        Ana Sayfaya Dön
    </a>

    <div class="auth-container">
        <div class="store-title">FF STORE</div>
        <div id="alert-container"></div>

        <form id="login-form">
            <div class="form-group">
                <label for="email" class="form-label">E-posta Adresi</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="submit-btn">
                <span class="loading-spinner"></span>
                <span class="btn-text">Giriş Yap</span>
            </button>
        </form>

        <a href="register.php" class="auth-link">Hesabın yok mu? Hemen üye ol</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            function showAlert(message, type = 'danger') {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                    </div>
                `;
                $('#alert-container').html(alertHtml);
            }

            $('#login-form').on('submit', function (e) {
                e.preventDefault();

                const $button = $(this).find('button[type="submit"]');
                const $spinner = $button.find('.loading-spinner');
                const $btnText = $button.find('.btn-text');

                // Disable button and show spinner
                $button.prop('disabled', true);
                $spinner.show();
                $btnText.text('Giriş yapılıyor...');

                $.ajax({
                    url: '../api/login.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            showAlert('Giriş başarılı! Yönlendiriliyorsunuz...', 'success');
                            setTimeout(() => {
                                window.location.href = '../index.php';
                            }, 1500);
                        } else {
                            showAlert(response.message);
                            $button.prop('disabled', false);
                            $spinner.hide();
                            $btnText.text('Giriş Yap');
                        }
                    },
                    error: function () {
                        showAlert('Bir hata oluştu. Lütfen tekrar deneyin.');
                        $button.prop('disabled', false);
                        $spinner.hide();
                        $btnText.text('Giriş Yap');
                    }
                });
            });
        });
    </script>
</body>

</html>