<?php
    session_start();
    require 'koneksi/koneksi.php';
    include 'header.php';
    include 'progress_bar.php';
    
    if(empty($_SESSION['USER']))
    {
        echo '<script>alert("Harap login !");window.location="index.php"</script>';
    }
    
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if (empty($id)) {
        echo '<script>alert("ID mobil tidak ditemukan!");window.location="blog.php"</script>';
        exit();
    }
    
    // Validate that id is numeric
    if (!is_numeric($id)) {
        echo '<script>alert("ID mobil tidak valid!");window.location="blog.php"</script>';
        exit();
    }
    
    $isi = $koneksi->query("SELECT * FROM mobil WHERE id_mobil = '$id'")->fetch();
    if (!$isi) {
        echo '<script>alert("Mobil tidak ditemukan!");window.location="blog.php"</script>';
        exit();
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Mobil - <?= htmlspecialchars($isi['merk']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1A237E;
            --primary-light: #534bae;
            --primary-dark: #000051;
            --secondary: #FF6B35;
            --secondary-light: #ff9b68;
            --secondary-dark: #c53c03;
            --light: #F8F9FA;
            --dark: #212529;
            --gray: #6C757D;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .booking-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 2rem;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 1.5rem;
            border-bottom: none;
        }
        
        .card-header h3 {
            margin: 0;
            font-weight: 700;
        }
        
        .car-image {
            height: 300px;
            object-fit: cover;
            width: 100%;
            border-radius: 12px 12px 0 0;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .feature-list li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        
        .feature-list li:last-child {
            border-bottom: none;
        }
        
        .feature-list li i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .price-tag {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 0.25rem rgba(26, 35, 126, 0.15);
        }
        
        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 35, 126, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-light) 100%);
            border: none;
            color: white;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--secondary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
            color: white;
        }
        
        .btn-outline-secondary {
            border: 2px solid var(--secondary);
            color: var(--secondary);
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--secondary);
            color: white;
        }
        
        .total-price-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
        }
        
        .total-price {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary);
        }
        
        .section-title {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-light);
        }
        
        .info-box {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .driver-selection {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1rem 0;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #e9ecef;
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--gray);
        }
        
        .step.active .step-number {
            background-color: var(--primary);
            color: white;
        }
        
        .step.completed .step-number {
            background-color: var(--success);
            color: white;
        }
        
        .step-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray);
        }
        
        .step.active .step-label {
            color: var(--primary);
        }
        
        @media (max-width: 768px) {
            .step-indicator {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .step-indicator::before {
                display: none;
            }
            
            .step {
                flex-direction: row;
                margin-bottom: 1rem;
            }
            
            .step-number {
                margin-right: 1rem;
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>
    <div class="booking-container py-4">
      
        
        <div class="row">
            <!-- Mobil Information Card -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <img src="assets/image/<?php echo $isi['gambar'];?>" class="car-image" alt="<?php echo $isi['merk'];?>">
                    <div class="card-body">
                        <h4 class="card-title text-primary"><?php echo $isi['merk'];?></h4>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <?php if($isi['status'] == 'Tersedia'){?>
                                <span class="status-badge bg-success text-white">
                                    <i class="fas fa-check-circle me-1"></i> Tersedia
                                </span>
                            <?php }else{?>
                                <span class="status-badge bg-danger text-white">
                                    <i class="fas fa-times-circle me-1"></i> Tidak Tersedia
                                </span>
                            <?php }?>
                            
                            <div class="price-tag">
                                Rp <?php echo number_format($isi['harga']);?>/hari
                            </div>
                        </div>
                        
                        <?php if(!empty($isi['keunggulan'])){ ?>
                        <h5 class="section-title mt-4">Keunggulan Paket</h5>
                        <ul class="feature-list">
                            <?php
                            $keunggulan = explode('||', $isi['keunggulan']);
                            foreach ($keunggulan as $item) {
                                if (!empty($item)) {
                            ?>
                                    <li>
                                        <i class="fas fa-check text-success"></i> <?= htmlspecialchars($item) ?>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <!-- Booking Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-calendar-check me-2"></i> Form Pemesanan</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="koneksi/proses.php?id=booking" id="bookingForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ktp" class="form-label fw-semibold">No. KTP / NIK</label>
                                        <input type="text" name="ktp" id="ktp" required class="form-control" placeholder="Masukkan No. KTP / NIK">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                                        <input type="text" name="nama" id="nama" required class="form-control" placeholder="Masukkan Nama Lengkap">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-semibold">Alamat Lengkap</label>
                                <input type="text" name="alamat" id="alamat" required class="form-control" placeholder="Masukkan Alamat Lengkap">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="no_tlp" class="form-label fw-semibold">No. Telepon</label>
                                        <input type="text" name="no_tlp" id="no_tlp" required class="form-control" placeholder="Masukkan No. Telepon">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal" class="form-label fw-semibold">Tanggal Sewa</label>
                                        <input type="date" name="tanggal" id="tanggal" required class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="lama_sewa" class="form-label fw-semibold">Lama Sewa (hari)</label>
                                <input type="number" name="lama_sewa" id="lama_sewa" required class="form-control" placeholder="Masukkan Lama Sewa" min="1" value="1">
                            </div>
                            
                            <!-- Driver Selection -->
                            <div class="driver-selection">
                                <h5 class="fw-semibold mb-3">Pilihan Supir (Opsional)</h5>
                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                    <div>
                                        <p class="mb-2">Tambah supir untuk perjalanan Anda</p>
                                        <div id="supir_info" class="alert alert-success mt-2" style="display:none;">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Supir dipilih:</strong>
                                            <span id="nama_supir"></span> -
                                            Rp<span id="harga_supir_display"></span>/hari
                                        </div>
                                    </div>
                                    <div class="mt-2 mt-md-0">
                                        <a href="supir.php?id=<?= $isi['id_mobil']; ?>" class="btn btn-primary me-2" id="pilih_supir_link">
                                            <i class="fas fa-user me-1"></i> Pilih Supir
                                        </a>
                                        <button type="button" id="hapus_supir" class="btn btn-outline-secondary" style="display:none;">
                                            <i class="fas fa-times me-1"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Total Price -->
                            <div class="total-price-box">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="fw-semibold mb-1">Total Biaya</h5>
                                        <small class="text-muted">Termasuk biaya mobil <?php if($isi['status'] == 'Tersedia'){ echo 'dan supir (jika dipilih)'; } ?></small>
                                    </div>
                                    <div class="text-end">
                                        <div class="total-price" id="total_harga_display">Rp<?= number_format($isi['harga']); ?></div>
                                        <small class="text-muted" id="detail_harga">Mobil: Rp<?= number_format($isi['harga']); ?>/hari</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden Fields -->
                            <input type="hidden" value="<?php echo $_SESSION['USER']['id_login'];?>" name="id_login">
                            <input type="hidden" value="<?php echo $isi['id_mobil'];?>" name="id_mobil">
                            <input type="hidden" value="<?php echo $isi['harga'];?>" name="harga_mobil">
                            <input type="hidden" id="harga_supir" name="harga_supir" value="0">
                            <input type="hidden" id="id_supir" name="id_supir" value="">
                            <input type="hidden" value="" name="total_harga" id="total_harga">
                            
                            <!-- Submit Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="blog.php" class="btn btn-outline-secondary me-md-2">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <?php if($isi['status'] == 'Tersedia'){?>
                                    <button type="submit" class="btn btn-secondary px-4">
                                        <i class="fas fa-calendar-check me-1"></i> Lanjutkan Booking
                                    </button>
                                <?php }else{?>
                                    <button type="button" class="btn btn-danger px-4" disabled>
                                        <i class="fas fa-times me-1"></i> Tidak Tersedia
                                    </button>
                                <?php }?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal').min = today;

            // Function to save form data to sessionStorage
            function saveFormData() {
                const formData = {
                    ktp: document.getElementById('ktp').value,
                    nama: document.getElementById('nama').value,
                    alamat: document.getElementById('alamat').value,
                    no_tlp: document.getElementById('no_tlp').value,
                    tanggal: document.getElementById('tanggal').value,
                    lama_sewa: document.getElementById('lama_sewa').value,
                    harga_supir: document.getElementById('harga_supir').value,
                    id_supir: document.getElementById('id_supir').value,
                    nama_supir: document.getElementById('nama_supir').textContent,
                    harga_supir_display: document.getElementById('harga_supir_display').textContent
                };
                sessionStorage.setItem('bookingFormData', JSON.stringify(formData));
            }

            // Function to restore form data from sessionStorage
            function restoreFormData() {
                const savedData = sessionStorage.getItem('bookingFormData');
                if (savedData) {
                    const formData = JSON.parse(savedData);
                    document.getElementById('ktp').value = formData.ktp || '';
                    document.getElementById('nama').value = formData.nama || '';
                    document.getElementById('alamat').value = formData.alamat || '';
                    document.getElementById('no_tlp').value = formData.no_tlp || '';
                    document.getElementById('tanggal').value = formData.tanggal || '';
                    document.getElementById('lama_sewa').value = formData.lama_sewa || '1';
                    document.getElementById('harga_supir').value = formData.harga_supir || '0';
                    document.getElementById('id_supir').value = formData.id_supir || '';

                    // Restore driver info if exists
                    if (formData.id_supir && formData.nama_supir) {
                        document.getElementById('nama_supir').textContent = formData.nama_supir;
                        document.getElementById('harga_supir_display').textContent = formData.harga_supir_display;
                        document.getElementById('supir_info').style.display = 'block';
                        document.getElementById('hapus_supir').style.display = 'inline-block';
                    }

                    // Clear the saved data after restoring
                    sessionStorage.removeItem('bookingFormData');
                }
            }

            // Restore form data on page load
            restoreFormData();

            // Cek apakah ada supir yang dipilih dari sessionStorage (from supir.php)
            const selectedSupir = sessionStorage.getItem('selectedSupir');
            if (selectedSupir) {
                const supir = JSON.parse(selectedSupir);
                document.getElementById('id_supir').value = supir.id;
                document.getElementById('harga_supir').value = supir.harga;
                document.getElementById('nama_supir').textContent = supir.nama;
                document.getElementById('harga_supir_display').textContent = supir.harga.toLocaleString();
                document.getElementById('supir_info').style.display = 'block';
                document.getElementById('hapus_supir').style.display = 'inline-block';
                updateTotalHarga();
                sessionStorage.removeItem('selectedSupir'); // Hapus dari sessionStorage setelah digunakan
            }

            // Event listener untuk link pilih supir - save form data before navigating
            document.getElementById('pilih_supir_link').addEventListener('click', function(e) {
                saveFormData();
            });

            // Event listener untuk tombol hapus supir
            document.getElementById('hapus_supir').addEventListener('click', function() {
                document.getElementById('id_supir').value = '';
                document.getElementById('harga_supir').value = '0';
                document.getElementById('supir_info').style.display = 'none';
                document.getElementById('hapus_supir').style.display = 'none';
                updateTotalHarga();
            });

            // Event listener untuk perubahan lama sewa
            document.getElementById('lama_sewa').addEventListener('input', updateTotalHarga);

            // Event listener untuk form submission - clear sessionStorage
            document.getElementById('bookingForm').addEventListener('submit', function() {
                sessionStorage.removeItem('bookingFormData');
            });

            function updateTotalHarga() {
                const hargaMobil = parseInt('<?= $isi['harga']; ?>');
                const hargaSupir = parseInt(document.getElementById('harga_supir').value) || 0;
                const lamaSewa = parseInt(document.getElementById('lama_sewa').value) || 1;
                const total = (hargaMobil + hargaSupir) * lamaSewa;

                document.getElementById('total_harga_display').textContent = 'Rp' + total.toLocaleString();
                document.getElementById('total_harga').value = total;

                // Update detail harga
                let detailText = `Mobil: Rp${hargaMobil.toLocaleString()}/hari`;
                if (hargaSupir > 0) {
                    detailText += ` + Supir: Rp${hargaSupir.toLocaleString()}/hari`;
                }
                document.getElementById('detail_harga').textContent = detailText;
            }

            // Initialize total harga
            updateTotalHarga();
        });
    </script>
</body>
</html>

<?php include 'footer.php';?>