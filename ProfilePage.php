<?php
session_start();
require_once('config.php');

$userId = $_SESSION['user_id'];
// Şifre değiştirme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['changePassword'])) {
    $currentPassword = $_POST['current-password'];
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-password'];

    // Kullanıcının mevcut şifresini kontrol et
    $sql = "SELECT password FROM users WHERE id = '$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Mevcut şifre doğruysa şifreyi güncelle
        if (password_verify($currentPassword, $row['password'])) {
            // Yeni şifre ve tekrar şifresini kontrol et
            if ($newPassword === $confirmPassword) {
                // Yeni şifreyi güvenli bir şekilde hashle
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Şifreyi güncelle
                $updateSql = "UPDATE users SET password = '$hashedPassword' WHERE id = '$userId'";
                $updateResult = $conn->query($updateSql);

                if ($updateResult) {
                    echo "Şifre başarıyla değiştirildi.";
                } else {
                    echo "Şifre değiştirilirken bir hata oluştu.";
                }
            } else {
                echo "Yeni şifreler uyuşmuyor.";
            }
        } else {
            echo "Mevcut şifre yanlış.";
        }
    } else {
        echo "Kullanıcı bulunamadı.";
    }
}

// Kullanıcının ToDo sayısını al
$todoCountSql = "SELECT COUNT(*) AS todo_count FROM todos WHERE user_id = '$userId'";
$todoCountResult = $conn->query($todoCountSql);
$todoCountRow = $todoCountResult->fetch_assoc();
$todoCount = isset($todoCountRow['todo_count']) ? $todoCountRow['todo_count'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"
    ></script>
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding-top: 70px; /* Navbar'ın üzerine yerleştirme */
        }

        .tab-content {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<!-- Header (Oturum Açık) -->
<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="./index.php">
            <img src="./Public/ToDoLogo.png" alt="Logo" width="40" height="40" />
            ToDo App
        </a>
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Arama Kutusu -->
        <form class="d-flex ms-auto" action="UserProfile.php" method="GET">
            <input class="form-control me-2" type="search" placeholder="Kullanıcı Adı" aria-label="Search" name="username">
            <button class="btn btn-outline-success" type="submit">Ara</button>
        </form>
        <div class="navbar-nav">
            <a class="nav-link " aria-current="page" href="http://localhost/Proje/index.php">Anasayfa</a>
            <a class="nav-link" href="./logout.php">Çıkış</a>
            <?php
            if ($_SESSION['email'] == "cmertyldz@gmail.com") {
                echo '<a class="nav-link" href="./Dashboard.php">Dashboard</a>';
            }
            ?>
            <a class="nav-link active" href="./ProfilePage.php">
                <?php
                echo '<img src="./uploads/'.$_SESSION['profile_image'].'" alt="Profile Image" width="35" height="35" class="rounded-circle"/>';
                ?>
                <?php echo $_SESSION['username']; ?>
            </a>
        </div>
    </div>
</nav>

<!-- Body -->
<div class="container mt-4">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info">Bilgiler</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password">Şifre</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="info">
            <h3 class="mb-4">Kullanıcı Bilgileri</h3>
            <p> <?php echo '<img class="rounded" src="./uploads/'.$_SESSION['profile_image'].'" alt="Profile Image" width="80" height="80" />'; ?></p>
            <p><strong>Kullanıcı Adı:</strong> <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Bilgi Yok'; ?></p>
            <p><strong>Eposta:</strong> <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'Bilgi Yok'; ?></p>
            <p><strong>ToDo Sayısı:</strong> <?php echo $todoCount; ?></p>
        </div>

        <div class="tab-pane fade" id="password">
            <h3 class="mb-4">Şifre Bilgisi</h3>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="mb-3">
                    <label for="current-password" class="form-label">Mevcut Şifre:</label>
                    <input type="password" class="form-control" id="current-password" name="current-password" required>
                </div>
                <div class="mb-3">
                    <label for="new-password" class="form-label">Yeni Şifre:</label>
                    <input type="password" class="form-control" id="new-password" name="new-password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm-password" class="form-label">Yeni Şifre Onay:</label>
                    <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
                </div>
                <button type="submit" name="changePassword" class="btn btn-primary">Şifreyi Değiştir</button>
            </form>
        </div>
    </div>
</div>

<footer class="fixed-bottom bg-light text-center p-3">
    <p>&copy; 2023 Your Company. All rights reserved.</p>
</footer>

<script>
    var infoTab = new bootstrap.Tab(document.getElementById('info-tab'));
    var passwordTab = new bootstrap.Tab(document.getElementById('password-tab'));

    infoTab.show();

    function openTab(tab) {
        if (tab === 'info') {
            infoTab.show();
        } else if (tab === 'password') {
            passwordTab.show();
        }
    }
</script>

</body>
</html>
