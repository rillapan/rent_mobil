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
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            $sql = "SELECT * FROM login WHERE level = 'Pengguna' ORDER BY id_login DESC";
                            $result = mysqli_query($koneksi, $sql);
                            $hasil = mysqli_fetch_all($result, MYSQLI_ASSOC);
                            foreach($hasil as $r){
                        ?>
                        <tr>
                            <td><?= $no;?></td>    
                            <td><?= htmlspecialchars($r->nama_pengguna);?></td>        
                            <td><?= htmlspecialchars($r->username);?></td>        
                            <td class="text-center">
                                <a href="<?php echo $url;?>admin/booking/booking.php?id=<?= $r->id_login;?>" 
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
</style>
<?php  include '../footer.php';?>