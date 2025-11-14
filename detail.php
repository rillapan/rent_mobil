<?php

    session_start();
    require 'koneksi/koneksi.php';
    include 'header.php';
    $id = strip_tags($_GET['id']);
    $hasil = mysqli_query($koneksi, "SELECT * FROM mobil WHERE id_mobil = '$id'");
    $hasil = mysqli_fetch_assoc($hasil);

    // Fetch additional images
    $sql_gambar_tambahan = "SELECT nama_gambar FROM mobil_gambar WHERE id_mobil = ?";
    $stmt_gambar_tambahan = mysqli_prepare($koneksi, $sql_gambar_tambahan);
    mysqli_stmt_bind_param($stmt_gambar_tambahan, "i", $id);
    mysqli_stmt_execute($stmt_gambar_tambahan);
    $result_stmt = mysqli_stmt_get_result($stmt_gambar_tambahan);
    $gambar_tambahan = [];
    while ($row = mysqli_fetch_assoc($result_stmt)) {
        $gambar_tambahan[] = $row['nama_gambar'];
    }
    mysqli_stmt_close($stmt_gambar_tambahan);
?>
<style>
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
    .carousel-item img {
        height: 400px; /* Adjust as needed */
        object-fit: cover;
    }
</style>
<div class="container mt-5">
<div class="row">
    <div class="col-sm-6 mb-4">
        <div id="carImageCarousel" class="carousel slide shadow-lg h-100" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carImageCarousel" data-slide-to="0" class="active"></li>
                <?php for ($i = 0; $i < count($gambar_tambahan); $i++): ?>
                    <li data-target="#carImageCarousel" data-slide-to="<?= $i + 1; ?>"></li>
                <?php endfor; ?>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/image/<?php echo htmlspecialchars($hasil['gambar']);?>" class="d-block w-100" alt="Gambar Utama Mobil">
                </div>
                <?php foreach ($gambar_tambahan as $index => $gambar): ?>
                    <div class="carousel-item">
                        <img src="assets/image/<?php echo htmlspecialchars($gambar);?>" class="d-block w-100" alt="Gambar Tambahan Mobil <?= $index + 1; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#carImageCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carImageCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="col-sm-6 mb-4">
        <div class="card shadow-lg h-100">
            <div class="card-body">
                <h4 class="card-title text-primary mb-3"><?php echo htmlspecialchars($hasil['merk']);?></h4>
                <p class="card-text text-muted">
                    <strong>Deskripsi:</strong><br>
                    <?php echo htmlspecialchars($hasil['deskripsi']);?>
                </p>
                <?php if(!empty($hasil['keunggulan'])){ ?>
                <hr>
                <h5 class="card-title text-primary mb-3 d-flex justify-content-between align-items-center" style="cursor:pointer;" id="toggleKeunggulan">
                    Keunggulan Paket
                    <i class="fas fa-chevron-up" id="toggleIcon"></i>
                </h5>
                <ul class="list-group list-group-flush" id="keunggulanList">
                    <?php
                    $keunggulan = explode('||', $hasil['keunggulan']);
                    foreach ($keunggulan as $item) {
                        if (!empty($item)) {
                    ?>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success mr-2"></i>
                                <?= htmlspecialchars($item) ?>
                            </li>
                    <?php
                        }
                    }
                    ?>
                </ul>
                <?php } ?>

                <script>
                    $(document).ready(function() {
                        $('#toggleKeunggulan').click(function() {
                            $('#keunggulanList').slideToggle(300);
                            $('#toggleIcon').toggleClass('fa-chevron-up fa-chevron-down');
                        });
                    });
                </script>
                <ul class="list-group list-group-flush mt-3">
                    <?php if($hasil['status'] == 'Tersedia'){?>
                    <li class="list-group-item status-available">
                        <i class="fas fa-check-circle"></i> Tersedia
                    </li>
                    <?php }else{?>
                    <li class="list-group-item status-not-available">
                        <i class="fas fa-times-circle"></i> Tidak Tersedia
                    </li>
                    <?php }?>
                    
                    <li class="list-group-item price-item">
                        <i class="fas fa-money-bill-wave"></i> Rp. <?php echo number_format(htmlspecialchars($hasil['harga']));?>/ hari
                    </li>
                </ul>
                <hr class="my-4"/>
                <div class="d-flex justify-content-between">
                    <a href="booking.php?id=<?php echo htmlspecialchars($hasil['id_mobil']);?>" class="btn btn-secondary btn-lg">
                        <i class="fas fa-calendar-check me-2"></i> Booking Sekarang!
                    </a>
                    <a href="blog.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>
         </div> 
    </div>
</div>
</div>


<?php include 'footer.php';?>