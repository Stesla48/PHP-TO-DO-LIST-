<?php
session_start();
require_once('config.php');

// Kullanıcının e-postasını kontrol et
$allowedEmail = "cmertyldz@gmail.com"; // İzin verilen e-posta adresi
$userEmail = $_SESSION['email']; // Kullanıcının oturum e-posta bilgisi (örnektir, gerçek uygulamada bu bilgiyi doğru bir şekilde almalısınız)

if ($userEmail !== $allowedEmail) {
    // Eğer e-posta izin verilen e-posta ile eşleşmiyorsa, ana sayfaya yönlendir
    header("Location: index.php");
    exit();
}

// Toplam kullanıcı sayısını çek
$sqlUsers = "SELECT COUNT(*) as totalUsers FROM users";
$resultUsers = $conn->query($sqlUsers);
$rowUsers = $resultUsers->fetch_assoc();
$totalUsers = $rowUsers['totalUsers'];

// Toplam ToDo sayısını çek
$sqlTodos = "SELECT COUNT(*) as totalTodos FROM todos";
$resultTodos = $conn->query($sqlTodos);
$rowTodos = $resultTodos->fetch_assoc();
$totalTodos = $rowTodos['totalTodos'];

//Logs
$sqlUsers = "SELECT * FROM users";
$users = $conn->query($sqlUsers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/Dashboard.css">
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
    <title>Dashboard</title>
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
            <a class="nav-link " aria-current="page" href="http://localhost/Proje/index.php">Anasayfa</a>
            <a class="nav-link" href="./logout.php">Çıkış</a>
            <?php
            if ($_SESSION['email'] == "cmertyldz@gmail.com") {
                echo '<a class="nav-link active" href="./Dashboard.php">Dashboard</a>';
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

    <header>
        <h1>Dashboard</h1>
        <nav>
            <ul>
            <li><a href="http://localhost/Proje/Dashboard.php">Anasayfa</a></li>
                <li><a href="http://localhost/Proje/DashboardLogs.php">Raporlar</a></li>
                <li><a href="http://localhost/Proje/DashboardSettings.php">Ayarlar</a></li>
            </ul>
        </nav>
    </header>

    <section class="main-content">
        <div class="card">
            <h2>Toplam Kullanıcılar</h2>
            <p><?php echo $totalUsers; ?></p>
        </div>

        <div class="card">
            <h2>Günlük Ziyaretçi Sayısı</h2>
            <p>?</p> 
        </div>

        <div class="card">
            <h2>ToDo Sayısı</h2>
            <p><?php echo $totalTodos; ?></p>
        </div>
    </section>

</body>
</html>
