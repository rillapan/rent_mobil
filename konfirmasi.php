<?php

    session_start();
    require 'koneksi/koneksi.php';
    include 'header.php';
    include 'progress_bar.php';
    if(empty($_SESSION['USER']))
    {
        echo '<script>alert("Harap Login");window.location="index.php"</script>';
        exit();
    }
    $kode_booking = $_GET['id'];
    $result_booking = mysqli_query($koneksi, "SELECT * FROM booking WHERE kode_booking = '$kode_booking'");
    $hasil = mysqli_fetch_assoc($result_booking);

    $id = $hasil['id_mobil'];
    $result_mobil = mysqli_query($koneksi, "SELECT * FROM mobil WHERE id_mobil = '$id'");
    $isi = mysqli_fetch_assoc($result_mobil);
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
</style>
<br>
<br>
<div class="container my-4">
<div class="row">
    <div class="col-sm-4">
        <div class="card shadow-lg">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Pembayaran Dapat Melalui</h5>
            </div>
            <div class="card-body text-center">
                <p class="lead mb-0">Transfer ke Rekening:</p>
                <h4 class="text-primary"><strong><?= $info_web->no_rek;?></strong></h4>
                <p class="text-muted">Atas Nama: <?= $info_web->nama_rental;?></p>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
         <div class="card shadow-lg">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i> Konfirmasi Pembayaran</h5>
            </div>
           <div class="card-body">
               <form method="post" action="koneksi/proses.php?id=konfirmasi">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td>Kode Booking  </td>
                            <td> :</td>
                            <td><?php echo htmlspecialchars($hasil['kode_booking']);?></td>
                        </tr>
                        <tr>
                            <td>No Rekening   </td>
                            <td> :</td>
                            <td><input type="text" name="no_rekening" required class="form-control"></td>
                        </tr>
                        <tr>
                            <td>Atas Nama </td>
                            <td> :</td>
                            <td><input type="text" name="nama" required class="form-control"></td>
                        </tr>
                        <tr>
                            <td>Nominal  </td>
                            <td> :</td>
                            <td><input type="text" name="nominal" required class="form-control"></td>
                        </tr>
                        <tr>
                            <td>Tanggal  Transfer</td>
                            <td> :</td>
                            <td><input type="date" name="tgl" required class="form-control"></td>
                        </tr>
                        <tr>
                            <td>Total yg Harus di Bayar </td>
                            <td> :</td>
                            <td>Rp. <?php echo number_format(htmlspecialchars($hasil['total_harga']));?></td>
                        </tr>
                    </table>
                    <input type="hidden" name="id_booking" value="<?php echo htmlspecialchars($hasil['id_booking']);?>">
                    <button type="submit" class="btn btn-secondary float-end"><i class="fas fa-paper-plane me-2"></i> Kirim</button>
               </form>
           </div>
         </div> 
    </div>
</div>
</div>
<br>
<br>
<br>

<?php include 'footer.php';?>