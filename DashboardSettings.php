<?php
session_start();
require_once('config.php');

// Kullanıcının e-postasını kontrol et
$allowedEmail = "cmertyldz@gmail.com";
$userEmail = $_SESSION['email'];

if ($userEmail !== $allowedEmail) {
    header("Location: index.php");
    exit();
}

// Kullanıcıların bilgilerini getir
$sqlUsers = "SELECT * FROM users";
$users = $conn->query($sqlUsers);

// Kullanıcıların todos sayısını getir
$sqlTodos = "SELECT user_id, COUNT(*) AS todos_count
             FROM todos
             GROUP BY user_id";
$todosResult = $conn->query($sqlTodos);

// Veritabanından alınan todos sonuçlarını bir diziye yerleştir
$todosData = [];
while ($row = $todosResult->fetch_assoc()) {
    $todosData[$row['user_id']] = $row['todos_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/Dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Dashboard Logs</title>
</head>
<body>
<!-- Header (Oturum Açık) -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
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
            <a class="nav-link active" aria-current="page" href="http://localhost/Proje/index.php">Anasayfa</a>
            <a class="nav-link" href="./logout.php">Çıkış</a>
            <?php
            if ($_SESSION['email'] == "cmertyldz@gmail.com") {
                echo '<a class="nav-link" href="./Dashboard.php">Dashboard</a>';
            }
            ?>
            <a class="nav-link" href="./ProfilePage.php">
                <?php
                echo '<img src="./uploads/'.$_SESSION['profile_image'].'" alt="Profile Image" width="35" height="35" class="rounded-circle"/>';
                ?>
                <?php echo $_SESSION['username']; ?>
            </a>

        </div>
    </div>
</nav>

<header class="text-center mt-4">
    <h1>Dashboard Ayarlar</h1>
    <nav>
        <ul>
            <li><a href="http://localhost/Proje/Dashboard.php">Ana Sayfa</a></li>
            <li><a href="http://localhost/Proje/DashboardLogs.php">Raporlar</a></li>
            <li><a href="http://localhost/Proje/DashboardSettings.php">Ayarlar</a></li>
        </ul>
    </nav>
</header>

<div class="container mt-4">
    <h2>Kullanıcılar</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kullanıcı ID</th>
                <th>Kullanıcı Adı</th>
                <th>Eposta</th>
                <th>Parola</th>
                <th>Profil Resmi</th>
                <th>ToDo Sayısı</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $users->fetch_assoc()) {
                $userId = $row['id'];
                $todosCount = isset($todosData[$userId]) ? $todosData[$userId] : 0;

                echo "<tr style='" . ($userId == 0 ? 'background-color: yellow;' : '') . "'>
                        <td>{$userId}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['password']}</td>
                        <td><img src='./uploads/{$row['profile_image']}' alt='Profile Image' width='80' height='80' /></td>
                        <td>{$todosCount}</td>
                        <td><button class='btn btn-danger' onclick='deleteUser({$userId})'>Sil</button></td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>




<script>
    function deleteUser(userId) {
        var confirmDelete = confirm("Bu kullanıcıyı silmek istediğinizden emin misiniz?");
        if (confirmDelete) {
            // Formu oluştur ve gerekli değeri ayarla
            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'deleteUser.php'; // Silme işlemini gerçekleştirecek ayrı bir PHP dosyası oluşturmanız gerekebilir

            // Input elemanını ekle
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'userId';
            input.value = userId;

            // Forma input elemanını ekle
            form.appendChild(input);

            if (userId != 0) {
                // Formu sayfaya ekleyip submit et
                document.body.appendChild(form);
                form.submit();
            } else {
                alert("ADMİNİ SİLEMEZSİN!");
            }
        }
    }
</script>

</body>
</html>
