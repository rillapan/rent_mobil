<?php
    require '../../koneksi/koneksi.php';
    $title_web = 'Edit Supir';
    $url = '../../';
    include '../header.php';

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header('Location: supir.php');
        exit;
    }

    $id_supir = $_GET['id'];
    $sql = "SELECT * FROM supir WHERE id_supir = ?";
    $row = $koneksi->prepare($sql);
    $row->execute([$id_supir]);
    $supir = $row->fetch(PDO::FETCH_ASSOC);

    if (!$supir) {
        header('Location: supir.php');
        exit;
    }
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
                <i class="fas fa-user-edit me-2"></i>Edit Supir
            </h5>
            <a href="supir.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
        <div class="card-body">
            <form method="post" action="proses.php?aksi=edit&id=<?= $id_supir ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_supir" class="form-label">Nama Supir</label>
                            <input type="text" class="form-control" id="nama_supir" name="nama_supir" value="<?= htmlspecialchars($supir['nama_supir']) ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="pengalaman" class="form-label">Pengalaman (Tahun)</label>
                            <input type="number" class="form-control" id="pengalaman" name="pengalaman" value="<?= htmlspecialchars($supir['pengalaman']) ?>" min="0" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="harga_sewa" class="form-label">Harga Sewa per Hari (Rp)</label>
                            <input type="number" class="form-control" id="harga_sewa" name="harga_sewa" value="<?= htmlspecialchars($supir['harga_sewa']) ?>" min="0" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Tersedia" <?= $supir['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                                <option value="Sedang Digunakan" <?= $supir['status'] == 'Sedang Digunakan' ? 'selected' : '' ?>>Sedang Digunakan</option>
                                <option value="Close" <?= $supir['status'] == 'Close' ? 'selected' : '' ?>>Close</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required><?= htmlspecialchars($supir['deskripsi']) ?></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="foto" class="form-label">Foto Supir</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah foto.</small>
                            <?php if (!empty($supir['foto'])): ?>
                                <div class="mt-2">
                                    <img src="../../assets/image/<?= htmlspecialchars($supir['foto']) ?>" alt="Foto Supir" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php';?>
