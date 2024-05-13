<?php
session_start();
require_once('config.php');

$allowedEmail = "cmertyldz@gmail.com";
$userEmail = $_SESSION['email'];

if ($userEmail !== $allowedEmail) {
    header("Location: index.php");
    exit();
}

// Logları çek
$sqlLogs = "SELECT * FROM logs";
$userLogs = $conn->query($sqlLogs);

// Arama kutusundan gelen kullanıcı adı
$searchUsername = isset($_GET['username']) ? $_GET['username'] : '';

// Kullanıcı adına göre logları filtrele
if (!empty($searchUsername)) {
    $sqlLogs .= " WHERE user_name LIKE '%$searchUsername%'";
    $userLogs = $conn->query($sqlLogs);
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
    <style>
        /* Stil tanımlamaları buraya gelecek */
        .search-box {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .search-box input {
            width: 300px;
        }

        .search-box button {
            margin-left: 10px;
        }
    </style>
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

<header>
        <h1>Dashboard Logs</h1>
        <nav>
            <ul>
                <li><a href="http://localhost/Proje/Dashboard.php">Ana Sayfa</a></li>
                <li><a href="http://localhost/Proje/DashboardLogs.php">Raporlar</a></li>
                <li><a href="http://localhost/Proje/DashboardSettings.php">Ayarlar</a></li>
            </ul>
        </nav>
    </header>

    <div class="search-box">
        <form class="d-flex">
            <input class="form-control me-2" type="search" placeholder="Kullanıcı Adı" aria-label="Search" name="username" value="<?php echo $searchUsername; ?>">
            <button class="btn btn-outline-success" type="submit">Ara</button>
        </form>
    </div>

    <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4">
            <?php
            while ($row = $userLogs->fetch_assoc()) {
                echo '<div class="col">';
                echo '<div class="card">';
                echo '<div class="card-body">';
                echo '<img src="./uploads/'.$row['user_image'].'" alt="Profile Image" width="35" height="35" class="rounded-circle"/>';
                echo '<h5 class="card-title">'."Kullanıcı: ".$row['user_name'].'</h5>';
                echo '<p class="card-text">'."User id: ".$row['user_id'].'</p>';
                echo '<p class="card-text">'.$row['action_type'].'</p>';
                echo '<p class="card-text"><small class="text-muted">'.$row['action_time'].'</small></p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    </div>

    <script>
        // Silme işlemini gerçekleştiren JavaScript fonksiyonu buraya gelecek
    </script>

</body>
</html>
