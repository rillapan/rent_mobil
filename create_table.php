<?php
$host = 'localhost';
$dbname = 'codekop_free_rental_mobil';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS supir (
        id_supir int(11) NOT NULL AUTO_INCREMENT,
        nama_supir varchar(255) NOT NULL,
        pengalaman int(11) NOT NULL COMMENT 'Total pengalaman dalam tahun',
        deskripsi text NOT NULL,
        foto text NOT NULL,
        harga_sewa int(11) NOT NULL COMMENT 'Harga sewa per hari',
        status enum('Tersedia','Sedang Digunakan','Close') NOT NULL DEFAULT 'Tersedia',
        PRIMARY KEY (id_supir)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

    $pdo->exec($sql);
    echo 'Tabel supir berhasil dibuat.';
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
