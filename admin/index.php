<?php
require '../koneksi/koneksi.php';
$title_web = 'Dashboard';
$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../';
include 'header.php';

// Ambil data info website
$sql_web = "SELECT * FROM infoweb WHERE id = 1";
$row_web = mysqli_prepare($koneksi, $sql_web);
mysqli_stmt_execute($row_web);
$result_stmt = mysqli_stmt_get_result($row_web);
$info_web = mysqli_fetch_object($result_stmt);
mysqli_stmt_close($row_web);

// Ambil data profil admin
$id_login = $_SESSION["USER"]["id_login"];
$sql_profil = "SELECT * FROM login WHERE id_login = ?";
$row_profil = mysqli_prepare($koneksi, $sql_profil);
mysqli_stmt_bind_param($row_profil, "i", $id_login);
mysqli_stmt_execute($row_profil);
$result_stmt = mysqli_stmt_get_result($row_profil);
$profil_admin = mysqli_fetch_object($result_stmt);
mysqli_stmt_close($row_profil);

function fetchSingle($koneksi, $sql, $params = []) {
    $stmt = mysqli_prepare($koneksi, $sql);
    if (!empty($params)) {
        $types = str_repeat('s', count($params)); // Assuming all params are strings, adjust if needed
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result_stmt = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_assoc($result_stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Data pendapatan
$pendapatan_hari = fetchSingle($koneksi, "SELECT SUM(total_harga) AS total FROM booking WHERE konfirmasi_pembayaran = 'Pembayaran Diterima' AND DATE(tanggal) = CURDATE()");
$pendapatan_minggu = fetchSingle($koneksi, "SELECT SUM(total_harga) AS total FROM booking WHERE konfirmasi_pembayaran = 'Pembayaran Diterima' AND YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)");
$pendapatan_bulan = fetchSingle($koneksi, "SELECT SUM(total_harga) AS total FROM booking WHERE konfirmasi_pembayaran = 'Pembayaran Diterima' AND YEAR(tanggal) = YEAR(CURDATE()) AND MONTH(tanggal) = MONTH(CURDATE())");
$pendapatan_tahun = fetchSingle($koneksi, "SELECT SUM(total_harga) AS total FROM booking WHERE konfirmasi_pembayaran = 'Pembayaran Diterima' AND YEAR(tanggal) = YEAR(CURDATE())");
$pendapatan_total = fetchSingle($koneksi, "SELECT SUM(total_harga) AS total FROM booking WHERE konfirmasi_pembayaran = 'Pembayaran Diterima'");

// Data pembayaran
$sql_pembayaran = "SELECT 
    COUNT(CASE WHEN konfirmasi_pembayaran = 'Pembayaran Diterima' THEN 1 END) as pembayaran_diterima,
    COUNT(CASE WHEN konfirmasi_pembayaran = 'Sedang Diproses' THEN 1 END) as sedang_diproses,
    COUNT(*) as total_booking
    FROM booking 
    JOIN mobil ON booking.id_mobil = mobil.id_mobil";
$stats_pembayaran = fetchSingle($koneksi, $sql_pembayaran);

// Data sewa
$sql_sewa = "SELECT
    COUNT(CASE WHEN mobil.status = 'Tersedia' AND DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) > CURDATE() THEN 1 END) as disewa,
    COUNT(CASE WHEN mobil.status = 'Tersedia' AND DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) <= CURDATE() THEN 1 END) as selesai,
    COUNT(CASE WHEN mobil.status = 'Tidak Tersedia' AND DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) = CURDATE() THEN 1 END) as masa_sewa_habis,
    COUNT(CASE WHEN mobil.status = 'Tidak Tersedia' AND DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) < CURDATE() THEN 1 END) as terlambat_pengembalian,
    COUNT(CASE WHEN booking.konfirmasi_pembayaran = 'Refund Diterima / Uang Dikembalikan' THEN 1 END) as dibatalkan
    FROM booking
    JOIN mobil ON booking.id_mobil = mobil.id_mobil";
$stats_sewa = fetchSingle($koneksi, $sql_sewa);

// Data mobil
$mobil_stats = fetchSingle($koneksi, "SELECT 
    COUNT(CASE WHEN status = 'Tersedia' THEN 1 END) AS mobil_tersedia,
    COUNT(CASE WHEN status = 'Tidak Tersedia' THEN 1 END) AS mobil_tidak,
    COUNT(*) AS mobil_total
    FROM mobil");

// Data supir
$supir_stats = fetchSingle($koneksi, "SELECT 
    COUNT(CASE WHEN status = 'Tersedia' THEN 1 END) AS supir_tersedia,
    COUNT(CASE WHEN status = 'Sedang Digunakan' THEN 1 END) AS supir_digunakan,
    COUNT(CASE WHEN status = 'Close' THEN 1 END) AS supir_close,
    COUNT(*) AS supir_total
    FROM supir");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title_web ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    :root {
        --primary: #1A237E;
        --secondary: #FF6B35;
        --success: #28a745;
        --light: #F8F9FA;
        --dark: #212529;
    }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card .card-body {
            padding: 1.5rem;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
            margin-bottom: 1rem;
        }
        
        .section-title {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary);
            display: inline-block;
        }
        
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        
        .highlight-card {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .trend-up {
            color: #28a745;
        }
        
        .trend-down {
            color: #dc3545;
        }
    </style>
</head>
<body>
  
    <div class="container">
        <!-- Ringkasan Pendapatan -->
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="section-title"><i class="fas fa-chart-line me-2"></i>Ringkasan Pendapatan</h3>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold">Hari Ini</div>
                                <div class="h4 fw-bold text-primary">Rp<?= number_format((int)($pendapatan_hari['total'] ?? 0), 0, ',', '.'); ?></div>
                            </div>
                            <div class="stat-icon text-primary">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Pendapatan hari ini</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card border-start border-info border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold">Minggu Ini</div>
                                <div class="h4 fw-bold text-info">Rp<?= number_format((int)($pendapatan_minggu['total'] ?? 0), 0, ',', '.'); ?></div>
                            </div>
                            <div class="stat-icon text-info">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Pendapatan 7 hari terakhir</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card border-start border-warning border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold">Bulan Ini</div>
                                <div class="h4 fw-bold text-warning">Rp<?= number_format((int)($pendapatan_bulan['total'] ?? 0), 0, ',', '.'); ?></div>
                            </div>
                            <div class="stat-icon text-warning">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Pendapatan bulan berjalan</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card highlight-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-semibold">Total Pendapatan</div>
                                <div class="h4 fw-bold">Rp<?= number_format((int)($pendapatan_total['total'] ?? 0), 0, ',', '.'); ?></div>
                            </div>
                            <div class="stat-icon text-white">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small>Hanya transaksi dengan status "Pembayaran Diterima"</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Pendapatan -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="chart-container">
                    <h4 class="mb-3">Grafik Pendapatan</h4>
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Statistik Lainnya -->
        <div class="row">
            <!-- Status Pembayaran -->
            <div class="col-lg-4 mb-4">
                <div class="chart-container h-100">
                    <h4 class="mb-3"><i class="fas fa-credit-card me-2"></i>Status Pembayaran</h4>
                    <canvas id="paymentChart" height="250"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pembayaran Diterima</span>
                            <span class="fw-bold"><?= $stats_pembayaran['pembayaran_diterima'] ?? 0 ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sedang Diproses</span>
                            <span class="fw-bold"><?= $stats_pembayaran['sedang_diproses'] ?? 0 ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total Booking</span>
                            <span class="fw-bold"><?= $stats_pembayaran['total_booking'] ?? 0 ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Sewa -->
            <div class="col-lg-4 mb-4">
                <div class="chart-container h-100">
                    <h4 class="mb-3"><i class="fas fa-car me-2"></i>Status Sewa</h4>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary rounded me-2" style="width: 12px; height: 12px;"></div>
                            <span>Disewa: <?= $stats_sewa['disewa'] ?? 0 ?></span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success rounded me-2" style="width: 12px; height: 12px;"></div>
                            <span>Selesai: <?= $stats_sewa['selesai'] ?? 0 ?></span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-info rounded me-2" style="width: 12px; height: 12px;"></div>
                            <span>Masa Sewa Habis: <?= $stats_sewa['masa_sewa_habis'] ?? 0 ?></span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-danger rounded me-2" style="width: 12px; height: 12px;"></div>
                            <span>Terlambat: <?= $stats_sewa['terlambat_pengembalian'] ?? 0 ?></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-dark rounded me-2" style="width: 12px; height: 12px;"></div>
                            <span>Dibatalkan: <?= $stats_sewa['dibatalkan'] ?? 0 ?></span>
                        </div>
                    </div>
                    <canvas id="rentalChart" height="200"></canvas>
                </div>
            </div>

            <!-- Ketersediaan Mobil & Supir -->
            <div class="col-lg-4 mb-4">
                <div class="chart-container h-100">
                    <h4 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Ketersediaan</h4>
                    
                    <h6 class="mt-4">Mobil</h6>
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $mobil_stats['mobil_total'] > 0 ? ($mobil_stats['mobil_tersedia'] / $mobil_stats['mobil_total']) * 100 : 0 ?>%">
                            <?= $mobil_stats['mobil_tersedia'] ?? 0 ?> Tersedia
                        </div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $mobil_stats['mobil_total'] > 0 ? ($mobil_stats['mobil_tidak'] / $mobil_stats['mobil_total']) * 100 : 0 ?>%">
                            <?= $mobil_stats['mobil_tidak'] ?? 0 ?> Tidak Tersedia
                        </div>
                    </div>
                    
                    <h6 class="mt-4">Supir</h6>
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $supir_stats['supir_total'] > 0 ? ($supir_stats['supir_tersedia'] / $supir_stats['supir_total']) * 100 : 0 ?>%">
                            <?= $supir_stats['supir_tersedia'] ?? 0 ?> Tersedia
                        </div>
                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $supir_stats['supir_total'] > 0 ? ($supir_stats['supir_digunakan'] / $supir_stats['supir_total']) * 100 : 0 ?>%">
                            <?= $supir_stats['supir_digunakan'] ?? 0 ?> Digunakan
                        </div>
                        <div class="progress-bar bg-dark" role="progressbar" style="width: <?= $supir_stats['supir_total'] > 0 ? ($supir_stats['supir_close'] / $supir_stats['supir_total']) * 100 : 0 ?>%">
                            <?= $supir_stats['supir_close'] ?? 0 ?> Close
                        </div>
                    </div>
                    
                    <div class="row mt-4 text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <div class="h5 mb-1"><?= $mobil_stats['mobil_total'] ?? 0 ?></div>
                                <small class="text-muted">Total Mobil</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <div class="h5 mb-1"><?= $supir_stats['supir_total'] ?? 0 ?></div>
                                <small class="text-muted">Total Supir</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Grafik Pendapatan
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: ['Hari Ini', 'Minggu Ini', 'Bulan Ini', 'Tahun Ini'],
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: [
                            <?= (int)($pendapatan_hari['total'] ?? 0) ?>,
                            <?= (int)($pendapatan_minggu['total'] ?? 0) ?>,
                            <?= (int)($pendapatan_bulan['total'] ?? 0) ?>,
                            <?= (int)($pendapatan_tahun['total'] ?? 0) ?>
                        ],
                        backgroundColor: [
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(72, 149, 239, 0.7)',
                            'rgba(76, 201, 240, 0.7)',
                            'rgba(63, 55, 201, 0.7)'
                        ],
                        borderColor: [
                            'rgba(67, 97, 238, 1)',
                            'rgba(72, 149, 239, 1)',
                            'rgba(76, 201, 240, 1)',
                            'rgba(63, 55, 201, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            // Grafik Status Pembayaran
            const paymentCtx = document.getElementById('paymentChart').getContext('2d');
            const paymentChart = new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pembayaran Diterima', 'Sedang Diproses'],
                    datasets: [{
                        data: [
                            <?= $stats_pembayaran['pembayaran_diterima'] ?? 0 ?>,
                            <?= $stats_pembayaran['sedang_diproses'] ?? 0 ?>
                        ],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.7)',
                            'rgba(255, 193, 7, 0.7)'
                        ],
                        borderColor: [
                            'rgba(40, 167, 69, 1)',
                            'rgba(255, 193, 7, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });

            // Grafik Status Sewa
            const rentalCtx = document.getElementById('rentalChart').getContext('2d');
            const rentalChart = new Chart(rentalCtx, {
                type: 'polarArea',
                data: {
                    labels: ['Disewa', 'Selesai', 'Masa Sewa Habis', 'Terlambat', 'Dibatalkan'],
                    datasets: [{
                        data: [
                            <?= $stats_sewa['disewa'] ?? 0 ?>,
                            <?= $stats_sewa['selesai'] ?? 0 ?>,
                            <?= $stats_sewa['masa_sewa_habis'] ?? 0 ?>,
                            <?= $stats_sewa['terlambat_pengembalian'] ?? 0 ?>,
                            <?= $stats_sewa['dibatalkan'] ?? 0 ?>
                        ],
                        backgroundColor: [
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(40, 167, 69, 0.7)',
                            'rgba(23, 162, 184, 0.7)',
                            'rgba(220, 53, 69, 0.7)',
                            'rgba(52, 58, 64, 0.7)'
                        ],
                        borderColor: [
                            'rgba(67, 97, 238, 1)',
                            'rgba(40, 167, 69, 1)',
                            'rgba(23, 162, 184, 1)',
                            'rgba(220, 53, 69, 1)',
                            'rgba(52, 58, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // SweetAlert untuk notifikasi
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status) {
                let title, text, icon;
                switch (status) {
                    case 'web_success':
                        title = 'Berhasil!';
                        text = 'Data info website telah berhasil diperbarui.';
                        icon = 'success';
                        break;
                    case 'web_error':
                        title = 'Gagal!';
                        text = 'Terjadi kesalahan saat memperbarui data info website.';
                        icon = 'error';
                        break;
                    case 'profile_success':
                        title = 'Berhasil!';
                        text = 'Data profil Anda telah berhasil diperbarui.';
                        icon = 'success';
                        break;
                    case 'profile_error':
                        title = 'Gagal!';
                        text = 'Terjadi kesalahan saat memperbarui profil Anda.';
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
                        window.history.replaceState({}, document.title, "index.php");
                    });
                }
            }
        });
    </script>
</body>
</html>

<?php include '../footer.php'; ?>