<?php
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['userName'];
    $email = $_POST['email'];
    $reEmail = $_POST['reEmail'];
    $password = $_POST['password'];
    $rePassword = $_POST['rePassword'];

    // Eposta ve şifre tekrarı kontrolü
    if ($email !== $reEmail || $password !== $rePassword) {
        echo "Hata: Eposta veya şifre eşleşmiyor.";
        exit;
    } else {
        // Veritabanında e-posta ve kullanıcı adı kontrolü
        $checkUserQuery = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
        $checkUserResult = $conn->query($checkUserQuery);
        

        if ($checkUserResult->num_rows > 0) {
            echo "Hata: Bu e-posta veya kullanıcı adı zaten kullanımda.";
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Dosya yüklenmişse
            if (!empty($_FILES['profileImage']['name'])) {
                $fileName = $_FILES['profileImage']['name'];
                $targetDir = "uploads/";

                // Hedef dizin yoksa oluştur
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                // Dosyayı hedef dizine taşı
                move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetDir . $fileName);

                // Kullanıcıyı veritabanına ekle
                $sql = "INSERT INTO users (username, email, password, profile_image) VALUES ('$username', '$email', '$passwordHash', '$fileName')";

                if ($conn->query($sql) === TRUE) {
                    echo "Kayıt başarılı!";
                    header("Location: index.php"); // Giriş başarılıysa ana sayfaya yönlendir
                } else {
                    echo "Hata: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Hata: Profil resmi seçilmedi.";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./CSS/SignUp.css" />
    <title>Sign-Up</title>
</head>
<body>
    <section class="signUpForm">
        <div class="form-box">
            <div class="form-value">
            <form method="post" action="Register.php" enctype="multipart/form-data">
                    <h2>Kayıt Ol</h2>
                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="userName" required />
                        <label for="userName">Kullanıcı Adı</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" required />
                        <label for="email">Eposta</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="reEmail" required />
                        <label for="reEmail">Eposta Tekrar</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" required />
                        <label for="password">Şifre</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="rePassword" required />
                        <label for="rePassword">Şifre Tekrar</label>
                    </div>
                    <!-- Resim Ekleme Inputu -->
                    <div class="inputbox">
                        <input type="file" name="profileImage" accept="image/*">
                    </div>

                    <button type="submit">Kayıt Ol</button>
                    <div class="logIn">
                        <p>Hesabın var mı? <a href="./Login.php">Giriş yap</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
