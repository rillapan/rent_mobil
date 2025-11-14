<?php
require '../../koneksi/koneksi.php';
$title_web = 'Daftar Booking';
$url = '../../';
include '../header.php';

// Inisialisasi filter
$filter_status = $_GET['status'] ?? '';
$filter_sewa = $_GET['sewa'] ?? '';
$where_clause = '';
$params = [];
$param_types = '';

// Filter status pembayaran
if (!empty($filter_status) && $filter_status != 'Semua Status') {
    $where_clause = ' WHERE booking.konfirmasi_pembayaran = ?';
    $params[] = $filter_status;
    $param_types .= 's';
}

// Filter status sewa
if (!empty($filter_sewa) && $filter_sewa != 'Semua') {
    $sewa_condition = '';
    
    switch($filter_sewa) {
        case 'Disewa':
            $sewa_condition = 'booking.tanggal <= CURDATE() AND DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) > CURDATE()';
            break;
        case 'Selesai':
            $sewa_condition = 'DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) <= CURDATE()';
            break;
        case 'Masa Sewa Habis':
            $sewa_condition = 'DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) = CURDATE()';
            break;
        case 'Terlambat Pengembalian':
            $sewa_condition = 'DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) < CURDATE() AND booking.status_pengembalian != "Dikembalikan"';
            break;
        case 'Dibatalkan':
            $sewa_condition = 'booking.konfirmasi_pembayaran = "Refund Diterima / Uang Dikembalikan"';
            break;
    }
    
    if (!empty($where_clause)) {
        $where_clause .= ' AND (' . $sewa_condition . ')';
    } else {
        $where_clause = ' WHERE (' . $sewa_condition . ')';
    }
}

// Filter berdasarkan ID user
if(!empty($_GET['id'])){
    $id = strip_tags($_GET['id']);
    if (!empty($where_clause)) {
        $where_clause .= ' AND id_login = ?';
    } else {
        $where_clause = ' WHERE id_login = ?';
    }
    $params[] = $id;
    $param_types .= 's';
}

// Query dengan prepared statement
$sql = "SELECT mobil.merk, mobil.status AS status_mobil, booking.*,
               DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) AS tanggal_akhir,
               supir.nama_supir
        FROM booking
        JOIN mobil ON booking.id_mobil = mobil.id_mobil
        LEFT JOIN supir ON booking.id_supir = supir.id_supir" . $where_clause . "
        ORDER BY id_booking DESC";

$row = mysqli_prepare($koneksi, $sql);

// Bind parameters jika ada
if (!empty($params)) {
    $bind_types = '';
    foreach ($params as $param) {
        $bind_types .= is_int($param) ? 'i' : 's';
    }
    mysqli_stmt_bind_param($row, $bind_types, ...$params);
}

mysqli_stmt_execute($row);
$result_stmt = mysqli_stmt_get_result($row);
$hasil = [];
while ($row_data = mysqli_fetch_assoc($result_stmt)) {
    $hasil[] = $row_data;
}
mysqli_stmt_close($row);

// Query untuk mendapatkan jumlah data per status pembayaran
$sql_pembayaran = "SELECT
    COUNT(CASE WHEN konfirmasi_pembayaran = 'Pembayaran Diterima' THEN 1 END) as pembayaran_diterima,
    COUNT(CASE WHEN konfirmasi_pembayaran = 'Sedang Diproses' THEN 1 END) as sedang_diproses,
    COUNT(*) as total_booking
    FROM booking
    JOIN mobil ON booking.id_mobil = mobil.id_mobil";

$stmt_pembayaran = mysqli_prepare($koneksi, $sql_pembayaran);
mysqli_stmt_execute($stmt_pembayaran);
$result_stmt_pembayaran = mysqli_stmt_get_result($stmt_pembayaran);
$stats_pembayaran = mysqli_fetch_assoc($result_stmt_pembayaran);
mysqli_stmt_close($stmt_pembayaran);

// Query untuk mendapatkan jumlah data per status sewa
$sql_sewa = "SELECT
    COUNT(CASE WHEN mobil.status = 'Tersedia' AND DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) > CURDATE() THEN 1 END) as disewa,
    COUNT(CASE WHEN mobil.status = 'Tersedia' AND DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) <= CURDATE() THEN 1 END) as selesai,
    COUNT(CASE WHEN mobil.status = 'Tidak Tersedia' AND DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) = CURDATE() THEN 1 END) as masa_sewa_habis,
    COUNT(CASE WHEN mobil.status = 'Tidak Tersedia' AND DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) < CURDATE() THEN 1 END) as terlambat_pengembalian,
    COUNT(CASE WHEN booking.konfirmasi_pembayaran = 'Refund Diterima / Uang Dikembalikan' THEN 1 END) as dibatalkan
    FROM booking
    JOIN mobil ON booking.id_mobil = mobil.id_mobil";

$stmt_sewa = mysqli_prepare($koneksi, $sql_sewa);
mysqli_stmt_execute($stmt_sewa);
$result_stmt_sewa = mysqli_stmt_get_result($stmt_sewa);
$stats_sewa = mysqli_fetch_assoc($result_stmt_sewa);
mysqli_stmt_close($stmt_sewa);

// Logika otomatis update status supir ketika sewa selesai
// Cari booking yang sudah selesai (tanggal akhir <= hari ini) dan supir masih 'Sedang Digunakan'
$sql_check_selesai = "SELECT b.id_booking, b.kode_booking, b.id_supir, s.status AS status_supir
                     FROM booking b
                     JOIN supir s ON b.id_supir = s.id_supir
                     WHERE b.id_supir IS NOT NULL
                     AND s.status = 'Sedang Digunakan'
                     AND DATE_ADD(b.tanggal, INTERVAL b.lama_sewa DAY) <= CURDATE()
                     AND (b.status_pengembalian IS NULL OR b.status_pengembalian = '' OR b.status_pengembalian = 'Belum Dikembalikan')
                     AND b.konfirmasi_pembayaran != 'Refund Diterima / Uang Dikembalikan'";

$stmt_check = mysqli_prepare($koneksi, $sql_check_selesai);
mysqli_stmt_execute($stmt_check);
$result_stmt_check = mysqli_stmt_get_result($stmt_check);
$bookings_selesai = [];
while ($row = mysqli_fetch_assoc($result_stmt_check)) {
    $bookings_selesai[] = $row;
}
mysqli_stmt_close($stmt_check);

// Update status supir menjadi 'Tersedia' untuk booking yang sudah selesai
if (!empty($bookings_selesai)) {
    foreach ($bookings_selesai as $booking) {
        $sql_update_supir = "UPDATE supir SET status = 'Tersedia' WHERE id_supir = ?";
        $stmt_update = mysqli_prepare($koneksi, $sql_update_supir);
        mysqli_stmt_bind_param($stmt_update, "i", $booking['id_supir']);
        mysqli_stmt_execute($stmt_update);
        mysqli_stmt_close($stmt_update);

        // Log untuk debugging (opsional, bisa dihapus nanti)
        error_log("Auto-updated supir ID {$booking['id_supir']} to 'Tersedia' for booking {$booking['kode_booking']}");
    }
}
?>

<br>
<div class="container my-4">
    <!-- Card Informasi Status Pembayaran -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Statistik Status Pembayaran</h5>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-success border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Pembayaran Diterima
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                <?= $stats_pembayaran['pembayaran_diterima'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-warning border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Sedang Diproses
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                <?= $stats_pembayaran['sedang_diproses'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Card Informasi Status Sewa -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3"><i class="fas fa-car me-2"></i>Statistik Status Sewa</h5>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-primary border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Disewa
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                <?= $stats_sewa['disewa'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play-circle fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-secondary border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-secondary text-uppercase mb-1">
                                Selesai
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                <?= $stats_sewa['selesai'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-stop-circle fa-2x text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-warning border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Masa Sewa Habis
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                <?= $stats_sewa['masa_sewa_habis'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-danger border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                                Terlambat
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                <?= $stats_sewa['terlambat_pengembalian'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-dark border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-dark text-uppercase mb-1">
                                Dibatalkan
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                <?= $stats_sewa['dibatalkan'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ban fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Booking Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white shadow">
                
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i> Daftar Booking
            </h5>
        </div>
        <div class="card-body">
            <!-- Filter Section -->
            <div class="mb-4">
                <form method="GET" action="booking.php" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="filterStatus" class="form-label">
                                <i class="fas fa-filter me-1"></i> Status Pembayaran
                            </label>
                            <select class="form-select" id="filterStatus" name="status">
                                <option value="">Semua Status</option>
                                <option value="Pembayaran Diterima" <?= (isset($_GET['status']) && $_GET['status'] == 'Pembayaran Diterima') ? 'selected' : ''; ?>>Pembayaran Diterima</option>
                                <option value="Sedang Diproses" <?= (isset($_GET['status']) && $_GET['status'] == 'Sedang Diproses') ? 'selected' : ''; ?>>Sedang Diproses</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filterSewa" class="form-label">
                                <i class="fas fa-calendar me-1"></i> Status Sewa
                            </label>
                            <select class="form-select" id="filterSewa" name="sewa">
                                <option value="">Semua</option>
                                <option value="Disewa" <?= (isset($_GET['sewa']) && $_GET['sewa'] == 'Disewa') ? 'selected' : ''; ?>>Disewa</option>
                                <option value="Selesai" <?= (isset($_GET['sewa']) && $_GET['sewa'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                                <option value="Masa Sewa Habis" <?= (isset($_GET['sewa']) && $_GET['sewa'] == 'Masa Sewa Habis') ? 'selected' : ''; ?>>Masa Sewa Habis</option>
                                <option value="Terlambat Pengembalian" <?= (isset($_GET['sewa']) && $_GET['sewa'] == 'Terlambat Pengembalian') ? 'selected' : ''; ?>>Terlambat Pengembalian</option>
                                <option value="Dibatalkan" <?= (isset($_GET['sewa']) && $_GET['sewa'] == 'Dibatalkan') ? 'selected' : ''; ?>>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Terapkan Filter
                            </button>
                            <?php if(!empty($_GET['status']) || !empty($_GET['sewa'])): ?>
                            <a href="booking.php" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="fas fa-times me-1"></i> Reset Filter
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div class="table-responsive">
                <?php if(empty($hasil)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>Tidak ada data booking yang ditemukan.
                </div>
                <?php else: ?>
                <table class="table table-striped table-hover table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="text-center">No.</th>
                            <th scope="col">Kode Booking</th>
                            <th scope="col">Merk Mobil</th>
                            <th scope="col">Supir</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Tanggal Sewa</th>
                            <th scope="col" class="text-center">Lama Sewa</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col" class="text-center">Status Sewa</th>
                            <th scope="col" class="text-center">Konfirmasi</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach($hasil as $isi): ?>
                        <tr>
                            <td class="text-center align-middle"><?= $no; ?></td>
                            <td class="align-middle">
                                <span class="fw-bold text-primary"><?= htmlspecialchars($isi['kode_booking']); ?></span>
                            </td>
                            <td class="align-middle"><?= htmlspecialchars($isi['merk']); ?></td>
                            <td class="align-middle">
                                <?php if (!empty($isi['id_supir']) && !empty($isi['nama_supir'])): ?>
                                    <span class="fw-bold"><?= htmlspecialchars($isi['nama_supir']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada supir</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle"><?= htmlspecialchars($isi['nama']); ?></td>
                            <td class="align-middle"><?= date('d/m/Y', strtotime($isi['tanggal'])); ?></td>
                            <td class="text-center align-middle">
                                <?= htmlspecialchars($isi['lama_sewa']); ?> hari
                                <br>
                                <small class="text-muted">Kembali: <?= date('d/m/Y', strtotime($isi['tanggal_akhir'])); ?></small>
                            </td>
                            <td class="align-middle fw-bold">Rp<?= number_format(htmlspecialchars($isi['total_harga']), 0, ',', '.'); ?></td>
                            <td class="text-center align-middle">
                                <?php
                                if (!empty($filter_sewa)) {
                                    switch($filter_sewa) {
                                        case 'Disewa':
                                            echo '<span class="badge bg-info text-white"><i class="fas fa-calendar-check me-1"></i> Disewa</span>';
                                            break;
                                        case 'Selesai':
                                            echo '<span class="badge bg-secondary text-white"><i class="fas fa-stop-circle me-1"></i> Selesai</span>';
                                            break;
                                        case 'Masa Sewa Habis':
                                            echo '<span class="badge bg-warning text-white"><i class="fas fa-clock me-1"></i> Masa Sewa Habis</span>';
                                            echo '<br><small class="text-muted">masa sewa habis hari ini</small>';
                                            break;
                                        case 'Terlambat Pengembalian':
                                            $tanggal_akhir = strtotime($isi['tanggal_akhir']);
                                            $sekarang = strtotime(date('Y-m-d'));
                                            $days_late = floor(($sekarang - $tanggal_akhir) / 86400);
                                            echo '<span class="badge bg-danger text-white"><i class="fas fa-exclamation-triangle me-1"></i> Terlambat</span>';
                                            echo '<br><small class="text-muted">melewati batas pengembalian selama ' . $days_late . ' hari</small>';
                                            break;
                                        case 'Dibatalkan':
                                            echo '<span class="badge bg-dark text-white"><i class="fas fa-ban me-1"></i> Dibatalkan</span>';
                                            break;
                                    }
                                } else {
                                    $status_mobil = $isi['status_mobil'];
                                    $tanggal_akhir = strtotime($isi['tanggal_akhir']);
                                    $sekarang = strtotime(date('Y-m-d'));
                                    $days_late = 0;
                                    $status_pengembalian = $isi['status_pengembalian'] ?? 'Belum Dikembalikan';
                                    $konfirmasi_pembayaran = $isi['konfirmasi_pembayaran'];

                                    // Jika dibatalkan (refund diterima), tampilkan 'Dibatalkan'
                                    if ($konfirmasi_pembayaran == 'Refund Diterima / Uang Dikembalikan') {
                                        echo '<span class="badge bg-dark text-white"><i class="fas fa-ban me-1"></i> Dibatalkan</span>';
                                    } elseif ($status_pengembalian == 'Dikembalikan') {
                                        echo '<span class="badge bg-secondary text-white"><i class="fas fa-stop-circle me-1"></i> Selesai</span>';
                                    } elseif ($status_mobil == 'Tersedia') {
                                        if ($tanggal_akhir <= $sekarang) {
                                            echo '<span class="badge bg-secondary text-white"><i class="fas fa-stop-circle me-1"></i> Selesai</span>';
                                        } else {
                                            echo '<span class="badge bg-info text-white"><i class="fas fa-calendar-check me-1"></i> Disewa</span>';
                                        }
                                    } elseif ($status_mobil == 'Tidak Tersedia') {
                                        if ($tanggal_akhir > $sekarang) {
                                            echo '<span class="badge bg-success text-white"><i class="fas fa-play-circle me-1"></i> Aktif</span>';
                                        } elseif ($tanggal_akhir == $sekarang) {
                                            echo '<span class="badge bg-warning text-white"><i class="fas fa-clock me-1"></i> Masa Sewa Habis</span>';
                                            echo '<br><small class="text-muted">masa sewa habis hari ini</small>';
                                        } else {
                                            $days_late = floor(($sekarang - $tanggal_akhir) / 86400);
                                            echo '<span class="badge bg-danger text-white"><i class="fas fa-exclamation-triangle me-1"></i> Terlambat</span>';
                                            echo '<br><small class="text-muted">melewati batas pengembalian selama ' . $days_late . ' hari</small>';
                                        }
                                    }
                                }
                                ?>
                            </td>
                            <td class="text-center align-middle">
                                <?php 
                                $status_bayar = $isi['konfirmasi_pembayaran'];
                                $badge_class = [
                                    'Pembayaran Diterima' => 'bg-success',
                                    'Sedang Diproses' => 'bg-warning',
                                ];
                                $badge_icon = [
                                    'Pembayaran Diterima' => 'fa-check-circle',
                                    'Sedang Diproses' => 'fa-hourglass-half',
                                   
                                ];
                                ?>
                                <span class="badge <?= $badge_class[$status_bayar] ?? 'bg-secondary'; ?> text-white">
                                    <i class="fas <?= $badge_icon[$status_bayar] ?? 'fa-question-circle'; ?> me-1"></i>
                                    <?= htmlspecialchars($status_bayar); ?>
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <a class="btn btn-secondary btn-sm" href="bayar.php?id=<?= $isi['kode_booking']; ?>" role="button" title="Lihat Detail Transaksi">
                                    <i class="fas fa-info-circle"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php $no++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
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
    
    .bg-gradient-primary {
        background: linear-gradient(45deg, var(--primary), var(--primary-dark)) !important;
    }

    .container {
        max-width: 1500px;
    }
    
    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
        color: white;
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
        font-size: 0.85em;
        border-radius: .3rem;
        white-space: nowrap;
    }
    
    .badge i {
        margin-right: .3em;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
    }
    
    .card {
        border: none;
        border-radius: 10px;
    }
    
    .card-header {
        border-radius: 10px 10px 0 0 !important;
        padding: 1rem 1.5rem;
    }
    
    .text-xs {
        font-size: 0.7rem;
    }
    
    .border-4 {
        border-width: 4px !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Optional: Add click functionality to status cards to filter
    const statusCards = document.querySelectorAll('.card[border-start]');
    statusCards.forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function() {
            const statusText = this.querySelector('.text-uppercase').textContent.trim();
            // You can add functionality to auto-filter when cards are clicked
            console.log('Filter by:', statusText);
        });
    });
});
</script>

<?php include '../footer.php'; ?>