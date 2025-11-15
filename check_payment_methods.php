<?php
require 'koneksi/koneksi.php';

echo "Checking payment methods in database...\n";

$result = mysqli_query($koneksi, "SELECT id, payment_type, provider_name, account_number, account_name, is_active FROM payment_methods ORDER BY id");
if ($result && mysqli_num_rows($result) > 0) {
    echo "Payment methods found:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: {$row['id']}, Type: {$row['payment_type']}, Provider: {$row['provider_name']}, Account: {$row['account_number']}, Active: {$row['is_active']}\n";
    }
} else {
    echo "No payment methods found in database\n";
}

// Check if payment_method_id column exists in booking table
$result_columns = mysqli_query($koneksi, "SHOW COLUMNS FROM booking LIKE 'payment_method_id'");
if (mysqli_num_rows($result_columns) > 0) {
    echo "payment_method_id column exists in booking table\n";
} else {
    echo "payment_method_id column does NOT exist in booking table\n";
}
?>
