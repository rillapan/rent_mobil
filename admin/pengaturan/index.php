<?php
require '../../koneksi/koneksi.php';
$title_web = 'Pengaturan Website';
$url = '../../';
include '../header.php';
?>
<div class="container mt-4" style="max-width: 1400px;">
    <div class="row g-4">
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <h5 class="mb-0"><i class="fas fa-globe me-2"></i> Info Website</h5>
                </div>
                <div class="card-body">
                    <?php if(isset($_GET['status']) && $_GET['status'] === 'web_success'){ ?>
                      <div class="alert alert-success" role="alert">Info Website berhasil diperbarui.</div>
                    <?php } elseif(isset($_GET['status']) && $_GET['status'] === 'web_error'){ ?>
                      <div class="alert alert-danger" role="alert">Terjadi kesalahan saat memperbarui Info Website.</div>
                    <?php } ?>
                    <form action="../proses.php?aksi=update_web" method="post">
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


    </div>

    <div class="row g-4 mt-4">
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex align-items-center">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i> Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">Kelola metode pembayaran yang tersedia untuk pelanggan.</p>
                    <div class="d-grid gap-2">
                        <a href="payment_methods.php" class="btn btn-info">
                            <i class="fas fa-cog me-2"></i>Kelola Metode Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i> Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">Kelola metode pembayaran yang tersedia untuk pelanggan.</p>
                    <div class="d-grid gap-2">
                        <a href="payment_methods.php" class="btn btn-success">
                            <i class="fas fa-cog me-2"></i>Kelola Metode Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php include '../footer.php'; ?>
