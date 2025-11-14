<?php
    session_start();
    require 'koneksi/koneksi.php';
    include 'header.php';
    if(empty($_SESSION['USER']))
    {
        echo '<script>alert("Harap Login");window.location="index.php"</script>';
    }

    $id =  $_SESSION["USER"]["id_login"];
    $sql = "SELECT * FROM login WHERE id_login = ?";
    $row = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($row, "i", $id);
    mysqli_stmt_execute($row);
    $result_stmt = mysqli_stmt_get_result($row);
    $user = mysqli_fetch_object($result_stmt);
    mysqli_stmt_close($row);

    // Query untuk mendapatkan mobil yang sedang dipesan/disewa
    $today = date('Y-m-d');
    $sql_booking = "SELECT 
                        booking.*, 
                        mobil.merk, 
                        mobil.gambar, 
                        mobil.harga as harga_mobil,
                        mobil.status as status_mobil,
                        supir.nama_supir,
                        supir.harga_sewa as harga_supir,
                        DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) as tanggal_selesai
                    FROM booking 
                    JOIN mobil ON booking.id_mobil = mobil.id_mobil 
                    LEFT JOIN supir ON booking.id_supir = supir.id_supir 
                    WHERE booking.id_login = ? 
                    AND booking.konfirmasi_pembayaran != 'Belum Bayar'
                    AND booking.konfirmasi_pembayaran != 'Refund Diterima / Uang Dikembalikan'
                    AND (booking.status_pengembalian IS NULL OR booking.status_pengembalian = '' OR booking.status_pengembalian = 'Belum Dikembalikan')
                    AND mobil.status = 'Tersedia'
                    AND DATE_ADD(booking.tanggal, INTERVAL booking.lama_sewa DAY) > ?
                    ORDER BY booking.tanggal DESC";
    $stmt_booking = mysqli_prepare($koneksi, $sql_booking);
    mysqli_stmt_bind_param($stmt_booking, "is", $id, $today);
    mysqli_stmt_execute($stmt_booking);
    $result_stmt = mysqli_stmt_get_result($stmt_booking);
    $active_bookings = [];
    while ($row = mysqli_fetch_assoc($result_stmt)) {
        $active_bookings[] = $row;
    }
    mysqli_stmt_close($stmt_booking);
?>
<style>
    :root {
        --primary: #1A237E; 
        --primary-dark: #1A3CC9;
        --secondary: #FF6B35;
        --secondary-dark: #E05A2B;
        --light: #F8F9FA;
        --dark: #212529;
        --gray: #6C757D;
    }
    body {
        background-color: var(--light);
    }
    .profile-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 0 15px;
    }
    .profile-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .profile-header h2 {
        color: var(--primary);
        font-weight: 700;
    }
    .card-header {
        background: linear-gradient(120deg, var(--primary), var(--primary-dark));
        color: white;
        font-weight: 600;
    }
    .form-group label {
        font-weight: 600;
    }
    .password-toggle {
        position: relative;
    }
    .password-toggle-icon {
        position: absolute;
        right: 15px;
        top: 70%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--gray);
    }
    .btn-primary-custom {
        background: var(--secondary);
        border-color: var(--secondary);
        color: white;
    }
    .btn-primary-custom:hover {
        background: var(--secondary-dark);
        border-color: var(--secondary-dark);
    }
    .rental-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        background: white;
        border: none;
    }
    .rental-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    .rental-card .card-img-top {
        height: 200px;
        object-fit: cover;
    }
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
    }
    .status-badge.active {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    .status-badge.processing {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        color: white;
    }
    .status-badge.completed {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    .status-badge.disewa {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }
    .status-badge.habis {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        color: white;
    }
    .status-badge.terlambat {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    .status-badge.dibatalkan {
        background: linear-gradient(135deg, #343a40, #212529);
        color: white;
    }
    .rental-info-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    .rental-info-item:last-child {
        border-bottom: none;
    }
    .rental-info-item i {
        width: 20px;
        margin-right: 0.75rem;
        color: var(--primary);
    }
    .countdown-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
    }
    .countdown-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        text-align: center;
    }
    .countdown-display {
        display: flex;
        justify-content: space-around;
        text-align: center;
    }
    .countdown-item {
        flex: 1;
    }
    .countdown-value {
        font-size: 2rem;
        font-weight: 700;
        display: block;
        line-height: 1.2;
    }
    .countdown-label {
        font-size: 0.85rem;
        opacity: 0.9;
        text-transform: uppercase;
        margin-top: 0.5rem;
    }
    .countdown-separator {
        font-size: 1.5rem;
        font-weight: 700;
        align-self: center;
    }
    .modal-detail-item {
        display: flex;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    .modal-detail-item:last-child {
        border-bottom: none;
    }
    .modal-detail-item i {
        width: 24px;
        margin-right: 1rem;
        color: var(--primary);
        margin-top: 0.25rem;
    }
    .modal-detail-item .detail-content {
        flex: 1;
    }
    .modal-detail-item .detail-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }
    .modal-detail-item .detail-value {
        color: var(--gray);
    }
    .modal-img-container {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .modal-img-container img {
        max-width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .countdown-expired {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .countdown-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    }
</style>
<div class="profile-container">
    <div class="profile-header">
        <h2>Profil Saya</h2>
        <p>Kelola informasi profil Anda untuk mengontrol, melindungi, dan mengamankan akun</p>
    </div>
    <div class="row">
        <!-- Informasi Akun -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa fa-user-circle mr-2"></i>Informasi Akun
                </div>
                <div class="card-body">
                    <form action="koneksi/proses.php?id=update_profil" method="post">
                        <div class="form-group">
                            <label for="nama_pengguna">Nama Pengguna</label>
                            <input type="text" class="form-control" value="<?= $user->nama_pengguna;?>" name="nama_pengguna" id="nama_pengguna" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" value="<?= $user->username;?>" name="username" id="username" required>
                        </div>
                        <div class="form-group">
                            <label for="no_hp">Nomor HP</label>
                            <input type="text" class="form-control" value="<?= $user->no_hp;?>" name="no_hp" id="no_hp" required>
                        </div>
                        <button type="submit" class="btn btn-primary-custom btn-block">
                            <i class="fa fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ubah Password -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa fa-lock mr-2"></i>Ubah Password
                </div>
                <div class="card-body">
                    <form action="koneksi/proses.php?id=ubah_password" method="post">
                        <div class="form-group password-toggle">
                            <label for="current_password">Password Saat Ini</label>
                            <input type="password" class="form-control" name="current_password" id="current_password" required>
                            <span class="password-toggle-icon" onclick="togglePassword('current_password')">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <div class="form-group password-toggle">
                            <label for="new_password">Password Baru</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" required>
                            <span class="password-toggle-icon" onclick="togglePassword('new_password')">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <div class="form-group password-toggle">
                            <label for="confirm_password">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                            <span class="password-toggle-icon" onclick="togglePassword('confirm_password')">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <button type="submit" class="btn btn-primary-custom btn-block">
                            <i class="fa fa-sync-alt mr-2"></i>Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobil yang Sedang Dipesan/Disewa -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-car mr-2"></i>Mobil yang Sedang Dipesan/Disewa
                </div>
                <div class="card-body">
                    <?php if(count($active_bookings) > 0): ?>
                        <div class="row">
                            <?php foreach($active_bookings as $booking): 
                                $tanggal_selesai = new DateTime($booking['tanggal_selesai']);
                                $today_obj = new DateTime();
                                $is_active = $tanggal_selesai >= $today_obj;
                                
                                $status_mobil = $booking['status_mobil'];
                                $tanggal_akhir = strtotime($booking['tanggal_selesai']);
                                $sekarang = strtotime(date('Y-m-d'));
                                $status_pengembalian = !empty($booking['status_pengembalian']) ? $booking['status_pengembalian'] : 'Belum Dikembalikan';
                                $konfirmasi_pembayaran = $booking['konfirmasi_pembayaran'];

                                $status_class = 'processing';
                                $status_text = 'Sedang Diproses';
                                $status_icon = 'fa-hourglass-half';

                                if ($konfirmasi_pembayaran == 'Refund Diterima / Uang Dikembalikan') {
                                    $status_class = 'dibatalkan';
                                    $status_text = 'Dibatalkan';
                                    $status_icon = 'fa-ban';
                                } elseif ($status_pengembalian == 'Dikembalikan') {
                                    $status_class = 'completed';
                                    $status_text = 'Selesai';
                                    $status_icon = 'fa-stop-circle';
                                } elseif ($status_mobil == 'Tersedia') {
                                    if ($tanggal_akhir <= $sekarang) {
                                        $status_class = 'completed';
                                        $status_text = 'Selesai';
                                        $status_icon = 'fa-stop-circle';
                                    } else {
                                        $status_class = 'disewa';
                                        $status_text = 'Disewa';
                                        $status_icon = 'fa-calendar-check';
                                    }
                                } elseif ($status_mobil == 'Tidak Tersedia') {
                                    if ($tanggal_akhir > $sekarang) {
                                        $status_class = 'active';
                                        $status_text = 'Aktif';
                                        $status_icon = 'fa-play-circle';
                                    } elseif ($tanggal_akhir == $sekarang) {
                                        $status_class = 'habis';
                                        $status_text = 'Masa Sewa Habis';
                                        $status_icon = 'fa-clock';
                                    } else {
                                        $status_class = 'terlambat';
                                        $status_text = 'Terlambat Pengembalian';
                                        $status_icon = 'fa-exclamation-triangle';
                                    }
                                }
                            ?>
                                <div class="col-lg-6 col-md-12 mb-4">
                                    <div class="rental-card card h-100">
                                        <img src="assets/image/<?= htmlspecialchars($booking['gambar']); ?>" class="card-img-top" alt="<?= htmlspecialchars($booking['merk']); ?>">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title mb-0"><?= htmlspecialchars($booking['merk']); ?></h5>
                                                <span class="status-badge <?= $status_class; ?>">
                                                    <i class="fas <?= $status_icon; ?>"></i>
                                                    <?= $status_text; ?>
                                                </span>
                                            </div>
                                            
                                            <div class="rental-info-item">
                                                <i class="fas fa-barcode"></i>
                                                <div>
                                                    <strong>Kode Booking:</strong><br>
                                                    <span class="text-muted"><?= htmlspecialchars($booking['kode_booking']); ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="rental-info-item">
                                                <i class="fas fa-calendar-alt"></i>
                                                <div>
                                                    <strong>Tanggal Sewa:</strong><br>
                                                    <span class="text-muted"><?= date('d/m/Y', strtotime($booking['tanggal'])); ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="rental-info-item">
                                                <i class="fas fa-calendar-check"></i>
                                                <div>
                                                    <strong>Tanggal Selesai:</strong><br>
                                                    <span class="text-muted"><?= date('d/m/Y', strtotime($booking['tanggal_selesai'])); ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="rental-info-item">
                                                <i class="fas fa-clock"></i>
                                                <div>
                                                    <strong>Lama Sewa:</strong><br>
                                                    <span class="text-muted"><?= htmlspecialchars($booking['lama_sewa']); ?> hari</span>
                                                </div>
                                            </div>
                                            
                                            <?php if(!empty($booking['nama_supir'])): ?>
                                            <div class="rental-info-item">
                                                <i class="fas fa-user-tie"></i>
                                                <div>
                                                    <strong>Supir:</strong><br>
                                                    <span class="text-muted"><?= htmlspecialchars($booking['nama_supir']); ?></span>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <div class="rental-info-item">
                                                <i class="fas fa-money-bill-wave"></i>
                                                <div>
                                                    <strong>Total Harga:</strong><br>
                                                    <span class="text-primary font-weight-bold">Rp <?= number_format($booking['total_harga']); ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3">
                                                <button type="button" 
                                                        class="btn btn-primary-custom btn-block btn-view-detail" 
                                                        data-toggle="modal" 
                                                        data-target="#bookingDetailModal"
                                                        data-booking='<?= base64_encode(json_encode($booking)); ?>'>
                                                    <i class="fas fa-info-circle mr-2"></i>Lihat Detail
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada mobil yang sedang dipesan/disewa</h5>
                            <p class="text-muted">Mulai sewa mobil impian Anda sekarang!</p>
                            <a href="blog.php" class="btn btn-primary-custom mt-3">
                                <i class="fas fa-car mr-2"></i>Lihat Pilihan Mobil
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Booking -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1" role="dialog" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(120deg, var(--primary), var(--primary-dark)); color: white;">
                <h5 class="modal-title" id="bookingDetailModalLabel">
                    <i class="fas fa-car mr-2"></i>Detail Pemesanan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Countdown Timer -->
                <div class="countdown-container" id="countdownContainer">
                    <div class="countdown-title">
                        <i class="fas fa-hourglass-half mr-2"></i>Waktu Tersisa Hingga Pengembalian
                    </div>
                    <div class="countdown-display" id="countdownDisplay">
                        <div class="countdown-item">
                            <span class="countdown-value" id="countdownDays">00</span>
                            <span class="countdown-label">Hari</span>
                        </div>
                        <span class="countdown-separator">:</span>
                        <div class="countdown-item">
                            <span class="countdown-value" id="countdownHours">00</span>
                            <span class="countdown-label">Jam</span>
                        </div>
                        <span class="countdown-separator">:</span>
                        <div class="countdown-item">
                            <span class="countdown-value" id="countdownMinutes">00</span>
                            <span class="countdown-label">Menit</span>
                        </div>
                        <span class="countdown-separator">:</span>
                        <div class="countdown-item">
                            <span class="countdown-value" id="countdownSeconds">00</span>
                            <span class="countdown-label">Detik</span>
                        </div>
                    </div>
                </div>

                <!-- Gambar Mobil -->
                <div class="modal-img-container">
                    <img id="modalCarImage" src="" alt="Car Image" class="img-fluid">
                </div>

                <!-- Detail Informasi -->
                <div class="modal-detail-item">
                    <i class="fas fa-car"></i>
                    <div class="detail-content">
                        <div class="detail-label">Merk Mobil</div>
                        <div class="detail-value" id="modalMerk">-</div>
                    </div>
                </div>

                <div class="modal-detail-item">
                    <i class="fas fa-barcode"></i>
                    <div class="detail-content">
                        <div class="detail-label">Kode Booking</div>
                        <div class="detail-value" id="modalKodeBooking">-</div>
                    </div>
                </div>

                <div class="modal-detail-item">
                    <i class="fas fa-calendar-alt"></i>
                    <div class="detail-content">
                        <div class="detail-label">Tanggal Sewa</div>
                        <div class="detail-value" id="modalTanggalSewa">-</div>
                    </div>
                </div>

                <div class="modal-detail-item">
                    <i class="fas fa-calendar-check"></i>
                    <div class="detail-content">
                        <div class="detail-label">Tanggal Pengembalian</div>
                        <div class="detail-value" id="modalTanggalPengembalian">-</div>
                    </div>
                </div>

                <div class="modal-detail-item">
                    <i class="fas fa-clock"></i>
                    <div class="detail-content">
                        <div class="detail-label">Lama Sewa</div>
                        <div class="detail-value" id="modalLamaSewa">-</div>
                    </div>
                </div>

                <div class="modal-detail-item" id="modalSupirContainer" style="display: none;">
                    <i class="fas fa-user-tie"></i>
                    <div class="detail-content">
                        <div class="detail-label">Supir</div>
                        <div class="detail-value" id="modalSupir">-</div>
                    </div>
                </div>

                <div class="modal-detail-item">
                    <i class="fas fa-money-bill-wave"></i>
                    <div class="detail-content">
                        <div class="detail-label">Harga Mobil per Hari</div>
                        <div class="detail-value" id="modalHargaMobil">-</div>
                    </div>
                </div>

                <div class="modal-detail-item" id="modalHargaSupirContainer" style="display: none;">
                    <i class="fas fa-money-bill"></i>
                    <div class="detail-content">
                        <div class="detail-label">Harga Supir per Hari</div>
                        <div class="detail-value" id="modalHargaSupir">-</div>
                    </div>
                </div>

                <div class="modal-detail-item">
                    <i class="fas fa-money-bill-wave"></i>
                    <div class="detail-content">
                        <div class="detail-label">Total Harga</div>
                        <div class="detail-value" style="font-size: 1.2rem; font-weight: 700; color: var(--primary);" id="modalTotalHarga">-</div>
                    </div>
                </div>

                <div class="modal-detail-item">
                    <i class="fas fa-info-circle"></i>
                    <div class="detail-content">
                        <div class="detail-label">Status Pembayaran</div>
                        <div class="detail-value">
                            <span class="status-badge" id="modalStatusBadge">-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a href="#" id="modalBayarLink" class="btn btn-primary-custom" style="display: none;">
                    <i class="fas fa-credit-card mr-2"></i>Lihat Pembayaran
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    let countdownInterval = null;
    let currentReturnDate = null;

    function togglePassword(inputId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = passwordInput.nextElementSibling.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function openBookingDetail(booking) {
        // Clear previous countdown
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }

        // Populate modal with booking data
        document.getElementById('modalCarImage').src = 'assets/image/' + booking.gambar;
        document.getElementById('modalMerk').textContent = booking.merk;
        document.getElementById('modalKodeBooking').textContent = booking.kode_booking;
        document.getElementById('modalTanggalSewa').textContent = formatDate(booking.tanggal);
        document.getElementById('modalTanggalPengembalian').textContent = formatDate(booking.tanggal_selesai);
        document.getElementById('modalLamaSewa').textContent = booking.lama_sewa + ' hari';
        document.getElementById('modalHargaMobil').textContent = 'Rp ' + formatNumber(booking.harga_mobil) + ' /hari';
        document.getElementById('modalTotalHarga').textContent = 'Rp ' + formatNumber(booking.total_harga);

        // Supir info
        if (booking.nama_supir && booking.nama_supir.trim() !== '') {
            document.getElementById('modalSupirContainer').style.display = 'flex';
            document.getElementById('modalSupir').textContent = booking.nama_supir;
            if (booking.harga_supir) {
                document.getElementById('modalHargaSupirContainer').style.display = 'flex';
                document.getElementById('modalHargaSupir').textContent = 'Rp ' + formatNumber(booking.harga_supir) + ' /hari';
            }
        } else {
            document.getElementById('modalSupirContainer').style.display = 'none';
            document.getElementById('modalHargaSupirContainer').style.display = 'none';
        }

        // Status badge
        const statusBadge = document.getElementById('modalStatusBadge');
        let statusClass = 'processing';
        let statusText = 'Sedang Diproses';
        let statusIcon = 'fa-hourglass-half';

        const statusMobil = booking.status_mobil;
        const tanggalAkhir = new Date(booking.tanggal_selesai);
        const today = new Date();
        const sameDay = tanggalAkhir.getFullYear() === today.getFullYear() && tanggalAkhir.getMonth() === today.getMonth() && tanggalAkhir.getDate() === today.getDate();
        const statusPengembalian = booking.status_pengembalian ? booking.status_pengembalian : 'Belum Dikembalikan';
        const konfirmasiPembayaran = booking.konfirmasi_pembayaran;

        if (konfirmasiPembayaran === 'Refund Diterima / Uang Dikembalikan') {
            statusClass = 'dibatalkan';
            statusText = 'Dibatalkan';
            statusIcon = 'fa-ban';
        } else if (statusPengembalian === 'Dikembalikan') {
            statusClass = 'completed';
            statusText = 'Selesai';
            statusIcon = 'fa-stop-circle';
        } else if (statusMobil === 'Tersedia') {
            if (tanggalAkhir <= today) {
                statusClass = 'completed';
                statusText = 'Selesai';
                statusIcon = 'fa-stop-circle';
            } else {
                statusClass = 'disewa';
                statusText = 'Disewa';
                statusIcon = 'fa-calendar-check';
            }
        } else if (statusMobil === 'Tidak Tersedia') {
            if (tanggalAkhir > today) {
                statusClass = 'active';
                statusText = 'Aktif';
                statusIcon = 'fa-play-circle';
            } else if (sameDay) {
                statusClass = 'habis';
                statusText = 'Masa Sewa Habis';
                statusIcon = 'fa-clock';
            } else {
                statusClass = 'terlambat';
                statusText = 'Terlambat Pengembalian';
                statusIcon = 'fa-exclamation-triangle';
            }
        }

        statusBadge.className = 'status-badge ' + statusClass;
        statusBadge.innerHTML = '<i class="fas ' + statusIcon + '"></i> ' + statusText;

        // Bayar link
        const bayarLink = document.getElementById('modalBayarLink');
        if (booking.konfirmasi_pembayaran != 'Pembayaran Diterima' && booking.konfirmasi_pembayaran != 'Sudah Dibayar') {
            bayarLink.href = 'bayar.php?id=' + booking.kode_booking;
            bayarLink.style.display = 'inline-block';
        } else {
            bayarLink.style.display = 'none';
        }

        // Set countdown
        currentReturnDate = new Date(booking.tanggal_selesai + ' 23:59:59').getTime();
        startCountdown();
    }

    function startCountdown() {
        if (!currentReturnDate) return;

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = currentReturnDate - now;

            if (distance < 0) {
                // Countdown expired
                document.getElementById('countdownDays').textContent = '00';
                document.getElementById('countdownHours').textContent = '00';
                document.getElementById('countdownMinutes').textContent = '00';
                document.getElementById('countdownSeconds').textContent = '00';
                
                const container = document.getElementById('countdownContainer');
                container.classList.add('countdown-expired');
                container.querySelector('.countdown-title').innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Waktu Pengembalian Telah Berlalu';
                
                clearInterval(countdownInterval);
                return;
            }

            // Calculate time units
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Update display
            document.getElementById('countdownDays').textContent = String(days).padStart(2, '0');
            document.getElementById('countdownHours').textContent = String(hours).padStart(2, '0');
            document.getElementById('countdownMinutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('countdownSeconds').textContent = String(seconds).padStart(2, '0');

            // Change color if less than 24 hours remaining
            const container = document.getElementById('countdownContainer');
            if (days === 0 && hours < 24) {
                container.classList.remove('countdown-expired');
                container.classList.add('countdown-warning');
            } else {
                container.classList.remove('countdown-expired', 'countdown-warning');
            }
        }

        // Update immediately
        updateCountdown();

        // Update every second
        countdownInterval = setInterval(updateCountdown, 1000);
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return day + '/' + month + '/' + year;
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Clear countdown when modal is closed
    $('#bookingDetailModal').on('hidden.bs.modal', function () {
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
    });

    // Handle button click to open modal with booking data
    $(document).on('click', '.btn-view-detail', function() {
        const bookingDataEncoded = $(this).attr('data-booking');
        if (bookingDataEncoded) {
            try {
                const bookingData = JSON.parse(atob(bookingDataEncoded));
                openBookingDetail(bookingData);
            } catch (e) {
                console.error('Error parsing booking data:', e);
            }
        }
    });
</script>

<?php include 'footer.php';?>

<?php if(isset($_GET['status'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let status = '<?php echo $_GET['status']; ?>';
        if (status === 'profilesuccess') {
            Swal.fire('Berhasil!', 'Informasi profil Anda telah diperbarui.', 'success');
        } else if (status === 'passwordchanged') {
            Swal.fire('Berhasil!', 'Password Anda telah diubah.', 'success');
        } else if (status === 'passworderror') {
            Swal.fire('Gagal!', 'Password saat ini salah.', 'error');
        } else if (status === 'passwordmismatch') {
            Swal.fire('Gagal!', 'Konfirmasi password baru tidak cocok.', 'error');
        }
    });
</script>
<?php endif; ?>
