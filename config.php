<?php

    $server_name = "localhost";
    $username = "root";
    $password = "";
    $database = "todo_app";
    $conn = mysqli_connect($server_name, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
   
?>
