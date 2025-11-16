<?php

require '../../koneksi/koneksi.php';

// Start the session if it hasn't been started yet.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['USER'])) {
    echo '<script>alert("Login Dulu !");window.location="../index.php";</script>';
    exit();
}

$aksi = $_GET['aksi'] ?? '';

if ($aksi == 'tambah') {

    $allowedTypes = [
        'image/png'   => 'png',
        'image/jpeg'  => 'jpg',
        'image/gif'   => 'gif',
        'image/webp'  => 'webp'
    ];
    if (empty($_FILES['gambar']['name'])) {
        header("location: mobil.php?pesan=gagal-tambah");
        exit();
    }
    $filepath = $_FILES['gambar']['tmp_name'];
    $fileSize = filesize($filepath);
    $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
    $filetype = finfo_file($fileinfo, $filepath);

    if (!in_array($filetype, array_keys($allowedTypes))) {
        header("location: mobil.php?pesan=gagal-tambah");
        exit();
    } elseif ($_FILES['gambar']["error"] > 0) {
        header("location: mobil.php?pesan=gagal-tambah");
        exit();
    } elseif (round($_FILES['gambar']["size"] / 1024) > 4096) {
        header("location: mobil.php?pesan=gagal-tambah");
        exit();
    } else {
        $dir = '../../assets/image/';
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $temp = explode(".", $_FILES["gambar"]["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp);
        $target_path = $dir . basename($newfilename);
        if (move_uploaded_file($tmp_name, $target_path)) {
            
            $keunggulan = isset($_POST['keunggulan']) ? array_filter($_POST['keunggulan']) : [];
            $keunggulan_string = implode('||', $keunggulan);

            $merk = $_POST['merk'];
            $nama_mobil = $_POST['nama_mobil'];
            $tahun_terbit = $_POST['tahun_terbit'];
            $jumlah_kursi = $_POST['jumlah_kursi'];
            $harga = $_POST['harga'];
            $deskripsi = $_POST['deskripsi'];
            $status = $_POST['status'];
            $gambar = $newfilename;

            $sql = "INSERT INTO `mobil`(`merk`, `nama_mobil`, `tahun_terbit`, `jumlah_kursi`, `harga`, `deskripsi`, `keunggulan`, `status`, `gambar`)
                VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "ssiiissss", $merk, $nama_mobil, $tahun_terbit, $jumlah_kursi, $harga, $deskripsi, $keunggulan_string, $status, $gambar);
            mysqli_stmt_execute($stmt);
            
            $id_mobil = mysqli_insert_id($koneksi);
            mysqli_stmt_close($stmt);

            if (!empty($_POST['no_plat'])) {
                $no_plats = $_POST['no_plat'];
                $sql_plat = "INSERT INTO mobil_plat (id_mobil, no_plat) VALUES (?, ?)";
                $stmt_plat = mysqli_prepare($koneksi, $sql_plat);
                foreach ($no_plats as $no_plat) {
                    $no_plat = trim($no_plat);
                    if (!empty($no_plat)) {
                        mysqli_stmt_bind_param($stmt_plat, "is", $id_mobil, $no_plat);
                        mysqli_stmt_execute($stmt_plat);
                    }
                }
                mysqli_stmt_close($stmt_plat);
            }


            if (isset($_FILES['gambar_tambahan'])) {
                foreach ($_FILES['gambar_tambahan']['tmp_name'] as $key => $tmp_name) {
                    if (!empty($tmp_name)) {
                        $filepath_tambahan = $_FILES['gambar_tambahan']['tmp_name'][$key];
                        $fileinfo_tambahan = finfo_open(FILEINFO_MIME_TYPE);
                        $filetype_tambahan = finfo_file($fileinfo_tambahan, $filepath_tambahan);

                        if (in_array($filetype_tambahan, array_keys($allowedTypes)) && $_FILES['gambar_tambahan']["error"][$key] == 0 && round($_FILES['gambar_tambahan']["size"][$key] / 1024) <= 4096) {
                            $temp_tambahan = explode(".", $_FILES["gambar_tambahan"]["name"][$key]);
                            $newfilename_tambahan = round(microtime(true)) . '_' . $key . '.' . end($temp_tambahan);
                            $target_path_tambahan = $dir . basename($newfilename_tambahan);

                            if (move_uploaded_file($filepath_tambahan, $target_path_tambahan)) {
                                $sql_gambar = "INSERT INTO mobil_gambar (id_mobil, nama_gambar) VALUES (?, ?)";
                                $stmt_gambar = mysqli_prepare($koneksi, $sql_gambar);
                                mysqli_stmt_bind_param($stmt_gambar, "is", $id_mobil, $newfilename_tambahan);
                                mysqli_stmt_execute($stmt_gambar);
                                mysqli_stmt_close($stmt_gambar);
                            }
                        }
                    }
                }
            }

            header("location: mobil.php?pesan=sukses-tambah");
            exit();
        } else {
            header("location: mobil.php?pesan=gagal-tambah");
            exit();
        }
    }

} elseif ($aksi == 'edit') {

    $id = $_GET['id'];

    // Handle deletion of additional images
    if (isset($_POST['hapus_gambar'])) {
        foreach ($_POST['hapus_gambar'] as $id_gambar) {
            $sql_select_gambar = "SELECT nama_gambar FROM mobil_gambar WHERE id_gambar = ?";
            $stmt_select_gambar = mysqli_prepare($koneksi, $sql_select_gambar);
            mysqli_stmt_bind_param($stmt_select_gambar, "i", $id_gambar);
            mysqli_stmt_execute($stmt_select_gambar);
            $result_stmt_select = mysqli_stmt_get_result($stmt_select_gambar);
            $row = mysqli_fetch_assoc($result_stmt_select);
            mysqli_stmt_close($stmt_select_gambar);
            $nama_gambar = $row['nama_gambar'];

            if ($nama_gambar && file_exists('../../assets/image/' . $nama_gambar)) {
                unlink('../../assets/image/' . $nama_gambar);
            }

            $sql_delete_gambar = "DELETE FROM mobil_gambar WHERE id_gambar = ?";
            $stmt_delete_gambar = mysqli_prepare($koneksi, $sql_delete_gambar);
            mysqli_stmt_bind_param($stmt_delete_gambar, "i", $id_gambar);
            mysqli_stmt_execute($stmt_delete_gambar);
            mysqli_stmt_close($stmt_delete_gambar);
        }
    }

    // Handle upload of new additional images
    if (isset($_FILES['gambar_tambahan'])) {
        $allowedTypes = [
            'image/png'   => 'png',
            'image/jpeg'  => 'jpg',
            'image/gif'   => 'gif',
            'image/webp'  => 'webp'
        ];
        $dir = '../../assets/image/';

        foreach ($_FILES['gambar_tambahan']['tmp_name'] as $key => $tmp_name) {
            if (!empty($tmp_name)) {
                $filepath_tambahan = $_FILES['gambar_tambahan']['tmp_name'][$key];
                $fileinfo_tambahan = finfo_open(FILEINFO_MIME_TYPE);
                $filetype_tambahan = finfo_file($fileinfo_tambahan, $filepath_tambahan);

                if (in_array($filetype_tambahan, array_keys($allowedTypes)) && $_FILES['gambar_tambahan']["error"][$key] == 0 && round($_FILES['gambar_tambahan']["size"][$key] / 1024) <= 4096) {
                    $temp_tambahan = explode(".", $_FILES["gambar_tambahan"]["name"][$key]);
                    $newfilename_tambahan = round(microtime(true)) . '_' . $key . '.' . end($temp_tambahan);
                    $target_path_tambahan = $dir . basename($newfilename_tambahan);

                    if (move_uploaded_file($filepath_tambahan, $target_path_tambahan)) {
                        $sql_gambar = "INSERT INTO mobil_gambar (id_mobil, nama_gambar) VALUES (?, ?)";
                        $stmt_gambar = mysqli_prepare($koneksi, $sql_gambar);
                        mysqli_stmt_bind_param($stmt_gambar, "is", $id, $newfilename_tambahan);
                        mysqli_stmt_execute($stmt_gambar);
                        mysqli_stmt_close($stmt_gambar);
                    }
                }
            }
        }
    }

    $gambar = $_POST['gambar_cek'];

    $keunggulan = isset($_POST['keunggulan']) ? array_filter($_POST['keunggulan']) : [];
    $keunggulan_string = implode('||', $keunggulan);

    $no_plat = $_POST['no_plat'];
    $merk = $_POST['merk'];
    $nama_mobil = $_POST['nama_mobil'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $jumlah_kursi = $_POST['jumlah_kursi'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];

    if (isset($_FILES['gambar']) && $_FILES['gambar']['size'] > 0) {
        $filepath = $_FILES['gambar']['tmp_name'];
        $fileSize = filesize($filepath);
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath);
        $allowedTypes = [
            'image/png'   => 'png',
            'image/jpeg'  => 'jpg',
            'image/gif'   => 'gif',
            'image/webp'  => 'webp'
        ];
        if(!in_array($filetype, array_keys($allowedTypes))) {
            header("location: mobil.php?pesan=gagal-upload");
            exit();
        }else if ($_FILES['gambar']["error"] > 0) {
            header("location: mobil.php?pesan=gagal-upload");
            exit();
        } elseif (round($_FILES['gambar']["size"] / 1024) > 4096) {
            header("location: mobil.php?pesan=gagal-upload");
            exit();
        } else {
            $dir = '../../assets/image/';
            $tmp_name = $_FILES['gambar']['tmp_name'];
            $temp = explode(".", $_FILES["gambar"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $target_path = $dir . basename($newfilename);
            if (move_uploaded_file($tmp_name, $target_path)) {
                if (file_exists('../../assets/image/'.$gambar)) {
                    unlink('../../assets/image/'.$gambar);
                }
                $gambar_update = $newfilename;
            } else {
                header("location: mobil.php?pesan=gagal-upload");
                exit();
            }
        }
    } else {
        $gambar_update = $_POST['gambar_cek'];
    }

    $sql = "UPDATE mobil SET merk=?, nama_mobil=?, tahun_terbit=?, jumlah_kursi=?, harga=?, deskripsi=?, keunggulan=?, status=?, gambar=? WHERE id_mobil = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "ssiiissssi", $merk, $nama_mobil, $tahun_terbit, $jumlah_kursi, $harga, $deskripsi, $keunggulan_string, $status, $gambar_update, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Jika status diubah secara manual, pastikan konsistensi dengan plat
    if ($status == 'Tidak Tersedia') {
        // Jika status diubah ke 'Tidak Tersedia' secara manual, tidak perlu update plat
        // Karena ini adalah override manual
    } elseif ($status == 'Tersedia') {
        // Jika status diubah ke 'Tersedia' secara manual, pastikan ada plat tersedia
        $sql_count_plat = "SELECT COUNT(*) as available_count FROM mobil_plat WHERE id_mobil = ? AND status_plat = 'Tersedia'";
        $stmt_count_plat = mysqli_prepare($koneksi, $sql_count_plat);
        mysqli_stmt_bind_param($stmt_count_plat, "i", $id);
        mysqli_stmt_execute($stmt_count_plat);
        $result_count = mysqli_stmt_get_result($stmt_count_plat);
        $count_data = mysqli_fetch_assoc($result_count);
        $available_count = $count_data['available_count'];
        mysqli_stmt_close($stmt_count_plat);

        // Jika tidak ada plat tersedia, ubah status kembali ke 'Tidak Tersedia'
        if ($available_count == 0) {
            $sql_update_status = "UPDATE mobil SET status = 'Tidak Tersedia' WHERE id_mobil = ?";
            $stmt_update_status = mysqli_prepare($koneksi, $sql_update_status);
            mysqli_stmt_bind_param($stmt_update_status, "i", $id);
            mysqli_stmt_execute($stmt_update_status);
            mysqli_stmt_close($stmt_update_status);
        }
    }

    // Proses plat nomor
    $submitted_plats = [];
    if (isset($_POST['no_plat'])) {
        for ($i = 0; $i < count($_POST['no_plat']); $i++) {
            if (!empty(trim($_POST['no_plat'][$i]))) {
                $submitted_plats[] = [
                    'id' => $_POST['id_plat'][$i] ?? null,
                    'no_plat' => trim($_POST['no_plat'][$i])
                ];
            }
        }
    }

    // 1. Dapatkan plat yang ada di DB
    $existing_plats_db = [];
    $sql_plats_db = "SELECT id_plat, no_plat, status_plat FROM mobil_plat WHERE id_mobil = ?";
    $stmt_plats_db = mysqli_prepare($koneksi, $sql_plats_db);
    mysqli_stmt_bind_param($stmt_plats_db, "i", $id);
    mysqli_stmt_execute($stmt_plats_db);
    $result_plats_db = mysqli_stmt_get_result($stmt_plats_db);
    while ($row = mysqli_fetch_assoc($result_plats_db)) {
        $existing_plats_db[$row['id_plat']] = $row;
    }
    mysqli_stmt_close($stmt_plats_db);

    $submitted_plat_ids = array_filter(array_column($submitted_plats, 'id'));

    // 2. Hapus plat yang tidak ada di form submit
    foreach ($existing_plats_db as $plat_id => $plat_data) {
        if (!in_array($plat_id, $submitted_plat_ids)) {
            if ($plat_data['status_plat'] == 'Dipesan') {
                // Jangan hapus plat yang sedang dipesan, mungkin tampilkan pesan error
                $_SESSION['error_message'] = "Tidak dapat menghapus plat nomor " . htmlspecialchars($plat_data['no_plat']) . " karena sedang dalam status dipesan.";
            } else {
                $sql_delete_plat = "DELETE FROM mobil_plat WHERE id_plat = ?";
                $stmt_delete_plat = mysqli_prepare($koneksi, $sql_delete_plat);
                mysqli_stmt_bind_param($stmt_delete_plat, "i", $plat_id);
                mysqli_stmt_execute($stmt_delete_plat);
                mysqli_stmt_close($stmt_delete_plat);
            }
        }
    }

    // 3. Update dan Tambah plat baru
    foreach ($submitted_plats as $plat) {
        if (!empty($plat['id'])) { // Ini adalah plat yang sudah ada -> UPDATE
            $sql_update_plat = "UPDATE mobil_plat SET no_plat = ? WHERE id_plat = ?";
            $stmt_update_plat = mysqli_prepare($koneksi, $sql_update_plat);
            mysqli_stmt_bind_param($stmt_update_plat, "si", $plat['no_plat'], $plat['id']);
            mysqli_stmt_execute($stmt_update_plat);
            mysqli_stmt_close($stmt_update_plat);
        } else { // Ini adalah plat baru -> INSERT
            $sql_insert_plat = "INSERT INTO mobil_plat (id_mobil, no_plat) VALUES (?, ?)";
            $stmt_insert_plat = mysqli_prepare($koneksi, $sql_insert_plat);
            mysqli_stmt_bind_param($stmt_insert_plat, "is", $id, $plat['no_plat']);
            mysqli_stmt_execute($stmt_insert_plat);
            mysqli_stmt_close($stmt_insert_plat);
        }
    }


    if ($success) {
        header("location: mobil.php?pesan=sukses-edit");
        exit();
    } else {
        header("location: mobil.php?pesan=gagal-edit");
        exit();
    }

} elseif ($aksi == 'hapus') {
    $id = $_GET['id'];
    $gambar = $_GET['gambar'];

    // Check if mobil status is 'Tersedia'
    $sql_check_mobil = "SELECT status FROM mobil WHERE id_mobil = ?";
    $stmt_check_mobil = mysqli_prepare($koneksi, $sql_check_mobil);
    mysqli_stmt_bind_param($stmt_check_mobil, "i", $id);
    mysqli_stmt_execute($stmt_check_mobil);
    $result_stmt_check_mobil = mysqli_stmt_get_result($stmt_check_mobil);
    $mobil_data = mysqli_fetch_assoc($result_stmt_check_mobil);
    mysqli_stmt_close($stmt_check_mobil);

    // Check if any plates are 'Dipesan'
    $sql_check_plat = "SELECT COUNT(*) as dipesan_count FROM mobil_plat WHERE id_mobil = ? AND status_plat = 'Dipesan'";
    $stmt_check_plat = mysqli_prepare($koneksi, $sql_check_plat);
    mysqli_stmt_bind_param($stmt_check_plat, "i", $id);
    mysqli_stmt_execute($stmt_check_plat);
    $result_stmt_check_plat = mysqli_stmt_get_result($stmt_check_plat);
    $plat_data = mysqli_fetch_assoc($result_stmt_check_plat);
    mysqli_stmt_close($stmt_check_plat);

    if ($mobil_data['status'] != 'Tersedia' || $plat_data['dipesan_count'] > 0) {
        header("location: mobil.php?pesan=gagal-hapus");
        exit();
    } else {
        // Delete additional images from mobil_gambar
        $sql_gambar = "SELECT nama_gambar FROM mobil_gambar WHERE id_mobil = ?";
        $stmt_gambar = mysqli_prepare($koneksi, $sql_gambar);
        mysqli_stmt_bind_param($stmt_gambar, "i", $id);
        mysqli_stmt_execute($stmt_gambar);
        $result_gambar = mysqli_stmt_get_result($stmt_gambar);
        while ($row_gambar = mysqli_fetch_assoc($result_gambar)) {
            if (file_exists('../../assets/image/' . $row_gambar['nama_gambar'])) {
                unlink('../../assets/image/' . $row_gambar['nama_gambar']);
            }
        }
        mysqli_stmt_close($stmt_gambar);

        // Delete mobil_gambar records
        $sql_delete_gambar = "DELETE FROM mobil_gambar WHERE id_mobil = ?";
        $stmt_delete_gambar = mysqli_prepare($koneksi, $sql_delete_gambar);
        mysqli_stmt_bind_param($stmt_delete_gambar, "i", $id);
        mysqli_stmt_execute($stmt_delete_gambar);
        mysqli_stmt_close($stmt_delete_gambar);

        // Delete mobil_plat records
        $sql_delete_plat = "DELETE FROM mobil_plat WHERE id_mobil = ?";
        $stmt_delete_plat = mysqli_prepare($koneksi, $sql_delete_plat);
        mysqli_stmt_bind_param($stmt_delete_plat, "i", $id);
        mysqli_stmt_execute($stmt_delete_plat);
        mysqli_stmt_close($stmt_delete_plat);

        // Delete main image
        if (file_exists('../../assets/image/'.$gambar)) {
            unlink('../../assets/image/'.$gambar);
        }

        // Delete mobil record
        $sql = "DELETE FROM mobil WHERE id_mobil = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("location: mobil.php?pesan=sukses-hapus");
        exit();
    }
}
