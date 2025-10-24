<?php
session_start();
 require 'koneksi.php';

if($_GET['id'] == 'login'){

    $user = $_POST['user'];

    $pass = $_POST['pass'];

    $row = $koneksi->prepare("SELECT * FROM login WHERE username = ? AND password = md5(?)");

    $row->execute(array($user,$pass));

    $hitung = $row->rowCount();

    if($hitung > 0)

    {

        $hasil = $row->fetch();

        $_SESSION['USER'] = $hasil;

        if($_SESSION['USER']['level'] == 'admin')

        {

            header('Location: ../admin/index.php?status=loginsuccess');  

        }

        else

        {

            header('Location: ../index.php?status=loginsuccess');

        }

    }

    else

    {

        header('Location: ../index.php?status=loginfailed');

    }

}

if($_GET['id'] == 'daftar')

{

    $data[] = $_POST['nama'];

    $data[] = $_POST['user'];

    $data[] = md5($_POST['pass']);

    $data[] = 'pengguna';

    $row = $koneksi->prepare("SELECT * FROM login WHERE username = ?");

    $row->execute(array($_POST['user']));

    $hitung = $row->rowCount();

    if($hitung > 0)

    {

        header('Location: ../index.php?status=registerfailed');

    }

    else

    {

        $sql = "INSERT INTO `login`(`nama_pengguna`, `username`, `password`, `level`)

                 VALUES (?,?,?,?)";

        $row = $koneksi->prepare($sql);

        $row->execute($data);

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

    $data[] = time();
    $data[] = $_POST['id_login'];
    $data[] = $_POST['id_mobil'];
    $data[] = $_POST['ktp'];
    $data[] = $_POST['nama'];
    $data[] = $_POST['alamat'];
    $data[] = $_POST['no_tlp'];
    $data[] = $_POST['tanggal'];
    $data[] = $lama_sewa;
    $data[] = $total_harga;
    $data[] = "Belum Bayar";
    $data[] = date('Y-m-d');
    $data[] = $_POST['id_supir'] ?: null; // ID supir, null jika tidak dipilih

    $sql = "INSERT INTO booking (kode_booking,
    id_login,
    id_mobil,
    ktp,
    nama,
    alamat,
    no_tlp,
    tanggal, lama_sewa, total_harga, konfirmasi_pembayaran, tgl_input, id_supir)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $row = $koneksi->prepare($sql);
    $row->execute($data);

    // Update status supir jika dipilih
    if (!empty($_POST['id_supir'])) {
        $sql_update_supir = "UPDATE supir SET status = 'Sedang Digunakan' WHERE id_supir = ?";
        $row_update_supir = $koneksi->prepare($sql_update_supir);
        $row_update_supir->execute([$_POST['id_supir']]);
    }

    header('Location: ../bayar.php?id='.$data[0].'&status=bookingsuccess');
}

if($_GET['id'] == 'konfirmasi')
{

    $data[] = $_POST['id_booking'];
    $data[] = $_POST['no_rekening'];
    $data[] = $_POST['nama'];
    $data[] = $_POST['nominal'];
    $data[] = $_POST['tgl'];

    $sql = "INSERT INTO `pembayaran`(`id_booking`, `no_rekening`, `nama_rekening`, `nominal`, `tanggal`) 
    VALUES (?,?,?,?,?)";
    $row = $koneksi->prepare($sql);
    $row->execute($data);

    $data2[] = 'Sedang Diproses'; // Changed to 'Sedang Diproses'
    $data2[] = $_POST['id_booking'];
    $sql2 = "UPDATE `booking` SET `konfirmasi_pembayaran`=? WHERE id_booking=?";
    $row2 = $koneksi->prepare($sql2);
    $row2->execute($data2);

    // Fetch kode_booking for redirect
    $booking_id_for_redirect = $_POST['id_booking'];
    $stmt_kode_booking = $koneksi->prepare("SELECT kode_booking FROM booking WHERE id_booking = ?");
    $stmt_kode_booking->execute([$booking_id_for_redirect]);
    $booking_info = $stmt_kode_booking->fetch();
    $kode_booking_value = $booking_info['kode_booking'] ?? '';

    header('Location: ../history.php?status=konfirmasisuccess&kode_booking='.$kode_booking_value);
}

if($_GET['id'] == 'update_profil')
{
    $id_login = $_SESSION['USER']['id_login'];
    $data[] = $_POST['nama_pengguna'];
    $data[] = $_POST['username'];
    $data[] = $id_login;

    $sql = "UPDATE login SET nama_pengguna = ?, username = ? WHERE id_login = ?";
    $row = $koneksi->prepare($sql);
    $row->execute($data);

    // Update session data
    $_SESSION['USER']['nama_pengguna'] = $_POST['nama_pengguna'];
    $_SESSION['USER']['username'] = $_POST['username'];

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
    $row = $koneksi->prepare($sql);
    $row->execute(array($id_login));
    $user = $row->fetch();

    if(md5($current_password) == $user['password'])
    {
        if($new_password == $confirm_password)
        {
            $hashed_password = md5($new_password);
            $sql_update = "UPDATE login SET password = ? WHERE id_login = ?";
            $row_update = $koneksi->prepare($sql_update);
            $row_update->execute(array($hashed_password, $id_login));
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

// Fungsi placeholder untuk mengirim notifikasi WhatsApp
function kirim_whatsapp($nomor_hp, $pesan) {
    // --- INTEGRASI WHATSAPP API DI SINI ---
    // Ini adalah fungsi placeholder. Anda perlu menggantinya dengan kode
    // untuk mengintegrasikan dengan layanan API WhatsApp pilihan Anda (misalnya Twilio, Fonnte, dll.).
    // Contoh:
    /*
    $api_url = "https://api.whatsapp.com/send"; // Ganti dengan URL API WhatsApp Anda
    $token = "YOUR_WHATSAPP_API_TOKEN"; // Ganti dengan token API Anda

    $data = [
        'phone' => $nomor_hp,
        'message' => $pesan,
        'token' => $token
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        ]
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($api_url, false, $context);
    // Anda bisa menambahkan logging di sini untuk melihat hasil pengiriman
    */
    error_log("WhatsApp Notifikasi ke " . $nomor_hp . ": " . $pesan); // Contoh logging
}
