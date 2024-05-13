<?php
session_start();
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, email,username,profile_image, password FROM users WHERE email = '$email' or username='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['username'] = $row['username']; // Kullanıcı adını oturum verilerine ekleyin
            $_SESSION['profile_image'] = $row['profile_image'];
            header("Location: index.php"); // Giriş başarılıysa ana sayfaya yönlendir
        } else {
            echo "Hata: Şifre yanlış.";
        }
    } else {
        echo "Hata: Kullanıcı bulunamadı.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./CSS/Login.css">
  <title>Sign-In</title>
</head>
<body>



<!-- Geri kalan sayfa içeriği -->
    <section>
        <div class="form-box">
            <div class="form-value">
                <form method="post" action="Login.php"> <!-- action düzeltildi -->
                    <h2>Giriş</h2>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="text" name="email" required>
                        <label for="email">Eposta veya Kullanıcı Adı</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" required>
                        <label for="password">Şifre</label>
                    </div>
                    <div class="forget">
                        <label for=""><input type="checkbox" name="remember">Beni Hatırla </label>
                        <label for=""><a href="./PasswordReset.php">Şifremi Unuttum</a></label>
                    </div>
                    <button type="submit">Giriş Yap</button>
                    <div class="register">
                        <p>Hesabın yok mu? <a href="./Register.php">Kayıt ol</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>
</html>
