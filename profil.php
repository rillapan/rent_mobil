<?php
session_start();
require 'koneksi/koneksi.php';
include 'header.php';

if (empty($_SESSION['USER'])) {
    echo '<script>alert("Harap Login");window.location="index.php"</script>';
    exit();
}

$id = $_SESSION["USER"]["id_login"];

// Proses Ubah Password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = mysqli_real_escape_string($koneksi, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($koneksi, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($koneksi, $_POST['confirm_password']);

    // Validasi password baru dan konfirmasi
    if ($new_password !== $confirm_password) {
        echo '<script>alert("Konfirmasi password baru tidak cocok!");window.location="profil.php"</script>';
        exit();
    }

    // Ambil password saat ini dari database
    $sql_check_password = "SELECT password FROM login WHERE id_login = ?";
    $stmt_check = mysqli_prepare($koneksi, $sql_check_password);
    mysqli_stmt_bind_param($stmt_check, "i", $id);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    $user_data = mysqli_fetch_assoc($result_check);

    // Verifikasi password saat ini
    if (!password_verify($current_password, $user_data['password'])) {
        echo '<script>alert("Password saat ini salah!");window.location="profil.php"</script>';
        exit();
    }

    // Hash password baru
    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password
    $sql_update_password = "UPDATE login SET password = ? WHERE id_login = ?";
    $stmt_update_password = mysqli_prepare($koneksi, $sql_update_password);
    mysqli_stmt_bind_param($stmt_update_password, "si", $hashed_new_password, $id);
    $success = mysqli_stmt_execute($stmt_update_password);

    if ($success) {
        echo '<script>alert("Password berhasil diubah!");window.location="profil.php"</script>';
    } else {
        echo '<script>alert("Gagal mengubah password.");window.location="profil.php"</script>';
    }
    exit();
}

// Proses Update Profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profil'])) {
    $nama_pengguna = mysqli_real_escape_string($koneksi, $_POST['nama_pengguna']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $provinsi = mysqli_real_escape_string($koneksi, $_POST['provinsi']);
    $kota_kabupaten = mysqli_real_escape_string($koneksi, $_POST['kota_kabupaten']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    
    $sql_update = "UPDATE login SET nama_pengguna = ?, username = ?, no_hp = ?, email = ?, provinsi = ?, kota_kabupaten = ?, jenis_kelamin = ? WHERE id_login = ?";
    $stmt_update = mysqli_prepare($koneksi, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "sssssssi", $nama_pengguna, $username, $no_hp, $email, $provinsi, $kota_kabupaten, $jenis_kelamin, $id);
    $success = mysqli_stmt_execute($stmt_update);

    // Proses Upload Foto
    if ($success && !empty($_FILES['foto']['name'])) {
        $upload_dir = 'assets/image/user/';
        // Buat direktori jika belum ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $foto_name = time() . '_' . basename($_FILES["foto"]["name"]);
        $target_file = $upload_dir . $foto_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek tipe file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            // Hapus foto lama jika bukan default
            $sql_foto_lama = "SELECT foto FROM login WHERE id_login = ?";
            $stmt_foto_lama = mysqli_prepare($koneksi, $sql_foto_lama);
            mysqli_stmt_bind_param($stmt_foto_lama, "i", $id);
            mysqli_stmt_execute($stmt_foto_lama);
            $result_foto_lama = mysqli_stmt_get_result($stmt_foto_lama);
            $data_foto_lama = mysqli_fetch_assoc($result_foto_lama);
            $foto_lama = $data_foto_lama['foto'];

            if ($foto_lama && $foto_lama != 'default.jpg' && file_exists($upload_dir . $foto_lama)) {
                unlink($upload_dir . $foto_lama);
            }

            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                $sql_update_foto = "UPDATE login SET foto = ? WHERE id_login = ?";
                $stmt_update_foto = mysqli_prepare($koneksi, $sql_update_foto);
                mysqli_stmt_bind_param($stmt_update_foto, "si", $foto_name, $id);
                mysqli_stmt_execute($stmt_update_foto);
            }
        }
    }

    if ($success) {
        // Perbarui data di session
        $_SESSION['USER']['nama_pengguna'] = $nama_pengguna;
        echo '<script>alert("Profil berhasil diperbarui!");window.location="profil.php"</script>';
    } else {
        echo '<script>alert("Gagal memperbarui profil.");window.location="profil.php"</script>';
    }
    exit();
}


// Ambil data user terbaru
$sql = "SELECT * FROM login WHERE id_login = ?";
$row = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($row, "i", $id);
mysqli_stmt_execute($row);
$result = mysqli_stmt_get_result($row);
$user = mysqli_fetch_object($result);
mysqli_stmt_close($row);

$foto_path = 'assets/image/user/' . ($user->foto ?? 'default.jpg');
if (!file_exists($foto_path)) {
    $foto_path = 'assets/image/user/default.jpg'; // Fallback ke default jika file tidak ada
}

?>
<style>
    .profile-container {
        max-width: 1000px;
        margin: 2rem auto;
    }
    .profile-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .profile-header h2 {
        color: var(--primary);
        font-weight: 700;
    }
    .card-header {
        background: linear-gradient(120deg, var(--primary), var(--primary-dark));
        color: white;
        font-weight: 600;
    }
    .profile-pic-wrapper {
        position: relative;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 1.5rem;
        border: 4px solid var(--primary);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .profile-pic {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .upload-button {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(0,0,0,0.6);
        color: white;
        text-align: center;
        padding: 10px 0;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }
    .profile-pic-wrapper:hover .upload-button {
        opacity: 1;
    }
    #upload-input {
        display: none;
    }
    .form-group label {
        font-weight: 600;
    }
    .btn-primary-custom {
        background: var(--secondary);
        border-color: var(--secondary);
        color: white;
        transition: all 0.3s ease;
    }
   
</style>

<div class="container profile-container">
    <div class="profile-header">
        <h2>Profil Saya</h2>
        <p>Kelola informasi profil Anda untuk mengontrol, melindungi, dan mengamankan akun.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-user-edit mr-2"></i>Identitas Profil Pelanggan
        </div>
        <div class="card-body">
            <form method="POST" action="profil.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="profile-pic-wrapper">
                            <img class="profile-pic" id="profile-pic-preview" src="<?php echo $foto_path; ?>" alt="Foto Profil">
                            <div class="upload-button" onclick="document.getElementById('upload-input').click();">
                                <i class="fas fa-camera"></i> Ganti Foto
                            </div>
                            <input id="upload-input" type="file" name="foto" accept="image/*">
                        </div>
                        <p class="text-muted small">Ukuran file maks: 2MB<br>Format: JPG, JPEG, PNG</p>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_lengkap">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_pengguna" value="<?php echo htmlspecialchars($user->nama_pengguna); ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user->username); ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_hp">Nomor HP</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($user->no_hp); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user->email ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="provinsi">Provinsi</label>
                                    <select class="form-control" id="provinsi" name="provinsi">
                                        <option value="" disabled selected>-- Pilih Provinsi --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kota_kabupaten">Kota/Kabupaten</label>
                                    <select class="form-control" id="kota_kabupaten" name="kota_kabupaten">
                                        <option value="" disabled selected>-- Pilih Kota/Kabupaten --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                                        <option value="" disabled <?php echo empty($user->jenis_kelamin) ? 'selected' : ''; ?>>-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki" <?php echo ($user->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                                        <option value="Perempuan" <?php echo ($user->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-right">
                    <button type="submit" name="update_profil" class="btn btn-primary-custom btn-lg">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <i class="fas fa-lock mr-2"></i>Ubah Password
        </div>
        <div class="card-body">
            <form method="POST" action="profil.php">
                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="text-right">
                    <button type="submit" name="change_password" class="btn btn-primary-custom">
                        <i class="fas fa-key mr-2"></i>Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/js/wilayah-indonesia.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadInput = document.getElementById('upload-input');
    const preview = document.getElementById('profile-pic-preview');

    uploadInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>

<?php include 'footer.php'; ?>