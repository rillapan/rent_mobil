<?php
    require '../../koneksi/koneksi.php';
    $title_web = 'Tambah Supir';
    $url = '../../';
    include '../header.php';
?>
<style>
    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
    }
    .btn-secondary:hover {
        background-color: #e55e2d;
        border-color: #e55e2d;
    }
     :root {
        --primary: #1A237E;
        --primary-dark: #1A3CC9;
        --secondary: #FF6B35;
        --light: #F8F9FA;
        --dark: #212529;
        --gray: #6C757D;
      }

      .card-header {
        background-color: var(--primary);
        color: white;
      }
</style>
<div class="container my-4">
    <div class="card shadow-lg">
        <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-user-plus me-2"></i>Tambah Supir
            </h5>
            <a href="supir.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
        <div class="card-body">
            <form method="post" action="proses.php?aksi=tambah" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_supir" class="form-label">Nama Supir</label>
                            <input type="text" class="form-control" id="nama_supir" name="nama_supir" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="pengalaman" class="form-label">Pengalaman (Tahun)</label>
                            <input type="number" class="form-control" id="pengalaman" name="pengalaman" min="0" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="harga_sewa" class="form-label">Harga Sewa per Hari (Rp)</label>
                            <input type="number" class="form-control" id="harga_sewa" name="harga_sewa" min="0" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Tersedia">Tersedia</option>
                                <option value="Sedang Digunakan">Sedang Digunakan</option>
                                <option value="Close">Close</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="foto" class="form-label">Foto Supir</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                            <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php';?>
