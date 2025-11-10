<?php
    session_start();
    session_destroy();
    require '../koneksi/koneksi.php';
    header("location:http://localhost/rental_mobil-master/index.php");
    exit;
?>
