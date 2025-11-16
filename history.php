<?php

    session_start();
    require 'koneksi/koneksi.php';
    if(empty($_SESSION['USER']))
    {
        echo '<script>alert("Harap Login");window.location="index.php"</script>';
        exit();
    }
    $id_login = $_SESSION['USER']['id_login'];

    include 'header.php';

    $stmt = mysqli_prepare($koneksi, "SELECT mobil.merk, booking.*, supir.nama_supir, supir.harga_sewa as harga_supir, mobil_plat.no_plat FROM booking JOIN mobil ON
    booking.id_mobil=mobil.id_mobil LEFT JOIN supir ON booking.id_supir=supir.id_supir LEFT JOIN mobil_plat ON booking.id_plat=mobil_plat.id_plat WHERE booking.id_login = ? ORDER BY id_booking DESC");
    mysqli_stmt_bind_param($stmt, "i", $id_login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $hasil = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $hasil[] = $row;
    }
    mysqli_stmt_close($stmt);
    $count = count($hasil);
?>
<style>
    /* Penyesuaian Style untuk Tabel */
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
        /* Tambahkan ini agar teks badge bisa pindah baris jika terlalu panjang */
        white-space: normal; 
        line-height: 1.2;
    }
    .badge i {
        margin-right: .3em;
    }
    
    /* Aturan umum untuk sel tabel agar teks bisa pindah baris */
    .table td, .table th {
        white-space: normal !important; /* Memastikan teks bisa pindah baris */
    }

    /* Penyesuaian Lebar Kolom Tertentu */
    .table-responsive table {
        table-layout: auto; /* Menggunakan layout otomatis */
        width: 100%;
        /* Hapus min-width atau kurangi nilainya untuk fleksibilitas yang lebih tinggi */
    }

    /* Penyesuaian Kolom dengan Konten Potensial Panjang */
    .table th:nth-child(8), /* Supir */
    .table td:nth-child(8) { 
        min-width: 120px; 
    }
    .table th:nth-child(9), /* Total Harga */
    .table td:nth-child(9) { 
        min-width: 130px; 
    }
    .table th:nth-child(10), /* Status */
    .table td:nth-child(10) { 
        min-width: 150px; 
    }
</style>
<br>
<br>
<div class="container-fluid my-4"> 
<div class="row">
   <div class="col-sm-10 mx-auto">
        <div class="card shadow-lg"> 
            <div class="card-header text-white bg-primary">
                <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i> Daftar Transaksi</h5>
            </div>
            <div class="card-body">
                <?php if($count > 0){?>
                <div class="table-responsive"> 
                    <table class="table table-striped table-bordered"> 
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">No.</th>
                                <th style="min-width: 100px;">Kode Booking</th>
                                <th style="min-width: 120px;">Mobil</th> 
                                <th class="d-none d-lg-table-cell" style="min-width: 100px;">No. Plat</th> 
                                <th style="min-width: 120px;">Penyewa</th> 
                                <th style="min-width: 100px;">Tgl Sewa</th> 
                                <th class="text-center" style="min-width: 70px;">Lama</th> 
                                <th class="d-none d-lg-table-cell">Supir</th> 
                                <th>Total Harga</th> 
                                <th class="text-center">Status</th> 
                                <th class="text-center" style="min-width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  $no=1; foreach($hasil as $isi){
                                $total_harga_display = str_replace(',', '', number_format(htmlspecialchars($isi['total_harga'])));
                            ?>
                            <tr>
                                <td class="text-center align-middle"><?php echo $no;?></td>
                                <td class="align-middle"><?= htmlspecialchars($isi['kode_booking']);?></td>
                                <td class="align-middle"><?= htmlspecialchars($isi['merk']);?></td>
                                <td class="align-middle d-none d-lg-table-cell"><?= htmlspecialchars($isi['no_plat']);?></td>
                                <td class="align-middle"><?= htmlspecialchars($isi['nama']);?></td>
                                <td class="align-middle"><?= date('d/m/Y', strtotime($isi['tanggal']));?></td>
                                <td class="text-center align-middle"><?= htmlspecialchars($isi['lama_sewa']);?> hari</td>
                                <td class="align-middle d-none d-lg-table-cell"> 
                                    <?php if (!empty($isi['nama_supir'])): ?>
                                        <div>
                                            <strong><?= htmlspecialchars($isi['nama_supir']);?></strong><br>
                                            <small class="text-muted">
                                                Rp<?= number_format($isi['harga_supir']);?>/hr
                                            </small>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle" style="word-break: break-all;">Rp. <?= number_format(htmlspecialchars($isi['total_harga']));?></td>
                                <td class="text-center align-middle">
                                    <?php
                                        $konfirmasi = $isi['konfirmasi_pembayaran'] ?? '';
                                        // Sesuaikan kelas badge
                                        $badgeClass = 'bg-secondary';
                                        if ($konfirmasi === 'Pembayaran Diterima' || $konfirmasi === 'Sudah Dibayar') {
                                            $badgeClass = 'bg-success';
                                        } elseif ($konfirmasi === 'Sedang Diproses') {
                                            $badgeClass = 'bg-warning text-dark';
                                        } elseif ($konfirmasi === 'Pembayaran Ditolak') {
                                            $badgeClass = 'bg-danger';
                                        } elseif (stripos($konfirmasi, 'Refund') !== false) {
                                            $badgeClass = 'bg-dark';
                                        } elseif ($konfirmasi === '' || is_null($konfirmasi)) {
                                            $konfirmasi = 'Belum Bayar';
                                            $badgeClass = 'bg-danger';
                                        }
                                        echo '<span class="badge ' . $badgeClass . '">' . htmlspecialchars($konfirmasi) . '</span>';
                                    ?>
                                </td>
                                <td class="text-center align-middle">
                                    <a class="btn btn-primary btn-sm" href="bayar.php?id=<?= $isi['kode_booking'];?>" role="button"><i class="fas fa-info-circle me-1"></i> Detail</a>  
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
    mysqli_query($koneksi, "UPDATE booking SET konfirmasi_pembayaran='Sedang Diproses' WHERE id_booking='$id_booking'");

    // Kirim notifikasi ke user (opsional)
    $pesan = "Konfirmasi Terkirim! Pembayaran Anda sedang kami proses. Terima kasih.";
    $user_id = $_SESSION['USER']['id_login'];
    mysqli_query($koneksi, "INSERT INTO notifikasi (id_login, pesan, status_baca) VALUES ('$user_id', '$pesan', 0)");

    // Kirim WhatsApp notifikasi
    $user_no_hp = $_SESSION['USER']['no_hp'];
    if (!empty($user_no_hp) && function_exists('kirim_whatsapp')) {
        kirim_whatsapp($user_no_hp, $pesan);
    }

    // Redirect ke halaman sukses
    header("Location: history.php?status=konfirmasisuccess");
    exit();
}
?>