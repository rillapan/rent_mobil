<?php
require '../../koneksi/koneksi.php';

if (isset($_GET['aksi'])) {
    $aksi = $_GET['aksi'];

    if ($aksi == 'tambah') {
        // Proses tambah supir
        $nama_supir = $_POST['nama_supir'];
        $pengalaman = $_POST['pengalaman'];
        $harga_sewa = $_POST['harga_sewa'];
        $status = $_POST['status'];
        $deskripsi = $_POST['deskripsi'];

        // Proses upload foto
        $foto = '';
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $target_dir = "../../assets/image/";
            $file_extension = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
            $allowed_extensions = array("jpg", "jpeg", "png", "gif");

            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = time() . "_" . uniqid() . "." . $file_extension;
                $target_file = $target_dir . $new_filename;

                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                    $foto = $new_filename;
                } else {
                    header('Location: tambah.php?pesan=gagal-upload');
                    exit;
                }
            } else {
                header('Location: tambah.php?pesan=gagal-upload');
                exit;
            }
        }

        $sql = "INSERT INTO supir (nama_supir, pengalaman, harga_sewa, status, deskripsi, foto) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "siisss", $nama_supir, $pengalaman, $harga_sewa, $status, $deskripsi, $foto);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: supir.php?pesan=sukses-tambah');
        } else {
            mysqli_stmt_close($stmt);
            header('Location: tambah.php?pesan=gagal-tambah');
        }

    } elseif ($aksi == 'edit') {
        // Proses edit supir
        $id_supir = $_GET['id'];
        $nama_supir = $_POST['nama_supir'];
        $pengalaman = $_POST['pengalaman'];
        $harga_sewa = $_POST['harga_sewa'];
        $status = $_POST['status'];
        $deskripsi = $_POST['deskripsi'];

        // Ambil data supir lama untuk foto
        $sql_old = "SELECT foto FROM supir WHERE id_supir = ?";
        $stmt_old = mysqli_prepare($koneksi, $sql_old);
        mysqli_stmt_bind_param($stmt_old, "i", $id_supir);
        mysqli_stmt_execute($stmt_old);
        $result_stmt_old = mysqli_stmt_get_result($stmt_old);
        $old_supir = mysqli_fetch_assoc($result_stmt_old);
        mysqli_stmt_close($stmt_old);
        $foto = $old_supir['foto'];

        // Proses upload foto baru jika ada
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $target_dir = "../../assets/image/";
            $file_extension = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
            $allowed_extensions = array("jpg", "jpeg", "png", "gif");

            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = time() . "_" . uniqid() . "." . $file_extension;
                $target_file = $target_dir . $new_filename;

                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                    // Hapus foto lama jika ada
                    if (!empty($foto) && file_exists($target_dir . $foto)) {
                        unlink($target_dir . $foto);
                    }
                    $foto = $new_filename;
                } else {
                    header('Location: edit.php?id=' . $id_supir . '&pesan=gagal-upload');
                    exit;
                }
            } else {
                header('Location: edit.php?id=' . $id_supir . '&pesan=gagal-upload');
                exit;
            }
        }

        $sql = "UPDATE supir SET nama_supir = ?, pengalaman = ?, harga_sewa = ?, status = ?, deskripsi = ?, foto = ? WHERE id_supir = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "siisssi", $nama_supir, $pengalaman, $harga_sewa, $status, $deskripsi, $foto, $id_supir);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: supir.php?pesan=sukses-edit');
        } else {
            mysqli_stmt_close($stmt);
            header('Location: edit.php?id=' . $id_supir . '&pesan=gagal-edit');
        }

    } elseif ($aksi == 'hapus') {
        // Proses hapus supir
        $id_supir = $_GET['id'];
        $foto = $_GET['foto'];

        // Hapus foto dari folder jika ada
        if (!empty($foto)) {
            $target_dir = "../../assets/image/";
            if (file_exists($target_dir . $foto)) {
                unlink($target_dir . $foto);
            }
        }

        $sql = "DELETE FROM supir WHERE id_supir = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_supir);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: supir.php?pesan=sukses-hapus');
        } else {
            mysqli_stmt_close($stmt);
            header('Location: supir.php?pesan=gagal-hapus');
        }
    }
} else {
    header('Location: supir.php');
}
?>
