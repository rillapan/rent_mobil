<?php
require '../../koneksi/koneksi.php';
$title_web = 'Laporan Transaksi';
$url = '../../';
include '../header.php';

// Data sewa selesai
$sql_selesai = "SELECT
    COUNT(CASE WHEN DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) <= CURDATE() AND YEARWEEK(DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY), 1) = YEARWEEK(CURDATE(), 1) THEN 1 END) AS minggu_ini,
    COUNT(CASE WHEN DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) <= CURDATE() AND YEAR(DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY)) = YEAR(CURDATE()) AND MONTH(DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY)) = MONTH(CURDATE()) THEN 1 END) AS bulan_ini,
    COUNT(CASE WHEN DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) <= CURDATE() AND YEAR(DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY)) = YEAR(CURDATE()) THEN 1 END) AS tahun_ini
    FROM booking";

$stmt_selesai = mysqli_prepare($koneksi, $sql_selesai);
mysqli_stmt_execute($stmt_selesai);
$result_stmt_selesai = mysqli_stmt_get_result($stmt_selesai);
$stats_selesai = mysqli_fetch_assoc($result_stmt_selesai);
mysqli_stmt_close($stmt_selesai);

// Data pendapatan
$sql_pendapatan = "SELECT SUM(total_harga) AS total FROM booking WHERE konfirmasi_pembayaran = 'Pembayaran Diterima'";
$stmt_pendapatan = mysqli_prepare($koneksi, $sql_pendapatan);
mysqli_stmt_execute($stmt_pendapatan);
$result_stmt_pendapatan = mysqli_stmt_get_result($stmt_pendapatan);
$pendapatan_result = mysqli_fetch_assoc($result_stmt_pendapatan);
$total_pendapatan = $pendapatan_result['total'] ?? 0;
mysqli_stmt_close($stmt_pendapatan);

// Data pendapatan per periode
$sql_pendapatan_periode = "SELECT
    SUM(CASE WHEN konfirmasi_pembayaran = 'Pembayaran Diterima' AND YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1) THEN total_harga ELSE 0 END) AS minggu_ini,
    SUM(CASE WHEN konfirmasi_pembayaran = 'Pembayaran Diterima' AND YEAR(tanggal) = YEAR(CURDATE()) AND MONTH(tanggal) = MONTH(CURDATE()) THEN total_harga ELSE 0 END) AS bulan_ini,
    SUM(CASE WHEN konfirmasi_pembayaran = 'Pembayaran Diterima' AND YEAR(tanggal) = YEAR(CURDATE()) THEN total_harga ELSE 0 END) AS tahun_ini
    FROM booking";

$stmt_pendapatan_periode = mysqli_prepare($koneksi, $sql_pendapatan_periode);
mysqli_stmt_execute($stmt_pendapatan_periode);
$result_stmt_pendapatan_periode = mysqli_stmt_get_result($stmt_pendapatan_periode);
$pendapatan_periode = mysqli_fetch_assoc($result_stmt_pendapatan_periode);
mysqli_stmt_close($stmt_pendapatan_periode);

// Data mobil terlaris
$sql_mobil = "SELECT mobil.merk AS merk, COUNT(*) AS jumlah
               FROM booking
               JOIN mobil ON booking.id_mobil = mobil.id_mobil
               GROUP BY mobil.id_mobil, mobil.merk
               ORDER BY jumlah DESC";
$stmt_mobil = mysqli_prepare($koneksi, $sql_mobil);
mysqli_stmt_execute($stmt_mobil);
$result_stmt_mobil = mysqli_stmt_get_result($stmt_mobil);
$data_mobil = [];
while ($row = mysqli_fetch_assoc($result_stmt_mobil)) {
    $data_mobil[] = $row;
}
mysqli_stmt_close($stmt_mobil);

// Data tren bulanan
$sql_tren = "SELECT
    MONTH(tanggal) as bulan,
    COUNT(*) as jumlah_sewa,
    SUM(CASE WHEN konfirmasi_pembayaran = 'Pembayaran Diterima' THEN total_harga ELSE 0 END) as pendapatan
    FROM booking
    WHERE YEAR(tanggal) = YEAR(CURDATE())
    GROUP BY MONTH(tanggal)
    ORDER BY bulan";

$stmt_tren = mysqli_prepare($koneksi, $sql_tren);
mysqli_stmt_execute($stmt_tren);
$result_stmt_tren = mysqli_stmt_get_result($stmt_tren);
$data_tren = [];
while ($row = mysqli_fetch_assoc($result_stmt_tren)) {
    $data_tren[] = $row;
}
mysqli_stmt_close($stmt_tren);

// Data status pembayaran
$sql_status = "SELECT
    konfirmasi_pembayaran,
    COUNT(*) as jumlah
    FROM booking
    GROUP BY konfirmasi_pembayaran";

$stmt_status = mysqli_prepare($koneksi, $sql_status);
mysqli_stmt_execute($stmt_status);
$result_stmt_status = mysqli_stmt_get_result($stmt_status);
$data_status = [];
while ($row = mysqli_fetch_assoc($result_stmt_status)) {
    $data_status[] = $row;
}
mysqli_stmt_close($stmt_status);
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
        
        .page-header {
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
        
        .section-title {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary);
            display: inline-block;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
            margin-bottom: 1rem;
        }
        
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .table-custom {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .table-custom thead th {
            background-color: var(--primary);
            color: white;
            border: none;
        }
        
        .table-custom tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Ringkasan Utama -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold">Total Pendapatan</div>
                                <div class="h4 fw-bold text-primary">Rp<?= number_format((int)$total_pendapatan, 0, ',', '.'); ?></div>
                            </div>
                            <div class="stat-icon text-primary">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Hanya transaksi dengan status "Pembayaran Diterima"</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card border-start border-info border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold">Sewa Selesai (Minggu Ini)</div>
                                <div class="h4 fw-bold text-info"><?= (int)($stats_selesai['minggu_ini'] ?? 0); ?></div>
                            </div>
                            <div class="stat-icon text-info">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Sewa yang selesai dalam 7 hari terakhir</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card border-start border-warning border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold">Sewa Selesai (Bulan Ini)</div>
                                <div class="h4 fw-bold text-warning"><?= (int)($stats_selesai['bulan_ini'] ?? 0); ?></div>
                            </div>
                            <div class="stat-icon text-warning">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Sewa yang selesai bulan ini</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold">Sewa Selesai (Tahun Ini)</div>
                                <div class="h4 fw-bold text-success"><?= (int)($stats_selesai['tahun_ini'] ?? 0); ?></div>
                            </div>
                            <div class="stat-icon text-success">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Sewa yang selesai tahun ini</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Utama -->
        <div class="row mb-4">
            <div class="col-lg-8 mb-4">
                <div class="chart-container">
                    <h4 class="section-title"><i class="fas fa-chart-bar me-2"></i>Statistik Sewa Selesai & Pendapatan</h4>
                    <canvas id="combinedChart" height="120"></canvas>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="chart-container h-100">
                    <h4 class="section-title"><i class="fas fa-chart-pie me-2"></i>Status Pembayaran</h4>
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Grafik Tren dan Mobil Terlaris -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <h4 class="section-title"><i class="fas fa-chart-line me-2"></i>Tren Bulanan (Tahun <?= date('Y') ?>)</h4>
                    <canvas id="trenChart" height="250"></canvas>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <h4 class="section-title"><i class="fas fa-car me-2"></i>Mobil Terlaris</h4>
                    <div class="d-flex justify-content-center">
                        <div style="max-width:400px;width:100%">
                            <canvas id="mobilChart" height="250"></canvas>
                        </div>
                    </div>
                    
                    <!-- Tabel data mobil terlaris -->
                    <div class="mt-4">
                        <h6 class="mb-3">Detail Mobil Terlaris</h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-custom">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Merk Mobil</th>
                                        <th class="text-end">Jumlah Booking</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($data_mobil) > 0): ?>
                                        <?php foreach ($data_mobil as $index => $mobil): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= htmlspecialchars($mobil['merk']) ?></td>
                                                <td class="text-end fw-bold"><?= $mobil['jumlah'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Tidak ada data mobil</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Pendapatan Per Periode -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-container">
                    <h4 class="section-title"><i class="fas fa-money-bill-wave me-2"></i>Ringkasan Pendapatan Per Periode</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <div class="text-muted small">Pendapatan Minggu Ini</div>
                                    <div class="h4 fw-bold text-primary">Rp<?= number_format((int)($pendapatan_periode['minggu_ini'] ?? 0), 0, ',', '.'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <div class="text-muted small">Pendapatan Bulan Ini</div>
                                    <div class="h4 fw-bold text-info">Rp<?= number_format((int)($pendapatan_periode['bulan_ini'] ?? 0), 0, ',', '.'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <div class="text-muted small">Pendapatan Tahun Ini</div>
                                    <div class="h4 fw-bold text-success">Rp<?= number_format((int)($pendapatan_periode['tahun_ini'] ?? 0), 0, ',', '.'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data untuk grafik
            const periodeLabels = ['Minggu Ini', 'Bulan Ini', 'Tahun Ini'];
            const sewaData = [
                <?= (int)($stats_selesai['minggu_ini'] ?? 0); ?>,
                <?= (int)($stats_selesai['bulan_ini'] ?? 0); ?>,
                <?= (int)($stats_selesai['tahun_ini'] ?? 0); ?>
            ];
            const pendapatanData = [
                <?= (int)($pendapatan_periode['minggu_ini'] ?? 0); ?>,
                <?= (int)($pendapatan_periode['bulan_ini'] ?? 0); ?>,
                <?= (int)($pendapatan_periode['tahun_ini'] ?? 0); ?>
            ];

            // Grafik kombinasi (Sewa & Pendapatan)
            const combinedCtx = document.getElementById('combinedChart').getContext('2d');
            new Chart(combinedCtx, {
                type: 'bar',
                data: {
                    labels: periodeLabels,
                    datasets: [
                        {
                            label: 'Sewa Selesai',
                            data: sewaData,
                            backgroundColor: 'rgba(67, 97, 238, 0.7)',
                            borderColor: 'rgba(67, 97, 238, 1)',
                            borderWidth: 1,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Pendapatan (Rp)',
                            data: pendapatanData,
                            backgroundColor: 'rgba(76, 201, 240, 0.7)',
                            borderColor: 'rgba(76, 201, 240, 1)',
                            borderWidth: 1,
                            type: 'line',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp' + value.toLocaleString('id-ID');
                                }
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label.includes('Pendapatan')) {
                                        return label + ': Rp' + context.raw.toLocaleString('id-ID');
                                    }
                                    return label + ': ' + context.raw;
                                }
                            }
                        }
                    }
                }
            });

            // Grafik status pembayaran
            const statusLabels = [
                <?php
                    $first = true;
                    foreach ($data_status as $status) {
                        echo ($first ? "" : ",") . "'" . str_replace("'", "\'", $status['konfirmasi_pembayaran']) . "'";
                        $first = false;
                    }
                ?>
            ];
            const statusData = [
                <?php
                    $first = true;
                    foreach ($data_status as $status) {
                        echo ($first ? "" : ",") . (int)$status['jumlah'];
                        $first = false;
                    }
                ?>
            ];
            const statusColors = ['#28a745', '#ffc107', '#dc3545', '#6c757d', '#17a2b8'];
            
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: statusColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Grafik tren bulanan
            const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const trenSewaData = new Array(12).fill(0);
            const trenPendapatanData = new Array(12).fill(0);
            
            <?php foreach ($data_tren as $tren): ?>
                trenSewaData[<?= $tren['bulan'] - 1 ?>] = <?= (int)$tren['jumlah_sewa'] ?>;
                trenPendapatanData[<?= $tren['bulan'] - 1 ?>] = <?= (int)$tren['pendapatan'] ?>;
            <?php endforeach; ?>
            
            const trenCtx = document.getElementById('trenChart').getContext('2d');
            new Chart(trenCtx, {
                type: 'line',
                data: {
                    labels: bulanLabels,
                    datasets: [
                        {
                            label: 'Jumlah Sewa',
                            data: trenSewaData,
                            borderColor: 'rgba(67, 97, 238, 1)',
                            backgroundColor: 'rgba(67, 97, 238, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Pendapatan (Rp)',
                            data: trenPendapatanData,
                            borderColor: 'rgba(76, 201, 240, 1)',
                            backgroundColor: 'rgba(76, 201, 240, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp' + value.toLocaleString('id-ID');
                                }
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    }
                }
            });

            // Grafik mobil terlaris
            const mobilLabels = [
                <?php
                    $first = true;
                    foreach ($data_mobil as $row) {
                        echo ($first ? "" : ",") . "'" . str_replace("'", "\'", $row['merk']) . "'";
                        $first = false;
                    }
                ?>
            ];
            const mobilData = [
                <?php
                    $first = true;
                    foreach ($data_mobil as $row) {
                        echo ($first ? "" : ",") . (int)$row['jumlah'];
                        $first = false;
                    }
                ?>
            ];
            
            // Generate warna dinamis
            const generateColors = (count) => {
                const colors = [];
                const baseHue = 220; // Warna biru
                for (let i = 0; i < count; i++) {
                    const hue = (baseHue + (i * 30)) % 360;
                    colors.push(`hsl(${hue}, 70%, 60%)`);
                }
                return colors;
            };
            
            const mobilCtx = document.getElementById('mobilChart').getContext('2d');
            new Chart(mobilCtx, {
                type: 'pie',
                data: {
                    labels: mobilLabels,
                    datasets: [{
                        data: mobilData,
                        backgroundColor: generateColors(mobilLabels.length),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>

<?php include '../footer.php'; ?>