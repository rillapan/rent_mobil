<?php

    session_start();
    require 'koneksi/koneksi.php';
    include 'header.php';
    if(empty($_SESSION['USER']))
    {
        echo '<script>alert("Harap login !");window.location="index.php"</script>';
        exit();
    }
    $kode_booking = $_GET['id'];
    $hasil = $koneksi->query("SELECT * FROM booking WHERE kode_booking = '$kode_booking'")->fetch();

    $id = $hasil['id_mobil'];
    $isi = $koneksi->query("SELECT * FROM mobil WHERE id_mobil = '$id'")->fetch();

    $unik  = random_int(100,999);
    
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
    .list-group-item.status-available {
        background-color: #28a745; /* Bootstrap success */
        color: white;
    }
    .list-group-item.status-not-available {
        background-color: #dc3545; /* Bootstrap danger */
        color: white;
    }
    .list-group-item.info-item {
        background-color: var(--primary-dark);
        color: white;
    }
    .list-group-item.price-item {
        background-color: var(--dark);
        color: white;
    }
</style>
<br>
<br>
<div class="container my-4">
    <!-- Bagian Atas: Pembayaran, Detail Mobil, Detail Supir -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-header text-white">
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Pembayaran Dapat Melalui</h5>
                </div>
                <div class="card-body text-center">
                    <p class="lead mb-0">Transfer ke Rekening:</p>
                    <h4 class="text-primary"><strong><?= $info_web->no_rek;?></strong></h4>
                    <p class="text-muted">Atas Nama: <?= $info_web->nama_rental;?></p>
                    <hr/>
                    <p class="text-muted">Jumlah yang harus dibayar:</p>
                    <h3 class="text-success">Rp. <?= number_format($hasil['total_harga'] + $unik);?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-header text-white">
                    <h5 class="mb-0"><i class="fas fa-car me-2"></i> Detail Mobil</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <img src="assets/image/<?= htmlspecialchars($isi['gambar']);?>" class="img-fluid rounded" alt="Gambar Mobil">
                    </li>
                    <li class="list-group-item">
                        <strong>Merk:</strong> <?= htmlspecialchars($isi['merk']);?><br>
                        <strong>No. Plat:</strong> <?= htmlspecialchars($isi['no_plat']);?><br>
                        <strong>Harga:</strong> Rp<?= number_format(htmlspecialchars($isi['harga']));?>/hari
                    </li>
                    <li class="list-group-item price-item">
                        <i class="fas fa-money-bill-wave"></i> Rp. <?php echo number_format(htmlspecialchars($isi['harga']));?>/ hari
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <?php if (!empty($hasil['id_supir'])): ?>
            <?php
                $id_supir = $hasil['id_supir'];
                $supir = $koneksi->query("SELECT * FROM supir WHERE id_supir = '$id_supir'")->fetch();
            ?>
            <div class="card shadow-lg">
                <div class="card-header text-white">
                    <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i> Detail Supir</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center">
                        <?php if (!empty($supir['foto'])): ?>
                            <img src="assets/image/<?= htmlspecialchars($supir['foto']);?>" class="img-fluid rounded-circle" alt="Foto Supir" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="fas fa-user-tie fa-3x text-secondary"></i>
                            </div>
                        <?php endif; ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Nama Supir:</strong> <?= htmlspecialchars($supir['nama_supir']);?><br>
                        <strong>Pengalaman:</strong> <?= htmlspecialchars($supir['pengalaman']);?> tahun<br>
                        <strong>Harga Sewa:</strong> Rp<?= number_format(htmlspecialchars($supir['harga_sewa']));?>/hari
                    </li>
                    <li class="list-group-item">
                        <strong>Deskripsi:</strong><br>
                        <p class="mb-0 text-muted small"><?= nl2br(htmlspecialchars($supir['deskripsi']));?></p>
                    </li>
                    <li class="list-group-item price-item">
                        <i class="fas fa-money-bill-wave"></i> Rp. <?php echo number_format(htmlspecialchars($supir['harga_sewa']));?>/ hari
                    </li>
                </ul>
            </div>
            <?php else: ?>
            <div class="card shadow-lg">
                <div class="card-header text-white">
                    <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i> Detail Supir</h5>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-user-tie fa-3x text-secondary mb-3"></i>
                    <p class="text-muted">Tidak ada supir yang dipilih</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bagian Bawah: Detail Booking -->
    <div class="row">
        <div class="col-12">
         <div class="card shadow-lg">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="fas fa-receipt me-2"></i> Detail Booking</h5>
            </div>
           <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td>Kode Booking  </td>
                            <td> :</td>
                            <td><?php echo htmlspecialchars($hasil['kode_booking']);?></td>
                        </tr>
                        <tr>
                            <td>KTP  </td>
                            <td> :</td>
                            <td><?php echo htmlspecialchars($hasil['ktp']);?></td>
                        </tr>
                        <tr>
                            <td>Nama  </td>
                            <td> :</td>
                            <td><?php echo htmlspecialchars($hasil['nama']);?></td>
                        </tr>
                        <tr>
                            <td>Telepon  </td>
                            <td> :</td>
                            <td><?php echo htmlspecialchars($hasil['no_tlp']);?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Sewa </td>
                            <td> :</td>
                            <td><?php echo date('d/m/Y', strtotime(htmlspecialchars($hasil['tanggal'])));?></td>
                        </tr>
                        <tr>
                            <td>Lama Sewa </td>
                            <td> :</td>
                            <td><?php echo htmlspecialchars($hasil['lama_sewa']);?> hari</td>
                        </tr>
                        <tr>
                            <td>Total Harga </td>
                            <td> :</td>
                            <td>Rp. <?php echo number_format(htmlspecialchars($hasil['total_harga']));?></td>
                        </tr>
                        <tr>
                            <td>Status </td>
                            <td> :</td>
                            <td>
                                <?php if($hasil['konfirmasi_pembayaran'] == 'Pembayaran Diterima'){ ?>
                                    <span class="badge bg-success text-white"><i class="fas fa-check-circle me-1"></i> Pembayaran Diterima</span>
                                <?php } elseif($hasil['konfirmasi_pembayaran'] == 'Sedang Diproses'){ ?>
                                    <span class="badge bg-warning text-white"><i class="fas fa-hourglass-half me-1"></i> Sedang Diproses</span>
                                <?php } elseif($hasil['konfirmasi_pembayaran'] == 'Sudah Dibayar'){ ?>
                                    <span class="badge bg-info text-white"><i class="fas fa-check-circle me-1"></i> Sudah Dibayar</span>
                                <?php } elseif($hasil['konfirmasi_pembayaran'] == 'Belum Dibayar'){ ?>
                                    <span class="badge bg-danger text-white"><i class="fas fa-times-circle me-1"></i> Belum Dibayar</span>
                                <?php } else { ?>
                                    <span class="badge bg-secondary text-white"><i class="fas fa-question-circle me-1"></i> <?php echo htmlspecialchars($hasil['konfirmasi_pembayaran']); ?></span>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>

                <?php if($hasil['konfirmasi_pembayaran'] == 'Belum Bayar'){?>
                    <a href="konfirmasi.php?id=<?php echo htmlspecialchars($kode_booking);?>" 
                    class="btn btn-secondary btn-lg float-end"><i class="fas fa-money-check-alt me-2"></i> Konfirmasi Pembayaran</a>
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
<?php if(isset($_GET['status']) && $_GET['status'] == 'bookingsuccess'): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Booking Berhasil!',
            text: 'Silahkan lakukan pembayaran untuk menyelesaikan proses booking.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    });
</script>
<?php endif; ?>