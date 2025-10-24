<?php

    session_start();
    require 'koneksi/koneksi.php';
    include 'header.php';
    if(empty($_SESSION['USER']))
    {
        echo '<script>alert("Harap Login");window.location="index.php"</script>';
        exit();
    }
    $id_login = $_SESSION['USER']['id_login'];
    $hasil = $koneksi->prepare("SELECT mobil.merk, booking.*, supir.nama_supir, supir.harga_sewa as harga_supir FROM booking JOIN mobil ON
    booking.id_mobil=mobil.id_mobil LEFT JOIN supir ON booking.id_supir=supir.id_supir WHERE booking.id_login = ? ORDER BY id_booking DESC");
    $hasil->execute(array($id_login));
    $count = $hasil->rowCount();
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
</style>
<br>
<br>
<div class="container my-4">
<div class="row">
    <div class="col-sm-12">
         <div class="card shadow-lg">
            <div class="card-header text-white">
                <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i> Daftar Transaksi</h5>
            </div>
            <div class="card-body">
                <?php if($count > 0){?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered ">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Kode Booking</th>
                                <th>Merk Mobil</th>
                                <th>Nama </th>
                                <th>Tanggal Sewa </th>
                                <th class="text-center">Lama Sewa </th>
                                <th>Supir</th>
                                <th>Total Harga</th>
                                <th class="text-center">Konfirmasi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  $no=1; foreach($hasil as $isi){?>
                            <tr>
                                <td class="text-center align-middle"><?php echo $no;?></td>
                                <td class="align-middle"><?= htmlspecialchars($isi['kode_booking']);?></td>
                                <td class="align-middle"><?= htmlspecialchars($isi['merk']);?></td>
                                <td class="align-middle"><?= htmlspecialchars($isi['nama']);?></td>
                                <td class="align-middle"><?= date('d/m/Y', strtotime($isi['tanggal']));?></td>
                                <td class="text-center align-middle"><?= htmlspecialchars($isi['lama_sewa']);?> hari</td>
                                <td class="align-middle">
                                    <?php if (!empty($isi['nama_supir'])): ?>
                                        <div>
                                            <strong><?= htmlspecialchars($isi['nama_supir']);?></strong><br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($isi['lama_sewa']);?> hari x Rp<?= number_format($isi['harga_supir']);?>
                                            </small>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">Rp. <?= number_format(htmlspecialchars($isi['total_harga']));?></td>
                                <td class="text-center align-middle">
                                    <?php if($isi['konfirmasi_pembayaran'] == 'Pembayaran Diterima'){ ?>
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Terbayarkan</span>
                                    <?php } elseif($isi['konfirmasi_pembayaran'] == 'Sedang Diproses'){ ?>
                                        <span class="badge bg-warning"><i class="fas fa-hourglass-half me-1"></i> Diproses</span>
                                    <?php } elseif($isi['konfirmasi_pembayaran'] == 'Sudah Dibayar'){ ?>
                                        <span class="badge bg-info"><i class="fas fa-info-circle me-1"></i> Selesai</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Belum Dibayar</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center align-middle">
                                    <a class="btn btn-primary btn-sm" href="bayar.php?id=<?= $isi['kode_booking'];?>" 
                                    role="button"><i class="fas fa-info-circle me-1"></i> Detail</a>   
                                </td>
                            </tr>
                            <?php $no++;}?>
                        </tbody>
                    </table>
                </div>
                <?php } else {?>
                    <div class="text-center py-5">
                        <h3 class="text-muted mb-3"><i class="fas fa-box-open fa-2x"></i></h3>
                        <h3 class="mb-3">Anda belum memiliki riwayat pesanan.</h3>
                        <p class="lead">Ayo mulai petualangan Anda dengan menyewa mobil impian!</p>
                        <a href="blog.php" class="btn btn-primary btn-lg mt-3"><i class="fas fa-car me-2"></i>Lihat Pilihan Mobil</a>
                    </div>
                <?php }?>
           </div>
         </div> 
    </div>
</div>
</div>

<br>

<br>

<br>


<?php include 'footer.php';?>
<?php if (isset($_GET['status']) && $_GET['status'] == 'konfirmasisuccess'): ?>
<div class="alert alert-success">
    Konfirmasi Terkirim! Pembayaran Anda sedang kami proses. Terima kasih.
</div>
<?php endif; ?>

<?php
// Proses konfirmasi pembayaran
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_booking'])) {
    $id_booking = $_POST['id_booking']; // pastikan ini didapat dari form

    // Update status booking otomatis ke "Sedang Diproses"
    $koneksi->query("UPDATE booking SET konfirmasi_pembayaran='Sedang Diproses' WHERE id_booking='$id_booking'");

    // Kirim notifikasi ke user (opsional)
    $pesan = "Konfirmasi Terkirim! Pembayaran Anda sedang kami proses. Terima kasih.";
    $user_id = $_SESSION['USER']['id_login'];
    $koneksi->query("INSERT INTO notifikasi (id_login, pesan, status_baca) VALUES ('$user_id', '$pesan', 0)");

    // Redirect ke halaman sukses
    header("Location: history.php?status=konfirmasisuccess");
    exit();
}
?>