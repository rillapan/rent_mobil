<?php

    session_start();
    require 'koneksi/koneksi.php';
    include 'header.php';
    if(isset($_GET['cari']))
    {
        $cari = strip_tags($_GET['cari']);
        $query =  $koneksi -> prepare('SELECT * FROM mobil WHERE merk LIKE ? ORDER BY id_mobil DESC');
        $query->execute(['%'.$cari.'%']);
        $query = $query->fetchAll();
    }else{
        $query =  $koneksi -> query('SELECT * FROM mobil ORDER BY id_mobil DESC')->fetchAll();
    }
?>
<style>
    /* Car Card Styles */
    .car-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        background: white;
        border: none;
        height: 100%;
    }

    .car-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .car-card .card-img-top {
        height: 200px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .car-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .car-card .card-body {
        padding: 1.25rem;
    }

    .car-card .card-title {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.75rem;
        font-size: 1.25rem;
    }

    .car-card .list-group-item {
        border: none;
        padding: 0.75rem 1.25rem;
        display: flex;
        align-items: center;
    }

    .car-card .list-group-item i {
        margin-right: 0.5rem;
        width: 20px;
        text-align: center;
    }

    .status-available {
        background: linear-gradient(to right, var(--success), #34d399);
        color: white;
    }

    .status-not-available {
        background: linear-gradient(to right, var(--danger), #f87171);
        color: white;
    }

    .benefit-item {
        background: linear-gradient(to right, var(--info), #38bdf8);
        color: white;
    }

    .price-item {
        background: linear-gradient(to right, var(--dark), #374151);
        color: white;
    }

    .car-card .card-footer-custom {
        background: white;
        border-top: 1px solid #e5e7eb;
        padding: 1rem 1.25rem;
    }
    /* Button Styles */
    .btn-primary-custom {
        background: var(--primary);
        border: none;
        color: white;
    }

    .btn-primary-custom:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .btn-success-custom {
        background: var(--secondary);
        border: none;
        color: white;
    }

    .btn-success-custom:hover {
        background: #e55a2b;
        color: white; /* Ensure text remains white */
        text-shadow: 0 0 5px rgba(255,255,255,0.5); /* Subtle text shadow */
    }

    .btn-info-custom {
        background: var(--primary);
        border: none;
        color: white;
    }
    .btn-info-custom:hover {
        background: var(--primary-dark);
        color: white; /* Ensure text remains white */
        text-shadow: 0 0 5px rgba(255,255,255,0.5); /* Subtle text shadow */
    }
    .card-title-custom {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1.5rem;
    }
    .card-car-item {
        transition: all 0.3s ease;
    }
    .card-car-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2); /* Stronger shadow */
    }
    .card-car-item .card-body-custom {
        background-color: var(--light);
        padding: 1rem;
    }
    .card-car-item .list-group-item {
        display: flex;
        align-items: center;
        padding: .75rem 1rem;
        font-weight: 500;
    }
    .card-car-item .list-group-item i {
        margin-right: .75rem;
    }
    .card-car-item .list-group-item.status-available {
        background-color: #28a745; /* Bootstrap success */
        color: white;
    }
    .card-car-item .list-group-item.status-not-available {
        background-color: #dc3545; /* Bootstrap danger */
        color: white;
    }
    .card-car-item .list-group-item.info-item {
        background-color: var(--primary-dark);
        color: white;
    }
    .card-car-item .list-group-item.price-item {
        background-color: var(--dark);
        color: white;
    }
    .btn-booking {
        background-color: var(--secondary);
        border-color: var(--secondary);
        color: white;
        transition: all 0.3s ease;
    }
    .btn-booking:hover {
        background-color: #e55e2d;
        border-color: #e55e2d;
        transform: translateY(-1px);
        color: white; /* Ensure text remains white */
        text-shadow: 0 0 5px rgba(255,255,255,0.5); /* Subtle text shadow */
    }
    .btn-detail {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white;
        transition: all 0.3s ease;
    }
    .btn-detail:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: translateY(-1px);
        color: white; /* Ensure text remains white */
        text-shadow: 0 0 5px rgba(255,255,255,0.5); /* Subtle text shadow */
    }
</style>
<br>
<br>
<div class="container my-4">
<div class="row">
    <div class="col-sm-12">
        <h4 class="card-title-custom">
        <?php
            if(isset($_GET['cari']))
            {
                echo 'Keyword Pencarian : ' . htmlspecialchars($cari);
            }else{
                echo 'Semua Mobil';
            }
        ?>
        </h4>

        <!-- Search Form -->
        <div class="row mt-3 mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="blog.php" class="d-flex flex-column flex-md-row gap-3">
                            <div class="flex-grow-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white border-0">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="cari" class="form-control form-control-lg border-0"
                                           placeholder="Cari mobil berdasarkan merk..."
                                           value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>">
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary-custom btn-lg px-4">
                                    <i class="fas fa-search mr-2"></i>Cari
                                </button>
                                <?php if(isset($_GET['cari'])): ?>
                                <a href="blog.php" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="fas fa-times mr-2"></i>Hapus Pencarian
                                </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
        <?php 
            $no =1;
            if (count($query) > 0) {
                foreach($query as $isi)
                {
        ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="car-card card h-100">
                    <img src="assets/image/<?= htmlspecialchars($isi['gambar']); ?>" class="card-img-top" alt="<?= htmlspecialchars($isi['merk']); ?>">
                    <div class="card-body pt-3 pb-0">
                        <h5 class="card-title mb-2"><?= htmlspecialchars($isi['merk']); ?></h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item <?php echo $isi['status'] == 'Tersedia' ? 'status-available' : 'status-not-available'; ?>">
                            <i class="fas <?php echo $isi['status'] == 'Tersedia' ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i> 
                            <?= htmlspecialchars($isi['status']); ?>
                        </li>
                        <li class="list-group-item price-item">
                            <i class="fas fa-money-bill-wave"></i> Rp. <?= number_format(htmlspecialchars($isi['harga'])); ?>/ hari
                        </li>
                    </ul>
                    <?php if(!empty($isi['keunggulan'])){ ?>
                    <div class="card-body border-top">
                        <h6 class="card-title text-primary mb-3 d-flex justify-content-between align-items-center" style="cursor:pointer;" id="toggleKeunggulan-<?= $isi['id_mobil'] ?>">
                            Keunggulan Paket
                            <i class="fas fa-chevron-up" id="toggleIcon-<?= $isi['id_mobil'] ?>"></i>
                        </h6>
                        <ul class="list-group list-group-flush" id="keunggulanList-<?= $isi['id_mobil'] ?>">
                            <?php
                            $keunggulan = explode('||', $isi['keunggulan']);
                            foreach ($keunggulan as $item) {
                                if (!empty($item)) {
                            ?>
                                    <li class="list-group-item px-0 py-2">
                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                        <?= htmlspecialchars($item) ?>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#toggleKeunggulan-<?= $isi['id_mobil'] ?>').click(function() {
                                $('#keunggulanList-<?= $isi['id_mobil'] ?>').slideToggle(300);
                                $('#toggleIcon-<?= $isi['id_mobil'] ?>').toggleClass('fa-chevron-up fa-chevron-down');
                            });
                        });
                    </script>
                    <?php } ?>
                    <div class="card-footer-custom">
                        <div class="d-flex justify-content-between">
                            <a href="detail.php?id=<?= htmlspecialchars($isi['id_mobil']); ?>" class="btn btn-info-custom">
                                <i class="fas fa-info-circle"></i> Detail
                            </a>
                            <a href="booking.php?id=<?= htmlspecialchars($isi['id_mobil']); ?>" class="btn btn-success-custom">
                                <i class="fas fa-calendar-check"></i> Booking
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php $no++;}
            } else {
            ?>
            <div class="col-12 text-center py-5">
                <h3 class="text-muted mb-3"><i class="fas fa-search fa-2x"></i></h3>
                <h3 class="mb-3">Mobil tidak ditemukan.</h3>
                <p class="lead">Coba kata kunci lain atau lihat semua mobil yang tersedia.</p>
                <a href="blog.php" class="btn btn-primary btn-lg mt-3"><i class="fas fa-car me-2"></i>Lihat Semua Mobil</a>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
</div>

<br>

<br>

<br>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.collapse').on('show.bs.collapse', function () {
                var trigger = $('[data-target="#' + $(this).attr('id') + '"]');
                var icon = trigger.find('i');
                icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            }).on('hide.bs.collapse', function () {
                var trigger = $('[data-target="#' + $(this).attr('id') + '"]');
                var icon = trigger.find('i');
                icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            });

            // Remove manual toggle handler to avoid conflicts
            // $('.toggle-keunggulan').click(function() {
            //     var target = $(this).data('target');
            //     $(target).collapse('toggle');
            // });
        });
    </script>
<?php include 'footer.php';?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
