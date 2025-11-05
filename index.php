<?php
session_start();
require 'koneksi/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rental Mobil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1A237E; 
            --primary-dark: #1A3CC9;
            --secondary: #FF6B35;
            --light: #F8F9FA;
            --dark: #212529;
            --gray: #6C757D;
            --success: #28a745; /* Standard Bootstrap success */
            --danger: #dc3545; /* Standard Bootstrap danger */
            --info: #17a2b8; /* Standard Bootstrap info */
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
            background-color: var(--light);
        }
        
        /* Header & Navigation Styles */
        .modern-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .brand-name {
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: 0.5px;
        }
        
        .brand-name span {
            color: var(--secondary);
        }
        
        .search-form {
            position: relative;
        }
        
        .search-form .form-control {
            border-radius: 50px;
            padding-left: 20px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            height: 45px;
        }
        
        .search-form .btn {
            position: absolute;
            right: 5px;
            top: 5px;
            border-radius: 50px;
            background: var(--secondary);
            border: none;
            height: 35px;
            width: 90px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .search-form .btn:hover {
            background: #e55a2b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        /* Carousel Styles */
        .carousel-modern {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        
        .carousel-indicators-modern li {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            border: 2px solid transparent;
        }
        
        .carousel-indicators-modern .active {
            background-color: var(--primary);
            border-color: white;
        }
        
        .carousel-control-prev-modern, 
        .carousel-control-next-modern {
            width: 50px;
            height: 50px;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.7;
            transition: all 0.3s ease;
        }
        
        .carousel-control-prev-modern { left: 15px; }
        .carousel-control-next-modern { right: 15px; }
        
        .carousel-control-prev-modern:hover, 
        .carousel-control-next-modern:hover {
            opacity: 0.9;
            background-color: var(--primary);
        }
        
        /* Auth Card Styles */
        .auth-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            border: none;
        }
        
        .auth-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .auth-card .card-header-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            text-align: center;
            padding: 1rem;
            border-bottom: 0;
        }
        
        .auth-card .card-body {
            padding: 1.5rem;
        }
        
        .auth-card .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .auth-card .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }
        
        .auth-card .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
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
        
        /* Modal Styles */
        .modal-content-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .modal-header-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            border-bottom: none;
            padding: 1rem 1.5rem;
        }
        
        /* Welcome Message */
        .welcome-message {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .car-card {
                margin-bottom: 1.25rem;
            }
            
            .auth-card {
                margin-bottom: 1.5rem;
            }
            
            .brand-name {
                font-size: 1.75rem;
                text-align: center;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php
    include 'header.php';
    ?>

<div class="container mt-4">
    <div class="carousel-modern">
        <div id="carouselId" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators carousel-indicators-modern">
                <?php 
                    $querymobil = $koneksi->query('SELECT * FROM mobil ORDER BY id_mobil DESC')->fetchAll();
                    $no = 1;
                    foreach($querymobil as $isi):
                ?>
                <li data-target="#carouselId" data-slide-to="<?= $no-1; ?>" class="<?= $no == 1 ? 'active' : ''; ?>"></li>
                <?php $no++; endforeach; ?>
            </ol>
            <div class="carousel-inner" role="listbox">
                <?php 
                    $no = 1;
                    foreach($querymobil as $isi):
                ?>
                <div class="carousel-item <?= $no == 1 ? 'active' : ''; ?>">
                    <img src="assets/image/<?= $isi['gambar']; ?>" alt="<?= $isi['merk']; ?>" 
                    class="img-fluid w-100" style="height:400px; object-fit:cover;">
                    <div class="carousel-caption d-none d-md-block">
                        <h3><?= $isi['merk']; ?></h3>
                        <p>Rp. <?= number_format($isi['harga']); ?>/hari</p>
                    </div>
                </div>
                <?php $no++; endforeach; ?>
            </div>
            </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php if(isset($_SESSION['USER'])): ?>
               
            <?php endif; ?>
            <div class="row">
                <?php 
                    $query = $koneksi->query('SELECT * FROM mobil ORDER BY id_mobil DESC')->fetchAll();
                    foreach($query as $isi):
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
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
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Daftar Pengguna Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="koneksi/proses.php?id=daftar">
                        <div class="form-group">
                            <label for="nama">Nama Pengguna</label>
                            <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="user" id="username" class="form-control" required placeholder="Buat username">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="pass" id="password" class="form-control" required placeholder="Buat password">
                        </div>
                        <div class="form-group">
                            <label for="no_hp">Nomor HP</label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control" required placeholder="Masukkan nomor HP">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Daftar Sekarang</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Optional JavaScript -->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <?php if(isset($_GET['status'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const status = '<?php echo $_GET['status']; ?>';
            if (status === 'loginsuccess') {
                Swal.fire({
                    title: 'Login Berhasil!',
                    text: 'Selamat datang kembali!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } else if (status === 'loginfailed') {
                Swal.fire({
                    title: 'Login Gagal!',
                    text: 'Username atau password salah.',
                    icon: 'error',
                    confirmButtonText: 'Coba Lagi'
                });
            } else if (status === 'registersuccess') {
                Swal.fire({
                    title: 'Pendaftaran Berhasil!',
                    text: 'Silahkan login dengan akun Anda.',
                    icon: 'success',
                    confirmButtonText: 'Login'
                });
            } else if (status === 'registerfailed') {
                Swal.fire({
                    title: 'Pendaftaran Gagal!',
                    text: 'Username sudah digunakan, coba yang lain.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
    <?php endif; ?>

</body>
</html>