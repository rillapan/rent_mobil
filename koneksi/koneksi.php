<?php
/*
  | Source Code Aplikasi Rental Mobil PHP & MySQL
  | 
  | @package   : rental_mobil
  | @file	   : koneksi.php 
  | @author    : fauzan1892 / Fauzan Falah
  | @copyright : Copyright (c) 2017-2021 Codekop.com (https://www.codekop.com)
  | @blog      : https://www.codekop.com/products/source-code-aplikasi-rental-mobil-php-mysql-7.html 
  | 
  | 
  | 
  | 
 */
    $user = 'root';
    $pass = '';
    $host = 'localhost';
    $dbname = 'codekop_free_rental_mobil';

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