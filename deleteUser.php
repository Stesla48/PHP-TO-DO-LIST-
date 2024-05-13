<?php
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['userId'])) {
        $userId = $_POST['userId'];

        // Kullanıcıyı sil
        $sql = "DELETE FROM users WHERE id = '$userId'";
        $result = $conn->query($sql);

        if ($result) {
            // Başarılı bir şekilde silindiğinde, sayfayı yenile
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Kullanıcı silinirken bir hata oluştu.";
        }
    } else {
        echo "Geçersiz istek.";
    }
} else {
    echo "Geçersiz istek methodu.";
}
?>
