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

    try {
        $koneksi = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    global $url;
    $url = "http://localhost/rental_mobil-master/";

    $sql_web = "SELECT * FROM infoweb WHERE id = 1";
    $stmt_web = $koneksi->prepare($sql_web);
    $stmt_web->execute();
    global $info_web;
    $info_web = $stmt_web->fetch(PDO::FETCH_OBJ);

    
    
    // error_reporting(0);
?>