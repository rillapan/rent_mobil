<?php
require '../../koneksi/koneksi.php';
session_start();

if (empty($_SESSION['USER']) || $_SESSION['USER']['level'] !== 'admin') {
    echo '<script>alert("Akses ditolak. Silakan login sebagai admin.");window.location="../index.php";</script>';
    exit();
}

$admin_id = $_SESSION['USER']['id_login'];

if (isset($_GET['id']) && $_GET['id'] === 'approve_refund' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $refund_id = isset($_POST['refund_id']) ? (int)$_POST['refund_id'] : 0;
    $nominal_refund = isset($_POST['nominal_refund']) ? (float)$_POST['nominal_refund'] : 0;
    $catatan_admin = isset($_POST['catatan_admin']) && $_POST['catatan_admin'] !== '' ? trim($_POST['catatan_admin']) : null;
    $kode_booking = $_POST['kode_booking'] ?? '';

    if ($refund_id <= 0 || $nominal_refund <= 0) {
        header('Location: index.php?status=refunderror');
        exit();
    }

    // Ambil data refund beserta booking dan data pelanggan
    $stmt_refund = mysqli_prepare($koneksi, "SELECT 
        rr.*, 
        b.id_login AS booking_user, 
        b.id_booking, 
        b.kode_booking, 
        b.total_harga, 
        b.nama AS nama_booking,
        l.nama_pengguna, 
        l.no_hp, 
        l.email
        FROM refund_requests rr
        JOIN booking b ON rr.id_booking = b.id_booking
        LEFT JOIN login l ON b.id_login = l.id_login
        WHERE rr.id = ?");
    
    if (!$stmt_refund) {
        error_log("Prepare failed: " . mysqli_error($koneksi));
        header('Location: index.php?status=refunderror');
        exit();
    }
    
    mysqli_stmt_bind_param($stmt_refund, "i", $refund_id);
    mysqli_stmt_execute($stmt_refund);
    $result_stmt_refund = mysqli_stmt_get_result($stmt_refund);
    $refund = mysqli_fetch_assoc($result_stmt_refund);
    mysqli_stmt_close($stmt_refund);

    if (!$refund) {
        error_log("Refund data not found for refund_id: $refund_id");
        header('Location: index.php?status=refunderror');
        exit();
    }

    $status_refund = 'Refund Diterima / Uang Dikembalikan';

    // Update data refund - nominal_refund harus DECIMAL, bukan string
    $stmt_update = mysqli_prepare($koneksi, "UPDATE refund_requests
        SET status = ?, nominal_refund = ?, catatan_admin = ?, processed_at = NOW(), admin_id = ?
        WHERE id = ?");

    if (!$stmt_update) {
        error_log("Prepare update failed: " . mysqli_error($koneksi));
        header('Location: index.php?status=refunderror');
        exit();
    }

    // Type hint: s=string, d=double, s=string, i=integer, i=integer
    mysqli_stmt_bind_param($stmt_update, "sdssi", $status_refund, $nominal_refund, $catatan_admin, $admin_id, $refund_id);

    if (!mysqli_stmt_execute($stmt_update)) {
        error_log("Execute update failed: " . mysqli_stmt_error($stmt_update));
        mysqli_stmt_close($stmt_update);
        header('Location: index.php?status=refunderror');
        exit();
    }
    mysqli_stmt_close($stmt_update);

    // Update status booking
    $stmt_update_booking = mysqli_prepare($koneksi, "UPDATE booking SET konfirmasi_pembayaran = ? WHERE id_booking = ?");
    mysqli_stmt_bind_param($stmt_update_booking, "si", $status_refund, $refund['id_booking']);
    mysqli_stmt_execute($stmt_update_booking);
    mysqli_stmt_close($stmt_update_booking);

    // Ambil data mobil dan plat dari booking untuk mengembalikan status ke 'Tersedia'
    $stmt_booking_data = mysqli_prepare($koneksi, "SELECT id_mobil, id_plat FROM booking WHERE id_booking = ?");
    mysqli_stmt_bind_param($stmt_booking_data, "i", $refund['id_booking']);
    mysqli_stmt_execute($stmt_booking_data);
    $result_booking_data = mysqli_stmt_get_result($stmt_booking_data);
    $booking_data = mysqli_fetch_assoc($result_booking_data);
    mysqli_stmt_close($stmt_booking_data);

    if ($booking_data) {
        $id_mobil = $booking_data['id_mobil'];
        $id_plat = $booking_data['id_plat'];

        // Update status plat kembali ke 'Tersedia' jika ada plat yang digunakan
        if (!empty($id_plat)) {
            $sql_update_plat = "UPDATE mobil_plat SET status_plat = 'Tersedia' WHERE id_plat = ?";
            $stmt_update_plat = mysqli_prepare($koneksi, $sql_update_plat);
            mysqli_stmt_bind_param($stmt_update_plat, "i", $id_plat);
            mysqli_stmt_execute($stmt_update_plat);
            mysqli_stmt_close($stmt_update_plat);
        }

        // Hitung ulang status mobil berdasarkan ketersediaan plat
        $sql_count_plat = "SELECT COUNT(*) as available_count FROM mobil_plat WHERE id_mobil = ? AND status_plat = 'Tersedia'";
        $stmt_count_plat = mysqli_prepare($koneksi, $sql_count_plat);
        mysqli_stmt_bind_param($stmt_count_plat, "i", $id_mobil);
        mysqli_stmt_execute($stmt_count_plat);
        $result_count = mysqli_stmt_get_result($stmt_count_plat);
        $count_data = mysqli_fetch_assoc($result_count);
        $available_count = $count_data['available_count'];
        mysqli_stmt_close($stmt_count_plat);

        // Update status mobil: jika ada plat tersedia, set 'Tersedia', else 'Tidak Tersedia'
        $new_status = $available_count > 0 ? 'Tersedia' : 'Tidak Tersedia';
        $sql_update_mobil_status = "UPDATE mobil SET status = ? WHERE id_mobil = ?";
        $stmt_update_mobil = mysqli_prepare($koneksi, $sql_update_mobil_status);
        mysqli_stmt_bind_param($stmt_update_mobil, "si", $new_status, $id_mobil);
        mysqli_stmt_execute($stmt_update_mobil);
        mysqli_stmt_close($stmt_update_mobil);
    }

    // Update status supir kembali ke 'Tersedia' jika ada supir yang digunakan
    $stmt_supir_data = mysqli_prepare($koneksi, "SELECT id_supir FROM booking WHERE id_booking = ?");
    mysqli_stmt_bind_param($stmt_supir_data, "i", $refund['id_booking']);
    mysqli_stmt_execute($stmt_supir_data);
    $result_supir_data = mysqli_stmt_get_result($stmt_supir_data);
    $supir_data = mysqli_fetch_assoc($result_supir_data);
    mysqli_stmt_close($stmt_supir_data);

    if (!empty($supir_data['id_supir'])) {
        $sql_update_supir = "UPDATE supir SET status = 'Tersedia' WHERE id_supir = ?";
        $stmt_update_supir = mysqli_prepare($koneksi, $sql_update_supir);
        mysqli_stmt_bind_param($stmt_update_supir, "i", $supir_data['id_supir']);
        mysqli_stmt_execute($stmt_update_supir);
        mysqli_stmt_close($stmt_update_supir);
    }

    // Kirim notifikasi ke pelanggan
    // Ambil total pembayaran dari refund_requests, jika tidak ada gunakan total_harga dari booking
    $total_pembayaran = ((float)$refund['total_pembayaran'] > 0) ? (float)$refund['total_pembayaran'] : (float)$refund['total_harga'];
    $total_formatted = 'Rp ' . number_format($total_pembayaran, 0, ',', '.');
    $nominal_refund_formatted = 'Rp ' . number_format($nominal_refund, 0, ',', '.');

    // Nama customer: prioritas dari nama_booking, kemudian nama_pelanggan dari refund_requests, kemudian nama_pengguna dari login
    $nama_customer = !empty($refund['nama_booking']) ? $refund['nama_booking'] : (!empty($refund['nama_pelanggan']) ? $refund['nama_pelanggan'] : ($refund['nama_pengguna'] ?: 'Pelanggan'));

    $link_detail = '../notifikasi.php';

    // Build notification message dynamically to handle conditionals
    $catatan_html = '';
    if (!empty($catatan_admin)) {
        $catatan_html = "<p style=\"margin: 5px 0;\"><strong>Catatan Admin :</strong> " . htmlspecialchars($catatan_admin) . "</p>";
    }

    $pesan_notifikasi = <<<HTML
<div style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h4 style="color: #2c3e50; margin-bottom: 10px;">
        <i class="fas fa-hand-holding-usd" style="color: #28a745;"></i> &nbsp; <strong>Refund Telah Diproses</strong>
    </h4>
    <p style="margin-bottom: 20px;">Halo $nama_customer, permintaan refund Anda telah disetujui. Uang Anda telah dikembalikan ke rekening tujuan.</p>
    <div style="background-color: #f8f9fa; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px;">
        <h5 style="color: #2c3e50; margin-top: 0; margin-bottom: 10px;">
            <i class="fas fa-info-circle" style="color: #28a745;"></i> <strong style="color: #333;">Detail Refund</strong>
        </h5>
        <p style="margin: 5px 0;"><strong>Kode Booking :</strong> {$refund['kode_booking']}</p>
        <p style="margin: 5px 0;"><strong>Total Pembayaran :</strong> $total_formatted</p>
        <p style="margin: 5px 0;"><strong>Nominal Refund :</strong> $nominal_refund_formatted</p>
        <p style="margin: 5px 0;"><strong>Alasan Refund :</strong> {$refund['alasan_refund']}</p>
        <p style="margin: 5px 0;"><strong>Status Refund :</strong> {$status_refund}</p>
        $catatan_html
    </div>
    <a href="$link_detail" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 10px;">
        <i class="fas fa-bell"></i> &nbsp; Lihat Notifikasi
    </a>
    <p style="color: #666; font-size: 0.9em; margin-top: 15px;">Terima kasih telah bersabar menunggu proses refund. Jika masih ada pertanyaan, silakan hubungi customer support kami.</p>
</div>
HTML;

    // Pastikan id_login ada sebelum insert notifikasi
    if (!empty($refund['booking_user'])) {
        $status_baca = 0; // Variabel untuk status_baca, tidak bisa langsung pass 0
        $stmt_notif = mysqli_prepare($koneksi, "INSERT INTO notifikasi (id_login, id_booking, pesan, status_baca) VALUES (?, ?, ?, ?)");
        
        if (!$stmt_notif) {
            error_log("Prepare notifikasi failed: " . mysqli_error($koneksi));
        } else {
            mysqli_stmt_bind_param($stmt_notif, "iisi", $refund['booking_user'], $refund['id_booking'], $pesan_notifikasi, $status_baca);
            
            if (!mysqli_stmt_execute($stmt_notif)) {
                error_log("Execute notifikasi failed: " . mysqli_stmt_error($stmt_notif));
            } else {
                error_log("Notifikasi refund berhasil dikirim ke user ID: " . $refund['booking_user']);
            }
            mysqli_stmt_close($stmt_notif);
        }
    } else {
        error_log("Warning: booking_user tidak ditemukan untuk refund_id: $refund_id");
    }

    // Kirim email ke customer jika ada email
    if (!empty($refund['email'])) {
        $subject = "Notifikasi Refund - Kode Booking: " . $refund['kode_booking'];
        $message = "<html><body>";
        $message .= "<h2>Refund Telah Diproses</h2>";
        $message .= "<p>Halo " . htmlspecialchars($nama_customer) . ",</p>";
        $message .= "<p>Permintaan refund Anda telah disetujui dan uang telah dikembalikan ke rekening Anda.</p>";
        $message .= "<p><strong>Detail Refund:</strong></p>";
        $message .= "<ul>";
        $message .= "<li>Kode Booking: " . $refund['kode_booking'] . "</li>";
        $message .= "<li>Total Pembayaran: $total_formatted</li>";
        $message .= "<li>Nominal Refund: $nominal_refund_formatted</li>";
        $message .= "</ul>";
        $message .= "<p>Terima kasih.</p>";
        $message .= "</body></html>";
        
        // Set sendmail_from untuk Windows (XAMPP) sebelum membuat headers
        $from_email = 'no-reply@rentalmobil.com';
        $from_name  = 'Rental Mobil';
        $reply_to   = 'support@rentalmobil.com';
        
        ini_set('sendmail_from', $from_email);
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$from_name} <{$from_email}>\r\n";
        $headers .= "Reply-To: {$reply_to}\r\n";
        $headers .= "Return-Path: {$from_email}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        
        $additional_parameters = "-f{$from_email}";
        
        $mail_sent = @mail($refund['email'], $subject, $message, $headers, $additional_parameters);
        if (!$mail_sent) {
            error_log("mail() failed to send refund notification to {$refund['email']}");
        }
    }

    header('Location: index.php?status=refundapproved');
    exit();
}

if (isset($_GET['id']) && $_GET['id'] === 'reject_refund' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $refund_id = isset($_POST['refund_id']) ? (int)$_POST['refund_id'] : 0;
    $alasan_penolakan = isset($_POST['alasan_penolakan']) && $_POST['alasan_penolakan'] !== '' ? trim($_POST['alasan_penolakan']) : '';
    $catatan_admin = isset($_POST['catatan_admin']) && $_POST['catatan_admin'] !== '' ? trim($_POST['catatan_admin']) : null;
    $kode_booking = $_POST['kode_booking'] ?? '';

    if ($refund_id <= 0 || empty($alasan_penolakan)) {
        header('Location: index.php?status=refunderror');
        exit();
    }

    // Ambil data refund beserta booking dan data pelanggan
    $stmt_refund = mysqli_prepare($koneksi, "SELECT 
        rr.*, 
        b.id_login AS booking_user, 
        b.id_booking, 
        b.kode_booking, 
        b.total_harga, 
        b.nama AS nama_booking,
        l.nama_pengguna, 
        l.no_hp, 
        l.email
        FROM refund_requests rr
        JOIN booking b ON rr.id_booking = b.id_booking
        LEFT JOIN login l ON b.id_login = l.id_login
        WHERE rr.id = ?");
    
    if (!$stmt_refund) {
        error_log("Prepare failed: " . mysqli_error($koneksi));
        header('Location: index.php?status=refunderror');
        exit();
    }
    
    mysqli_stmt_bind_param($stmt_refund, "i", $refund_id);
    mysqli_stmt_execute($stmt_refund);
    $result_stmt_refund = mysqli_stmt_get_result($stmt_refund);
    $refund = mysqli_fetch_assoc($result_stmt_refund);
    mysqli_stmt_close($stmt_refund);

    if (!$refund) {
        error_log("Refund data not found for refund_id: $refund_id");
        header('Location: index.php?status=refunderror');
        exit();
    }

    $status_refund = 'Refund Ditolak';

    // Update data refund
    $stmt_update = mysqli_prepare($koneksi, "UPDATE refund_requests
        SET status = ?, alasan_penolakan = ?, catatan_admin = ?, processed_at = NOW(), admin_id = ?
        WHERE id = ?");
    
    if (!$stmt_update) {
        error_log("Prepare update failed: " . mysqli_error($koneksi));
        header('Location: index.php?status=refunderror');
        exit();
    }
    
    mysqli_stmt_bind_param($stmt_update, "sssii", $status_refund, $alasan_penolakan, $catatan_admin, $admin_id, $refund_id);
    
    if (!mysqli_stmt_execute($stmt_update)) {
        error_log("Execute update failed: " . mysqli_stmt_error($stmt_update));
        mysqli_stmt_close($stmt_update);
        header('Location: index.php?status=refunderror');
        exit();
    }
    mysqli_stmt_close($stmt_update);

    // Update status booking
    $stmt_update_booking = mysqli_prepare($koneksi, "UPDATE booking SET konfirmasi_pembayaran = ? WHERE id_booking = ?");
    mysqli_stmt_bind_param($stmt_update_booking, "si", $status_refund, $refund['id_booking']);
    mysqli_stmt_execute($stmt_update_booking);
    mysqli_stmt_close($stmt_update_booking);

    // Kirim notifikasi ke pelanggan
    $total_pembayaran = $refund['total_pembayaran'] ?: $refund['total_harga'];
    $total_formatted = 'Rp ' . number_format((float)$total_pembayaran, 0, ',', '.');

    $pesan_notifikasi = <<<HTML
<div style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h4 style="color: #dc3545; margin-bottom: 10px;">
        <i class="fas fa-times-circle" style="color: #dc3545;"></i> &nbsp; <strong>Refund Ditolak</strong>
    </h4>
    <p style="margin-bottom: 20px;">Maaf, permintaan refund Anda tidak dapat diproses.</p>
    <div style="background-color: #f8f9fa; border-left: 4px solid #dc3545; padding: 15px; margin-bottom: 20px;">
        <h5 style="color: #2c3e50; margin-top: 0; margin-bottom: 10px;">
            <i class="fas fa-info-circle" style="color: #dc3545;"></i> <strong style="color: #333;">Detail Penolakan</strong>
        </h5>
        <p style="margin: 5px 0;"><strong>Kode Booking :</strong> {$refund['kode_booking']}</p>
        <p style="margin: 5px 0;"><strong>Total Pembayaran :</strong> {$total_formatted}</p>
        <p style="margin: 5px 0;"><strong>Alasan Refund :</strong> {$refund['alasan_refund']}</p>
        <p style="margin: 5px 0;"><strong>Alasan Penolakan :</strong> {$alasan_penolakan}</p>
        <?php if (!empty($catatan_admin)) : ?>
        <p style="margin: 5px 0;"><strong>Catatan Admin :</strong> {$catatan_admin}</p>
        <?php endif; ?>
    </div>
    <p style="color: #666; font-size: 0.9em;">Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi customer support kami.</p>
</div>
HTML;

    // Pastikan id_login ada sebelum insert notifikasi
    if (!empty($refund['booking_user'])) {
        $status_baca = 0; // Variabel untuk status_baca, tidak bisa langsung pass 0
        $stmt_notif = mysqli_prepare($koneksi, "INSERT INTO notifikasi (id_login, id_booking, pesan, status_baca) VALUES (?, ?, ?, ?)");
        
        if (!$stmt_notif) {
            error_log("Prepare notifikasi failed: " . mysqli_error($koneksi));
        } else {
            mysqli_stmt_bind_param($stmt_notif, "iisi", $refund['booking_user'], $refund['id_booking'], $pesan_notifikasi, $status_baca);
            
            if (!mysqli_stmt_execute($stmt_notif)) {
                error_log("Execute notifikasi failed: " . mysqli_stmt_error($stmt_notif));
            } else {
                error_log("Notifikasi refund ditolak berhasil dikirim ke user ID: " . $refund['booking_user']);
            }
            mysqli_stmt_close($stmt_notif);
        }
    } else {
        error_log("Warning: booking_user tidak ditemukan untuk refund_id: $refund_id");
    }

    header('Location: index.php?status=refundrejected');
    exit();
}

header('Location: index.php');
exit();
