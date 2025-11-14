<?php
session_start();
require 'koneksi/koneksi.php';
require 'header.php';

if (!isset($_SESSION['USER'])) {
    echo '<script>alert("Anda harus login untuk melihat notifikasi.");window.location="login.php";</script>';
    exit();
}

$id_login_user = $_SESSION['USER']['id_login'];

// Tandai semua notifikasi yang belum dibaca sebagai sudah dibaca
$stmt_mark_read = mysqli_prepare($koneksi, "UPDATE notifikasi SET status_baca = 1 WHERE id_login = ? AND status_baca = 0");
mysqli_stmt_bind_param($stmt_mark_read, "i", $id_login_user);
mysqli_stmt_execute($stmt_mark_read);
mysqli_stmt_close($stmt_mark_read);

// Ambil semua notifikasi untuk user yang sedang login dengan data booking
$stmt_notifikasi = mysqli_prepare($koneksi, "
    SELECT n.*, b.kode_booking, b.konfirmasi_pembayaran
    FROM notifikasi n
    LEFT JOIN booking b ON n.id_booking = b.id_booking
    WHERE n.id_login = ?
    ORDER BY n.created_at DESC
");
mysqli_stmt_bind_param($stmt_notifikasi, "i", $id_login_user);
mysqli_stmt_execute($stmt_notifikasi);
$result_stmt = mysqli_stmt_get_result($stmt_notifikasi);
$notifikasi = [];
while ($row = mysqli_fetch_assoc($result_stmt)) {
    $notifikasi[] = $row;
}
mysqli_stmt_close($stmt_notifikasi);

?>

<div class="container" style="margin-top: 30px;">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-bell"></i> Notifikasi Anda</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($notifikasi)) { ?>
                        <div class="alert alert-info text-center" role="alert">
                            Tidak ada notifikasi untuk Anda saat ini.
                        </div>
                    <?php } else { ?>
                        <ul class="list-group">
                            <?php foreach ($notifikasi as $notif) { 
                                // Deteksi jika notifikasi adalah untuk Pembayaran Ditolak
                                $is_pembayaran_ditolak = false;
                                if (!empty($notif['konfirmasi_pembayaran']) && $notif['konfirmasi_pembayaran'] == 'Pembayaran Ditolak') {
                                    $is_pembayaran_ditolak = true;
                                } elseif (stripos($notif['pesan'], 'Pembayaran Ditolak') !== false || stripos($notif['pesan'], 'pembayaran Anda ditolak') !== false) {
                                    $is_pembayaran_ditolak = true;
                                }
                            ?>
                                <li class="list-group-item <?php echo ($notif['status_baca'] == 0) ? 'list-group-item-light' : ''; ?> <?php echo $is_pembayaran_ditolak ? 'border-danger' : ''; ?>">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="mb-2"><?= $notif['pesan']; ?></div>
                                            <small class="text-muted"><i class="far fa-clock"></i> <?= date('d M Y, H:i', strtotime($notif['created_at'])); ?></small>
                                            
                                            <!-- Opsi Aksi untuk Pembayaran Ditolak -->
                                            <?php if ($is_pembayaran_ditolak && !empty($notif['kode_booking'])) { ?>
                                                <div class="mt-3 p-3 bg-light border rounded">
                                                    <h6 class="text-danger mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Pilih Tindakan:</h6>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <a href="bayar.php?id=<?= htmlspecialchars($notif['kode_booking']); ?>" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-money-bill-wave me-1"></i> Lunasi Pembayaran
                                                        </a>
                                                        <button type="button" class="btn btn-warning btn-sm" onclick="showRefundModal('<?= htmlspecialchars($notif['kode_booking']); ?>')">
                                                            <i class="fas fa-undo me-1"></i> Batalkan Pesanan (Ajukan Refund)
                                                        </button>
                                                        <a href="kontak.php" class="btn btn-info btn-sm">
                                                            <i class="fas fa-headset me-1"></i> Hubungi Customer Support
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($notif['status_baca'] == 0) { ?>
                                            <span class="badge badge-primary badge-pill ml-2">Baru</span>
                                        <?php } ?>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Refund -->
<div class="modal fade" id="refundModal" tabindex="-1" role="dialog" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="refundModalLabel">
                    <i class="fas fa-undo me-2"></i>Ajukan Refund
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="koneksi/proses.php?id=ajukan_refund">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Permintaan refund Anda akan diproses oleh tim admin. Kami akan menghubungi Anda dalam 1-3 hari kerja.
                    </div>
                    
                    <div class="form-group">
                        <label for="kode_booking_refund">Kode Booking</label>
                        <input type="text" class="form-control" id="kode_booking_refund" name="kode_booking" readonly required>
                    </div>
                    
                    <div class="form-group">
                        <label for="alasan_refund">Alasan Refund <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasan_refund" name="alasan_refund" rows="4" placeholder="Jelaskan alasan Anda mengajukan refund..." required></textarea>
                        <small class="form-text text-muted">Mohon jelaskan alasan refund secara detail untuk mempercepat proses.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="no_rekening_refund">Nomor Rekening untuk Refund <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="no_rekening_refund" name="no_rekening_refund" placeholder="Masukkan nomor rekening Anda" required>
                        <small class="form-text text-muted">Pastikan nomor rekening Anda benar untuk proses refund.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="nama_rekening_refund">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_rekening_refund" name="nama_rekening_refund" placeholder="Masukkan nama pemilik rekening" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane me-2"></i>Ajukan Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRefundModal(kodeBooking) {
    document.getElementById('kode_booking_refund').value = kodeBooking;
    $('#refundModal').modal('show');
}
</script>

<?php 
// Tampilkan notifikasi sukses/gagal
if(isset($_GET['status'])) {
    if($_GET['status'] == 'refundsuccess') {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Permintaan refund Anda telah dikirim. Tim admin akan memproses dalam 1-3 hari kerja.",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            });
        </script>';
    } elseif($_GET['status'] == 'refundfailed') {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Gagal!",
                    text: "Terjadi kesalahan saat mengajukan refund. Silakan coba lagi.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            });
        </script>';
    }
}
require 'footer.php'; ?>