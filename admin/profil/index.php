<?php
require '../../koneksi/koneksi.php';
$title_web = 'Profil';
$url = '../../';
include '../header.php';

$id_login = $_SESSION["USER"]["id_login"];
$sql_profil = "SELECT * FROM login WHERE id_login = ?";
$row_profil = mysqli_prepare($koneksi, $sql_profil);
mysqli_stmt_bind_param($row_profil, "i", $id_login);
mysqli_stmt_execute($row_profil);
$result_stmt = mysqli_stmt_get_result($row_profil);
$profil_admin = mysqli_fetch_object($result_stmt);
mysqli_stmt_close($row_profil);
?>
<div class="container mt-4" style="max-width: 900px;">
    <div class="row g-4">
        <div class="col-lg-8 col-md-12 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i> Profil Admin</h5>
                </div>
                <div class="card-body">
                    <?php if(isset($_GET['status']) && $_GET['status'] === 'profile_success'){ ?>
                      <div class="alert alert-success" role="alert">Profil berhasil diperbarui.</div>
                    <?php } elseif(isset($_GET['status']) && $_GET['status'] === 'profile_error'){ ?>
                      <div class="alert alert-danger" role="alert">Terjadi kesalahan saat memperbarui profil.</div>
                    <?php } ?>
                    <form action="../proses.php?aksi=update_profil" method="post">
                        <div class="mb-3">
                            <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($profil_admin->nama_pengguna); ?>" name="nama_pengguna" id="nama_pengguna" required placeholder="Nama lengkap Anda"/>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($profil_admin->username); ?>" name="username" id="username" required placeholder="Username untuk login"/>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password baru jika ingin mengubah"/>
                            <div class="form-text">Biarkan kosong jika tidak ingin mengubah password.</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>
