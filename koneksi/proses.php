<?php
session_start();
 require 'koneksi.php';


//fungsi untuk login
if($_GET['id'] == 'login'){ // Proses login
    $user = $_POST['user']; // Mengambil data username dari form login
    $pass = $_POST['pass'];  // Mengambil data password dari form login
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM login WHERE username = ? AND password = md5(?)");
    mysqli_stmt_bind_param($stmt, "ss", $user, $pass); // Mengikat parameter, fungsi "ss" untuk dua string
    mysqli_stmt_execute($stmt);  // Menjalankan query
    $result_stmt = mysqli_stmt_get_result($stmt); // Mendapatkan hasil query
    $result = []; // Inisialisasi array hasil
    while ($row = mysqli_fetch_assoc($result_stmt)) { // Mengambil data hasil query
        $result[] = $row; // Menyimpan data ke array hasil
    }
    mysqli_stmt_close($stmt); // Menutup statement

    $hitung = count($result); // Menghitung jumlah hasil

    if($hitung > 0){  // Jika user ditemukan
        $hasil = $result[0]; // Ambil data user pertama
        $_SESSION['USER'] = $hasil; // Login sukses, simpan data user ke session
        if($_SESSION['USER']['level'] == 'admin') // Jika user admin
        {
            header('Location: ../admin/index.php?status=loginsuccess');// Redirect ke halaman admin
        }else
        {
            header('Location: ../blog.php?status=loginsuccess');// Redirect ke halaman utama
        }
    }
    else                {
        header('Location: ../index.php?status=loginfailed'); // Login gagal, redirect ke halaman login
    }

}

if($_GET['id'] == 'daftar') // Proses registrasi  , from registrasi akan dismpan ke databse tabel login
{
    // Mengambil data dari form registrasi
    $nama = $_POST['nama']; // Mengambil data nama dari form registrasi
    $user = $_POST['user']; // Mengambil data username dari form registrasi
    $pass = md5($_POST['pass']); // Mengambil data password dari form registrasi dan mengenkripsinya dengan md5
    $level = 'pengguna'; // Level user default adalah 'pengguna'
    $no_hp = $_POST['no_hp']; // Mengambil data no_hp dari form registrasi
    $email = $_POST['email']; // Mengambil data email dari form registrasi

    // Cek apakah username atau email sudah digunakan
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM login WHERE username = ? OR email = ?"); // Cek apakah username atau email sudah digunakan
    mysqli_stmt_bind_param($stmt, "ss", $user, $email); // Mengikat parameter
    mysqli_stmt_execute($stmt);  // Menjalankan query
    $result_stmt = mysqli_stmt_get_result($stmt);  // Mendapatkan hasil query
    $result = []; // Inisialisasi array hasil
    while ($row = mysqli_fetch_assoc($result_stmt)) { // Mengambil data hasil query
        $result[] = $row; // Menyimpan data ke array hasil
    }
    mysqli_stmt_close($stmt); // Menutup statement
    $hitung = count($result);  // Menghitung jumlah hasil

    if($hitung > 0)  // Jika username atau email sudah digunakan
    {
        header('Location: ../register.php?status=registerfailed');
    }
    else  // Jika username dan email belum digunakan
    {
        $sql = "INSERT INTO `login`(`nama_pengguna`, `username`, `password`, `level`, `no_hp`, `email`)
                 VALUES (?,?,?,?,?,?)"; // Query untuk memasukkan data user baru
        $stmt_insert = mysqli_prepare($koneksi, $sql); // Mempersiapkan statement
        mysqli_stmt_bind_param($stmt_insert, "ssssss", $nama, $user, $pass, $level, $no_hp, $email); // Mengikat parameter
        mysqli_stmt_execute($stmt_insert); // Menjalankan query
        mysqli_stmt_close($stmt_insert);  // Menutup statement
        header('Location: ../register.php?status=registersuccess'); // Redirect ke halaman register dengan status sukses
    }
}
  
if($_GET['id'] == 'booking')
{
    mysqli_autocommit($koneksi, false); // Start transaction
    $commit = true;

    $id_mobil = $_POST['id_mobil'];
    $id_plat_mobil = null;

    if (isset($_POST['id_plat_mobil'])) {
        if ($_POST['id_plat_mobil'] == 'otomatis') {
            // Ambil plat tersedia pertama secara acak dan kunci barisnya
            $sql_plat = "SELECT id_plat FROM mobil_plat WHERE id_mobil = ? AND status_plat = 'Tersedia' ORDER BY RAND() LIMIT 1 FOR UPDATE";
            $stmt_plat = mysqli_prepare($koneksi, $sql_plat);
            mysqli_stmt_bind_param($stmt_plat, "i", $id_mobil);
            mysqli_stmt_execute($stmt_plat);
            $result_plat = mysqli_stmt_get_result($stmt_plat);
            if ($row = mysqli_fetch_assoc($result_plat)) {
                $id_plat_mobil = $row['id_plat'];
            }
            mysqli_stmt_close($stmt_plat);
        } else {
            // Kunci baris plat yang dipilih
            $sql_plat = "SELECT id_plat FROM mobil_plat WHERE id_plat = ? AND status_plat = 'Tersedia' FOR UPDATE";
            $stmt_plat = mysqli_prepare($koneksi, $sql_plat);
            mysqli_stmt_bind_param($stmt_plat, "i", $_POST['id_plat_mobil']);
            mysqli_stmt_execute($stmt_plat);
            $result_plat = mysqli_stmt_get_result($stmt_plat);
            if ($row = mysqli_fetch_assoc($result_plat)) {
                $id_plat_mobil = $row['id_plat'];
            }
            mysqli_stmt_close($stmt_plat);
        }
    }

    if (is_null($id_plat_mobil)) {
        $commit = false;
        header('Location: ../booking.php?id='.$id_mobil.'&status=plat_tidak_tersedia');
    } else {
        // Update status plat menjadi 'Dipesan'
        $sql_update_plat = "UPDATE mobil_plat SET status_plat = 'Dipesan' WHERE id_plat = ?";
        $stmt_update_plat = mysqli_prepare($koneksi, $sql_update_plat);
        mysqli_stmt_bind_param($stmt_update_plat, "i", $id_plat_mobil);
        if (!mysqli_stmt_execute($stmt_update_plat)) {
            $commit = false;
        }
        mysqli_stmt_close($stmt_update_plat);

        if ($commit) {
            $harga_mobil = $_POST['harga_mobil'];
            $harga_supir = $_POST['harga_supir'];
            $lama_sewa = $_POST['lama_sewa'];
            $total = ($harga_mobil + $harga_supir) * $lama_sewa;
            $unik  = random_int(100,999);
            $total_harga = $total + $unik;

            $kode_booking = time();
            $id_login = $_POST['id_login'];
            $ktp = $_POST['ktp'];
            $nama = $_POST['nama'];
            $alamat = $_POST['alamat'];
            $no_tlp = $_POST['no_tlp'];
            $tanggal = $_POST['tanggal'];
            $konfirmasi_pembayaran = "Belum Bayar";
            $tgl_input = date('Y-m-d');
            $id_supir = $_POST['id_supir'] ?: null;

            $sql = "INSERT INTO booking (kode_booking, id_login, id_mobil, id_plat, ktp, nama, alamat, no_tlp, tanggal, lama_sewa, total_harga, konfirmasi_pembayaran, tgl_input, id_supir)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "iiiissssssisss", $kode_booking, $id_login, $id_mobil, $id_plat_mobil, $ktp, $nama, $alamat, $no_tlp, $tanggal, $lama_sewa, $total_harga, $konfirmasi_pembayaran, $tgl_input, $id_supir);
            
            if (!mysqli_stmt_execute($stmt)) {
                $commit = false;
            }
            mysqli_stmt_close($stmt);

            if ($commit && !empty($_POST['id_supir'])) {
                $sql_update_supir = "UPDATE supir SET status = 'Sedang Digunakan' WHERE id_supir = ?";
                $stmt_update_supir = mysqli_prepare($koneksi, $sql_update_supir);
                mysqli_stmt_bind_param($stmt_update_supir, "i", $_POST['id_supir']);
                if (!mysqli_stmt_execute($stmt_update_supir)) {
                    $commit = false;
                }
                mysqli_stmt_close($stmt_update_supir);
            }
        }

        if ($commit) {
            mysqli_commit($koneksi);
            header('Location: ../bayar.php?id='.$kode_booking.'&status=bookingsuccess');
        } else {
            mysqli_rollback($koneksi);
            header('Location: ../booking.php?id='.$id_mobil.'&status=bookinggagal');
        }
    }
    mysqli_autocommit($koneksi, true); // End transaction
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
    $alasan_penolakan = '-';
    $stmt_penolakan = mysqli_prepare($koneksi, "SELECT reason FROM booking_rejections WHERE id_booking = ?");
    mysqli_stmt_bind_param($stmt_penolakan, "i", $booking['id_booking']);
    mysqli_stmt_execute($stmt_penolakan);
    $result_stmt = mysqli_stmt_get_result($stmt_penolakan);
    $data_penolakan = mysqli_fetch_assoc($result_stmt);
    if ($data_penolakan) {
        $alasan_penolakan = $data_penolakan['reason'];
    }
    mysqli_stmt_close($stmt_penolakan);

    // Ambil informasi pengguna
    $nama_pengguna = $booking['nama'] ?? '';
    $no_hp_user = $booking['no_tlp'] ?? '';
    $email_user = '';
    $stmt_user = mysqli_prepare($koneksi, "SELECT nama_pengguna, no_hp, email FROM login WHERE id_login = ?");
    mysqli_stmt_bind_param($stmt_user, "i", $id_login);
    mysqli_stmt_execute($stmt_user);
    $result_stmt = mysqli_stmt_get_result($stmt_user);
    $user_data = mysqli_fetch_assoc($result_stmt);
    if ($user_data) {
        $nama_pengguna = $user_data['nama_pengguna'] ?: $nama_pengguna;
        $no_hp_user = $user_data['no_hp'] ?: $no_hp_user;
        $email_user = $user_data['email'] ?? '';
    }
    mysqli_stmt_close($stmt_user);

    if (empty($nama_pengguna)) {
        $nama_pengguna = 'Pelanggan';
    }

    // Create table refund_requests jika belum ada
    $create_table_query = "CREATE TABLE IF NOT EXISTS refund_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_booking INT NOT NULL,
        kode_booking VARCHAR(255) NOT NULL,
        id_login INT NOT NULL,
        nama_pelanggan VARCHAR(255) NOT NULL,
        no_hp VARCHAR(50),
        email_pelanggan VARCHAR(255),
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    mysqli_query($koneksi, $create_table_query);

    // Pastikan kolom yang dipakai ada di database (jika tabel sudah ada versi lama)
    $col_check = mysqli_query($koneksi, "SHOW COLUMNS FROM refund_requests LIKE 'email_pelanggan'");
    if ($col_check && mysqli_num_rows($col_check) == 0) {
        // tambahkan kolom jika belum ada
        mysqli_query($koneksi, "ALTER TABLE refund_requests ADD COLUMN email_pelanggan VARCHAR(255) NULL AFTER no_hp");
    }

    // Siapkan semua variable sebagai variabel (penting untuk bind_param)
    $param_id_booking = (int)$booking['id_booking'];
    $param_kode_booking = $kode_booking;
    $param_id_login = (int)$booking['id_login'];
    $param_nama_pelanggan = $nama_pengguna;
    $param_no_hp = $no_hp_user;
    $param_email = $email_user;
    $param_metode = 'Transfer Bank';
    $param_alasan_penolakan = $alasan_penolakan;
    $param_alasan_refund = $alasan_refund;
    $param_no_rek = $no_rekening_refund;
    $param_nama_rek = $nama_rekening_refund;
    $param_status = 'Menunggu Refund';
    $param_total = (float)$booking['total_harga'];
    $param_tanggal = null;
    if (!empty($booking['tanggal'])) {
        $parsed = date('Y-m-d', strtotime($booking['tanggal']));
        $param_tanggal = $parsed ?: null;
    }

    // Prepare statement insert/update refund_requests
    $stmt_refund = mysqli_prepare($koneksi, "INSERT INTO refund_requests
        (id_booking, kode_booking, id_login, nama_pelanggan, no_hp, email_pelanggan, metode_pembayaran, alasan_penolakan, alasan_refund, no_rekening_refund, nama_rekening_refund, status, total_pembayaran, tanggal_pesanan)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            nama_pelanggan = VALUES(nama_pelanggan),
            no_hp = VALUES(no_hp),
            email_pelanggan = VALUES(email_pelanggan),
            metode_pembayaran = VALUES(metode_pembayaran),
            alasan_penolakan = VALUES(alasan_penolakan),
            alasan_refund = VALUES(alasan_refund),
            no_rekening_refund = VALUES(no_rekening_refund),
            nama_rekening_refund = VALUES(nama_rekening_refund),
            status = 'Menunggu Refund',
            total_pembayaran = VALUES(total_pembayaran),
            tanggal_pesanan = VALUES(tanggal_pesanan)");

    if (!$stmt_refund) {
        error_log("Prepare refund failed: " . mysqli_error($koneksi));
        header('Location: ../notifikasi.php?status=refundfailed');
        exit();
    }

    // Type string must match 14 params: i (id_booking), s (kode), i (id_login), then 9 strings, then d, then s
    $types = "isisssssssssds"; // i s i + 9*s + d + s
    mysqli_stmt_bind_param($stmt_refund, $types,
        $param_id_booking,
        $param_kode_booking,
        $param_id_login,
        $param_nama_pelanggan,
        $param_no_hp,
        $param_email,
        $param_metode,
        $param_alasan_penolakan,
        $param_alasan_refund,
        $param_no_rek,
        $param_nama_rek,
        $param_status,
        $param_total,
        $param_tanggal
    );

    if (!mysqli_stmt_execute($stmt_refund)) {
        error_log("Refund insert error: " . mysqli_stmt_error($stmt_refund));
        mysqli_stmt_close($stmt_refund);
        header('Location: ../notifikasi.php?status=refundfailed');
        exit();
    }
    mysqli_stmt_close($stmt_refund);

    // Status refund sudah di-track di tabel refund_requests dengan kolom 'status'
    // Tidak perlu update booking_rejections karena tabel tersebut hanya menyimpan alasan penolakan

    header('Location: ../notifikasi.php?status=refundsuccess');
    exit();
} // <-- tutup if($_GET['id'] == 'ajukan_refund')