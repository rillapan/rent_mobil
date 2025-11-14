<?php
require '../../koneksi/koneksi.php';
$title_web = 'Pengajuan Refund';
$url = '../../';
include '../header.php';

// Pastikan tabel refund_requests tersedia (untuk kompatibilitas deployment)
$koneksi->exec("CREATE TABLE IF NOT EXISTS refund_requests (
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

// Pastikan tabel booking_rejections tersedia
$koneksi->exec("CREATE TABLE IF NOT EXISTS booking_rejections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_booking INT NOT NULL,
    kode_booking VARCHAR(255) NOT NULL,
    reason TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_booking (id_booking)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Ambil daftar refund beserta detail tambahan
$sql_refund = "SELECT 
        rr.*,
        b.tanggal AS tanggal_booking,
        b.tgl_input,
        b.total_harga,
        b.konfirmasi_pembayaran,
        b.no_tlp,
        b.nama AS nama_booking,
        b.alamat,
        mobil.merk AS merk_mobil,
        mobil.no_plat,
        mobil.harga AS harga_mobil,
        l.nama_pengguna,
        l.no_hp AS user_no_hp,
        br.reason AS alasan_penolakan_db
    FROM refund_requests rr
    JOIN booking b ON rr.id_booking = b.id_booking
    LEFT JOIN mobil ON b.id_mobil = mobil.id_mobil
    LEFT JOIN login l ON rr.id_login = l.id_login
    LEFT JOIN booking_rejections br ON b.id_booking = br.id_booking
    ORDER BY rr.status = 'Menunggu Refund' DESC, rr.created_at DESC";
$stmt_refund = $koneksi->prepare($sql_refund);
$stmt_refund->execute();
$refund_requests = $stmt_refund->fetchAll(PDO::FETCH_ASSOC);

$pending_requests = [];
$completed_requests = [];
foreach ($refund_requests as $refund) {
    if ($refund['status'] === 'Menunggu Refund') {
        $pending_requests[] = $refund;
    } else {
        $completed_requests[] = $refund;
    }
}

$admin_name_map = [];
if (!empty($completed_requests)) {
    $admin_ids = array_unique(array_filter(array_column($completed_requests, 'admin_id')));
    if (!empty($admin_ids)) {
        $placeholders = implode(',', array_fill(0, count($admin_ids), '?'));
        $stmt_admin_map = $koneksi->prepare("SELECT id_login, nama_pengguna FROM login WHERE id_login IN ($placeholders)");
        $stmt_admin_map->execute($admin_ids);
        $admin_name_map = $stmt_admin_map->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}

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
    .refund-section-title {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1rem;
    }
    .refund-card {
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        border: none;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .refund-card .card-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 1rem 1.5rem;
    }
    .refund-card .card-body {
        padding: 1.5rem;
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
    .refund-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 0.9rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .refund-badge.pending {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        color: #fff;
    }
    .refund-badge.completed {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff;
    }
    .refund-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .alert-empty {
        border-radius: 12px;
        background: #f8f9fe;
        border: 1px solid #e0e5ff;
        color: #2c3e50;
        padding: 2rem;
        text-align: center;
    }
    @media(max-width: 576px) {
        .refund-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="refund-section-title mb-1"><i class="fas fa-undo me-2"></i>Pengajuan Refund Pelanggan</h2>
            <p class="text-muted mb-0">Kelola dan proses permintaan refund dari pelanggan dengan status pembayaran ditolak.</p>
        </div>
        <div class="text-right">
            <span class="refund-badge pending me-2"><i class="fas fa-hourglass-half"></i> Pending: <?= count($pending_requests); ?></span>
            <span class="refund-badge completed"><i class="fas fa-check-circle"></i> Selesai: <?= count($completed_requests); ?></span>
        </div>
    </div>

    <div class="mb-5">
        <h4 class="mb-3"><i class="fas fa-hourglass-half text-warning me-2"></i>Menunggu Proses</h4>
        <?php if (empty($pending_requests)) : ?>
            <div class="alert alert-info alert-empty">
                <i class="fas fa-check-circle fa-2x mb-3 text-primary"></i>
                <h5>Tidak ada pengajuan refund yang menunggu proses.</h5>
                <p class="mb-0">Semua pengajuan refund telah diproses oleh tim admin.</p>
            </div>
        <?php else : ?>
            <?php foreach ($pending_requests as $refund) : 
                $alasan_penolakan = $refund['alasan_penolakan'] ?: ($refund['alasan_penolakan_db'] ?? '-');
                $tanggal_pengajuan = format_tanggal($refund['created_at']);
                $tanggal_pesanan = format_tanggal($refund['tanggal_pesanan'] ?? $refund['tanggal_booking']);
                $total_pembayaran = $refund['total_pembayaran'] > 0 ? format_rupiah($refund['total_pembayaran']) : format_rupiah($refund['total_harga']);
            ?>
            <div class="card refund-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Kode Booking: <?= htmlspecialchars($refund['kode_booking']); ?></h5>
                        <small class="text-white-50">Diajukan pada <?= $tanggal_pengajuan; ?></small>
                    </div>
                    <span class="refund-badge pending"><i class="fas fa-spinner fa-pulse"></i> Menunggu Refund</span>
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
                    <div class="mb-3">
                        <strong class="d-block text-secondary mb-1"><i class="fas fa-car me-1"></i>Detail Kendaraan</strong>
                        <div class="text-muted">
                            <?= htmlspecialchars($refund['merk_mobil']); ?> &middot; No Plat: <?= htmlspecialchars($refund['no_plat']); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="refund-actions">
                        <button 
                            class="btn btn-success"
                            data-toggle="modal"
                            data-target="#approveRefundModal"
                            data-refund='<?= htmlspecialchars(json_encode([
                                'id' => $refund['id'],
                                'kode_booking' => $refund['kode_booking'],
                                'nama' => $refund['nama_pelanggan'] ?? $refund['nama_pengguna'] ?? $refund['nama_booking'],
                                'total' => $refund['total_pembayaran'] ?: $refund['total_harga'],
                                'rekening' => $refund['no_rekening_refund'],
                                'atas_nama' => $refund['nama_rekening_refund']
                            ]), ENT_QUOTES, 'UTF-8'); ?>'>
                            <i class="fas fa-check-circle me-1"></i> Proses Refund
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div>
        <h4 class="mb-3"><i class="fas fa-history text-success me-2"></i>Riwayat Refund Selesai</h4>
        <?php if (empty($completed_requests)) : ?>
            <div class="alert alert-light alert-empty border">
                <i class="fas fa-info-circle fa-2x mb-3 text-secondary"></i>
                <h5>Belum ada refund yang diselesaikan.</h5>
                <p class="mb-0">Refund yang telah disetujui akan tampil pada bagian ini.</p>
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Kode Booking</th>
                            <th>Pelanggan</th>
                            <th>Total Pembayaran</th>
                            <th>Nominal Refund</th>
                            <th>Tanggal Proses</th>
                            <th>Diproses Oleh</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($completed_requests as $refund) : ?>
                        <tr>
                            <td><?= htmlspecialchars($refund['kode_booking']); ?></td>
                            <td><?= htmlspecialchars($refund['nama_pelanggan'] ?? $refund['nama_pengguna'] ?? $refund['nama_booking']); ?><br>
                                <small class="text-muted"><?= htmlspecialchars($refund['no_hp'] ?? $refund['user_no_hp'] ?? $refund['no_tlp']); ?></small>
                            </td>
                            <td><?= format_rupiah($refund['total_pembayaran'] ?: $refund['total_harga']); ?></td>
                            <td><?= format_rupiah($refund['nominal_refund']); ?></td>
                            <td><?= format_tanggal($refund['processed_at']); ?></td>
                            <td><?php
                                if (!empty($refund['admin_id'])) {
                                    $admin_name = $admin_name_map[$refund['admin_id']] ?? 'Admin';
                                    echo htmlspecialchars($admin_name);
                                } else {
                                    echo '-';
                                }
                            ?></td>
                            <td><?= nl2br(htmlspecialchars($refund['alasan_refund'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Proses Refund -->
<div class="modal fade" id="approveRefundModal" tabindex="-1" role="dialog" aria-labelledby="approveRefundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveRefundModalLabel"><i class="fas fa-check-circle me-2"></i>Proses Pengembalian Dana</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="proses.php?id=approve_refund" id="approveRefundForm">
                <div class="modal-body">
                    <input type="hidden" name="refund_id" id="modalRefundId">
                    <input type="hidden" name="kode_booking" id="modalKodeBooking">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Pastikan dana telah ditransfer ke rekening pelanggan sebelum mengonfirmasi refund.
                    </div>
                    <div class="form-group">
                        <label for="modalNamaPelanggan">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="modalNamaPelanggan" readonly>
                    </div>
                    <div class="form-group">
                        <label for="modalNominalRefund">Nominal Refund (Rp)</label>
                        <input type="number" class="form-control" id="modalNominalRefund" name="nominal_refund" min="0" step="1000" required>
                        <small class="text-muted">Masukkan nominal yang dikembalikan. Nilai default sesuai total pembayarannya.</small>
                    </div>
                    <div class="form-group">
                        <label for="modalCatatanAdmin">Catatan Admin (Opsional)</label>
                        <textarea class="form-control" id="modalCatatanAdmin" name="catatan_admin" rows="3" placeholder="Masukkan catatan tambahan untuk arsip internal..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-1"></i> Konfirmasi Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#approveRefundModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const refundData = button.data('refund');
        if (!refundData) return;

        $('#modalRefundId').val(refundData.id);
        $('#modalKodeBooking').val(refundData.kode_booking);
        $('#modalNamaPelanggan').val(refundData.nama);
        const totalAmount = parseFloat(refundData.total);
        $('#modalNominalRefund').val(!isNaN(totalAmount) ? Math.round(totalAmount) : '');
    });
</script>

<?php if (isset($_GET['status']) && $_GET['status'] === 'refundapproved') : ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Refund Berhasil Diproses!',
            text: 'Notifikasi telah dikirim ke pelanggan.',
            icon: 'success',
            confirmButtonText: 'OK'
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

