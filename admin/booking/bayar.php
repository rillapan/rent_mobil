<?php

    require '../../koneksi/koneksi.php';
    $title_web = 'Konfirmasi Pembayaran';
    include '../header.php';

    // Cek apakah user sudah login
    if (empty($_SESSION['USER'])) {
        echo '<script>alert("Silakan login terlebih dahulu."); window.location="index.php"</script>';
        exit();
    }

    $kode_booking = htmlspecialchars($_GET['id']);

    // Mengambil data booking menggunakan prepared statement untuk keamanan
    $stmt_booking = mysqli_prepare($koneksi, "SELECT * FROM booking WHERE kode_booking = ?");
    mysqli_stmt_bind_param($stmt_booking, "s", $kode_booking);
    mysqli_stmt_execute($stmt_booking);
    $result_stmt_booking = mysqli_stmt_get_result($stmt_booking);
    $hasil = mysqli_fetch_assoc($result_stmt_booking);
    mysqli_stmt_close($stmt_booking);

    if (!$hasil) {
        echo '<script>alert("Data booking tidak ditemukan."); window.location="peminjaman.php"</script>';
        exit();
    }

    // Mengambil data pembayaran
    $id_booking = $hasil['id_booking'];
    $stmt_pembayaran = mysqli_prepare($koneksi, "SELECT * FROM pembayaran WHERE id_booking = ?");
    mysqli_stmt_bind_param($stmt_pembayaran, "i", $id_booking);
    mysqli_stmt_execute($stmt_pembayaran);
    $result_stmt_pembayaran = mysqli_stmt_get_result($stmt_pembayaran);
    $result_pembayaran = [];
    while ($row = mysqli_fetch_assoc($result_stmt_pembayaran)) {
        $result_pembayaran[] = $row;
    }
    mysqli_stmt_close($stmt_pembayaran);
    $c = count($result_pembayaran);
    $hsl = $c > 0 ? $result_pembayaran[0] : null;

    // Mengambil data mobil
    $id_mobil = $hasil['id_mobil'];
    $stmt_mobil = mysqli_prepare($koneksi, "SELECT * FROM mobil WHERE id_mobil = ?");
    mysqli_stmt_bind_param($stmt_mobil, "i", $id_mobil);
    mysqli_stmt_execute($stmt_mobil);
    $result_stmt_mobil = mysqli_stmt_get_result($stmt_mobil);
    $isi = mysqli_fetch_assoc($result_stmt_mobil);
    mysqli_stmt_close($stmt_mobil);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title><?= $title_web; ?></title>
    <style>
        :root {
            --primary: #1A237E;
            --primary-dark: #1A3CC9;
            --secondary: #FF6B35;
            --light: #F8F9FA;
            --dark: #212529;
        }
        .card-header {
            background-color: var(--primary);
            color: white;
        }
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .btn-secondary {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        .btn-secondary:hover {
            background-color: #e55e2d;
            border-color: #e55e2d;
        }
        .list-group-item.status-available {
            background-color: #28a745;
            color: white;
        }
        .list-group-item.status-not-available {
            background-color: #dc3545;
            color: white;
        }
    </style>
    <script>
        function toggleRejectionForm() {
            const statusSelect = document.getElementById('statusSelect');
            const rejectionForm = document.getElementById('rejectionForm');
            const pesanPenolakan = document.getElementById('pesanPenolakan');
            
            if (statusSelect.value === 'Pembayaran Ditolak') {
                rejectionForm.style.display = 'block';
                pesanPenolakan.setAttribute('required', 'required');
            } else {
                rejectionForm.style.display = 'none';
                pesanPenolakan.removeAttribute('required');
                pesanPenolakan.value = '';
                document.getElementById('quickMessageSelect').value = '';
            }
        }
        
        function fillQuickMessage() {
            const quickSelect = document.getElementById('quickMessageSelect');
            const textarea = document.getElementById('pesanPenolakan');
            
            if (quickSelect.value) {
                textarea.value = quickSelect.value;
            }
        }
        
        // Check on page load if status is already "Pembayaran Ditolak"
        document.addEventListener('DOMContentLoaded', function() {
            toggleRejectionForm();
        });
    </script>
</head>
<body>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-12 col-md-5">
            <div class="card shadow-lg mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-money-check-alt me-2"></i> Detail Pembayaran</h5>
                </div>
                <div class="card-body">
                    <?php if ($c > 0) : ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">No. Rekening</td>
                                        <td><?= htmlspecialchars($hsl['no_rekening']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Atas Nama</td>
                                        <td><?= htmlspecialchars($hsl['nama_rekening']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nominal</td>
                                        <td>Rp<?= number_format(htmlspecialchars($hsl['nominal']), 0, ',', '.'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tgl Transfer</td>
                                        <td><?= htmlspecialchars($hsl['tanggal']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-3">
                            <h4 class="text-muted"><i class="fas fa-exclamation-circle me-2"></i> Belum ada data pembayaran.</h4>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-lg">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-car me-2"></i> Detail Mobil</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-0">
                        <img src="../../assets/image/<?= htmlspecialchars($isi['gambar']); ?>" class="img-fluid rounded" alt="Gambar Mobil">
                    </li>
                    <li class="list-group-item">
                        <p class="mb-1"><strong>Merk:</strong> <?= htmlspecialchars($isi['merk']); ?></p>
                        <p class="mb-1"><strong>No. Plat:</strong> <?= htmlspecialchars($isi['no_plat']); ?></p>
                        <p class="mb-0"><strong>Harga:</strong> Rp<?= number_format(htmlspecialchars($isi['harga']), 0, ',', '.'); ?>/hari</p>
                    </li>
                    <?php if ($isi['status'] == 'Tersedia') : ?>
                        <li class="list-group-item status-available">
                            <i class="fas fa-check-circle me-2"></i> Tersedia
                        </li>
                    <?php else : ?>
                        <li class="list-group-item status-not-available">
                            <i class="fas fa-times-circle me-2"></i> Tidak Tersedia
                        </li>
                    <?php endif; ?>
                    <li class="list-group-item bg-primary text-white d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-ticket-alt me-2"></i> Bonus</span>
                        <span>Free E-toll 50k</span>
                    </li>
                    <li class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Harga</h6>
                        <h6 class="mb-0">Rp<?= number_format(htmlspecialchars($isi['harga']), 0, ',', '.'); ?> / hari</h6>
                    </li>
                </ul>
                <div class="card-footer text-center">
                    <a href="<?= $url; ?>admin/peminjaman/peminjaman.php?id=<?= htmlspecialchars($hasil['kode_booking']); ?>" class="btn btn-secondary btn-lg w-100">
                        <i class="fas fa-exchange-alt me-2"></i> Ubah Status Peminjaman
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-7">
            <div class="card shadow-lg">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i> Detail Booking</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="proses.php?id=konfirmasi">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">Kode Booking</td>
                                        <td><?= htmlspecialchars($hasil['kode_booking']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">KTP</td>
                                        <td><?= htmlspecialchars($hasil['ktp']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nama</td>
                                        <td><?= htmlspecialchars($hasil['nama']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Telepon</td>
                                        <td><?= htmlspecialchars($hasil['no_tlp']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tanggal Sewa</td>
                                        <td><?= date('d/m/Y', strtotime(htmlspecialchars($hasil['tanggal']))); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Lama Sewa</td>
                                        <td><?= htmlspecialchars($hasil['lama_sewa']); ?> hari</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Total Harga</td>
                                        <td>Rp<?= number_format(htmlspecialchars($hasil['total_harga']), 0, ',', '.'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status Konfirmasi</td>
                                        <td>
                                            <select class="form-select" name="status" id="statusSelect" onchange="toggleRejectionForm()">
                                                <option value="Sedang Diproses" <?= ($hasil['konfirmasi_pembayaran'] == 'Sedang Diproses') ? 'selected' : ''; ?>>Sedang Diproses</option>
                                                <option value="Pembayaran Diterima" <?= ($hasil['konfirmasi_pembayaran'] == 'Pembayaran Diterima') ? 'selected' : ''; ?>>Pembayaran Diterima</option>
                                                <option value="Pembayaran Ditolak" <?= ($hasil['konfirmasi_pembayaran'] == 'Pembayaran Ditolak') ? 'selected' : ''; ?>>Pembayaran Ditolak</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Form Pesan Penolakan (muncul saat memilih Pembayaran Ditolak) -->
                        <div id="rejectionForm" style="display: none; margin-top: 20px; padding: 20px; background-color: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                            <h6 class="mb-3"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Pesan Penolakan Pembayaran</h6>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Pesan Cepat:</label>
                                <select class="form-select" id="quickMessageSelect" onchange="fillQuickMessage()">
                                    <option value="">-- Pilih Pesan Cepat --</option>
                                    <option value="Pembayaran tidak masuk ke rekening">Pembayaran tidak masuk ke rekening</option>
                                    <option value="Jumlah nominal yang dibayarkan kurang">Jumlah nominal yang dibayarkan kurang</option>
                                    <option value="Bukti transfer tidak jelas atau tidak valid">Bukti transfer tidak jelas atau tidak valid</option>
                                    <option value="Data pembayaran tidak sesuai dengan pesanan">Data pembayaran tidak sesuai dengan pesanan</option>
                                    <option value="Pembayaran melebihi batas waktu yang ditentukan">Pembayaran melebihi batas waktu yang ditentukan</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Atau Tulis Pesan Manual:</label>
                                <textarea class="form-control" name="pesan_penolakan" id="pesanPenolakan" rows="4" placeholder="Tuliskan alasan penolakan pembayaran secara detail..." required></textarea>
                                <small class="text-muted">Pesan ini akan dikirim ke pelanggan melalui notifikasi.</small>
                            </div>
                        </div>
                        
                        <input type="hidden" name="id_booking" value="<?= htmlspecialchars($hasil['id_booking']); ?>">
                        <button type="submit" class="btn btn-secondary float-end mt-3">
                            <i class="fas fa-save me-2"></i> Ubah Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>

</body>
</html>