<?php

    $user = 'root';
    $pass = '';
    $host = 'localhost';
    $dbname = 'rent_affan';

    $koneksi = mysqli_connect($host, $user, $pass, $dbname);
    if (!$koneksi) {
        die("Connection failed: " . mysqli_connect_error());
    }

    global $url;
    $url = "http://localhost/rental_mobil-master/";

    $sql_web = "SELECT * FROM infoweb WHERE id = 1";
    $result_web = mysqli_query($koneksi, $sql_web);
    global $info_web;
    $info_web = mysqli_fetch_object($result_web);

    
    
    // error_reporting(0);
?>