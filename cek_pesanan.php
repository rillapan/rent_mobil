<?php
    session_start();
    require 'koneksi/koneksi.php';
    include 'header.php';

    $booking_data = null;
    $error_message = null;

    if (isset($_POST['kode_booking'])) {
        $kode_booking = htmlspecialchars($_POST['kode_booking']);

        $sql = "SELECT mobil.merk, mobil.no_plat, mobil.gambar, booking.* FROM booking JOIN mobil ON 
                booking.id_mobil=mobil.id_mobil WHERE booking.kode_booking = ?";
        $row = $koneksi->prepare($sql);
        $row->execute(array($kode_booking));
        $booking_data = $row->fetch();

        if (!$booking_data) {
            $error_message = "Pesanan dengan Kode Booking '" . $kode_booking . "' tidak ditemukan.";
        }
    }
?>
<style>
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
    .badge {
        color: white !important;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: .5em .75em;
        font-size: 95%;
        border-radius: .3rem;
    }
    .badge i {
        margin-right: .3em;
    }
    /* Custom Modal Styles */
    .custom-modal-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border-bottom: none;
        border-top-left-radius: .3rem;
        border-top-right-radius: .3rem;
    }
    .custom-modal-footer {
        border-top: none;
    }
    .custom-modal-content {
        border-radius: .3rem;
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }
</style>
<br>
<br>
<div class="container my-4">
    <div class="card shadow-lg">
        <div class="card-header text-white">
            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i> Cek Detail Pesanan</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="cek_pesanan.php">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="kode_booking" placeholder="Masukkan Kode Booking Anda" required>
                    <button class="btn btn-secondary" type="submit"><i class="fas fa-search me-2"></i>Cari Pesanan</button>
                </div>
            </form>

            <?php if ($booking_data): ?>
                <hr>
                <h5 class="mb-3">Informasi Pesanan</h5>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <td>Kode Booking</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($booking_data['kode_booking']); ?></td>
                            </tr>
                            <tr>
                                <td>Nama Pelanggan</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($booking_data['nama']); ?></td>
                            </tr>
                            <tr>
                                <td>No. Telepon</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($booking_data['no_tlp']); ?></td>
                            </tr>
                            <tr>
                                <td>No. KTP</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($booking_data['ktp']); ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Sewa</td>
                                <td>:</td>
                                <td><?= date('d/m/Y', strtotime(htmlspecialchars($booking_data['tanggal']))); ?></td>
                            </tr>
                            <tr>
                                <td>Lama Sewa</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($booking_data['lama_sewa']); ?> hari</td>
                            </tr>
                            <tr>
                                <td>Total Harga</td>
                                <td>:</td>
                                <td>Rp. <?= number_format(htmlspecialchars($booking_data['total_harga'])); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-car me-2"></i> Detail Mobil</h6>
                            </div>
                            <div class="card-body text-center">
                                <img src="assets/image/<?= htmlspecialchars($booking_data['gambar']); ?>" class="img-fluid rounded mb-3" style="max-height: 150px; object-fit: cover;" alt="Gambar Mobil">
                                <h5><?= htmlspecialchars($booking_data['merk']); ?></h5>
                                <p class="text-muted">No. Plat: <?= htmlspecialchars($booking_data['no_plat']); ?></p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Status Pembayaran:</strong>
                                    <?php if($booking_data['konfirmasi_pembayaran'] == 'Pembayaran Diterima'){ ?>
                                        <span class="badge bg-success float-end"><i class="fas fa-check-circle me-1"></i> Pembayaran Diterima</span>
                                    <?php } elseif($booking_data['konfirmasi_pembayaran'] == 'Sedang Diproses'){ ?>
                                        <span class="badge bg-warning float-end"><i class="fas fa-hourglass-half me-1"></i> Diproses</span>
                                    <?php }else { ?>
                                        <span class="badge bg-danger float-end"><i class="fas fa-times-circle me-1"></i> Belum Dibayar</span>
                                    <?php } ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<br>
<br>
<?php include 'footer.php';?>

<!-- Custom Not Found Modal -->
<div class="modal fade" id="notFoundModal" tabindex="-1" role="dialog" aria-labelledby="notFoundModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content custom-modal-content">
      <div class="modal-header custom-modal-header">
        <h5 class="modal-title" id="notFoundModalLabel"><i class="fas fa-exclamation-triangle me-2"></i> Pesanan Tidak Ditemukan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center py-4">
        <i class="fas fa-frown fa-4x text-warning mb-3"></i>
        <p class="lead"><?= $error_message; ?></p>
        <p class="text-muted">Pastikan Kode Booking yang Anda masukkan sudah benar.</p>
      </div>
      <div class="modal-footer custom-modal-footer justify-content-center">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<?php if ($error_message): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#notFoundModal').modal('show');
    });
</script>
<?php endif; ?>