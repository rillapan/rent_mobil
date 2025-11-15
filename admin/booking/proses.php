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
    
    // Update status booking
    error_log("Attempting to update id_booking: $id_booking to status: $status");
    $update_query = mysqli_prepare($koneksi, "UPDATE booking SET konfirmasi_pembayaran=? WHERE id_booking=?");
    mysqli_stmt_bind_param($update_query, 'si', $status, $id_booking);
    mysqli_stmt_execute($update_query);

    // Jika status adalah 'Pembayaran Ditolak', update status sewa menjadi 'Dibatalkan'
    if ($status == 'Pembayaran Ditolak') {
        $update_status_sewa = mysqli_prepare($koneksi, "UPDATE booking SET status_sewa='Dibatalkan' WHERE id_booking=?");
        mysqli_stmt_bind_param($update_status_sewa, 'i', $id_booking);
        mysqli_stmt_execute($update_status_sewa);
    }

    $affected_rows = mysqli_stmt_affected_rows($update_query);
    if ($affected_rows === 0) { error_log("No rows affected by update for id_booking: $id_booking with status: $status. Check if id_booking exists or status is already the same."); }

    // Ambil data user dan mobil
    $sql = "SELECT booking.*, mobil.merk
            FROM booking
            JOIN mobil ON booking.id_mobil = mobil.id_mobil
            WHERE booking.id_booking=?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_booking);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $booking = mysqli_fetch_assoc($result);
    $user_id = $booking['id_login'];
    $no_wa = $booking['no_tlp'];
    $customer_name = $booking['nama']; // Get customer name
    $merk_mobil = $booking['merk'];
    $kode_booking = $booking['kode_booking'];

    // Tentukan pesan notifikasi
    $pesan = '';
    $title = '';
    $message = '';
    $icon = '';
    $color = '';

    if ($status == 'Pembayaran Diterima') {
        $title = 'Pembayaran Diterima';
        $message = 'Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.';
        $icon = 'fa-check-circle';
        $color = '#2ecc71';
    } elseif ($status == 'Sedang Diproses') {
        $title = 'Sedang Diproses';
        $message = 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.';
        $icon = 'fa-cogs';
        $color = '#f39c12';
    } elseif ($status == 'Pembayaran Ditolak') {
        $title = 'Pembayaran Ditolak';
        $message = 'Maaf, pembayaran Anda ditolak. ' . htmlspecialchars($pesan_penolakan) . ' Silakan hubungi customer support atau lakukan pembayaran ulang dengan benar.';
        $icon = 'fa-times-circle';
        $color = '#e74c3c';

        // Simpan alasan penolakan pembayaran untuk referensi refund
        $koneksi->exec("CREATE TABLE IF NOT EXISTS booking_rejections (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_booking INT NOT NULL,
            kode_booking VARCHAR(255) NOT NULL,
            reason TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_booking (id_booking)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $stmt_penolakan = mysqli_prepare($koneksi, "INSERT INTO booking_rejections (id_booking, kode_booking, reason, created_at)
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE reason = VALUES(reason), created_at = NOW()");
        mysqli_stmt_bind_param($stmt_penolakan, 'iss', $id_booking, $kode_booking, $pesan_penolakan);
        mysqli_stmt_execute($stmt_penolakan);
    }

    $link_detail = $url . 'bayar.php?id=' . $kode_booking;

    $pesan = <<<HTML
<div style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h4 style="color: #2c3e50; margin-bottom: 10px;">
        <i class="fas $icon" style="color: $color;"></i> &nbsp; <strong>$title</strong>
    </h4>
    <p style="margin-bottom: 20px;">$message</p>
    
    <div style="background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;">
        <h5 style="color: #2c3e50; margin-top: 0; margin-bottom: 10px;">
            <i class="fas fa-receipt" style="color: #FF6B35;"></i> <strong style="color: #333;">Identitas Pesanan</strong>
        </h5>
        <p style="margin: 5px 0;"><strong>No Booking :</strong> $kode_booking</p>
        <p style="margin: 5px 0;"><strong>Nama :</strong> $customer_name</p>
        <p style="margin: 5px 0;"><strong>Mobil :</strong> $merk_mobil</p>
    </div>
    
    <a href="$link_detail" style="display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;">
        <i class="fas fa-eye"></i> &nbsp; Lihat Detail Pesanan
    </a>
</div>
HTML;

    // Simpan notifikasi ke database
    // Untuk Pembayaran Ditolak, simpan juga pesan penolakan dan status khusus
    $status_baca = 0;
    $insert_query = mysqli_prepare($koneksi, "INSERT INTO notifikasi (id_login, id_booking, pesan, status_baca) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($insert_query, 'iisi', $user_id, $id_booking, $pesan, $status_baca);
    mysqli_stmt_execute($insert_query);

    // Kirim WhatsApp (gunakan API WhatsApp Gateway, contoh: https://wa.me/?phone=)
    // Ganti dengan API WhatsApp Gateway yang Anda gunakan
    $pesan_wa = urlencode($pesan);
    $no_wa = preg_replace('/[^0-9]/', '', $no_wa); // pastikan hanya angka
    // Contoh: header("Location: https://wa.me/62$no_wa?text=$pesan_wa");
    // Atau gunakan CURL ke API WhatsApp Gateway Anda di sini

    // Redirect dengan notifikasi yang lebih menarik
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
            animation: fadeIn 0.3s ease;
        }
        
        .notification-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 90%;
            max-width: 450px;
            overflow: hidden;
            animation: slideUp 0.4s ease;
        }
        
        .notification-header {
            padding: 20px;
            text-align: center;
            color: white;
        }
        
        /* Tema warna sesuai permintaan */
        .notification-header.completed {
            background: #2196F3; /* Biru untuk selesai */
        }
        
        .notification-header.success {
            background: #4CAF50; /* Hijau untuk pembayaran diterima */
        }
        
        .notification-header.processing {
            background: #FFC107; /* Kuning untuk diproses */
            color: #333; /* Teks gelap untuk kontras dengan latar kuning */
        }
        
        .notification-header.pending {
            background: #F44336; /* Merah untuk belum dibayar */
        }
        
        .notification-body {
            padding: 25px 20px;
            text-align: center;
        }
        
        .notification-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }
        
        .notification-icon.completed {
            color: #2196F3; /* Biru */
        }
        
        .notification-icon.success {
            color: #4CAF50; /* Hijau */
        }
        
        .notification-icon.processing {
            color: #FFC107; /* Kuning */
        }
        
        .notification-icon.pending {
            color: #F44336; /* Merah */
        }
        
        .notification-title {
            font-size: 22px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .notification-message {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .notification-button {
            display: inline-block;
            padding: 12px 25px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        /* Warna tombol sesuai dengan status */
        .notification-button.completed {
            background: #2196F3;
        }
        
        .notification-button.success {
            background: #4CAF50;
        }
        
        .notification-button.processing {
            background: #FFC107;
            color: #333;
        }
        
        .notification-button.pending {
            background: #F44336;
        }
        
        .notification-button.completed:hover {
            background: #0b7dda;
        }
        
        .notification-button.success:hover {
            background: #388E3C;
        }
        
        .notification-button.processing:hover {
            background: #ffa000;
        }
        
        .notification-button.pending:hover {
            background: #d32f2f;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Animasi putar untuk status diproses */
        .fa-spin {
            animation: fa-spin 2s infinite linear;
        }
        
        @keyframes fa-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="notification-overlay">
        <div class="notification-box">
            <?php
            // Tentukan kelas CSS berdasarkan status
            $headerClass = '';
            $iconClass = '';
            $buttonClass = '';
            $icon = '';
            
            if ($status == 'Pembayaran Diterima') {
                $headerClass = 'success';
                $iconClass = 'success';
                $buttonClass = 'success';
                $icon = '<i class="fas fa-check-circle"></i>';
            } elseif ($status == 'Sedang Diproses') {
                $headerClass = 'processing';
                $iconClass = 'processing';
                $buttonClass = 'processing';
                $icon = '<i class="fas fa-cog fa-spin"></i>';
            } elseif ($status == 'Pembayaran Ditolak') {
                $headerClass = 'pending';
                $iconClass = 'pending';
                $buttonClass = 'pending';
                $icon = '<i class="fas fa-times-circle"></i>';
            } else {
                // Default untuk status selesai atau lainnya
                $headerClass = 'completed';
                $iconClass = 'completed';
                $buttonClass = 'completed';
                $icon = '<i class="fas fa-check-circle"></i>';
            }
            ?>
            
            <div class="notification-header <?php echo $headerClass; ?>">
                <h2><?php echo $icon; ?> Berhasil!</h2>
            </div>
            <div class="notification-body">
                <div class="notification-icon <?php echo $iconClass; ?>">
                    <?php echo $icon; ?>
                </div>
                <h3 class="notification-title">Status Berhasil Diubah</h3>
                <p class="notification-message">Notifikasi telah dikirim kepada <strong><?php echo htmlspecialchars($customer_name); ?></strong></p>
                <p class="notification-message">Status: <strong><?php echo $status; ?></strong></p>
                <button class="notification-button <?php echo $buttonClass; ?>" onclick="window.location='bayar.php?id=<?php echo $booking['kode_booking']; ?>'">Lanjutkan</button>
            </div>
        </div>
    </div>
    <script>
        // Redirect otomatis setelah 3 detik
        setTimeout(function() {
            window.location = "bayar.php?id=<?php echo $booking['kode_booking']; ?>";
        }, 3000);
    </script>
</body>
</html>
<?php  exit();
}
?>