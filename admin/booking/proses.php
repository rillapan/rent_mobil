<?php
require '../../koneksi/koneksi.php';
session_start();

if(empty($_SESSION['USER'])) {
    echo '<script>alert("login dulu");window.location="index.php"</script>';
    exit();
}

if ($_GET['id'] == 'konfirmasi' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_booking = $_POST['id_booking'];
    $status = $_POST['status'];
    $pesan_penolakan = isset($_POST['pesan_penolakan']) ? trim($_POST['pesan_penolakan']) : '';
    
    // Validasi pesan penolakan jika status adalah Pembayaran Ditolak
    if ($status == 'Pembayaran Ditolak' && empty($pesan_penolakan)) {
        echo '<script>alert("Pesan penolakan wajib diisi untuk status Pembayaran Ditolak.");window.history.back();</script>';
        exit();
    }
    
    // Update status booking - HAPUS status_sewa, hanya update konfirmasi_pembayaran
    $update_query = mysqli_prepare($koneksi, "UPDATE booking SET konfirmasi_pembayaran=? WHERE id_booking=?");
    mysqli_stmt_bind_param($update_query, 'si', $status, $id_booking);
    mysqli_stmt_execute($update_query);
    mysqli_stmt_close($update_query);

    // Jika status adalah 'Pembayaran Diterima' dan ada supir, update status supir
    if ($status == 'Pembayaran Diterima') {
        $sql_supir = "SELECT id_supir FROM booking WHERE id_booking = ?";
        $stmt_supir = mysqli_prepare($koneksi, $sql_supir);
        mysqli_stmt_bind_param($stmt_supir, "i", $id_booking);
        mysqli_stmt_execute($stmt_supir);
        $result_stmt_supir = mysqli_stmt_get_result($stmt_supir);
        $booking_data = mysqli_fetch_assoc($result_stmt_supir);
        mysqli_stmt_close($stmt_supir);

        if (!empty($booking_data['id_supir'])) {
            $sql_update_supir = "UPDATE supir SET status = 'Sedang Digunakan' WHERE id_supir = ?";
            $stmt_update_supir = mysqli_prepare($koneksi, $sql_update_supir);
            mysqli_stmt_bind_param($stmt_update_supir, "i", $booking_data['id_supir']);
            mysqli_stmt_execute($stmt_update_supir);
            mysqli_stmt_close($stmt_update_supir);
        }
    }

    // Ambil data user dan mobil
    $sql = "SELECT booking.*, mobil.merk, login.email, login.nama_pengguna
            FROM booking
            JOIN mobil ON booking.id_mobil = mobil.id_mobil
            JOIN login ON booking.id_login = login.id_login
            WHERE booking.id_booking=?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_booking);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $booking = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$booking) {
        echo '<script>alert("Booking tidak ditemukan");window.history.back();</script>';
        exit();
    }

    $user_id = $booking['id_login'];
    $customer_email = $booking['email'];
    $customer_name = $booking['nama_pengguna'];
    $merk_mobil = $booking['merk'];
    $kode_booking = $booking['kode_booking'];

    // Tentukan pesan notifikasi
    $title = '';
    $message = '';
    $icon = '';
    $headerClass = '';

    if ($status == 'Pembayaran Diterima') {
        $title = 'Pembayaran Diterima';
        $message = 'Yeay! Pembayaran Anda sudah kami terima 🎉. Pesanan Anda akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.';
        $icon = 'fa-check-circle';
        $headerClass = 'success';
    } elseif ($status == 'Sedang Diproses') {
        $title = 'Sedang Diproses';
        $message = 'Pesanan Anda sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.';
        $icon = 'fa-cogs';
        $headerClass = 'processing';
    } elseif ($status == 'Pembayaran Ditolak') {
        $title = 'Pembayaran Ditolak';
        $message = 'Maaf, pembayaran Anda ditolak. ' . htmlspecialchars($pesan_penolakan) . ' Silakan hubungi customer support atau lakukan pembayaran ulang dengan benar.';
        $icon = 'fa-times-circle';
        $headerClass = 'pending';

        // Simpan alasan penolakan pembayaran
        $create_table_query = "CREATE TABLE IF NOT EXISTS booking_rejections (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_booking INT NOT NULL,
            kode_booking VARCHAR(255) NOT NULL,
            reason TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_booking (id_booking)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        mysqli_query($koneksi, $create_table_query);

        $stmt_penolakan = mysqli_prepare($koneksi, "INSERT INTO booking_rejections (id_booking, kode_booking, reason)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE reason = VALUES(reason), created_at = NOW()");
        mysqli_stmt_bind_param($stmt_penolakan, "iss", $id_booking, $kode_booking, $pesan_penolakan);
        mysqli_stmt_execute($stmt_penolakan);
        mysqli_stmt_close($stmt_penolakan);
    }

    $link_detail = 'bayar.php?id=' . $kode_booking;

    $pesan = <<<HTML
<div style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h4 style="color: #2c3e50; margin-bottom: 10px;">
        <i class="fas $icon"></i> &nbsp; <strong>$title</strong>
    </h4>
    <p style="margin-bottom: 20px;">$message</p>
    
    <div style="background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;">
        <h5 style="color: #2c3e50; margin-top: 0; margin-bottom: 10px;">
            <i class="fas fa-receipt"></i> <strong>Identitas Pesanan</strong>
        </h5>
        <p style="margin: 5px 0;"><strong>No Booking :</strong> $kode_booking</p>
        <p style="margin: 5px 0;"><strong>Nama :</strong> $customer_name</p>
        <p style="margin: 5px 0;"><strong>Mobil :</strong> $merk_mobil</p>
    </div>
    
    <a href="$link_detail" style="display: inline-block; padding: 10px 20px; background-color: #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;">
        <i class="fas fa-eye"></i> &nbsp; Lihat Detail Pesanan
    </a>
</div>
HTML;

    // Simpan notifikasi ke database
    $status_baca = 0;
    $insert_query = mysqli_prepare($koneksi, "INSERT INTO notifikasi (id_login, id_booking, pesan, status_baca) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($insert_query, "iisi", $user_id, $id_booking, $pesan, $status_baca);
    mysqli_stmt_execute($insert_query);
    mysqli_stmt_close($insert_query);

    // Kirim email ke customer
    if (!empty($customer_email)) {
        $subject = "Notifikasi: " . $title;
        $email_body = "<html><body>";
        $email_body .= "<h2>$title</h2>";
        $email_body .= "<p>Halo " . htmlspecialchars($customer_name) . ",</p>";
        $email_body .= "<p>$message</p>";
        $email_body .= "<p><strong>Kode Booking:</strong> $kode_booking</p>";
        $email_body .= "</body></html>";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        // Gunakan alamat yang valid untuk From / Reply-To / Return-Path
        $from_email = 'no-reply@rentalmobil.com';
        $from_name  = 'Rental Mobil';
        $reply_to   = 'support@rentalmobil.com';

        $headers .= "From: {$from_name} <{$from_email}>\r\n";
        $headers .= "Reply-To: {$reply_to}\r\n";
        $headers .= "Return-Path: {$from_email}\r\n";

        // Untuk Windows (XAMPP) set sendmail_from agar PHP tidak mengeluarkan warning
        ini_set('sendmail_from', $from_email);

        // Tambahkan parameter -f untuk memastikan Return-Path (beberapa konfigurasi SMTP/Sendmail)
        $additional_parameters = "-f{$from_email}";

        // Gunakan @ untuk menekan warning runtime; logging bisa ditambahkan jika gagal
        if (!@mail($customer_email, $subject, $email_body, $headers, $additional_parameters)) {
            error_log("mail() failed to send booking notification to {$customer_email}. Headers: {$headers}");
        }
    }

    // Tampilkan notifikasi success
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Notifikasi Konfirmasi</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .notification-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.7);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 10000;
            }
            .notification-box {
                background: white;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                width: 90%;
                max-width: 450px;
                overflow: hidden;
            }
            .notification-header {
                padding: 20px;
                text-align: center;
                color: white;
            }
            .notification-header.success { background: #4CAF50; }
            .notification-header.processing { background: #FFC107; color: #333; }
            .notification-header.pending { background: #F44336; }
            .notification-body {
                padding: 25px 20px;
                text-align: center;
            }
            .notification-button {
                display: inline-block;
                padding: 12px 25px;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
                border: none;
                cursor: pointer;
                font-size: 16px;
            }
            .notification-button.success { background: #4CAF50; }
            .notification-button.processing { background: #FFC107; color: #333; }
            .notification-button.pending { background: #F44336; }
        </style>
    </head>
    <body>
        <div class="notification-overlay">
            <div class="notification-box">
                <div class="notification-header <?php echo $headerClass; ?>">
                    <h2><i class="fas <?php echo $icon; ?>"></i> Berhasil!</h2>
                </div>
                <div class="notification-body">
                    <h3><?php echo $title; ?></h3>
                    <p>Notifikasi telah dikirim kepada <strong><?php echo htmlspecialchars($customer_name); ?></strong></p>
                    <button class="notification-button <?php echo $headerClass; ?>" onclick="window.location='bayar.php?id=<?php echo $kode_booking; ?>'">Lanjutkan</button>
                </div>
            </div>
        </div>
        <script>
            setTimeout(function() {
                window.location = "bayar.php?id=<?php echo $kode_booking; ?>";
            }, 3000);
        </script>
    </body>
    </html>
    <?php
    exit();
}
?>