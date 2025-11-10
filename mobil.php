<?php
session_start();
require 'koneksi/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Mobil - Rental Mobil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Daftar lengkap mobil rental dengan berbagai pilihan kendaraan berkualitas.">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1A237E;
            --primary-dark: #0d1452;
            --primary-light: #534bae;
            --secondary: #FF6B35;
            --secondary-dark: #e55a2b;
            --light: #F8F9FA;
            --dark: #212529;
            --gray: #6C757D;
            --gray-light: #e9ecef;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
            --border-radius: 12px;
            --box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
            background-color: var(--light);
            line-height: 1.6;
        }

        /* Header Styles */
        .modern-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand-name {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: 0.5px;
        }

        .brand-name span {
            color: var(--secondary);
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: var(--transition);
            border-radius: 6px;
        }

        .navbar-nav .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .navbar-nav .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.15);
        }

        /* Page Header */
        .page-header {
        
            color: black;
            padding: 3rem 0;
            text-align: center;
        }

        .page-title {
            color: var(--secondary-dark);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .filter-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        /* Car Card Styles */
        .car-card {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: var(--transition);
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
            padding: 1.5rem;
        }

        .car-card .card-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
        }

        .car-card .card-text {
            color: var(--gray);
            margin-bottom: 1rem;
        }

        .car-card .list-group-item {
            border: none;
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            background: transparent;
        }

        .car-card .list-group-item i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
            color: var(--primary);
        }

        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-available {
            background: rgba(40, 167, 69, 0.15);
            color: var(--success);
        }

        .status-not-available {
            background: rgba(220, 53, 69, 0.15);
            color: var(--danger);
        }

        .price-tag {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
        }

        .price-period {
            font-size: 0.9rem;
            color: var(--gray);
        }

        .car-card .card-footer-custom {
            background: white;
            border-top: 1px solid var(--gray-light);
            padding: 1rem 1.5rem;
        }

        /* Button Styles */
        .btn-primary-custom {
            background: var(--primary);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .btn-secondary-custom {
            background: var(--secondary);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-secondary-custom:hover {
            background: var(--secondary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .btn-outline-custom {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-outline-custom:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Footer */
        .modern-footer {
            background: var(--dark);
            color: white;
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
        }

        /* No Results Message */
        .no-results {
            text-align: center;
            padding: 3rem 1rem;
        }

        .no-results i {
            font-size: 4rem;
            color: var(--gray);
            margin-bottom: 1rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .car-card {
                margin-bottom: 1.25rem;
            }

            .brand-name {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title" >Daftar Mobil</h1>
            <p class="page-subtitle">Temukan berbagai pilihan mobil berkualitas untuk kebutuhan perjalanan Anda</p>
        </div>
    </section>

    <div class="container my-5">
   
        <!-- Cars Grid -->
        <div class="row" id="cars-container">
            <?php
                $query = $koneksi->query('SELECT * FROM mobil ORDER BY id_mobil DESC')->fetchAll();
                foreach($query as $isi):
            ?>
            <div class="col-md-6 col-lg-4 mb-4 car-item"
                 data-type="<?= htmlspecialchars($isi['tipe'] ?? ''); ?>"
                 data-price="<?= htmlspecialchars($isi['harga']); ?>"
                 data-transmission="<?= htmlspecialchars($isi['transmisi'] ?? ''); ?>"
                 data-status="<?= htmlspecialchars($isi['status']); ?>">
                <div class="car-card card h-100">
                    <img src="assets/image/<?= htmlspecialchars($isi['gambar']); ?>" class="card-img-top" alt="<?= htmlspecialchars($isi['merk']); ?>">
                    <div class="card-body pt-3 pb-0">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0"><?= htmlspecialchars($isi['merk']); ?></h5>
                            <span class="status-badge <?php echo $isi['status'] == 'Tersedia' ? 'status-available' : 'status-not-available'; ?>">
                                <?= htmlspecialchars($isi['status']); ?>
                            </span>
                        </div>
                        <p class="card-text"><?= htmlspecialchars($isi['deskripsi'] ?? 'Mobil berkualitas dengan performa terbaik.'); ?></p>
                        <div class="mb-3">
                            <span class="price-tag">Rp. <?= number_format(htmlspecialchars($isi['harga'])); ?></span>
                            <span class="price-period">/hari</span>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-car"></i> Tipe: <?= htmlspecialchars($isi['tipe'] ?? 'Standard'); ?>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-cogs"></i> Transmisi: <?= htmlspecialchars($isi['transmisi'] ?? 'Manual'); ?>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-user-friends"></i> Kapasitas: <?= htmlspecialchars($isi['kapasitas'] ?? '4'); ?> Orang
                        </li>
                    </ul>

                    <?php if(!empty($isi['keunggulan'])): ?>
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
                    <?php endif; ?>

                    <div class="card-footer-custom">
                        <div class="d-flex justify-content-between">
                            <a href="detail.php?id=<?= htmlspecialchars($isi['id_mobil']); ?>" class="btn btn-outline-custom">
                                <i class="fas fa-info-circle"></i> Detail
                            </a>
                            <a href="booking.php?id=<?= htmlspecialchars($isi['id_mobil']); ?>" class="btn btn-secondary-custom <?= $isi['status'] != 'Tersedia' ? 'disabled' : ''; ?>">
                                <i class="fas fa-calendar-check"></i> Booking
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Message -->
        <div id="no-results" class="no-results" style="display: none;">
            <i class="fas fa-search"></i>
            <h4 class="text-muted">Tidak ada mobil yang sesuai dengan filter</h4>
            <p class="text-muted">Coba ubah kriteria pencarian Anda</p>
            <button id="reset-filters" class="btn btn-primary-custom">Reset Filter</button>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Toggle keunggulan section
            $('[id^="toggleKeunggulan-"]').click(function() {
                var id = $(this).attr('id').replace('toggleKeunggulan-', '');
                $('#keunggulanList-' + id).slideToggle(300);
                $('#toggleIcon-' + id).toggleClass('fa-chevron-up fa-chevron-down');
            });

            // Filter functionality
            function filterCars() {
                var type = $('#filterType').val();
                var priceRange = $('#filterPrice').val();
                var transmission = $('#filterTransmission').val();
                var status = $('#filterStatus').val();

                var visibleCount = 0;

                $('.car-item').each(function() {
                    var carType = $(this).data('type');
                    var carPrice = parseInt($(this).data('price'));
                    var carTransmission = $(this).data('transmission');
                    var carStatus = $(this).data('status');

                    var typeMatch = type === '' || carType === type;
                    var transmissionMatch = transmission === '' || carTransmission === transmission;
                    var statusMatch = status === '' || carStatus === status;

                    var priceMatch = true;
                    if (priceRange !== '') {
                        var priceParts = priceRange.split('-');
                        var minPrice = parseInt(priceParts[0]);
                        var maxPrice = parseInt(priceParts[1]);
                        priceMatch = carPrice >= minPrice && carPrice <= maxPrice;
                    }

                    if (typeMatch && priceMatch && transmissionMatch && statusMatch) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                if (visibleCount === 0) {
                    $('#no-results').show();
                } else {
                    $('#no-results').hide();
                }
            }

            // Apply filters on change
            $('#filterType, #filterPrice, #filterTransmission, #filterStatus').change(function() {
                filterCars();
            });

            // Reset filters
            $('#reset-filters').click(function() {
                $('#filterType, #filterPrice, #filterTransmission, #filterStatus').val('');
                filterCars();
            });

            // Status messages
            <?php if(isset($_GET['status'])): ?>
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
            <?php endif; ?>
        });
    </script>
</body>
</html>
