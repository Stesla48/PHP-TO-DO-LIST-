<?php
session_start();
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Giriş yapma formu işlemleri
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, email, password FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            header("Location: index.php"); // Giriş başarılıysa ana sayfaya yönlendir
        } else {
            $htmlOutput .= "Hata: Şifre yanlış.";
        }
    } else {
        $htmlOutput .= "Hata: Kullanıcı bulunamadı.";
    }
}

// Oturumu kontrol et ve uygun HTML dosyasını include et
if (isset($_SESSION['user_id'])) {
    // Oturum açık durumu
    include('loggedin_template.php');
} else {
    // Oturum kapalı durumu
    header("Location: Login.php"); // Oturum kapalıysa Login.php'ye yönlendir
    exit();
}
?>
