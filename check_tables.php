<?php
require 'koneksi/koneksi.php';

$result = mysqli_query($koneksi, 'SHOW TABLES');
echo "Tables in database:\n";
while($row = mysqli_fetch_array($result)) {
    echo $row[0] . "\n";
}

// Check if tbl_merk exists
$result_merk = mysqli_query($koneksi, "SHOW TABLES LIKE 'tbl_merk'");
if (mysqli_num_rows($result_merk) > 0) {
    echo "\n✓ tbl_merk table exists\n";
} else {
    echo "\n✗ tbl_merk table does not exist\n";
}
?>