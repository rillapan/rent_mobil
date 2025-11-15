<?php
require 'koneksi/koneksi.php';

// Create tbl_merk table
$sql = "CREATE TABLE IF NOT EXISTS `tbl_merk` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `merk` varchar(20) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($koneksi, $sql)) {
    echo "✓ tbl_merk table created successfully\n";

    // Insert some sample data
    $sample_data = [
        'Toyota',
        'Honda',
        'Mitsubishi',
        'Suzuki',
        'Nissan',
        'Daihatsu',
        'BMW',
        'Mercedes-Benz'
    ];

    foreach ($sample_data as $merk) {
        $insert_sql = "INSERT INTO tbl_merk (merk) VALUES ('$merk')";
        mysqli_query($koneksi, $insert_sql);
    }

    echo "✓ Sample merk data inserted\n";
} else {
    echo "✗ Error creating table: " . mysqli_error($koneksi) . "\n";
}
?>
