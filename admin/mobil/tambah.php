<?php
    require '../../koneksi/koneksi.php';
    $title_web = 'Tambah Mobil';
    include '../header.php';
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

    .section-title {
        color: var(--primary);
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid var(--primary);
        display: inline-block;
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

    .info-box {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid var(--primary);
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-box h6 {
        color: var(--primary);
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .info-box ul {
        margin-bottom: 0;
    }

    .info-box li {
        margin-bottom: 0.5rem;
    }
</style>

<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Mobil Baru
                    </h4>
                    <p class="mb-0 opacity-75">Menambahkan data mobil baru ke dalam sistem</p>
                </div>
                <a href="mobil.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Info Box -->
            <div class="info-box">
                <h6><i class="fas fa-lightbulb me-2"></i>Informasi Penting</h6>
                <ul class="mb-0">
                    <li>Pastikan semua data yang dimasukkan sudah benar dan lengkap</li>
                    <li>Gambar utama akan ditampilkan sebagai thumbnail di halaman utama</li>
                    <li>Gambar tambahan akan ditampilkan di halaman detail mobil</li>
                    <li>Format gambar yang didukung: JPG, PNG, JPEG (Maksimal 2MB per gambar)</li>
                </ul>
            </div>

            <form method="post" action="proses.php?aksi=tambah" enctype="multipart/form-data">
                
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
                            <input type="text" class="form-control" id="no_plat" name="no_plat" placeholder="Contoh: B 1234 ABC" required>
                            <div class="form-text">Masukkan nomor plat kendaraan dengan format yang benar</div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="merk" class="form-label">Merk Mobil</label>
                            <select class="form-select" id="merk" name="merk" required>
                                <option value="">Pilih Merk Mobil</option>
                                <?php
                                $sql_merk = "SELECT * FROM tbl_merk ORDER BY merk ASC";
                                $row_merk = mysqli_prepare($koneksi, $sql_merk);
                                mysqli_stmt_execute($row_merk);
                                $result_merk = mysqli_stmt_get_result($row_merk);
                                while ($merk = mysqli_fetch_assoc($result_merk)) {
                                    echo '<option value="' . htmlspecialchars($merk['merk']) . '">' . htmlspecialchars($merk['merk']) . '</option>';
                                }
                                mysqli_stmt_close($row_merk);
                                ?>
                            </select>
                            <div class="form-text">Pilih merk mobil dari daftar yang tersedia</div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="harga" class="form-label">Harga Sewa per Hari</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh: 300000" min="0" required>
                            </div>
                            <div class="form-text">Masukkan harga sewa per hari dalam Rupiah</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="deskripsi" class="form-label">Deskripsi Mobil</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi lengkap tentang mobil, fitur, kondisi, dan spesifikasi" required></textarea>
                            <div class="form-text">Jelaskan secara detail tentang mobil untuk menarik perhatian customer</div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="status" class="form-label">Status Ketersediaan</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="" disabled selected>Pilih Status Ketersediaan</option>
                                <option value="Tersedia">Tersedia</option>
                                <option value="Tidak Tersedia">Tidak Tersedia</option>
                            </select>
                            <div class="form-text">Pilih status ketersediaan mobil</div>
                        </div>
                    </div>
                </div>

                <!-- Keunggulan Paket -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="section-title">
                            <i class="fas fa-star me-2"></i>Keunggulan Paket
                        </h5>
                        <p class="text-muted mb-3">Tambahkan keunggulan-keunggulan yang ditawarkan dalam paket sewa mobil ini</p>
                        
                        <div class="form-group">
                            <div id="keunggulan-container">
                                <!-- Dynamic inputs will be added here -->
                            </div>
                            <button type="button" class="btn btn-outline-primary mt-3" id="add-keunggulan">
                                <i class="fas fa-plus me-2"></i>Tambah Keunggulan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Gambar Mobil -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="section-title">
                            <i class="fas fa-images me-2"></i>Gambar Mobil
                        </h5>
                    </div>
                </div>

                <!-- Gambar Utama -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gambar" class="form-label">Gambar Utama</label>
                            <input type="file" accept="image/*" class="form-control" id="gambar" name="gambar" required>
                            <div class="form-text">Gambar utama akan ditampilkan sebagai thumbnail. Format: JPG, PNG, JPEG. Maksimal 2MB.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info h-100">
                            <h6><i class="fas fa-info-circle me-2"></i>Tips Gambar Utama</h6>
                            <ul class="mb-0">
                                <li>Gunakan gambar dengan resolusi tinggi</li>
                                <li>Pilih angle terbaik dari mobil</li>
                                <li>Pastikan gambar jelas dan terang</li>
                                <li>Rasio disarankan: 4:3 atau 16:9</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Gambar Tambahan -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-plus-circle me-2"></i>Gambar Tambahan
                        </h6>
                        <p class="text-muted mb-3">Tambahkan gambar-gambar lain untuk menunjukkan detail mobil dari berbagai angle</p>
                        
                        <div id="gambar-tambahan-container">
                            <!-- Dynamic image inputs will be added here -->
                        </div>
                        <button type="button" class="btn btn-outline-primary mt-3" id="add-gambar-tambahan">
                            <i class="fas fa-plus me-2"></i>Tambah Gambar Tambahan
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <hr>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Pastikan semua data sudah lengkap dan benar sebelum menyimpan
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save me-2"></i>Simpan Mobil Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Keunggulan Management
    const container = document.getElementById('keunggulan-container');
    const addButton = document.getElementById('add-keunggulan');

    const updateKeunggulanNumbering = () => {
        const advantages = container.querySelectorAll('.input-group-text');
        advantages.forEach((adv, index) => {
            adv.textContent = index + 1;
        });
    }

    const addAdvantageInput = (value = '') => {
        const advantageCount = container.children.length + 1;
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mb-3');
        inputGroup.innerHTML = `
            <span class="input-group-text bg-primary text-white fw-bold">${advantageCount}</span>
            <input type="text" class="form-control" name="keunggulan[]" value="${value}" placeholder="Contoh: Bensin gratis, Supir profesional, Free antar-jemput, dll." required>
            <button type="button" class="btn btn-danger remove-keunggulan">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(inputGroup);
        updateKeunggulanNumbering();
    }

    addButton.addEventListener('click', () => addAdvantageInput());

    container.addEventListener('click', function(e) {
        const removeButton = e.target.closest('.remove-keunggulan');
        if (removeButton) {
            if (container.children.length > 1) {
                removeButton.closest('.input-group').remove();
                updateKeunggulanNumbering();
            } else {
                alert('Setidaknya harus ada satu keunggulan.');
            }
        }
    });

    // Add one input on page load
    addAdvantageInput();

    // Gambar Tambahan Management
    const gambarTambahanContainer = document.getElementById('gambar-tambahan-container');
    const addGambarTambahanButton = document.getElementById('add-gambar-tambahan');

    const addImageInput = () => {
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mb-3');
        inputGroup.innerHTML = `
            <input type="file" accept="image/*" class="form-control" name="gambar_tambahan[]" required>
            <button type="button" class="btn btn-danger remove-gambar-tambahan">
                <i class="fas fa-times"></i>
            </button>
        `;
        gambarTambahanContainer.appendChild(inputGroup);
    };

    addGambarTambahanButton.addEventListener('click', addImageInput);

    gambarTambahanContainer.addEventListener('click', function(e) {
        const removeButton = e.target.closest('.remove-gambar-tambahan');
        if (removeButton) {
            removeButton.closest('.input-group').remove();
        }
    });

    // Add one image input on page load
    addImageInput();

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const harga = document.getElementById('harga').value;
        if (harga < 0) {
            e.preventDefault();
            alert('Harga tidak boleh negatif!');
            document.getElementById('harga').focus();
        }

        // Validate at least one advantage exists
        const advantages = container.querySelectorAll('input[name="keunggulan[]"]');
        let hasValidAdvantage = false;
        advantages.forEach(adv => {
            if (adv.value.trim() !== '') {
                hasValidAdvantage = true;
            }
        });

        if (!hasValidAdvantage) {
            e.preventDefault();
            alert('Setidaknya harus ada satu keunggulan yang diisi!');
            container.querySelector('input[name="keunggulan[]"]').focus();
        }
    });

    // Real-time validation for price input
    const hargaInput = document.getElementById('harga');
    hargaInput.addEventListener('input', function() {
        if (this.value < 0) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
});
</script>

<?php include '../footer.php';?>