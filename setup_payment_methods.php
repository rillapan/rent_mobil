<?php
require 'koneksi/koneksi.php';

// Create payment_methods table
$sql_create_table = "
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_type` enum('bank','ewallet') NOT NULL,
  `provider_name` varchar(100) NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if (mysqli_query($koneksi, $sql_create_table)) {
    echo "✓ Payment methods table created successfully<br>";
} else {
    echo "✗ Error creating table: " . mysqli_error($koneksi) . "<br>";
}

// Add columns to booking table (check if they exist first)
$columns_to_add = [];
$result = mysqli_query($koneksi, "SHOW COLUMNS FROM booking LIKE 'payment_method_id'");
if (mysqli_num_rows($result) == 0) {
    $columns_to_add[] = "ADD COLUMN `payment_method_id` INT NULL AFTER `total_harga`";
}

$result = mysqli_query($koneksi, "SHOW COLUMNS FROM booking LIKE 'payment_proof'");
if (mysqli_num_rows($result) == 0) {
    $columns_to_add[] = "ADD COLUMN `payment_proof` VARCHAR(255) NULL AFTER `payment_method_id`";
}

$result = mysqli_query($koneksi, "SHOW COLUMNS FROM booking LIKE 'payment_status'");
if (mysqli_num_rows($result) == 0) {
    $columns_to_add[] = "ADD COLUMN `payment_status` ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending' AFTER `payment_proof`";
}

if (!empty($columns_to_add)) {
    $sql_alter_booking = "ALTER TABLE `booking` " . implode(', ', $columns_to_add);
    if (mysqli_query($koneksi, $sql_alter_booking)) {
        echo "✓ Booking table updated successfully<br>";
    } else {
        echo "✗ Error updating booking table: " . mysqli_error($koneksi) . "<br>";
    }
} else {
    echo "✓ Booking table columns already exist<br>";
}

// Insert sample payment methods
$sample_methods = [
    ['bank', 'BCA', '123-456-7890', 'PT Rental Mobil Indonesia', 1],
    ['bank', 'Mandiri', '098-765-4321', 'PT Rental Mobil Indonesia', 1],
    ['ewallet', 'GoPay', '081234567890', 'PT Rental Mobil Indonesia', 1],
    ['ewallet', 'OVO', '081234567891', 'PT Rental Mobil Indonesia', 1],
];

$inserted = 0;
foreach ($sample_methods as $method) {
    $sql_insert = "INSERT INTO payment_methods (payment_type, provider_name, account_number, account_name, is_active) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql_insert);
    mysqli_stmt_bind_param($stmt, 'sssss', $method[0], $method[1], $method[2], $method[3], $method[4]);

    if (mysqli_stmt_execute($stmt)) {
        $inserted++;
    }
    mysqli_stmt_close($stmt);
}

echo "✓ Inserted $inserted sample payment methods<br>";

// Verify setup
$result = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM payment_methods WHERE is_active = 1");
$row = mysqli_fetch_assoc($result);
echo "✓ Active payment methods: " . $row['count'] . "<br>";

echo "<br><strong>Setup completed! You can now test the payment confirmation at:</strong><br>";
echo "<a href='konfirmasi.php?id=1763128180'>http://localhost/rental_mobil-master/konfirmasi.php?id=1763128180</a><br>";
echo "<br><strong>Or manage payment methods in admin:</strong><br>";
echo "<a href='admin/pengaturan/payment_methods.php'>http://localhost/rental_mobil-master/admin/pengaturan/payment_methods.php</a>";
?>
