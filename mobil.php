<?php
session_start();
require 'koneksi/koneksi.php';

// Determine sort order
$sort = isset($_GET['sort']) ? strip_tags($_GET['sort']) : 'id_mobil DESC';
$allowed_sorts = [
    'harga_asc' => 'harga ASC',
    'harga_desc' => 'harga DESC',
    'tahun_asc' => 'tahun_terbit ASC',
    'tahun_desc' => 'tahun_terbit DESC',
    'kursi_asc' => 'jumlah_kursi ASC',
    'kursi_desc' => 'jumlah_kursi DESC',
    'id_mobil DESC' => 'id_mobil DESC'
];
$order_by = isset($allowed_sorts[$sort]) ? $allowed_sorts[$sort] : 'id_mobil DESC';

// Determine status filter
$status_filter = isset($_GET['status']) ? strip_tags($_GET['status']) : 'semua';
$allowed_statuses = ['semua', 'tersedia', 'tidak_tersedia'];
$status_filter = in_array($status_filter, $allowed_statuses) ? $status_filter : 'semua';

if(isset($_GET['cari']))
{
    $cari = strip_tags($_GET['cari']);
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM mobil WHERE nama_mobil LIKE ? OR merk LIKE ? ORDER BY $order_by");
    $search_term = '%' . $cari . '%';
    mysqli_stmt_bind_param($stmt, "ss", $search_term, $search_term);
    mysqli_stmt_execute($stmt);
    $result_stmt = mysqli_stmt_get_result($stmt);
    $query = [];
    while ($row = mysqli_fetch_assoc($result_stmt)) {
        $query[] = $row;
    }
    mysqli_stmt_close($stmt);
}else{
    $result = mysqli_query($koneksi, "SELECT * FROM mobil ORDER BY $order_by");
    $query = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $query[] = $row;
    }
}

// Filter by status if not 'semua'
if ($status_filter !== 'semua') {
    $filtered_query = [];
    foreach ($query as $isi) {
        $sql_plat = "SELECT COUNT(*) as available_count FROM mobil_plat WHERE id_mobil = ? AND status_plat = 'Tersedia'";
        $stmt_plat = mysqli_prepare($koneksi, $sql_plat);
        mysqli_stmt_bind_param($stmt_plat, "i", $isi['id_mobil']);
        mysqli_stmt_execute($stmt_plat);
        $result_plat = mysqli_stmt_get_result($stmt_plat);
        $plat_data = mysqli_fetch_assoc($result_plat);
        $available_plates_count = $plat_data['available_count'];
        if ($isi['status'] == 'Tidak Tersedia') {
            $dynamic_status = 'Tidak Tersedia';
        } else {
            $dynamic_status = $available_plates_count > 0 ? 'Tersedia' : 'Tidak Tersedia';
        }
        mysqli_stmt_close($stmt_plat);

        if (($status_filter == 'tersedia' && $dynamic_status == 'Tersedia') ||
            ($status_filter == 'tidak_tersedia' && $dynamic_status == 'Tidak Tersedia')) {
            $filtered_query[] = $isi;
        }
    }
    $query = $filtered_query;
}
?>
<?php include 'header.php'; ?>
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
    .badge-custom {
        margin-left: 50px;
        margin-bottom: 10px;
        background: #f8f9fa;
        color: #495057;
        font-weight: bold;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        display: inline-flex;
        align-items: center;
        margin-top: 0.5rem;
        font-size: 0.9rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #dee2e6;
        position: relative;
        overflow: hidden;
    }
    .badge-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    .badge-custom:hover::before {
        left: 100%;
    }
    .badge-custom i {
        margin-right: 0.5rem;
    }
</style>
<br>
<br>

    <div class="container my-5">
        <div class="row">
            <div class="col-sm-12">
        <h4 class="text-primary font-weight-bold mb-4">
                <?php
                    if(isset($_GET['cari']))
                    {
                        echo 'Hasil Pencarian: "' . htmlspecialchars($cari) . '"';
                    }else{
                        echo 'Daftar Semua Mobil';
                    }
                ?>
                </h4>

                <!-- Search and Filter Form -->
                <div class="row mt-3 mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <form method="GET" action="mobil.php" class="d-flex flex-column flex-md-row gap-3">
                                    <div class="flex-grow-1">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-primary text-white border-0">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="cari" class="form-control form-control-lg border-0"
                                                   placeholder="Cari mobil berdasarkan nama atau merk..."
                                                   value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 align-items-end">
                                        <div class="form-group mb-0">
                                            <label for="status" class="sr-only">Status</label>
                                            <select name="status" id="status" class="form-control form-control-lg">
                                                <option value="semua" <?php echo (isset($_GET['status']) && $_GET['status'] == 'semua') ? 'selected' : ''; ?>>Semua Status</option>
                                                <option value="tersedia" <?php echo (isset($_GET['status']) && $_GET['status'] == 'tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                                                <option value="tidak_tersedia" <?php echo (isset($_GET['status']) && $_GET['status'] == 'tidak_tersedia') ? 'selected' : ''; ?>>Tidak Tersedia</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label for="sort" class="sr-only">Urutkan Berdasarkan</label>
                                            <select name="sort" id="sort" class="form-control form-control-lg">
                                                <option value="id_mobil DESC" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'id_mobil DESC') ? 'selected' : ''; ?>>Default</option>
                                                <option value="harga_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'harga_asc') ? 'selected' : ''; ?>>Harga Terendah</option>
                                                <option value="harga_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'harga_desc') ? 'selected' : ''; ?>>Harga Tertinggi</option>
                                                <option value="tahun_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'tahun_desc') ? 'selected' : ''; ?>>Tahun Terbaru</option>
                                                <option value="tahun_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'tahun_asc') ? 'selected' : ''; ?>>Tahun Terlama</option>
                                                <option value="kursi_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'kursi_asc') ? 'selected' : ''; ?>>Kursi Terkecil</option>
                                                <option value="kursi_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'kursi_desc') ? 'selected' : ''; ?>>Kursi Terbesar</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary-custom btn-lg px-4">
                                            <i class="fas fa-search mr-2"></i>Cari
                                        </button>
                                        <?php if(isset($_GET['cari']) || isset($_GET['sort']) || isset($_GET['status'])): ?>
                                        <a href="mobil.php" class="btn btn-outline-custom btn-lg px-4">
                                            <i class="fas fa-times mr-2"></i>Reset
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Cars Grid -->
        <div class="row" id="cars-container">
            <?php
                foreach($query as $isi):
                    $sql_plat = "SELECT COUNT(*) as available_count FROM mobil_plat WHERE id_mobil = ? AND status_plat = 'Tersedia'";
                    $stmt_plat = mysqli_prepare($koneksi, $sql_plat);
                    mysqli_stmt_bind_param($stmt_plat, "i", $isi['id_mobil']);
                    mysqli_stmt_execute($stmt_plat);
                    $result_plat = mysqli_stmt_get_result($stmt_plat);
                    $plat_data = mysqli_fetch_assoc($result_plat);
                    $available_plates_count = $plat_data['available_count'];
                    if ($isi['status'] == 'Tidak Tersedia') {
                        $dynamic_status = 'Tidak Tersedia';
                    } else {
                        $dynamic_status = $available_plates_count > 0 ? 'Tersedia' : 'Tidak Tersedia';
                    }
                    mysqli_stmt_close($stmt_plat);
            ?>
            <div class="col-md-6 col-lg-4 mb-4 car-item"
                
                 data-price="<?= htmlspecialchars($isi['harga']); ?>"
               
                 data-status="<?= htmlspecialchars($isi['status']); ?>">
                <div class="car-card card h-100">
                    <img src="assets/image/<?= htmlspecialchars($isi['gambar']); ?>" class="card-img-top" alt="<?= htmlspecialchars($isi['nama_mobil'] ?? $isi['merk']); ?>">
                    <div class="card-body pt-3 pb-0">
                        <h5 class="card-title mb-2"><?= htmlspecialchars($isi['nama_mobil'] ?? $isi['merk']); ?></h5>
                        <p class="card-text mb-1">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>Tahun: <?= htmlspecialchars($isi['tahun_terbit'] ?? 'N/A'); ?> |
                                <i class="fas fa-users me-1"></i>Kursi: <?= htmlspecialchars($isi['jumlah_kursi'] ?? 'N/A'); ?>
                            </small>
                        </p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item <?php echo $dynamic_status == 'Tersedia' ? 'status-available' : 'status-not-available'; ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas <?php echo $dynamic_status == 'Tersedia' ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                                    <?= htmlspecialchars($dynamic_status); ?>
                                </div>
                                <?php if ($dynamic_status == 'Tersedia') { ?>
                                    <span class="badge badge-custom"><i class="fas fa-car"></i> Plat tersedia: <?= $available_plates_count; ?></span>
                                <?php } ?>
                            </div>
                        </li>
                        <li class="list-group-item price-item">
                            <i class="fas fa-money-bill-wave"></i> Rp. <?= number_format(htmlspecialchars($isi['harga'])); ?>/ hari
                        </li>
                    </ul>
                    <ul class="list-group list-group-flush">
                       
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

                    <?php if(!empty($isi['keunggulan'])): ?>
                    <div class="card-body border-top">
                       
                        <div class="collapse" id="keunggulanCollapse-<?= $isi['id_mobil'] ?>">
                            <ul class="list-group list-group-flush">
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
                    </div>
                    <?php endif; ?>
                    <div class="card-footer-custom">
                        <div class="d-flex justify-content-between">
                            <a href="detail.php?id=<?= htmlspecialchars($isi['id_mobil']); ?>" class="btn btn-outline-custom">
                                <i class="fas fa-info-circle"></i> Detail
                            </a>
                            <a href="booking.php?id=<?= htmlspecialchars($isi['id_mobil']); ?>" class="btn btn-secondary-custom <?php echo $dynamic_status != 'Tersedia' ? 'disabled' : ''; ?>">
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
            <h4 class="text-muted">Mobil tidak ditemukan.</h4>
            <p class="text-muted">Coba kata kunci lain atau lihat semua mobil yang tersedia.</p>
            <a href="mobil.php" class="btn btn-primary-custom">Lihat Semua Mobil</a>
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
            $('.collapse').on('show.bs.collapse', function () {
                var trigger = $('[data-target="#' + $(this).attr('id') + '"]');
                var icon = trigger.find('i');
                icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            }).on('hide.bs.collapse', function () {
                var trigger = $('[data-target="#' + $(this).attr('id') + '"]');
                var icon = trigger.find('i');
                icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
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
                        confirmButtonText: 'OK'
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
