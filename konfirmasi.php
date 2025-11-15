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

    // Get active payment methods
    $result_payment_methods = mysqli_query($koneksi, "SELECT * FROM payment_methods WHERE is_active = 1 ORDER BY payment_type, provider_name");
    $payment_methods = mysqli_fetch_all($result_payment_methods, MYSQLI_ASSOC);
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
            <div class="card-body">
                <?php if (!empty($payment_methods)): ?>
                    <?php foreach ($payment_methods as $method): ?>
                        <div class="mb-3 p-3 border rounded">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-<?= $method['payment_type'] == 'bank' ? 'university' : 'mobile-alt' ?> me-2"></i>
                                <?= strtoupper($method['payment_type']) == 'BANK' ? 'Bank Transfer' : 'E-Wallet' ?>
                            </h6>
                            <p class="mb-1"><strong><?= htmlspecialchars($method['provider_name']); ?></strong></p>
                            <p class="mb-1">No. Rekening: <strong><?= htmlspecialchars($method['account_number']); ?></strong></p>
                            <p class="mb-0">Atas Nama: <strong><?= htmlspecialchars($method['account_name']); ?></strong></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted">
                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                        <p>Belum ada metode pembayaran yang tersedia</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
         <div class="card shadow-lg">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i> Konfirmasi Pembayaran</h5>
            </div>
           <div class="card-body">
               <form method="post" action="koneksi/proses.php?id=konfirmasi" enctype="multipart/form-data">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td>Kode Booking  </td>
                            <td> :</td>
                            <td><?php echo htmlspecialchars($hasil['kode_booking']);?></td>
                        </tr>
                        <tr>
                            <td>Metode Pembayaran</td>
                            <td> :</td>
                            <td>
                                <select name="payment_method_id" required class="form-control">
                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                    <?php foreach ($payment_methods as $method): ?>
                                        <option value="<?php echo $method['id']; ?>">
                                            <?php echo strtoupper($method['payment_type']) == 'BANK' ? 'Bank' : 'E-Wallet'; ?> - <?php echo htmlspecialchars($method['provider_name']); ?> (<?php echo htmlspecialchars($method['account_number']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
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
                            <td>Bukti Pembayaran</td>
                            <td> :</td>
                            <td><input type="file" name="payment_proof" accept="image/*" required class="form-control"></td>
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