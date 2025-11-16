<?php
require '../koneksi/koneksi.php';
session_start();

if (empty($_SESSION['USER']) || $_SESSION['USER']['level'] != 'admin') {
    echo '<script>alert("Anda tidak memiliki akses ke halaman ini!");window.location="../index.php";</script>';
    exit();
}

$aksi = $_GET['aksi'] ?? '';

if ($aksi == 'update_web') {
    $nama_rental = $_POST['nama_rental'];
    $email = $_POST['email'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $no_rek = $_POST['no_rek'];

    $logo = '';
    if (!empty($_FILES['logo']['name'])) {
        $target_dir = "../assets/image/";
        $target_file = $target_dir . basename($_FILES["logo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["logo"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                $logo = basename($_FILES["logo"]["name"]);
            }
        }
    }

    if (!empty($logo)) {
        $sql = "UPDATE infoweb SET nama_rental = ?, email = ?, telp = ?, alamat = ?, no_rek = ?, logo = ? WHERE id = 1";
        $stmt = $koneksi->prepare($sql);
        if ($stmt->execute([$nama_rental, $email, $telp, $alamat, $no_rek, $logo])) {
            header('Location: pengaturan/index.php?status=web_success');
        } else {
            header('Location: pengaturan/index.php?status=web_error');
        }
    } else {
        $sql = "UPDATE infoweb SET nama_rental = ?, email = ?, telp = ?, alamat = ?, no_rek = ? WHERE id = 1";
        $stmt = $koneksi->prepare($sql);
        if ($stmt->execute([$nama_rental, $email, $telp, $alamat, $no_rek])) {
            header('Location: pengaturan/index.php?status=web_success');
        } else {
            header('Location: pengaturan/index.php?status=web_error');
        }
    }
} elseif ($aksi == 'update_profil') {
    $id_login = $_SESSION["USER"]["id_login"];
    $nama_pengguna = $_POST['nama_pengguna'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $sql = "UPDATE login SET nama_pengguna = ?, username = ?, password = md5(?) WHERE id_login = ?";
        $stmt = $koneksi->prepare($sql);
        if ($stmt->execute([$nama_pengguna, $username, $password, $id_login])) {
            $_SESSION['USER']['nama_pengguna'] = $nama_pengguna;
            $_SESSION['USER']['username'] = $username;
            header('Location: profil/index.php?status=profile_success');
        } else {
            header('Location: profil/index.php?status=profile_error');
        }
    } else {
        $sql = "UPDATE login SET nama_pengguna = ?, username = ? WHERE id_login = ?";
        $stmt = $koneksi->prepare($sql);
        if ($stmt->execute([$nama_pengguna, $username, $id_login])) {
            $_SESSION['USER']['nama_pengguna'] = $nama_pengguna;
            $_SESSION['USER']['username'] = $username;
            header('Location: profil/index.php?status=profile_success');
        } else {
            header('Location: profil/index.php?status=profile_error');
        }
    }
} else {
    header('Location: index.php');
}
?>