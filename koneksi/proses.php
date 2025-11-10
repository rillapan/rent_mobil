<?php
session_start();
 require 'koneksi.php';

if($_GET['id'] == 'login'){

    $user = $_POST['user'];

    $pass = $_POST['pass'];

    $stmt = $koneksi->prepare("SELECT * FROM login WHERE username = ? AND password = md5(?)");

    $stmt->execute([$user, $pass]);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    $stmt = $koneksi->prepare("SELECT * FROM login WHERE username = ?");

    $stmt->execute([$user]);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $hitung = count($result);

    if($hitung > 0)

    {

        header('Location: ../index.php?status=registerfailed');

    }

    else

    {

        $sql = "INSERT INTO `login`(`nama_pengguna`, `username`, `password`, `level`, `no_hp`)

                 VALUES (?,?,?,?,?)";

        $stmt_insert = $koneksi->prepare($sql);

        $stmt_insert->execute([$nama, $user, $pass, $level, $no_hp]);

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
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$kode_booking, $id_login, $id_mobil, $ktp, $nama, $alamat, $no_tlp, $tanggal, $lama_sewa, $total_harga, $konfirmasi_pembayaran, $tgl_input, $id_supir]);

    // Update status supir jika dipilih
    if (!empty($_POST['id_supir'])) {
        $sql_update_supir = "UPDATE supir SET status = 'Sedang Digunakan' WHERE id_supir = ?";
        $stmt_update_supir = $koneksi->prepare($sql_update_supir);
        $stmt_update_supir->execute([$_POST['id_supir']]);
    }

    header('Location: ../bayar.php?id='.$kode_booking.'&status=bookingsuccess');
}

if($_GET['id'] == 'konfirmasi')
{

    $id_booking = $_POST['id_booking'];
    $no_rekening = $_POST['no_rekening'];
    $nama = $_POST['nama'];
    $nominal = $_POST['nominal'];
    $tgl = $_POST['tgl'];

    $sql = "INSERT INTO `pembayaran`(`id_booking`, `no_rekening`, `nama_rekening`, `nominal`, `tanggal`)
    VALUES (?,?,?,?,?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$id_booking, $no_rekening, $nama, $nominal, $tgl]);

    $status = 'Sedang Diproses'; // Changed to 'Sedang Diproses'
    $sql2 = "UPDATE `booking` SET `konfirmasi_pembayaran`=? WHERE id_booking=?";
    $stmt2 = $koneksi->prepare($sql2);
    $stmt2->execute([$status, $id_booking]);

    // Fetch kode_booking for redirect
    $booking_id_for_redirect = $_POST['id_booking'];
    $stmt_kode_booking = $koneksi->prepare("SELECT kode_booking FROM booking WHERE id_booking = ?");
    $stmt_kode_booking->execute([$booking_id_for_redirect]);
    $result_kode = $stmt_kode_booking->fetch(PDO::FETCH_ASSOC);
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
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$nama_pengguna, $username, $no_hp, $id_login]);

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
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$id_login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(md5($current_password) == $user['password'])
    {
        if($new_password == $confirm_password)
        {
            $hashed_password = md5($new_password);
            $sql_update = "UPDATE login SET password = ? WHERE id_login = ?";
            $stmt_update = $koneksi->prepare($sql_update);
            $stmt_update->execute([$hashed_password, $id_login]);
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
