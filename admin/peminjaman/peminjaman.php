<?php
    require '../../koneksi/koneksi.php';
    $title_web = 'Peminjaman';
    $url = '../../';
    include '../header.php';
    if(!empty($_GET['id'])){
        $kode_booking = $_GET['id'];
        
        $result_booking = mysqli_query($koneksi, "SELECT * FROM booking WHERE kode_booking = '$kode_booking'");
        $hasil = mysqli_fetch_assoc($result_booking);

        if(!$hasil)
        {
            echo '<script>window.location="peminjaman.php?status=notfound";</script>';
            exit;
        }
        $id_booking = $hasil['id_booking'];
        $result_pembayaran = mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE id_booking = '$id_booking'");
        $hsl = mysqli_fetch_assoc($result_pembayaran);


        $id = $hasil['id_mobil'];
        $result_mobil = mysqli_query($koneksi, "SELECT * FROM mobil WHERE id_mobil = '$id'");
        $isi = mysqli_fetch_assoc($result_mobil);
    } // Add closing brace here for the if(!empty($_GET['id'])) block
?>
<br>
<br>
<div class="container my-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <h5 class="card-title mb-0"><i class="fa fa-search"></i> Cari Booking</h5>
                </div>
                <div class="card-body">
                    <form method="get" action="peminjaman.php" class="d-flex">
                        <input type="text" class="form-control me-2" 
                               value="<?= htmlspecialchars($_GET['id'] ?? ''); ?>" 
                               name="id" 
                               placeholder="Tulis Kode Booking lalu tekan Enter"
                               required>
                        <button class="btn btn-secondary" type="submit"><i class="fa fa-search"></i> Cari</button>
                    </form>
                </div>
            </div>
        </div>
        
        <?php if(!empty($_GET['id']) && isset($hasil)) { ?>
        
        <div class="col-lg-4 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white d-flex align-items-center">
                    <h5 class="card-title mb-0"><i class="fa fa-file-text-o"></i> Detail Pembayaran</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <th scope="row">No. Rekening</th>
                                <td>:</td>
                                <td><?= htmlspecialchars($hsl['no_rekening'] ?? '-');?></td>
                            </tr>
                            <tr>
                                <th scope="row">Atas Nama</th>
                                <td>:</td>
                                <td><?= htmlspecialchars($hsl['nama_rekening'] ?? '-');?></td>
                            </tr>
                            <tr>
                                <th scope="row">Nominal</th>
                                <td>:</td>
                                <td>Rp<?= number_format(htmlspecialchars($hsl['nominal'] ?? 0), 0, ',', '.');?></td>
                            </tr>
                            <tr>
                                <th scope="row">Tgl. Transfer</th>
                                <td>:</td>
                                <td><?= htmlspecialchars($hsl['tanggal'] ?? '-');?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0"><?= htmlspecialchars($isi['merk']);?></h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center">
                        <?php if($isi['status'] == 'Tersedia'){?>
                            <span class="badge bg-success"><i class="fa fa-check-circle"></i> Tersedia</span>
                        <?php } else {?>
                            <span class="badge bg-danger"><i class="fa fa-times-circle"></i> Tidak Tersedia</span>
                        <?php } ?>
                    </li>
                   
                    <li class="list-group-item bg-light"><i class="fa fa-money text-dark"></i> Rp. <?= number_format(htmlspecialchars($isi['harga']), 0, ',', '.');?> / hari</li>
                </ul>
            </div>
        </div>
        
        <div class="col-lg-8 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex align-items-center">
                    <h5 class="card-title mb-0"><i class="fa fa-info-circle"></i> Detail Booking & Status Mobil</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="proses.php?id=konfirmasi">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th scope="row">Kode Booking</th>
                                    <td>:</td>
                                    <td><?= htmlspecialchars($hasil['kode_booking']);?></td>
                                </tr>
                                <tr>
                                    <th scope="row">KTP</th>
                                    <td>:</td>
                                    <td><?= htmlspecialchars($hasil['ktp']);?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Nama</th>
                                    <td>:</td>
                                    <td><?= htmlspecialchars($hasil['nama']);?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Telepon</th>
                                    <td>:</td>
                                    <td><?= htmlspecialchars($hasil['no_tlp']);?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Tanggal Sewa</th>
                                    <td>:</td>
                                    <td><?= date('d/m/Y', strtotime($hasil['tanggal']));?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Lama Sewa</th>
                                    <td>:</td>
                                    <td><?= htmlspecialchars($hasil['lama_sewa']);?> hari</td>
                                </tr>
                                <tr>
                                    <th scope="row">Total Harga</th>
                                    <td>:</td>
                                    <td>Rp<?= number_format(htmlspecialchars($hasil['total_harga']), 0, ',', '.');?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Status Mobil</th>
                                    <td>:</td>
                                    <td>
                                        <select class="form-select" name="status">
                                               <option value="Tersedia" <?= ($isi['status'] == 'Tersedia') ? 'selected' : '';?>>Tersedia (Kembali)</option>
                                            <option value="Tidak Tersedia" <?= ($isi['status'] == 'Tidak Tersedia') ? 'selected' : '';?>>Tidak Tersedia (Pinjam)</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Status Pengembalian</th>
                                    <td>:</td>
                                    <td>
                                        <select class="form-select" name="status_pengembalian">
                                            <option value="Belum Dikembalikan" <?= ($hasil['status_pengembalian'] ?? 'Belum Dikembalikan') == 'Belum Dikembalikan' ? 'selected' : '';?>>Belum Dikembalikan</option>
                                            <option value="Dikembalikan" <?= ($hasil['status_pengembalian'] ?? 'Belum Dikembalikan') == 'Dikembalikan' ? 'selected' : '';?>>Dikembalikan</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr id="pengembalian_fields" style="display: none;">
                                    <th scope="row">Tanggal Pengembalian</th>
                                    <td>:</td>
                                    <td>
                                        <input type="date" class="form-control" name="tanggal_pengembalian" value="<?= date('Y-m-d'); ?>">
                                    </td>
                                </tr>
                                <tr id="denda_fields" style="display: none;">
                                    <th scope="row">Denda (jika ada)</th>
                                    <td>:</td>
                                    <td>
                                        <input type="number" class="form-control" name="denda" placeholder="0" min="0">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <input type="hidden" name="id_mobil" value="<?= htmlspecialchars($isi['id_mobil']);?>">
                        <input type="hidden" name="kode_booking" value="<?= htmlspecialchars($hasil['kode_booking']);?>">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                             <button type="submit" class="btn btn-primary mt-3">
                                <i class="fa fa-refresh"></i> Ubah Status Mobil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php } // Add this closing brace ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusPengembalianSelect = document.querySelector('select[name="status_pengembalian"]');
    const pengembalianFields = document.getElementById('pengembalian_fields');
    const dendaFields = document.getElementById('denda_fields');

    function togglePengembalianFields() {
        if (statusPengembalianSelect.value === 'Dikembalikan') {
            pengembalianFields.style.display = 'table-row';
            dendaFields.style.display = 'table-row';
        } else {
            pengembalianFields.style.display = 'none';
            dendaFields.style.display = 'none';
        }
    }

    statusPengembalianSelect.addEventListener('change', togglePengembalianFields);
    togglePengembalianFields(); // Initial check

    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const bookingId = urlParams.get('id');

    if (status) {
        let title, text, icon;
        switch (status) {
            case 'notfound':
                title = 'Gagal!';
                text = 'Kode Booking tidak ditemukan atau tidak ada datanya.';
                icon = 'error';
                break;
            case 'update_success':
                title = 'Berhasil!';
                text = 'Status mobil telah berhasil diperbarui.';
                icon = 'success';
                break;
            case 'pengembalian_success':
                title = 'Berhasil!';
                text = 'Mobil telah berhasil dikembalikan.';
                icon = 'success';
                break;
            case 'update_error':
                title = 'Gagal!';
                text = 'Terjadi kesalahan saat memperbarui status mobil.';
                icon = 'error';
                break;
        }

        if (title) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonText: 'OK'
            }).then(() => {
                // Build the new URL, keeping the ID if it exists
                let newUrl = 'peminjaman.php';
                if (bookingId && status !== 'notfound') {
                    newUrl += '?id=' + bookingId;
                }
                window.history.replaceState({}, document.title, newUrl);
            });
        }
    }
});
</script>
<style>
    :root {
        --primary: #1A237E;
        --secondary: #FF6B35;
        --light: #F8F9FA;
        --dark: #212529;
    }
    .bg-primary { background-color: var(--primary) !important; }
    .bg-secondary { background-color: var(--secondary) !important; }
    .bg-info { background-color: #0d6efd !important; } /* Reusing Bootstrap's info color */
    .bg-success { background-color: #198754 !important; } /* Reusing Bootstrap's success color */
    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
        color: white;
    }
    .btn-secondary:hover {
        background-color: #e55e2d;
        border-color: #e55e2d;
    }
</style>
<br>
<br>
<br>
<?php  include '../footer.php';?>