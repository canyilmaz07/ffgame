<?php
// api/login.php
session_start();
require_once '../config/database.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Debug modunu kapalı konuma alalım
error_reporting(0);
ini_set('display_errors', 0);

// Sadece JSON çıktısı vereceğimizi belirtelim
header('Content-Type: application/json');

// Önceki çıktı tamponlarını temizleyelim
ob_clean();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek metodu']);
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$verificationCode = $_POST['verification_code'] ?? '';

try {
    // İlk aşama: Email ve şifre kontrolü
    if (!empty($email) && !empty($password) && empty($verificationCode)) {
        // mysqli sorgusu yapalım
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user || !password_verify($password, $user['password'])) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz e-posta veya şifre.']);
            exit;
        }

        // Doğrulama kodu oluştur ve güncelle
        $code = sprintf("%06d", mt_rand(0, 999999));
        $expires = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        
        $stmt = $conn->prepare("UPDATE users SET verification_code = ?, verification_expires_at = ? WHERE id = ?");
        $stmt->bind_param("ssi", $code, $expires, $user['id']);
        $stmt->execute();

        // Mail gönderme işlemi
        $mail = new PHPMailer(true);

        try {
            // Debug modu kapalı
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'emirpaytarphpmailer@gmail.com';
            $mail->Password = 'htvo fiwu shyx pwzh';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('emirpaytarphpmailer@gmail.com', 'FF Game Store');
            $mail->addAddress($user['email']);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            $mail->Subject = 'Giriş Doğrulama Kodu';
            $mail->Body = "Merhaba {$user['full_name']},<br><br>Giriş doğrulama kodunuz: <b>{$code}</b><br>Bu kod 5 dakika içinde geçerliliğini yitirecektir.";

            $mail->send();

            echo json_encode([
                'success' => true,
                'message' => 'Doğrulama kodu e-posta adresinize gönderildi.',
                'requireVerification' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Mail gönderilemedi. Lütfen tekrar deneyin.'
            ]);
        }
        exit;
    }

    // İkinci aşama: Doğrulama kodu kontrolü
    if (!empty($verificationCode)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE verification_code = ? AND verification_expires_at > NOW()");
        $stmt->bind_param("s", $verificationCode);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz veya süresi dolmuş doğrulama kodu.']);
            exit;
        }

        // Session token oluştur ve kaydet
        $sessionToken = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        $stmt = $conn->prepare("INSERT INTO sessions (user_id, session_token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user['id'], $sessionToken, $expiresAt);
        $stmt->execute();

        // Doğrulama kodunu sıfırla
        $stmt = $conn->prepare("UPDATE users SET verification_code = NULL, verification_expires_at = NULL WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();

        // Session'ı başlat
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['session_token'] = $sessionToken;
        $_SESSION['full_name'] = $user['full_name'];

        echo json_encode(['success' => true, 'message' => 'Giriş başarılı!']);
        exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu. Lütfen tekrar deneyin.'
    ]);
    exit;
}

// Geçersiz istek durumu
echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
?>