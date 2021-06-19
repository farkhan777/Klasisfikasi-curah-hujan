<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "pc2021";

    $mysqli = mysqli_connect($server, $username, $password, $database);

    if (mysqli_connect_errno()) {
        echo 'Koneksi gagal, ada masalah pada: '.mysql_connect_error();
        exit();
        mysql_close($mysqli);
    }
?>