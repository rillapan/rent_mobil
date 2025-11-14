<?php
    session_start();
    require '../koneksi/koneksi.php';
    $title_web = 'Daftar Transaksi';
    include '../header.php';

    if(empty($_SESSION['USER']))
    {
        echo '<script>alert("Anda harus login untuk melihat transaksi.");window.location="../index.php"</script>';
        exit();
    }

    $id_login = $_SESSION['USER']['id_login'];

    $sql = "SELECT mobil.merk, booking.* FROM booking JOIN mobil ON
            booking.id_mobil=mobil.id_mobil WHERE booking.id_login = ? ORDER BY id_booking DESC";

    $row = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($row, "i", $id_login);
    mysqli_stmt_execute($row);
    $result_stmt = mysqli_stmt_get_result($row);
    $hasil = [];
    while ($row_data = mysqli_fetch_assoc($result_stmt)) {
        $hasil[] = $row_data;
    }
    mysqli_stmt_close($row);
?>

<br>
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-history me-2"></i> Daftar Transaksi Anda
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($hasil)): ?>
                <div class="alert alert-info text-center" role="alert">
                    Anda belum memiliki transaksi. Silakan lakukan pemesanan mobil.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col" class="text-center">No.</th>
                                <th scope="col">Kode Booking</th>
                                <th scope="col">Merk Mobil</th>
                                <th scope="col">Tanggal Sewa</th>
                                <th scope="col" class="text-center">Lama Sewa</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $no = 1; 
                                foreach($hasil as $isi) {
                            ?>
                            <tr>
                                <td class="text-center align-middle"><?= $no;?></td>
                                <td class="align-middle"><?= htmlspecialchars($isi['kode_booking']);?></td>
                                <td class="align-middle"><?= htmlspecialchars($isi['merk']);?></td>
                                <td class="align-middle"><?= date('d/m/Y', strtotime($isi['tanggal']));?></td>
                                <td class="text-center align-middle"><?= htmlspecialchars($isi['lama_sewa']);?> hari</td>
                                <td class="align-middle">Rp<?= number_format(htmlspecialchars($isi['total_harga']), 0, ',', '.');?></td>
                                <td class="text-center align-middle">
                                    <?php if($isi['konfirmasi_pembayaran'] == 'Pembayaran Diterima'){ ?>
                                        <span class="badge bg-success text-white"><i class="fas fa-check-circle me-1"></i> Pembayaran Diterima</span>
                                    <?php } elseif($isi['konfirmasi_pembayaran'] == 'Sedang Diproses'){ ?>
                                        <span class="badge bg-warning text-white"><i class="fas fa-hourglass-half me-1"></i> Diproses</span>
                                    <?php }else { ?>
                                        <span class="badge bg-danger text-white"><i class="fas fa-times-circle me-1"></i> Belum Dibayar</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center align-middle">
                                    <a class="btn btn-secondary btn-sm" href="../bayar.php?id=<?= $isi['kode_booking'];?>" 
                                       role="button" title="Lihat Detail Transaksi">
                                       <i class="fas fa-info-circle"></i> Detail
                                    </a> 
                                </td>
                            </tr>
                            <?php $no++;}?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Menggunakan tema warna dari file sebelumnya */
    :root {
        --primary: #1A237E; 
        --primary-dark: #1A3CC9;
        --secondary: #FF6B35;
        --light: #F8F9FA;
        --dark: #212529;
        --gray: #6C757D;
    }
    .bg-primary {
        background-color: var(--primary) !important;
    }
    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
        color: white;
    }
    .btn-secondary:hover {
        background-color: #e55e2d;
        border-color: #e55e2d;
    }
    .badge {
        /* Ensure text is white and centered within the badge */
        color: white !important;
        text-align: center;
        display: inline-flex; /* Use flexbox for better vertical alignment of icon and text */
        align-items: center;
        justify-content: center;
        padding: .5em .75em; /* Slightly more padding */
        font-size: 95%; /* Slightly larger font */
        border-radius: .3rem; /* Slightly more rounded corners */
    }
    .badge i {
        margin-right: .3em; /* Space between icon and text */
    }
</style>
<?php  include '../footer.php';?>