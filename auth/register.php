<?php
// register.php
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
    <title>FF Game Store - Üye Ol</title>
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

        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* 50% transparent black */
        }

        video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="video-background">
        <div class="video-overlay"></div>
        <video autoplay muted loop>
            <source src="/sources/video/bg.mp4" type="video/mp4">
        </video>
    </div>

    <a href="../index.php" class="back-to-home">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        Ana Sayfaya Dön
    </a>

    <div class="auth-container">
        <div id="alert-container"></div>

        <form id="register-form">
            <div class="form-group">
                <label for="username" class="form-label">Kullanıcı Adı</label>
                <input type="text" class="form-control" id="username" name="username" required
                    pattern="[a-zA-Z0-9_]{3,20}"
                    title="3-20 karakter arası, sadece harf, rakam ve alt çizgi (_) kullanabilirsiniz">
            </div>

            <div class="form-group">
                <label for="full_name" class="form-label">Ad Soyad</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">E-posta Adresi</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="birth_date" class="form-label">Doğum Tarihi</label>
                <input type="date" class="form-control" id="birth_date" name="birth_date" required
                    max="<?php echo date('Y-m-d', strtotime('-13 years')); ?>">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="password" name="password" required
                    pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"
                    title="En az 8 karakter, en az bir harf ve bir rakam içermelidir">
            </div>

            <div class="form-group">
                <label for="password_confirm" class="form-label">Şifre Tekrar</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
            </div>

            <button type="submit" class="submit-btn">
                <span class="loading-spinner"></span>
                <span class="btn-text">Üye Ol</span>
            </button>
        </form>

        <a href="login.php" class="auth-link">Zaten üye misin? Giriş yap</a>
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

                // Alert'i otomatik olarak kaldır
                setTimeout(() => {
                    $('#alert-container').empty();
                }, 5000);
            }

            // Şifre kontrolü
            $('#password, #password_confirm').on('input', function () {
                const password = $('#password').val();
                const confirmPassword = $('#password_confirm').val();

                if (confirmPassword && password !== confirmPassword) {
                    $('#password_confirm')[0].setCustomValidity('Şifreler eşleşmiyor');
                    $(this).addClass('is-invalid');
                } else {
                    $('#password_confirm')[0].setCustomValidity('');
                    $(this).removeClass('is-invalid');
                }
            });

            // Yaş kontrolü
            $('#birth_date').on('change', function () {
                const birthDate = new Date($(this).val());
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const month = today.getMonth() - birthDate.getMonth();

                if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if (age < 13) {
                    $(this)[0].setCustomValidity('13 yaşından küçükler üye olamaz');
                    showAlert('13 yaşından küçükler üye olamaz');
                } else {
                    $(this)[0].setCustomValidity('');
                }
            });

            // Form gönderimi
            $('#register-form').on('submit', function (e) {
                e.preventDefault();

                const $form = $(this);
                const $button = $form.find('button[type="submit"]');
                const $spinner = $button.find('.loading-spinner');
                const $btnText = $button.find('.btn-text');

                // Şifre kontrolü
                const password = $('#password').val();
                const confirmPassword = $('#password_confirm').val();

                if (password !== confirmPassword) {
                    showAlert('Şifreler eşleşmiyor');
                    return;
                }

                // Button'u disable et ve spinner'ı göster
                $button.prop('disabled', true);
                $spinner.show();
                $btnText.text('Kayıt yapılıyor...');

                $.ajax({
                    url: '../api/register.php',
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            showAlert('Kayıt başarılı! Giriş sayfasına yönlendiriliyorsunuz...', 'success');
                            setTimeout(() => {
                                window.location.href = 'login.php';
                            }, 2000);
                        } else {
                            showAlert(response.message);
                            $button.prop('disabled', false);
                            $spinner.hide();
                            $btnText.text('Üye Ol');
                        }
                    },
                    error: function (xhr, status, error) {
                        showAlert('Bir hata oluştu. Lütfen tekrar deneyin.');
                        $button.prop('disabled', false);
                        $spinner.hide();
                        $btnText.text('Üye Ol');
                        console.error(xhr, status, error);
                    }
                });
            });

            // Kullanıcı adı kontrolü
            let usernameTimeout;
            $('#username').on('input', function () {
                const $input = $(this);
                const username = $input.val();

                // Önceki timeout'u temizle
                clearTimeout(usernameTimeout);

                // En az 3 karakter girilmişse kontrol et
                if (username.length >= 3) {
                    usernameTimeout = setTimeout(() => {
                        $.ajax({
                            url: '../api/check_username.php',
                            type: 'POST',
                            data: { username: username },
                            dataType: 'json',
                            success: function (response) {
                                if (!response.available) {
                                    $input[0].setCustomValidity('Bu kullanıcı adı zaten kullanılıyor');
                                    $input.addClass('is-invalid');
                                } else {
                                    $input[0].setCustomValidity('');
                                    $input.removeClass('is-invalid');
                                }
                            }
                        });
                    }, 500); // 500ms bekle
                }
            });

            // Email kontrolü
            let emailTimeout;
            $('#email').on('input', function () {
                const $input = $(this);
                const email = $input.val();

                // Önceki timeout'u temizle
                clearTimeout(emailTimeout);

                // Geçerli bir email ise kontrol et
                if (email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                    emailTimeout = setTimeout(() => {
                        $.ajax({
                            url: '../api/check_email.php',
                            type: 'POST',
                            data: { email: email },
                            dataType: 'json',
                            success: function (response) {
                                if (!response.available) {
                                    $input[0].setCustomValidity('Bu email adresi zaten kullanılıyor');
                                    $input.addClass('is-invalid');
                                } else {
                                    $input[0].setCustomValidity('');
                                    $input.removeClass('is-invalid');
                                }
                            }
                        });
                    }, 500); // 500ms bekle
                }
            });
        });
    </script>
</body>

</html>