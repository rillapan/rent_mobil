<?php
    session_start();
    require 'koneksi/koneksi.php';
    include 'header.php';
    if(empty($_SESSION['USER']))
    {
        echo '<script>alert("Harap Login");window.location="index.php"</script>';
    }

    $id =  $_SESSION["USER"]["id_login"];
    $sql = "SELECT * FROM login WHERE id_login = ?";
    $row = $koneksi->prepare($sql);
    $row->execute(array($id));
    $user = $row->fetch(PDO::FETCH_OBJ);
?>
<style>
    :root {
        --primary: #1A237E; 
        --primary-dark: #1A3CC9;
        --secondary: #FF6B35;
        --secondary-dark: #E05A2B;
        --light: #F8F9FA;
        --dark: #212529;
        --gray: #6C757D;
    }
    body {
        background-color: var(--light);
    }
    .profile-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 0 15px;
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
    .form-group label {
        font-weight: 600;
    }
    .password-toggle {
        position: relative;
    }
    .password-toggle-icon {
        position: absolute;
        right: 15px;
        top: 70%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--gray);
    }
    .btn-primary-custom {
        background: var(--secondary);
        border-color: var(--secondary);
        color: white;
    }
    .btn-primary-custom:hover {
        background: var(--secondary-dark);
        border-color: var(--secondary-dark);
    }
</style>
<div class="profile-container">
    <div class="profile-header">
        <h2>Profil Saya</h2>
        <p>Kelola informasi profil Anda untuk mengontrol, melindungi, dan mengamankan akun</p>
    </div>
    <div class="row">
        <!-- Informasi Akun -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa fa-user-circle mr-2"></i>Informasi Akun
                </div>
                <div class="card-body">
                    <form action="koneksi/proses.php?id=update_profil" method="post">
                        <div class="form-group">
                            <label for="nama_pengguna">Nama Pengguna</label>
                            <input type="text" class="form-control" value="<?= $user->nama_pengguna;?>" name="nama_pengguna" id="nama_pengguna" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" value="<?= $user->username;?>" name="username" id="username" required>
                        </div>
                        <div class="form-group">
                            <label for="no_hp">Nomor HP</label>
                            <input type="text" class="form-control" value="<?= $user->no_hp;?>" name="no_hp" id="no_hp" required>
                        </div>
                        <button type="submit" class="btn btn-primary-custom btn-block">
                            <i class="fa fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ubah Password -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa fa-lock mr-2"></i>Ubah Password
                </div>
                <div class="card-body">
                    <form action="koneksi/proses.php?id=ubah_password" method="post">
                        <div class="form-group password-toggle">
                            <label for="current_password">Password Saat Ini</label>
                            <input type="password" class="form-control" name="current_password" id="current_password" required>
                            <span class="password-toggle-icon" onclick="togglePassword('current_password')">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <div class="form-group password-toggle">
                            <label for="new_password">Password Baru</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" required>
                            <span class="password-toggle-icon" onclick="togglePassword('new_password')">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <div class="form-group password-toggle">
                            <label for="confirm_password">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                            <span class="password-toggle-icon" onclick="togglePassword('confirm_password')">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <button type="submit" class="btn btn-primary-custom btn-block">
                            <i class="fa fa-sync-alt mr-2"></i>Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = passwordInput.nextElementSibling.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

<?php include 'footer.php';?>

<?php if(isset($_GET['status'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let status = '<?php echo $_GET['status']; ?>';
        if (status === 'profilesuccess') {
            Swal.fire('Berhasil!', 'Informasi profil Anda telah diperbarui.', 'success');
        } else if (status === 'passwordchanged') {
            Swal.fire('Berhasil!', 'Password Anda telah diubah.', 'success');
        } else if (status === 'passworderror') {
            Swal.fire('Gagal!', 'Password saat ini salah.', 'error');
        } else if (status === 'passwordmismatch') {
            Swal.fire('Gagal!', 'Konfirmasi password baru tidak cocok.', 'error');
        }
    });
</script>
<?php endif; ?>
