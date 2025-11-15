<?php
session_start();
 require 'koneksi.php';

if($_GET['id'] == 'login'){

    $user = $_POST['user'];

    $pass = $_POST['pass'];

    $stmt = mysqli_prepare($koneksi, "SELECT * FROM login WHERE username = ? AND password = md5(?)");

    mysqli_stmt_bind_param($stmt, "ss", $user, $pass);

    mysqli_stmt_execute($stmt);

    $result_stmt = mysqli_stmt_get_result($stmt);

    $result = [];

    while ($row = mysqli_fetch_assoc($result_stmt)) {

        $result[] = $row;

    }

    mysqli_stmt_close($stmt);

    $hitung = count($result);

    if($hitung > 0)

    {

        $hasil = $result[0];

        $_SESSION['USER'] = $hasil;

        if($_SESSION['USER']['level'] == 'admin')

        {

            header('Location: ../admin/index.php?status=loginsuccess');

        }

        else

        {

            header('Location: ../blog.php?status=loginsuccess');

        }

    }

    else

    {

        header('Location: ../index.php?status=loginfailed');

    }

}

if($_GET['id'] == 'daftar')

{

    $nama = $_POST['nama'];
    $user = $_POST['user'];
    $pass = md5($_POST['pass']);
    $level = 'pengguna';
    $no_hp = $_POST['no_hp'];

    $stmt = mysqli_prepare($koneksi, "SELECT * FROM login WHERE username = ?");

    mysqli_stmt_bind_param($stmt, "s", $user);

    mysqli_stmt_execute($stmt);

    $result_stmt = mysqli_stmt_get_result($stmt);

    $result = [];

    while ($row = mysqli_fetch_assoc($result_stmt)) {

        $result[] = $row;

    }

    mysqli_stmt_close($stmt);

    $hitung = count($result);

    if($hitung > 0)

    {

        header('Location: ../index.php?status=registerfailed');

    }

    else

    {

        $sql = "INSERT INTO `login`(`nama_pengguna`, `username`, `password`, `level`, `no_hp`)

                 VALUES (?,?,?,?,?)";

        $stmt_insert = mysqli_prepare($koneksi, $sql);

        mysqli_stmt_bind_param($stmt_insert, "sssss", $nama, $user, $pass, $level, $no_hp);

        mysqli_stmt_execute($stmt_insert);

        mysqli_stmt_close($stmt_insert);

        header('Location: ../index.php?status=registersuccess');

    }

}

if($_GET['id'] == 'booking')
{
    $harga_mobil = $_POST['harga_mobil'];
    $harga_supir = $_POST['harga_supir'];
    $lama_sewa = $_POST['lama_sewa'];
    $total = ($harga_mobil + $harga_supir) * $lama_sewa;
    $unik  = random_int(100,999);
    $total_harga = $total + $unik;

    $kode_booking = time();
    $id_login = $_POST['id_login'];
    $id_mobil = $_POST['id_mobil'];
    $ktp = $_POST['ktp'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];
    $tanggal = $_POST['tanggal'];
    $konfirmasi_pembayaran = "Belum Bayar";
    $tgl_input = date('Y-m-d');
    $id_supir = $_POST['id_supir'] ?: null; // ID supir, null jika tidak dipilih

    $sql = "INSERT INTO booking (kode_booking,
    id_login,
    id_mobil,
    ktp,
    nama,
    alamat,
    no_tlp,
    tanggal, lama_sewa, total_harga, konfirmasi_pembayaran, tgl_input, id_supir)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "iiissssssisss", $kode_booking, $id_login, $id_mobil, $ktp, $nama, $alamat, $no_tlp, $tanggal, $lama_sewa, $total_harga, $konfirmasi_pembayaran, $tgl_input, $id_supir);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Update status supir jika dipilih
    if (!empty($_POST['id_supir'])) {
        $sql_update_supir = "UPDATE supir SET status = 'Sedang Digunakan' WHERE id_supir = ?";
        $stmt_update_supir = mysqli_prepare($koneksi, $sql_update_supir);
        mysqli_stmt_bind_param($stmt_update_supir, "i", $_POST['id_supir']);
        mysqli_stmt_execute($stmt_update_supir);
        mysqli_stmt_close($stmt_update_supir);
    }

    header('Location: ../bayar.php?id='.$kode_booking.'&status=bookingsuccess');
}

if($_GET['id'] == 'konfirmasi')
{

    $id_booking = $_POST['id_booking'];
    $payment_method_id = $_POST['payment_method_id'];
    $no_rekening = $_POST['no_rekening'];
    $nama = $_POST['nama'];
    $nominal = $_POST['nominal'];
    $tgl = $_POST['tgl'];

    // Handle file upload
    $payment_proof = '';
    if(isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == 0) {
        $upload_dir = '../assets/image/payment_proofs/';
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
        $file_name = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $upload_dir . $file_name;

        if(move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target_file)) {
            $payment_proof = 'payment_proofs/' . $file_name;
        }
    }

    $sql = "INSERT INTO `pembayaran`(`id_booking`, `no_rekening`, `nama_rekening`, `nominal`, `tanggal`)
    VALUES (?,?,?,?,?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "issss", $id_booking, $no_rekening, $nama, $nominal, $tgl);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $status = 'Sedang Diproses'; // Changed to 'Sedang Diproses'

    // First check if payment_method_id exists and is valid
    if (!empty($payment_method_id)) {
        $check_payment = mysqli_prepare($koneksi, "SELECT id FROM payment_methods WHERE id = ? AND is_active = 1");
        mysqli_stmt_bind_param($check_payment, "i", $payment_method_id);
        mysqli_stmt_execute($check_payment);
        $result_check = mysqli_stmt_get_result($check_payment);
        if (mysqli_num_rows($result_check) == 0) {
            // Invalid payment method, set to NULL
            $payment_method_id = NULL;
        }
        mysqli_stmt_close($check_payment);
    }

    $sql2 = "UPDATE `booking` SET `konfirmasi_pembayaran`=?, `payment_method_id`=?, `payment_proof`=?, `payment_status`='pending' WHERE id_booking=?";
    $stmt2 = mysqli_prepare($koneksi, $sql2);
    mysqli_stmt_bind_param($stmt2, "sisi", $status, $payment_method_id, $payment_proof, $id_booking);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    // Fetch kode_booking for redirect
    $booking_id_for_redirect = $_POST['id_booking'];
    $stmt_kode_booking = mysqli_prepare($koneksi, "SELECT kode_booking FROM booking WHERE id_booking = ?");
    mysqli_stmt_bind_param($stmt_kode_booking, "i", $booking_id_for_redirect);
    mysqli_stmt_execute($stmt_kode_booking);
    $result_stmt = mysqli_stmt_get_result($stmt_kode_booking);
    $result_kode = mysqli_fetch_assoc($result_stmt);
    mysqli_stmt_close($stmt_kode_booking);
    $kode_booking_value = $result_kode['kode_booking'] ?? '';

    header('Location: ../history.php?status=konfirmasisuccess&kode_booking='.$kode_booking_value);
}

if($_GET['id'] == 'update_profil')
{
    $id_login = $_SESSION['USER']['id_login'];
    $nama_pengguna = $_POST['nama_pengguna'];
    $username = $_POST['username'];
    $no_hp = $_POST['no_hp'];

    $sql = "UPDATE login SET nama_pengguna = ?, username = ?, no_hp = ? WHERE id_login = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $nama_pengguna, $username, $no_hp, $id_login);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Update session data
    $_SESSION['USER']['nama_pengguna'] = $_POST['nama_pengguna'];
    $_SESSION['USER']['username'] = $_POST['username'];
    $_SESSION['USER']['no_hp'] = $_POST['no_hp'];

    header('Location: ../profil.php?status=profilesuccess');
}

if($_GET['id'] == 'ubah_password')
{
    $id_login = $_SESSION['USER']['id_login'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Get current user's password from DB
    $sql = "SELECT password FROM login WHERE id_login = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_login);
    mysqli_stmt_execute($stmt);
    $result_stmt = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result_stmt);
    mysqli_stmt_close($stmt);

    if(md5($current_password) == $user['password'])
    {
        if($new_password == $confirm_password)
        {
            $hashed_password = md5($new_password);
            $sql_update = "UPDATE login SET password = ? WHERE id_login = ?";
            $stmt_update = mysqli_prepare($koneksi, $sql_update);
            mysqli_stmt_bind_param($stmt_update, "si", $hashed_password, $id_login);
            mysqli_stmt_execute($stmt_update);
            mysqli_stmt_close($stmt_update);
            header('Location: ../profil.php?status=passwordchanged');
        }
        else
        {
            header('Location: ../profil.php?status=passwordmismatch');
        }
    }
    else
    {
        header('Location: ../profil.php?status=passworderror');
    }
}

// Fungsi untuk mengirim notifikasi WhatsApp menggunakan Fonnte API
function kirim_whatsapp($nomor_hp, $pesan) {
    $token = "YOUR_FONNTE_API_TOKEN"; // Ganti dengan token API Fonnte Anda
    $api_url = "https://api.fonnte.com/send";

    // Pastikan nomor HP dimulai dengan 62 (format Indonesia)
    if (substr($nomor_hp, 0, 1) == '0') {
        $nomor_hp = '62' . substr($nomor_hp, 1);
    }

    $data = [
        'target' => $nomor_hp,
        'message' => $pesan,
        'countryCode' => '62' // Kode negara Indonesia
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Authorization: ' . $token
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if (curl_errno($curl)) {
        error_log("Error sending WhatsApp: " . curl_error($curl));
        return false;
    }

    curl_close($curl);

    if ($http_code == 200) {
        error_log("WhatsApp sent successfully to " . $nomor_hp);
        return true;
    } else {
        error_log("Failed to send WhatsApp to " . $nomor_hp . ". Response: " . $response);
        return false;
    }
}

if($_GET['id'] == 'ajukan_refund')
{
    if(empty($_SESSION['USER'])) {
        header('Location: ../index.php?status=loginfailed');
        exit();
    }

    $kode_booking = $_POST['kode_booking'];
    $alasan_refund = $_POST['alasan_refund'];
    $no_rekening_refund = $_POST['no_rekening_refund'];
    $nama_rekening_refund = $_POST['nama_rekening_refund'];
    $id_login = $_SESSION['USER']['id_login'];

    // Ambil data booking
    $stmt_booking = mysqli_prepare($koneksi, "SELECT * FROM booking WHERE kode_booking = ? AND id_login = ?");
    mysqli_stmt_bind_param($stmt_booking, "si", $kode_booking, $id_login);
    mysqli_stmt_execute($stmt_booking);
    $result_stmt = mysqli_stmt_get_result($stmt_booking);
    $booking = mysqli_fetch_assoc($result_stmt);
    mysqli_stmt_close($stmt_booking);

    if(!$booking) {
        header('Location: ../notifikasi.php?status=refundfailed');
        exit();
    }

    // Ambil informasi penolakan pembayaran (jika ada)
    $stmt_penolakan = mysqli_prepare($koneksi, "SELECT reason FROM booking_rejections WHERE id_booking = ?");
    mysqli_stmt_bind_param($stmt_penolakan, "i", $booking['id_booking']);
    mysqli_stmt_execute($stmt_penolakan);
    $result_stmt = mysqli_stmt_get_result($stmt_penolakan);
    $data_penolakan = mysqli_fetch_assoc($result_stmt);
    mysqli_stmt_close($stmt_penolakan);
    $alasan_penolakan = $data_penolakan['reason'] ?? '-';

    // Ambil informasi pengguna
    $stmt_user = mysqli_prepare($koneksi, "SELECT nama_pengguna, no_hp FROM login WHERE id_login = ?");
    mysqli_stmt_bind_param($stmt_user, "i", $booking['id_login']);
    mysqli_stmt_execute($stmt_user);
    $result_stmt = mysqli_stmt_get_result($stmt_user);
    $user_data = mysqli_fetch_assoc($result_stmt);
    mysqli_stmt_close($stmt_user);
    $nama_pengguna = $user_data['nama_pengguna'] ?? $booking['nama'];
    $no_hp_user = $user_data['no_hp'] ?? $booking['no_tlp'];

    // Simpan atau perbarui data refund request
    $koneksi->exec("CREATE TABLE IF NOT EXISTS refund_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_booking INT NOT NULL,
        kode_booking VARCHAR(255) NOT NULL,
        id_login INT NOT NULL,
        nama_pelanggan VARCHAR(255) NOT NULL,
        no_hp VARCHAR(50),
        metode_pembayaran VARCHAR(100),
        alasan_penolakan TEXT,
        alasan_refund TEXT,
        no_rekening_refund VARCHAR(100),
        nama_rekening_refund VARCHAR(150),
        catatan_admin TEXT,
        status VARCHAR(50) DEFAULT 'Menunggu Refund',
        nominal_refund DECIMAL(15,2) DEFAULT NULL,
        total_pembayaran DECIMAL(15,2) DEFAULT 0,
        tanggal_pesanan DATE DEFAULT NULL,
        processed_at DATETIME DEFAULT NULL,
        admin_id INT DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_refund_booking (id_booking)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $total_pembayaran_decimal = number_format($booking['total_harga'], 2, '.', '');
    $tanggal_pesanan = null;
    if (!empty($booking['tanggal'])) {
        $timestamp_tanggal = strtotime($booking['tanggal']);
        if ($timestamp_tanggal) {
            $tanggal_pesanan = date('Y-m-d', $timestamp_tanggal);
        }
    }

    $stmt_refund = mysqli_prepare($koneksi, "INSERT INTO refund_requests
        (id_booking, kode_booking, id_login, nama_pelanggan, no_hp, metode_pembayaran, alasan_penolakan, alasan_refund, no_rekening_refund, nama_rekening_refund, status, total_pembayaran, tanggal_pesanan)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Menunggu Refund', ?, ?)
        ON DUPLICATE KEY UPDATE
            nama_pelanggan = VALUES(nama_pelanggan),
            no_hp = VALUES(no_hp),
            metode_pembayaran = VALUES(metode_pembayaran),
            alasan_penolakan = VALUES(alasan_penolakan),
            alasan_refund = VALUES(alasan_refund),
            no_rekening_refund = VALUES(no_rekening_refund),
            nama_rekening_refund = VALUES(nama_rekening_refund),
            status = 'Menunggu Refund',
            total_pembayaran = VALUES(total_pembayaran),
            tanggal_pesanan = VALUES(tanggal_pesanan),
            catatan_admin = NULL,
            nominal_refund = NULL,
            processed_at = NULL,
            admin_id = NULL");
    mysqli_stmt_bind_param($stmt_refund, "isisssssssss", $booking['id_booking'], $kode_booking, $booking['id_login'], $nama_pengguna, $no_hp_user, 'Transfer Bank', $alasan_penolakan, $alasan_refund, $no_rekening_refund, $nama_rekening_refund, $total_pembayaran_decimal, $tanggal_pesanan);
    mysqli_stmt_execute($stmt_refund);
    mysqli_stmt_close($stmt_refund);

    // Siapkan data untuk notifikasi admin
    $total_harga_formatted = number_format($booking['total_harga'], 0, ',', '.');
    $pesan_refund = <<<HTML
<div style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h4 style="color: #e74c3c; margin-bottom: 10px;">
        <i class="fas fa-undo" style="color: #e74c3c;"></i> &nbsp; <strong>Permintaan Refund</strong>
    </h4>
    <p style="margin-bottom: 20px;">Pelanggan mengajukan refund untuk booking berikut:</p>
    
    <div style="background-color: #f8f9fa; border-left: 4px solid #e74c3c; padding: 15px; margin-bottom: 20px;">
        <h5 style="color: #2c3e50; margin-top: 0; margin-bottom: 10px;">
            <i class="fas fa-receipt" style="color: #FF6B35;"></i> <strong style="color: #333;">Detail Booking</strong>
        </h5>
        <p style="margin: 5px 0;"><strong>Kode Booking :</strong> {$kode_booking}</p>
        <p style="margin: 5px 0;"><strong>Nama :</strong> {$booking['nama']}</p>
        <p style="margin: 5px 0;"><strong>Total Harga :</strong> Rp {$total_harga_formatted}</p>
        <p style="margin: 5px 0;"><strong>Alasan Penolakan :</strong> {$alasan_penolakan}</p>
        <p style="margin: 5px 0;"><strong>Metode Pembayaran :</strong> Transfer Bank</p>
    </div>
    
    <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px;">
        <h5 style="color: #2c3e50; margin-top: 0; margin-bottom: 10px;">
            <i class="fas fa-info-circle" style="color: #ffc107;"></i> <strong style="color: #333;">Detail Refund</strong>
        </h5>
        <p style="margin: 5px 0;"><strong>Alasan Refund :</strong> {$alasan_refund}</p>
        <p style="margin: 5px 0;"><strong>No. Rekening :</strong> {$no_rekening_refund}</p>
        <p style="margin: 5px 0;"><strong>Nama Pemilik Rekening :</strong> {$nama_rekening_refund}</p>
    </div>
    
    <p style="color: #666; font-size: 0.9em;">Silakan proses refund ini segera.</p>
</div>
HTML;

    // Simpan notifikasi untuk admin (level admin)
    // Ambil semua admin
    $stmt_admin = mysqli_prepare($koneksi, "SELECT id_login FROM login WHERE level = 'admin'");
    mysqli_stmt_execute($stmt_admin);
    $result_stmt = mysqli_stmt_get_result($stmt_admin);
    $admins = [];
    while ($row = mysqli_fetch_assoc($result_stmt)) {
        $admins[] = $row;
    }
    mysqli_stmt_close($stmt_admin);

    foreach($admins as $admin) {
        $stmt_notif = mysqli_prepare($koneksi, "INSERT INTO notifikasi (id_login, id_booking, pesan, status_baca) VALUES (?, ?, ?, 0)");
        mysqli_stmt_bind_param($stmt_notif, "iisi", $admin['id_login'], $booking['id_booking'], $pesan_refund, 0);
        mysqli_stmt_execute($stmt_notif);
        mysqli_stmt_close($stmt_notif);
    }

    // Kirim notifikasi konfirmasi ke user
    $pesan_konfirmasi = <<<HTML
<div style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h4 style="color: #3498db; margin-bottom: 10px;">
        <i class="fas fa-check-circle" style="color: #3498db;"></i> &nbsp; <strong>Permintaan Refund Diterima</strong>
    </h4>
    <p style="margin-bottom: 20px;">Terima kasih! Permintaan refund Anda untuk booking <strong>{$kode_booking}</strong> telah kami terima.</p>
    <p style="margin-bottom: 20px;">Tim admin akan memproses refund Anda dalam 1-3 hari kerja. Kami akan menghubungi Anda melalui notifikasi atau kontak yang terdaftar.</p>
    <p style="color: #666; font-size: 0.9em;">Jika ada pertanyaan, silakan hubungi customer support kami.</p>
</div>
HTML;

    $stmt_notif_user = mysqli_prepare($koneksi, "INSERT INTO notifikasi (id_login, id_booking, pesan, status_baca) VALUES (?, ?, ?, 0)");
    mysqli_stmt_bind_param($stmt_notif_user, "iisi", $id_login, $booking['id_booking'], $pesan_konfirmasi, 0);
    mysqli_stmt_execute($stmt_notif_user);
    mysqli_stmt_close($stmt_notif_user);

    // Update status booking menjadi "Menunggu Refund" atau status khusus
    $status_refund = 'Menunggu Refund';
    $stmt_update = mysqli_prepare($koneksi, "UPDATE booking SET konfirmasi_pembayaran = ? WHERE id_booking = ?");
    mysqli_stmt_bind_param($stmt_update, "si", $status_refund, $booking['id_booking']);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    header('Location: ../notifikasi.php?status=refundsuccess');
}