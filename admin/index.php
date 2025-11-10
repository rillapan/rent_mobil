<?php

    require '../koneksi/koneksi.php';
    $title_web = 'Dashboard';
    $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../';
    include 'header.php';

    // Ambil data info website
    $sql_web = "SELECT * FROM infoweb WHERE id = 1";
    $row_web = $koneksi->prepare($sql_web);
    $row_web->execute();
    $info_web = $row_web->fetch(PDO::FETCH_OBJ);

    // Ambil data profil admin
    $id_login = $_SESSION["USER"]["id_login"];
    $sql_profil = "SELECT * FROM login WHERE id_login = ?";
    $row_profil = $koneksi->prepare($sql_profil);
    $row_profil->execute(array($id_login));
    $profil_admin = $row_profil->fetch(PDO::FETCH_OBJ);
?>

<div class="container mt-4" style="max-width: 1400px;">
    <div class="row g-4">
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <h5 class="mb-0"><i class="fas fa-globe me-2"></i> Info Website</h5>
                </div>
                <div class="card-body">
                    <form action="proses.php?aksi=update_web" method="post">
                        <div class="mb-3">
                            <label for="nama_rental" class="form-label">Nama Rental</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($info_web->nama_rental); ?>" name="nama_rental" id="nama_rental" placeholder="Masukkan nama rental" required/>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?= htmlspecialchars($info_web->email); ?>" name="email" id="email" placeholder="contoh@email.com" required/>
                            </div>
                            <div class="col-md-6">
                                <label for="telp" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" value="<?= htmlspecialchars($info_web->telp); ?>" name="telp" id="telp" placeholder="081234567890" required/>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat lengkap" required><?= htmlspecialchars($info_web->alamat); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="no_rek" class="form-label">Nomor Rekening</label>
                            <textarea class="form-control" name="no_rek" id="no_rek" rows="2" placeholder="Contoh: BCA 123-456-7890 an. Nama Anda" required><?= htmlspecialchars($info_web->no_rek); ?></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i> Profil Admin</h5>
                </div>
                <div class="card-body">
                    <form action="proses.php?aksi=update_profil" method="post">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status) {
            let title, text, icon;
            switch (status) {
                case 'web_success':
                    title = 'Berhasil!';
                    text = 'Data info website telah berhasil diperbarui.';
                    icon = 'success';
                    break;
                case 'web_error':
                    title = 'Gagal!';
                    text = 'Terjadi kesalahan saat memperbarui data info website.';
                    icon = 'error';
                    break;
                case 'profile_success':
                    title = 'Berhasil!';
                    text = 'Data profil Anda telah berhasil diperbarui.';
                    icon = 'success';
                    break;
                case 'profile_error':
                    title = 'Gagal!';
                    text = 'Terjadi kesalahan saat memperbarui profil Anda.';
                    icon = 'error';
                    break;
            }

            if (title) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.replaceState({}, document.title, "index.php");
                });
            }
        }
    });
</script>
<style>
    /* Menggunakan tema warna yang konsisten */
    :root {
        --primary: #1A237E;
        --secondary: #FF6B35;
        --success: #28a745;
        --light: #F8F9FA;
        --dark: #212529;
    }
    .bg-primary { background-color: var(--primary) !important; }
    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
        color: white;
    }
    .btn-secondary:hover {
        background-color: #e55e2d;
        border-color: #e55e2d;
    }
    .bg-success { background-color: var(--success) !important; }
    .btn-success { background-color: var(--success) !important; border-color: var(--success); }
    .btn-success:hover { background-color: #218838 !important; border-color: #218838; }
</style>
<?php include 'footer.php';?>