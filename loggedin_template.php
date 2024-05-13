<?php
require_once('config.php');

// ToDo ekleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $user_image = $_SESSION['profile_image'];

    if (isset($_POST['editTodoId']) && isset($_POST['newTodoText'])) {
        $todoId = $_POST['editTodoId'];
        $newTodoText = $_POST['newTodoText'];

        $sql = "UPDATE todos SET todo_text = '$newTodoText' WHERE id = '$todoId' AND user_id = '$_SESSION[user_id]'";
        $result = $conn->query($sql);

        //Logs
        $sqlLogAdd = "INSERT INTO logs (user_id , action_type,action_time,user_name,user_image) VALUES ('$userId' ,'ToDo Düzenlendi', NOW(),'$username','$user_image');";
        $users = $conn->query($sqlLogAdd);

        if ($result) {
            // Başarılı bir şekilde güncellendiğinde, sayfayı yenile
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "ToDo güncellenirken bir hata oluştu.";
        }
    } elseif (isset($_POST['deleteTodoId'])) {
        $todoId = $_POST['deleteTodoId'];

        $sql = "DELETE FROM todos WHERE id = '$todoId' AND user_id = '$_SESSION[user_id]'";
        $result = $conn->query($sql);

        //Logs
        $sqlLogAdd = "INSERT INTO logs (user_id , action_type,action_time,user_name,user_image) VALUES ('$userId' ,'ToDo Silindi', NOW(),'$username','$user_image');";
        $users = $conn->query($sqlLogAdd);

        if ($result) {
            // Başarılı bir şekilde silindiğinde, sayfayı yenile
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "ToDo silinirken bir hata oluştu.";
        }
    } else {
        $userId = $_SESSION['user_id'];
        $todoText = $_POST['todoText'];
        

        $sql = "INSERT INTO todos (user_id, todo_text) VALUES ('$userId', '$todoText')";
        $result = $conn->query($sql);
        
        //Logs
        $sqlLogAdd = "INSERT INTO logs (user_id , action_type,action_time,user_name,user_image) VALUES ('$userId' ,'ToDo Oluşturuldu', NOW(),'$username','$user_image');";
        $users = $conn->query($sqlLogAdd);

        if ($result) {
            // Başarılı bir şekilde eklenirse, sayfayı yenile
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "ToDo eklenirken bir hata oluştu.";
        }
    }
}

// ToDo'ları çek
$userId = $_SESSION['user_id'];
$sql = "SELECT id, todo_text FROM todos WHERE user_id = '$userId'";
$result = $conn->query($sql);
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
    <title>ToDo Listesi</title>
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
            <a class="nav-link active" aria-current="page" href="#">Anasayfa</a>
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
        <div class="col-md-6 offset-md-3">
            <img src="./Public/ToDo.png" alt="HomePage İmage" class="mx-auto d-block" width="400" height="400">

            <section class="todoForm mt-3">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="d-flex flex-column align-items-center">
                            <h2 class="text-center">ToDo Ekle</h2>
                            <div class="mb-3">
                                <input type="text" name="todoText" required class="form-control"/>
                            </div>
                            <button type="submit" class="btn btn-primary">Ekle</button>
                        </form>
                    </div>
                </div>
            </section>

        <ul class="list-group mt-3">
            <?php
                // ToDo'ları listele
                while ($row = $result->fetch_assoc()) {
                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>' . $row['todo_text'] . '</span>
                            <div>
                                <button class="btn btn-warning me-2" onclick="openEditModal(' . $row['id'] . ', \'' . $row['todo_text'] . '\')">Düzenle</button>
                                <button class="btn btn-danger" onclick="deleteTodo(' . $row['id'] . ')">Sil</button>
                            </div>
                          </li>';
                }
            ?>
        </ul>
    </div>

    <!-- Düzenleme Modalı -->
    <div class="modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">ToDo Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" action="">
                        <div class="mb-3">
                            <label for="newTodoText" class="form-label">Yeni ToDo Metni</label>
                            <input type="text" class="form-control" id="newTodoText" name="newTodoText" required>
                        </div>
                        <input type="hidden" id="editTodoId" name="editTodoId">
                        <button type="button" class="btn btn-primary" onclick="updateTodo()">Güncelle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer
        style="
            background-color: #f2f2f2;
            padding: 10px;
            text-align: center;
            margin-top: 20px;
            bottom: 0;
            width: 100%;
        "
    >
        <p>&copy; 2023 Your Company. All rights reserved.</p>
    </footer>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        function openEditModal(todoId, todoText) {
            // Modal içindeki formu güncelle
            document.getElementById('editTodoId').value = todoId;
            document.getElementById('newTodoText').value = todoText;

            // Modal'ı aç
            var myModal = new bootstrap.Modal(document.getElementById('editModal'));
            myModal.show();
        }

        function updateTodo() {
            // Formu submit et
            document.getElementById('editForm').submit();
        }

        function deleteTodo(todoId) {
            var confirmDelete = confirm("Bu ToDo'yu silmek istediğinizden emin misiniz?");
            if (confirmDelete) {
                // Formu oluştur ve gerekli değeri ayarla
                var form = document.createElement('form');
                form.method = 'post';
                form.action = '<?php echo $_SERVER['PHP_SELF']; ?>';

                // Input elemanını ekle
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'deleteTodoId';
                input.value = todoId;

                // Forma input elemanını ekle
                form.appendChild(input);

                // Formu sayfaya ekleyip submit et
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
