# TODO: Convert PDO to mysqli in rental_mobil-master

## Step 1: Convert koneksi/koneksi.php

- [x] Replace PDO connection with mysqli_connect
- [x] Update error handling from PDOException to mysqli_connect_error
- [x] Change info_web fetch from PDO to mysqli_query and mysqli_fetch_object

## Step 2: Convert koneksi/proses.php

- [x] Replace all PDO prepared statements with mysqli equivalents
- [x] Update login process: mysqli_prepare, mysqli_stmt_bind_param, mysqli_stmt_execute, mysqli_stmt_get_result, mysqli_fetch_assoc
- [x] Update register process similarly
- [x] Update booking process
- [x] Update konfirmasi process
- [x] Update update_profil process
- [x] Update ubah_password process
- [x] Update ajukan_refund process (including admin notifications)
- [x] Ensure all fetches use mysqli_fetch_assoc or mysqli_fetch_object

## Step 3: Update other PHP files

- [x] profil.php: Replace PDO usages
- [x] notifikasi.php: Replace PDO usages
- [x] header.php: Replace PDO usages
- [x] detail.php: Replace PDO usages
- [x] create_table.php: Replace PDO usages
- [x] admin/index.php: Replace PDO usages
- [x] admin/profil/index.php: Replace PDO usages
- [x] admin/booking/proses.php: Replace PDO usages
- [x] admin/booking/booking.php: Replace PDO usages
- [x] admin/booking/bayar.php: Replace PDO usages
- [x] admin/user/index.php: Replace PDO usages
- [x] admin/header.php: Replace PDO usages
- [x] admin/laporan/index.php: Replace PDO usages
- [x] admin/mobil/edit.php: Replace PDO usages
- [x] admin/mobil/proses.php: Replace PDO usages
- [x] admin/peminjaman/proses.php: Replace PDO usages
- [x] admin/peminjaman/peminjaman.php: Replace PDO usages
- [x] admin/refund/index.php: Replace PDO usages
- [x] admin/refund/proses.php: Replace PDO usages
- [x] admin/supir/edit.php: Replace PDO usages
- [x] admin/supir/proses.php: Replace PDO usages
- [x] admin/mobil/mobil.php: Replace PDO usages
- [x] user/transaksi.php: Replace PDO usages
- [x] supir.php: Replace PDO usages
- [x] mobil.php: Replace PDO usages
- [x] index.php: Replace PDO usages
- [x] blog.php: Replace PDO usages
- [x] admin/supir/supir.php: Replace PDO usages
- [x] booking.php: Replace PDO usages
- [x] bayar.php: Replace PDO usages
- [x] history.php: Replace PDO usages

## Step 4: Ensure session management and logic intact

- [x] Verify all redirects and session updates work
- [x] Check business logic for login, booking, refunds, etc.

## Step 5: Test the application

- [x] Run locally via XAMPP
- [x] Test login, register, booking, payment, profile update, password change, refund
- [x] Check for mysqli errors
- [x] Debug and fix any issues
