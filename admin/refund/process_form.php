<?php
require '../../koneksi/koneksi.php';
$title_web = 'Proses Pengembalian Dana';
$url = '../../';
include '../header.php';

// Pastikan tabel refund_requests tersedia
mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS refund_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_booking INT NOT NULL,
    kode_booking VARCHAR(255) NOT NULL,
    id_login INT NOT NULL,
    nama_pelanggan VARCHAR(255) NOT NULL,
    no_hp VARCHAR(50),
    metode_pembayaran VARCHAR(100),
    alasan_penolakan TEXT,
    alasan_refund TEXT,
    no_rekening_refund VARCHAR(100),
    nama_rekening_refund VARCHAR(150),
    catatan_admin TEXT,
    status VARCHAR(50) DEFAULT 'Menunggu Refund',
    nominal_refund DECIMAL(15,2) DEFAULT NULL,
    total_pembayaran DECIMAL(15,2) DEFAULT 0,
    tanggal_pesanan DATE DEFAULT NULL,
    processed_at DATETIME DEFAULT NULL,
    admin_id INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_refund_booking (id_booking)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Ambil ID refund dari URL
$refund_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($refund_id <= 0) {
    echo '<script>alert("ID refund tidak valid.");window.location="index.php";</script>';
    exit();
}

// Ambil data refund beserta detail booking
$sql_refund = "SELECT
        rr.*,
        b.tanggal AS tanggal_booking,
        b.tgl_input,
        b.total_harga,
        b.konfirmasi_pembayaran,
        b.no_tlp,
        b.nama,
        b.nama AS nama_booking,
        b.alamat,
        mobil.merk AS merk_mobil,
        mobil_plat.no_plat,
        mobil.harga AS harga_mobil,
        l.nama_pengguna,
        l.no_hp AS user_no_hp,
        br.reason AS alasan_penolakan_db
    FROM refund_requests rr
    JOIN booking b ON rr.id_booking = b.id_booking
    LEFT JOIN mobil ON b.id_mobil = mobil.id_mobil
    LEFT JOIN mobil_plat ON b.id_plat = mobil_plat.id_plat
    LEFT JOIN login l ON b.id_login = l.id_login
    LEFT JOIN booking_rejections br ON b.id_booking = br.id_booking
    WHERE rr.id = ? AND rr.status = 'Menunggu Refund'";
$stmt_refund = mysqli_prepare($koneksi, $sql_refund);
mysqli_stmt_bind_param($stmt_refund, "i", $refund_id);
mysqli_stmt_execute($stmt_refund);
$result_stmt_refund = mysqli_stmt_get_result($stmt_refund);
$refund = mysqli_fetch_assoc($result_stmt_refund);
mysqli_stmt_close($stmt_refund);

if (!$refund) {
    echo '<script>alert("Data refund tidak ditemukan atau sudah diproses.");window.location="index.php";</script>';
    exit();
}

$alasan_penolakan = $refund['alasan_penolakan'] ?: ($refund['alasan_penolakan_db'] ?? '-');
$tanggal_pengajuan = format_tanggal($refund['created_at']);
$tanggal_pesanan = format_tanggal($refund['tanggal_pesanan'] ?? $refund['tanggal_booking']);
$total_pembayaran = $refund['total_pembayaran'] > 0 ? format_rupiah($refund['total_pembayaran']) : format_rupiah($refund['total_harga']);
$nominal_default = ($refund['total_pembayaran'] > 0) ? $refund['total_pembayaran'] : $refund['total_harga'];

function format_rupiah($nominal) {
    if ($nominal === null) {
        return '-';
    }
    return 'Rp ' . number_format((float)$nominal, 0, ',', '.');
}

function format_tanggal($tanggal) {
    if (empty($tanggal)) {
        return '-';
    }
    $timestamp = strtotime($tanggal);
    if (!$timestamp) {
        return $tanggal;
    }
    return date('d M Y', $timestamp);
}
?>

<style>
    .process-form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .refund-info-card {
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        border: none;
        margin-bottom: 2rem;
    }
    .refund-info-card .card-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px 12px 0 0;
    }
    .refund-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .refund-info-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        min-height: 88px;
    }
    .refund-info-item i {
        color: var(--primary);
        font-size: 1.1rem;
        margin-top: 2px;
    }
    .refund-info-item strong {
        display: block;
        color: var(--dark);
        font-size: 0.95rem;
    }
    .refund-info-item span {
        color: var(--gray);
        font-size: 0.9rem;
    }
    .process-form-card {
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        border: none;
    }
    .process-form-card .card-header {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px 12px 0 0;
    }
    .form-group label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }
    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 0.75rem 1rem;
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .btn-process {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-process:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }
    .alert-info-custom {
        background: linear-gradient(135deg, #d1ecf1, #bee5eb);
        border: 1px solid #bee5eb;
        border-radius: 8px;
        color: #0c5460;
    }
</style>

<div class="container mt-4 mb-5">
    <div class="process-form-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="fas fa-hand-holding-usd me-2"></i>Proses Pengembalian Dana</h2>
                <p class="text-muted mb-0">Lengkapi form di bawah untuk memproses pengembalian dana pelanggan.</p>
            </div>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Informasi Refund -->
        <div class="card refund-info-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Detail Pengajuan Refund</h5>
            </div>
            <div class="card-body">
                <div class="refund-info-grid">
                    <div class="refund-info-item">
                        <i class="fas fa-receipt"></i>
                        <div>
                            <strong>Identitas Pesanan</strong>
                            <span>ID: <?= htmlspecialchars($refund['kode_booking']); ?><br>
                                Tanggal: <?= $tanggal_pesanan; ?><br>
                                Total: <?= $total_pembayaran; ?>
                            </span>
                        </div>
                    </div>
                    <div class="refund-info-item">
                        <i class="fas fa-user"></i>
                        <div>
                            <strong>Identitas Pelanggan</strong>
                            <span><?= htmlspecialchars($refund['nama_pelanggan'] ?? $refund['nama_pengguna'] ?? $refund['nama_booking']); ?><br>
                                Kontak: <?= htmlspecialchars($refund['no_hp'] ?? $refund['user_no_hp'] ?? $refund['no_tlp']); ?><br>
                                Metode: <?= htmlspecialchars($refund['metode_pembayaran'] ?? 'Transfer Bank'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="refund-info-item">
                        <i class="fas fa-times-circle"></i>
                        <div>
                            <strong>Alasan Penolakan</strong>
                            <span><?= nl2br(htmlspecialchars($alasan_penolakan)); ?></span>
                        </div>
                    </div>
                    <div class="refund-info-item">
                        <i class="fas fa-comment-dots"></i>
                        <div>
                            <strong>Alasan Refund Pelanggan</strong>
                            <span><?= nl2br(htmlspecialchars($refund['alasan_refund'])); ?></span>
                        </div>
                    </div>
                    <div class="refund-info-item">
                        <i class="fas fa-university"></i>
                        <div>
                            <strong>Data Pengembalian Dana</strong>
                            <span>Rekening: <?= htmlspecialchars($refund['no_rekening_refund']); ?><br>
                                Atas Nama: <?= htmlspecialchars($refund['nama_rekening_refund']); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <?php if (!empty($refund['merk_mobil'])) : ?>
                <div class="mb-0">
                    <strong class="d-block text-secondary mb-1"><i class="fas fa-car me-1"></i>Detail Kendaraan</strong>
                    <div class="text-muted">
                        <?= htmlspecialchars($refund['merk_mobil']); ?> &middot; No Plat: <?= htmlspecialchars($refund['no_plat']); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Form Proses Refund -->
        <div class="card process-form-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Form Proses Pengembalian Dana</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info-custom">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Pastikan dana telah ditransfer ke rekening pelanggan sebelum mengonfirmasi refund.</strong>
                </div>

                <form method="post" action="proses.php?id=approve_refund">
                    <input type="hidden" name="refund_id" value="<?= $refund['id']; ?>">
                    <input type="hidden" name="kode_booking" value="<?= htmlspecialchars($refund['kode_booking']); ?>">

                    <div class="form-group mb-4">
                        <label for="nama_pelanggan">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="nama_pelanggan" value="<?= htmlspecialchars($refund['nama_pelanggan'] ?? $refund['nama_pengguna'] ?? $refund['nama_booking']); ?>" readonly>
                        <small class="text-muted">Nama pelanggan berdasarkan data booking.</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="nominal_refund">Nominal Refund (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="nominal_refund" name="nominal_refund" min="0" step="1000" value="<?= $nominal_default; ?>" required>
                        <small class="text-muted">Masukkan nominal yang dikembalikan. Nilai default sesuai total pembayaran pelanggan.</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="catatan_admin">Catatan Admin (Opsional)</label>
                        <textarea class="form-control" id="catatan_admin" name="catatan_admin" rows="4" placeholder="Masukkan catatan tambahan untuk arsip internal..."></textarea>
                        <small class="text-muted">Catatan ini hanya untuk arsip admin dan tidak akan dikirim ke pelanggan.</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-process">
                            <i class="fas fa-check-circle me-1"></i> Konfirmasi Refund
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_GET['status']) && $_GET['status'] === 'refundapproved') : ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Refund Berhasil Diproses!',
            text: 'Notifikasi telah dikirim ke pelanggan.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'index.php';
        });
    });
</script>
<?php elseif (isset($_GET['status']) && $_GET['status'] === 'refunderror') : ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Terjadi Kesalahan',
            text: 'Gagal memproses refund. Silakan coba kembali.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
</script>
<?php endif; ?>

<?php include '../footer.php'; ?>
