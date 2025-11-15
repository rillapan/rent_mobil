<?php

    require '../../koneksi/koneksi.php';
    $title_web = 'User';
    $url = '../../';
    include '../header.php';
?>

<br>
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fa fa-users"></i> Daftar User / Pelanggan
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama Pengguna</th>
                            <th scope="col">Username</th>
                            <th scope="col">Foto Profil</th>
                            <th scope="col">Detail Pelanggan</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            $sql = "SELECT * FROM login WHERE level = 'Pengguna' ORDER BY id_login DESC";
                            $stmt = mysqli_prepare($koneksi, $sql);
                            mysqli_stmt_execute($stmt);
                            $result_stmt = mysqli_stmt_get_result($stmt);
                            $hasil = [];
                            while ($row = mysqli_fetch_assoc($result_stmt)) {
                                $hasil[] = $row;
                            }
                            mysqli_stmt_close($stmt);
                            foreach($hasil as $r){
                        ?>
                        <tr>
                            <td><?= $no;?></td>
                            <td><?= htmlspecialchars($r['nama_pengguna']);?></td>
                            <td><?= htmlspecialchars($r['username']);?></td>
                            <td class="text-center">
                                <img src="../../assets/image/user/<?= htmlspecialchars($r['foto'] ?? 'default.jpg'); ?>" alt="Foto Profil" width="50" height="50" class="img-thumbnail">
                            </td>
                            <td>
                                <div class="customer-details">
                                    <small>
                                        <strong>Email:</strong> <?= htmlspecialchars($r['email'] ?? '-');?><br>
                                        <strong>No. HP:</strong> <?= htmlspecialchars($r['no_hp'] ?? '-');?><br>
                                        <strong>Provinsi:</strong> <?= htmlspecialchars($r['provinsi'] ?? '-');?><br>
                                        <strong>Kota/Kab:</strong> <?= htmlspecialchars($r['kota_kabupaten'] ?? '-');?><br>
                                        <strong>Jenis Kelamin:</strong> <?= htmlspecialchars($r['jenis_kelamin'] ?? '-');?>
                                    </small>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo $url;?>admin/booking/booking.php?id=<?= $r['id_login'];?>"
                                   class="btn btn-secondary btn-sm" title="Lihat Detail Transaksi">
                                    <i class="fa fa-info-circle"></i> Detail Transaksi
                                </a>
                                </td>
                        </tr>
                        <?php $no++; }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
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
        background-color: #e55e2d; /* sedikit lebih gelap dari secondary */
        border-color: #e55e2d;
    }

    .customer-details {
        max-width: 250px;
        line-height: 1.4;
    }

    .customer-details small {
        font-size: 0.85em;
        color: var(--gray);
    }

    .customer-details strong {
        color: var(--dark);
    }
</style>
<?php  include '../footer.php';?>