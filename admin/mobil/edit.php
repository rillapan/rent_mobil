<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require '../../koneksi/koneksi.php';
    $title_web = 'Edit Mobil';
    include '../header.php';

    $id = $_GET['id'];

    $sql = "SELECT * FROM mobil WHERE id_mobil = ?";
    $row = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($row, "i", $id);
    mysqli_stmt_execute($row);
    $result_stmt = mysqli_stmt_get_result($row);
    $hasil = mysqli_fetch_assoc($result_stmt);
    mysqli_stmt_close($row);
    if(!$hasil){
        echo '<script>alert("Mobil tidak ditemukan !");window.location="mobil.php";</script>';
        exit;
    }

    $sql_gambar = "SELECT * FROM mobil_gambar WHERE id_mobil = ?";
    $row_gambar = mysqli_prepare($koneksi, $sql_gambar);
    mysqli_stmt_bind_param($row_gambar, "i", $id);
    mysqli_stmt_execute($row_gambar);
    $result_stmt_gambar = mysqli_stmt_get_result($row_gambar);
    $hasil_gambar = [];
    while ($row = mysqli_fetch_object($result_stmt_gambar)) {
        $hasil_gambar[] = $row;
    }
    mysqli_stmt_close($row_gambar);
?>
<style>
    :root {
        --primary: #1A237E; 
        --primary-dark: #1A3CC9;
        --secondary: #FF6B35;
        --light: #F8F9FA;
        --dark: #212529;
        --gray: #6C757D;
    }

    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 1rem;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.5rem 2rem;
        border-bottom: none;
    }

    .card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.25);
    }

    .input-group-text {
        background-color: var(--primary);
        color: white;
        border: 2px solid var(--primary);
        font-weight: 600;
    }

    .btn {
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(26, 35, 126, 0.3);
    }

    .btn-secondary {
        background: linear-gradient(135deg, var(--secondary) 0%, #e55e2d 100%);
        color: white;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(255, 107, 53, 0.3);
    }

    .btn-outline-primary {
        border: 2px solid var(--primary);
        color: var(--primary);
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(220, 53, 69, 0.3);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .image-card {
        transition: all 0.3s ease;
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .image-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
    }

    .image-card.deleted {
        opacity: 0.5;
        border: 2px dashed #dc3545;
        transform: scale(0.95);
    }

    .image-card .card {
        border-radius: 0.75rem;
        overflow: hidden;
        height: 100%;
    }

    .image-card .card-img-top {
        height: 180px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .image-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .image-card .card-body {
        padding: 1rem;
        background: var(--light);
    }

    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .section-title {
        color: var(--primary);
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid var(--primary);
        display: inline-block;
    }

    .main-image-container {
        border: 3px solid var(--primary);
        border-radius: 1rem;
        padding: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .main-image-container img {
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .input-group {
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .input-group .form-control {
        border-radius: 0;
    }

    .input-group .btn {
        border-radius: 0;
    }

    .form-text {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    hr {
        border: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--primary), transparent);
        margin: 2rem 0;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
    }

    .status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }
</style>

<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold">
                        <i class="fas fa-edit me-2"></i>Edit Mobil
                    </h4>
                    <p class="mb-0 opacity-75">Mengedit data mobil <?= htmlspecialchars($hasil['merk']); ?></p>
                </div>
                <a href="mobil.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                </a>
            </div>
        </div>

        <div class="card-body">
            <form method="post" action="proses.php?aksi=edit&id=<?= htmlspecialchars($id); ?>" enctype="multipart/form-data">
                
                <!-- Informasi Utama -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="section-title">
                            <i class="fas fa-info-circle me-2"></i>Informasi Utama
                        </h5>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_plat" class="form-label">Nomor Plat</label>
                            <input type="text" class="form-control" id="no_plat" value="<?= htmlspecialchars($hasil['no_plat']); ?>" name="no_plat" placeholder="Masukkan nomor plat kendaraan" required>
                        </div>

                        <div class="form-group mt-3">
                            <label for="merk" class="form-label">Merk Mobil</label>
                            <input type="text" class="form-control" id="merk" value="<?= htmlspecialchars($hasil['merk']); ?>" name="merk" placeholder="Masukkan merk mobil" required>
                        </div>

                        <div class="form-group mt-3">
                            <label for="harga" class="form-label">Harga Sewa per Hari</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="harga" value="<?= htmlspecialchars($hasil['harga']); ?>" name="harga" placeholder="Masukkan harga sewa" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="deskripsi" class="form-label">Deskripsi Mobil</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi lengkap mobil" required><?= htmlspecialchars($hasil['deskripsi']); ?></textarea>
                        </div>

                        <div class="form-group mt-3">
                            <label for="status" class="form-label">Status Ketersediaan</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="" disabled>Pilih Status Ketersediaan</option>
                                <option value="Tersedia" <?= $hasil['status'] == 'Tersedia' ? 'selected' : ''; ?>>Tersedia</option>
                                <option value="Tidak Tersedia" <?= $hasil['status'] == 'Tidak Tersedia' ? 'selected' : ''; ?>>Tidak Tersedia</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Keunggulan Paket -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="section-title">
                            <i class="fas fa-star me-2"></i>Keunggulan Paket
                        </h5>
                        
                        <div class="form-group">
                            <div id="keunggulan-container">
                                <?php 
                                    $keunggulan = explode('||', $hasil['keunggulan']);
                                    $no = 1;
                                    foreach($keunggulan as $item){
                                        if(!empty($item)){
                                ?>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white fw-bold"><?= $no; ?></span>
                                    <input type="text" class="form-control" name="keunggulan[]" value="<?= htmlspecialchars($item); ?>" placeholder="Contoh: Bensin gratis, Supir profesional, dll." required>
                                    <button type="button" class="btn btn-danger remove-keunggulan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <?php $no++;}}
                                if(empty($hasil['keunggulan'])){
                                ?>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white fw-bold">1</span>
                                    <input type="text" class="form-control" name="keunggulan[]" placeholder="Contoh: Bensin gratis, Supir profesional, dll." required>
                                    <button type="button" class="btn btn-danger remove-keunggulan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <?php } ?>
                            </div>
                            <button type="button" class="btn btn-outline-primary mt-2" id="add-keunggulan">
                                <i class="fas fa-plus me-2"></i>Tambah Keunggulan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Gambar Utama -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="section-title">
                            <i class="fas fa-image me-2"></i>Gambar Utama
                        </h5>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gambar" class="form-label">Ubah Gambar Utama</label>
                            <input type="file" accept="image/*" class="form-control" id="gambar" name="gambar">
                            <input type="hidden" value="<?= htmlspecialchars($hasil['gambar']); ?>" name="gambar_cek">
                            <div class="form-text">Format: JPG, PNG, JPEG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="main-image-container text-center">
                            <label class="form-label mb-3">Gambar Utama Saat Ini</label>
                            <div class="position-relative d-inline-block">
                                <img src="../../assets/image/<?= htmlspecialchars($hasil['gambar']); ?>" class="img-fluid rounded" style="max-width: 300px;" alt="Gambar Mobil">
                                <span class="badge bg-primary status-badge">Utama</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gambar Tambahan -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="section-title">
                            <i class="fas fa-images me-2"></i>Gambar Tambahan
                        </h5>
                    </div>
                </div>

                <!-- Gambar Tambahan Saat Ini -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-list me-2"></i>Gambar Tambahan Saat Ini
                        </h6>
                        <div class="row" id="existing-gambar-tambahan-container">
                            <?php if(empty($hasil_gambar)): ?>
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-info-circle me-2"></i>Belum ada gambar tambahan
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach($hasil_gambar as $gambar): ?>
                                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4 image-card" id="image-card-<?= $gambar->id_gambar; ?>">
                                        <div class="card h-100 shadow-sm">
                                            <img src="../../assets/image/<?= htmlspecialchars($gambar->nama_gambar); ?>" class="card-img-top" alt="Gambar Tambahan">
                                            <div class="card-body text-center">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input delete-gambar-checkbox" name="hapus_gambar[]" value="<?= $gambar->id_gambar; ?>" id="delete-<?= $gambar->id_gambar; ?>">
                                                    <label class="form-check-label text-danger fw-bold" for="delete-<?= $gambar->id_gambar; ?>">
                                                        <i class="fas fa-trash me-1"></i>Hapus Gambar
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tambah Gambar Tambahan Baru -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Gambar Tambahan Baru
                        </h6>
                        <div id="gambar-tambahan-container-new">
                            <!-- Dynamic image inputs will be added here -->
                        </div>
                        <button type="button" class="btn btn-outline-primary mt-3" id="add-gambar-tambahan-new">
                            <i class="fas fa-plus me-2"></i>Tambah Gambar Baru
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <hr>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Pastikan semua data sudah benar sebelum menyimpan
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Keunggulan Management
    const keunggulanContainer = document.getElementById('keunggulan-container');
    const addKeunggulanButton = document.getElementById('add-keunggulan');

    const updateKeunggulanNumbering = () => {
        const advantages = keunggulanContainer.querySelectorAll('.input-group-text');
        advantages.forEach((adv, index) => {
            adv.textContent = index + 1;
        });
    }

    const addAdvantageInput = (value = '') => {
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mb-3');
        inputGroup.innerHTML = `
            <span class="input-group-text bg-primary text-white fw-bold">${keunggulanContainer.children.length + 1}</span>
            <input type="text" class="form-control" name="keunggulan[]" value="${value}" placeholder="Contoh: Bensin gratis, Supir profesional, dll." required>
            <button type="button" class="btn btn-danger remove-keunggulan">
                <i class="fas fa-times"></i>
            </button>
        `;
        keunggulanContainer.appendChild(inputGroup);
        updateKeunggulanNumbering();
    }

    addKeunggulanButton.addEventListener('click', () => addAdvantageInput());

    keunggulanContainer.addEventListener('click', function(e) {
        const removeButton = e.target.closest('.remove-keunggulan');
        if (removeButton) {
            if (keunggulanContainer.children.length > 1) {
                removeButton.closest('.input-group').remove();
                updateKeunggulanNumbering();
            } else {
                alert('Setidaknya harus ada satu keunggulan.');
            }
        }
    });

    // Dynamic image upload fields for new images
    const newGambarTambahanContainer = document.getElementById('gambar-tambahan-container-new');
    const addGambarTambahanNewButton = document.getElementById('add-gambar-tambahan-new');

    const addImageInput = () => {
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mb-3');
        inputGroup.innerHTML = `
            <input type="file" accept="image/*" class="form-control" name="gambar_tambahan[]" required>
            <button type="button" class="btn btn-danger remove-gambar-tambahan-new">
                <i class="fas fa-times"></i>
            </button>
        `;
        newGambarTambahanContainer.appendChild(inputGroup);
    };

    addGambarTambahanNewButton.addEventListener('click', addImageInput);

    newGambarTambahanContainer.addEventListener('click', function(e) {
        const removeButton = e.target.closest('.remove-gambar-tambahan-new');
        if (removeButton) {
            removeButton.closest('.input-group').remove();
        }
    });

    // Add one new image input on page load
    addImageInput();

    // Handle existing image deletion checkboxes
    const existingGambarTambahanContainer = document.getElementById('existing-gambar-tambahan-container');
    existingGambarTambahanContainer.addEventListener('change', function(e) {
        const checkbox = e.target.closest('.delete-gambar-checkbox');
        if (checkbox) {
            const imageCard = checkbox.closest('.image-card');
            if (checkbox.checked) {
                imageCard.classList.add('deleted');
            } else {
                imageCard.classList.remove('deleted');
            }
        }
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const harga = document.getElementById('harga').value;
        if (harga < 0) {
            e.preventDefault();
            alert('Harga tidak boleh negatif!');
            document.getElementById('harga').focus();
        }
    });
});
</script>

<?php include '../footer.php';?>