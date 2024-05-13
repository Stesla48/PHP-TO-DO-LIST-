<?php
session_start();
require_once('config.php');

$username = $_GET['username'];

$sql = "SELECT users.*, COUNT(todos.id) AS totalTodos FROM users LEFT JOIN todos ON users.id = todos.user_id WHERE username = '$username'";
$result = $conn->query($sql);

$user = $result->fetch_assoc();

if ($user["username"] == $_SESSION['username']) {
    header("Location: ProfilePage.php");
    exit();
}

else if ($user["username"] == null) {
    header("Location: index.php");
    exit();
}

else if ($user["username"] == "Admin") {
    header("Location: https://www.fizikist.com/uploads/img/1660416649_void-in-space-ljpg.webp");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Profili</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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

    <div class="container mt-5">
        <div class="card">
        <div class="card-header">
            <h5 class="card-title">Kullanıcı Profili</h5>
        </div>
        <div class="card-body">
            <img src="./uploads/<?php echo $user['profile_image']; ?>" alt="Profil Resmi" class="rounded-circle mb-3" width="150" height="150">
            <h5 class="card-subtitle mb-2 text-muted">Kullanıcı Adı: <?php echo $user['username']; ?></h5>
            <p class="card-text">E-posta: <?php echo $user['email']; ?></p>
            <p class="card-text">Toplam ToDo Sayısı: <?php echo $user['totalTodos']; ?></p>
            <!-- Diğer kullanıcı bilgilerini buraya ekleyebilirsiniz -->
        </div>
    </div>
</div>
</body>
</html>
