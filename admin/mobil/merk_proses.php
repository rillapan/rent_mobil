<?php
require '../../koneksi/koneksi.php';

header('Content-Type: application/json');

$aksi = $_GET['aksi'] ?? '';

switch ($aksi) {
    case 'tambah':
        tambahMerk();
        break;
    case 'edit':
        editMerk();
        break;
    case 'hapus':
        hapusMerk();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Aksi tidak valid']);
        break;
}

function tambahMerk() {
    global $koneksi;

    $merk = trim($_POST['merk'] ?? '');

    if (empty($merk)) {
        echo json_encode(['success' => false, 'message' => 'Nama merk tidak boleh kosong']);
        return;
    }

    // Check if merk already exists
    $stmt = mysqli_prepare($koneksi, "SELECT id FROM tbl_merk WHERE merk = ?");
    mysqli_stmt_bind_param($stmt, "s", $merk);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['success' => false, 'message' => 'Merk sudah ada dalam database']);
        mysqli_stmt_close($stmt);
        return;
    }
    mysqli_stmt_close($stmt);

    // Insert new merk
    $stmt = mysqli_prepare($koneksi, "INSERT INTO tbl_merk (merk) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $merk);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Merk berhasil ditambahkan']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan merk']);
    }

    mysqli_stmt_close($stmt);
}

function editMerk() {
    global $koneksi;

    $id = $_POST['id'] ?? '';
    $merk = trim($_POST['merk'] ?? '');

    if (empty($id) || empty($merk)) {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        return;
    }

    // Check if merk already exists (excluding current id)
    $stmt = mysqli_prepare($koneksi, "SELECT id FROM tbl_merk WHERE merk = ? AND id != ?");
    mysqli_stmt_bind_param($stmt, "si", $merk, $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['success' => false, 'message' => 'Merk sudah ada dalam database']);
        mysqli_stmt_close($stmt);
        return;
    }
    mysqli_stmt_close($stmt);

    // Update merk
    $stmt = mysqli_prepare($koneksi, "UPDATE tbl_merk SET merk = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $merk, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Merk berhasil diupdate']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mengupdate merk']);
    }

    mysqli_stmt_close($stmt);
}

function hapusMerk() {
    global $koneksi;

    $id = $_GET['id'] ?? '';

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
        return;
    }

    // Check if merk is being used in mobil table
    $stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) as count FROM mobil WHERE merk = (SELECT merk FROM tbl_merk WHERE id = ?)");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Merk tidak dapat dihapus karena masih digunakan oleh mobil']);
        mysqli_stmt_close($stmt);
        return;
    }
    mysqli_stmt_close($stmt);

    // Delete merk
    $stmt = mysqli_prepare($koneksi, "DELETE FROM tbl_merk WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Merk berhasil dihapus']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus merk']);
    }

    mysqli_stmt_close($stmt);
}
?>
