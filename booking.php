<?php
    session_start();
    require 'koneksi/koneksi.php';
    include 'header.php';
    include 'progress_bar.php';
    
    if(empty($_SESSION['USER']))
    {
        echo '<script>alert("Harap login !");window.location="index.php"</script>';
    }
    
    // CEK APAKAH USER SUDAH MELENGKAPI PROFIL
    $id_user = $_SESSION['USER']['id_login'];
    // tambahkan kolom ktp pada query profil
    $check_profil = mysqli_query($koneksi, "SELECT nama_pengguna, no_hp, email, provinsi, kota_kabupaten, jenis_kelamin, ktp FROM login WHERE id_login = '$id_user'");
    $profil_data = mysqli_fetch_assoc($check_profil);
    
    // Cek field-field penting yang harus diisi
    $profil_lengkap = true;
    $field_kosong = array();
    
    if(empty($profil_data['nama_pengguna']) || $profil_data['nama_pengguna'] == '') {
        $profil_lengkap = false;
        $field_kosong[] = 'nama lengkap';
    }
    if(empty($profil_data['no_hp']) || $profil_data['no_hp'] == '') {
        $profil_lengkap = false;
        $field_kosong[] = 'nomor HP';
    }
    if(empty($profil_data['email']) || $profil_data['email'] == '') {
        $profil_lengkap = false;
        $field_kosong[] = 'email';
    }
    if(empty($profil_data['provinsi']) || $profil_data['provinsi'] == '') {
        $profil_lengkap = false;
        $field_kosong[] = 'provinsi';
    }
    if(empty($profil_data['kota_kabupaten']) || $profil_data['kota_kabupaten'] == '') {
        $profil_lengkap = false;
        $field_kosong[] = 'kota/kabupaten';
    }
    if(empty($profil_data['jenis_kelamin']) || $profil_data['jenis_kelamin'] == '') {
        $profil_lengkap = false;
        $field_kosong[] = 'jenis kelamin';
    }

    
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if (empty($id)) {
        echo '<script>alert("ID mobil tidak ditemukan!");window.location="blog.php"</script>';
        exit();
    }
    
    // Validate that id is numeric
    if (!is_numeric($id)) {
        echo '<script>alert("ID mobil tidak valid!");window.location="blog.php"</script>';
        exit();
    }
    
    $result_mobil = mysqli_query($koneksi, "SELECT * FROM mobil WHERE id_mobil = '$id'");
    $isi = mysqli_fetch_assoc($result_mobil);
    if (!$isi) {
        echo '<script>alert("Mobil tidak ditemukan!");window.location="blog.php"</script>';
        exit();
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Mobil - <?= htmlspecialchars($isi['merk']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary: #1A237E;
            --primary-light: #534bae;
            --primary-dark: #000051;
            --secondary: #FF6B35;
            --secondary-light: #ff9b68;
            --secondary-dark: #c53c03;
            --light: #F8F9FA;
            --dark: #212529;
            --gray: #6C757D;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .booking-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 2rem;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 1.5rem;
            border-bottom: none;
        }
        
        .card-header h3 {
            margin: 0;
            font-weight: 700;
        }
        
        .car-image {
            height: 300px;
            object-fit: cover;
            width: 100%;
            border-radius: 12px 12px 0 0;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .feature-list li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        
        .feature-list li:last-child {
            border-bottom: none;
        }
        
        .feature-list li i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .price-tag {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 0.25rem rgba(26, 35, 126, 0.15);
        }
        
        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 35, 126, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-light) 100%);
            border: none;
            color: white;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--secondary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
            color: white;
        }
        
        .btn-outline-secondary {
            border: 2px solid var(--secondary);
            color: var(--secondary);
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--secondary);
            color: white;
        }
        
        .total-price-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
        }
        
        .total-price {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary);
        }
        
        .section-title {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-light);
        }
        
        .info-box {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .driver-selection {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1rem 0;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #e9ecef;
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--gray);
        }
        
        .step.active .step-number {
            background-color: var(--primary);
            color: white;
        }
        
        .step.completed .step-number {
            background-color: var(--success);
            color: white;
        }
        
        .step-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray);
        }
        
        .step.active .step-label {
            color: var(--primary);
        }
        
        @media (max-width: 768px) {
            .step-indicator {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .step-indicator::before {
                display: none;
            }
            
            .step {
                flex-direction: row;
                margin-bottom: 1rem;
            }
            
            .step-number {
                margin-right: 1rem;
                margin-bottom: 0;
            }
        }

        .form-control[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
            color: #495057;
        }
        
        .form-control[readonly]:focus {
            border-color: #e9ecef;
            box-shadow: none;
        }

        /* Professional Sweet Alert Custom Style */
        .swal-professional {
            background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
            border-radius: 24px;
            border: none;
            box-shadow: 0 25px 80px rgba(26, 35, 126, 0.3);
            backdrop-filter: blur(10px);
            overflow: hidden;
            position: relative;
        }

        .swal-professional::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #FF6B35, #FF8E53, #FF6B35);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        .swal-professional .swal-title {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .swal-professional .swal-text {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1.05rem;
            line-height: 1.7;
            text-align: center;
            font-weight: 400;
        }

        .swal-professional .swal-icon {
            width: 90px;
            height: 90px;
            margin: 1.5rem auto 1rem;
            border: 3px solid rgba(255, 215, 0, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            animation: gentlePulse 2.5s ease-in-out infinite;
        }

        .swal-professional .swal-icon--warning {
            border-color: rgba(255, 215, 0, 0.5);
        }

        .swal-professional .swal-icon--warning .swal-icon--warning__body,
        .swal-professional .swal-icon--warning .swal-icon--warning__dot {
            background-color: #FFD700;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
        }

        .swal-professional .swal-button-container {
            margin-top: 2rem;
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .swal-professional .swal-button {
            background: linear-gradient(135deg, #FF6B35 0%, #FF8E53 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 32px;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
            position: relative;
            overflow: hidden;
        }

        .swal-professional .swal-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .swal-professional .swal-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 107, 53, 0.4);
            background: linear-gradient(135deg, #E55A2B 0%, #FF7B42 100%);
        }

        .swal-professional .swal-button:hover::before {
            left: 100%;
        }

        .swal-professional .swal-button:active {
            transform: translateY(-1px);
        }

        .swal-professional .swal-button--cancel {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: rgba(255, 255, 255, 0.8);
            box-shadow: none;
        }

        .swal-professional .swal-button--cancel:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.1);
        }

        /* Feature Cards */
        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem 0;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #FFD700, #FFE55C);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.25rem;
            color: #1a237e;
        }

        /* Animations */
        @keyframes gentlePulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(255, 215, 0, 0);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Progress indicator */
        .profile-progress {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            height: 6px;
            margin: 1.5rem 0;
            overflow: hidden;
        }

        .profile-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #FF6B35, #FF8E53);
            border-radius: 10px;
            width: 30%;
            animation: progressFill 2s ease-in-out;
        }

        @keyframes progressFill {
            from {
                width: 0%;
            }
            to {
                width: 30%;
            }
        }

        /* Floating Animation */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(0, -10px); }
            100% { transform: translate(0, 0px); }
        }

        /* Profile Required Badge */
        .profile-required-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #FF6B35, #FF8E53);
            color: white;
            padding: 10px 15px;
            border-radius: 25px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
            z-index: 1000;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <!-- Profile Required Badge -->
    <?php if(!$profil_lengkap): ?>
    <div class="profile-required-badge floating">
        <i class="fas fa-exclamation-circle me-2"></i>
        Profil Perlu Dilengkapi
    </div>
    <?php endif; ?>
    
    <div class="booking-container py-4">
        <div class="row">
            <!-- Mobil Information Card -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <img src="assets/image/<?php echo $isi['gambar'];?>" class="car-image" alt="<?php echo $isi['nama_mobil'] ?? $isi['merk'];?>">
                    <div class="card-body">
                        <h4 class="card-title text-primary"><?php echo $isi['nama_mobil'] ?? $isi['merk'];?></h4>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>Tahun Terbit: <?php echo htmlspecialchars($isi['tahun_terbit'] ?? 'Tidak tersedia'); ?><br>
                                <i class="fas fa-users me-1"></i>Jumlah Kursi: <?php echo htmlspecialchars($isi['jumlah_kursi'] ?? 'Tidak tersedia'); ?>
                            </small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <?php if($isi['status'] == 'Tersedia'){?>
                                <span class="status-badge bg-success text-white">
                                    <i class="fas fa-check-circle me-1"></i> Tersedia
                                </span>
                            <?php }else{?>
                                <span class="status-badge bg-danger text-white">
                                    <i class="fas fa-times-circle me-1"></i> Tidak Tersedia
                                </span>
                            <?php }?>
                            
                            <div class="price-tag">
                                Rp <?php echo number_format($isi['harga']);?>/hari
                            </div>
                        </div>
                        
                        <?php if(!empty($isi['keunggulan'])){ ?>
                        <h5 class="section-title mt-4">Keunggulan Paket</h5>
                        <ul class="feature-list">
                            <?php
                            $keunggulan = explode('||', $isi['keunggulan']);
                            foreach ($keunggulan as $item) {
                                if (!empty($item)) {
                            ?>
                                    <li>
                                        <i class="fas fa-check text-success"></i> <?= htmlspecialchars($item) ?>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <!-- Booking Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-calendar-check me-2"></i> Form Pemesanan</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="koneksi/proses.php?id=booking" id="bookingForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="nama" name="nama" 
                                               value="<?php echo htmlspecialchars($profil_data['nama_pengguna'] ?? $_SESSION['USER']['nama_pengguna'] ?? ''); ?>" 
                                               placeholder="Masukkan nama lengkap" required readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ktp">No. KTP / NIK</label>
                                        <input type="text" class="form-control" id="ktp" name="ktp" 
                                               value="<?php echo htmlspecialchars($profil_data['ktp'] ?? $_SESSION['USER']['ktp'] ?? ''); ?>" 
                                               placeholder="Masukkan No. KTP / NIK" required readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-semibold">Alamat Lengkap</label>
                                <input type="text" name="alamat" id="alamat" required class="form-control" placeholder="Masukkan Alamat Lengkap">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="no_tlp" class="form-label fw-semibold">No. Telepon</label>
                                        <input type="text" name="no_tlp" id="no_tlp" required class="form-control" placeholder="Masukkan No. Telepon">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal" class="form-label fw-semibold">Tanggal Sewa</label>
                                        <input type="date" name="tanggal" id="tanggal" required class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="lama_sewa" class="form-label fw-semibold">Lama Sewa (hari)</label>
                                <input type="number" name="lama_sewa" id="lama_sewa" required class="form-control" placeholder="Masukkan Lama Sewa" min="1" value="1">
                            </div>
                            
                            <!-- License Plate Selection -->
                            <div class="driver-selection">
                                <h5 class="fw-semibold mb-3">Pilihan Nomor Plat</h5>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nomor Plat Tersedia</label>
                                    <div class="row g-2" id="plat-options">
                                        <?php
                                        $sql_plat = "SELECT id_plat, no_plat, status_plat FROM mobil_plat WHERE id_mobil = ? ORDER BY no_plat";
                                        $stmt_plat = mysqli_prepare($koneksi, $sql_plat);
                                        mysqli_stmt_bind_param($stmt_plat, "i", $isi['id_mobil']);
                                        mysqli_stmt_execute($stmt_plat);
                                        $result_plat = mysqli_stmt_get_result($stmt_plat);

                                        $available_plates = [];
                                        while ($plat = mysqli_fetch_assoc($result_plat)) {
                                            $available_plates[] = $plat;
                                        }
                                        mysqli_stmt_close($stmt_plat);

                                        if (empty($available_plates)) {
                                            echo '<div class="col-12"><div class="alert alert-warning">Tidak ada nomor plat tersedia untuk mobil ini.</div></div>';
                                        } else {
                                            foreach ($available_plates as $plat) {
                                                $status_class = $plat['status_plat'] == 'Tersedia' ? 'success' : 'danger';
                                                $status_text = $plat['status_plat'] == 'Tersedia' ? 'Tersedia' : 'Dipesan';
                                                $disabled = $plat['status_plat'] != 'Tersedia' ? 'disabled' : '';
                                                echo '<div class="col-md-6 col-lg-4">';
                                                echo '<div class="form-check">';
                                                echo '<input class="form-check-input" type="radio" name="id_plat_mobil" value="' . $plat['id_plat'] . '" id="plat_' . $plat['id_plat'] . '" ' . $disabled . '>';
                                                echo '<label class="form-check-label" for="plat_' . $plat['id_plat'] . '">';
                                                echo '<span class="badge bg-' . $status_class . ' me-2">' . $status_text . '</span>';
                                                echo htmlspecialchars($plat['no_plat']);
                                                echo '</label>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                    <div>
                                        <p class="mb-2">Pilih nomor plat secara manual atau biarkan sistem memilih otomatis</p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="id_plat_mobil" value="otomatis" id="plat_otomatis" checked>
                                            <label class="form-check-label fw-semibold" for="plat_otomatis">
                                                <i class="fas fa-magic me-2"></i>Pilih Otomatis (Sistem akan memilih plat yang tersedia)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Driver Selection -->
                            <div class="driver-selection">
                                <h5 class="fw-semibold mb-3">Pilihan Supir (Opsional)</h5>
                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                    <div>
                                        <p class="mb-2">Tambah supir untuk perjalanan Anda</p>
                                        <div id="supir_info" class="alert alert-success mt-2" style="display:none;">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Supir dipilih:</strong>
                                            <span id="nama_supir"></span> -
                                            Rp<span id="harga_supir_display"></span>/hari
                                        </div>
                                    </div>
                                    <div class="mt-2 mt-md-0">
                                        <a href="supir.php?id=<?= $isi['id_mobil']; ?>" class="btn btn-primary me-2" id="pilih_supir_link">
                                            <i class="fas fa-user me-1"></i> Pilih Supir
                                        </a>
                                        <button type="button" id="hapus_supir" class="btn btn-outline-secondary" style="display:none;">
                                            <i class="fas fa-times me-1"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Total Price -->
                            <div class="total-price-box">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="fw-semibold mb-1">Total Biaya</h5>
                                        <small class="text-muted">Termasuk biaya mobil <?php if($isi['status'] == 'Tersedia'){ echo 'dan supir (jika dipilih)'; } ?></small>
                                    </div>
                                    <div class="text-end">
                                        <div class="total-price" id="total_harga_display">Rp<?= number_format($isi['harga']); ?></div>
                                        <small class="text-muted" id="detail_harga">Mobil: Rp<?= number_format($isi['harga']); ?>/hari</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden Fields -->
                            <input type="hidden" value="<?php echo $_SESSION['USER']['id_login'];?>" name="id_login">
                            <input type="hidden" value="<?php echo $isi['id_mobil'];?>" name="id_mobil">
                            <input type="hidden" value="<?php echo $isi['harga'];?>" name="harga_mobil">
                            <input type="hidden" id="harga_supir" name="harga_supir" value="0">
                            <input type="hidden" id="id_supir" name="id_supir" value="">
                            <input type="hidden" value="" name="total_harga" id="total_harga">
                            
                            <!-- Submit Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="blog.php" class="btn btn-outline-secondary me-md-2">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <?php if($isi['status'] == 'Tersedia' && $profil_lengkap){?>
                                    <button type="submit" class="btn btn-secondary px-4">
                                        <i class="fas fa-calendar-check me-1"></i> Lanjutkan Booking
                                    </button>
                                <?php }elseif($isi['status'] == 'Tersedia' && !$profil_lengkap){?>
                                    <button type="button" class="btn btn-warning px-4" onclick="showProfileRequiredModal()" id="bookingBtnIncomplete">
                                        <i class="fas fa-user-edit me-1"></i> Lanjutkan Booking
                                    </button>
                                <?php }else{?>
                                    <button type="button" class="btn btn-danger px-4" disabled>
                                        <i class="fas fa-times me-1"></i> Tidak Tersedia
                                    </button>
                                <?php }?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal').min = today;

            // Function to save form data to sessionStorage
            function saveFormData() {
                const formData = {
                    ktp: document.getElementById('ktp').value,
                    nama: document.getElementById('nama').value,
                    alamat: document.getElementById('alamat').value,
                    no_tlp: document.getElementById('no_tlp').value,
                    tanggal: document.getElementById('tanggal').value,
                    lama_sewa: document.getElementById('lama_sewa').value,
                    harga_supir: document.getElementById('harga_supir').value,
                    id_supir: document.getElementById('id_supir').value,
                    nama_supir: document.getElementById('nama_supir').textContent,
                    harga_supir_display: document.getElementById('harga_supir_display').textContent
                };
                sessionStorage.setItem('bookingFormData', JSON.stringify(formData));
            }

            // Function to restore form data from sessionStorage
            function restoreFormData() {
                const savedData = sessionStorage.getItem('bookingFormData');
                if (savedData) {
                    const formData = JSON.parse(savedData);
                    document.getElementById('ktp').value = formData.ktp || '';
                    document.getElementById('nama').value = formData.nama || '';
                    document.getElementById('alamat').value = formData.alamat || '';
                    document.getElementById('no_tlp').value = formData.no_tlp || '';
                    document.getElementById('tanggal').value = formData.tanggal || '';
                    document.getElementById('lama_sewa').value = formData.lama_sewa || '1';
                    document.getElementById('harga_supir').value = formData.harga_supir || '0';
                    document.getElementById('id_supir').value = formData.id_supir || '';

                    // Restore driver info if exists
                    if (formData.id_supir && formData.nama_supir) {
                        document.getElementById('nama_supir').textContent = formData.nama_supir;
                        document.getElementById('harga_supir_display').textContent = formData.harga_supir_display;
                        document.getElementById('supir_info').style.display = 'block';
                        document.getElementById('hapus_supir').style.display = 'inline-block';
                    }

                    // Clear the saved data after restoring
                    sessionStorage.removeItem('bookingFormData');
                }
            }

            // Restore form data on page load
            restoreFormData();

            // Cek apakah ada supir yang dipilih dari sessionStorage (from supir.php)
            const selectedSupir = sessionStorage.getItem('selectedSupir');
            if (selectedSupir) {
                const supir = JSON.parse(selectedSupir);
                document.getElementById('id_supir').value = supir.id;
                document.getElementById('harga_supir').value = supir.harga;
                document.getElementById('nama_supir').textContent = supir.nama;
                document.getElementById('harga_supir_display').textContent = supir.harga.toLocaleString();
                document.getElementById('supir_info').style.display = 'block';
                document.getElementById('hapus_supir').style.display = 'inline-block';
                updateTotalHarga();
                sessionStorage.removeItem('selectedSupir'); // Hapus dari sessionStorage setelah digunakan
            }

            // Event listener untuk link pilih supir - save form data before navigating
            document.getElementById('pilih_supir_link').addEventListener('click', function(e) {
                saveFormData();
            });

            // Event listener untuk tombol hapus supir
            document.getElementById('hapus_supir').addEventListener('click', function() {
                document.getElementById('id_supir').value = '';
                document.getElementById('harga_supir').value = '0';
                document.getElementById('supir_info').style.display = 'none';
                document.getElementById('hapus_supir').style.display = 'none';
                updateTotalHarga();
            });

            // Event listener untuk perubahan lama sewa
            document.getElementById('lama_sewa').addEventListener('input', updateTotalHarga);

            // Event listener untuk form submission - clear sessionStorage
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                <?php if(!$profil_lengkap): ?>
                e.preventDefault();
                showProfileRequiredModal();
                <?php else: ?>
                sessionStorage.removeItem('bookingFormData');
                <?php endif; ?>
            });

            function updateTotalHarga() {
                const hargaMobil = parseInt('<?= $isi['harga']; ?>');
                const hargaSupir = parseInt(document.getElementById('harga_supir').value) || 0;
                const lamaSewa = parseInt(document.getElementById('lama_sewa').value) || 1;
                const total = (hargaMobil + hargaSupir) * lamaSewa;

                document.getElementById('total_harga_display').textContent = 'Rp' + total.toLocaleString();
                document.getElementById('total_harga').value = total;

                // Update detail harga
                let detailText = `Mobil: Rp${hargaMobil.toLocaleString()}/hari`;
                if (hargaSupir > 0) {
                    detailText += ` + Supir: Rp${hargaSupir.toLocaleString()}/hari`;
                }
                document.getElementById('detail_harga').textContent = detailText;
            }

            // Initialize total harga
            updateTotalHarga();

            // Add animation for incomplete profile button
            <?php if(!$profil_lengkap): ?>
            const bookingBtn = document.getElementById('bookingBtnIncomplete');
            if (bookingBtn) {
                // Add pulse animation
                bookingBtn.style.animation = 'pulse 2s infinite';
                
                // Color change animation
                let colorState = true;
                setInterval(() => {
                    if (colorState) {
                        bookingBtn.classList.remove('btn-warning');
                        bookingBtn.classList.add('btn-danger');
                    } else {
                        bookingBtn.classList.remove('btn-danger');
                        bookingBtn.classList.add('btn-warning');
                    }
                    colorState = !colorState;
                }, 2000);
            }
            <?php endif; ?>
        });

        // Professional Modal Function untuk Profil Required
        function showProfileRequiredModal() {
            const modalHTML = `
                <div class="swal-professional">
                   
                    
                    <h2 class="swal-title">
                        <i class="fas fa-user-check me-2"></i>
                        Lengkapi Profil Anda
                    </h2
                    
                    <div class="swal-text">
                        <p style="margin-bottom: 1.5rem; color: white;">
                            Untuk melanjutkan proses booking, kami membutuhkan informasi profil lengkap Anda.
                        </p>

                        
                        <div class="feature-card" style="display: flex; align-items: center; margin: 1.5rem 0;">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <strong style="display: block; margin-bottom: 0.25rem; color: white;">Keamanan Terjamin</strong>
                                <small style="color: white;">Data Anda dilindungi dengan enkripsi standar industri</small>
                            </div>
                        </div>

                        <div class="feature-card" style="display: flex; align-items: center;">
                            <div class="feature-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div>
                                <strong style="display: block; margin-bottom: 0.25rem; color: white;">Proses Cepat</strong>
                                <small style="color: white;">Hanya membutuhkan 2 menit untuk melengkapi</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Show SweetAlert2 dengan konfigurasi profesional
            Swal.fire({
                html: modalHTML,
                width: 480,
                padding: '2rem',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-user-edit me-2"></i>Lengkapi Profil Sekarang',
                cancelButtonText: '<i class="fas fa-clock me-2"></i>Ingatkan Saya Nanti',
                reverseButtons: true,
                focusConfirm: false,
                allowOutsideClick: false,
                allowEscapeKey: true,
                customClass: {
                    popup: 'swal-professional',
                    confirmButton: 'swal-button',
                    cancelButton: 'swal-button swal-button--cancel'
                },
                buttonsStyling: false,
                showClass: {
                    popup: 'animate__animated animate__fadeInUp'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutDown'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke halaman profil dengan parameter tracking
                    window.location.href = 'profil.php?from_booking=true&redirect_source=booking_modal';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Tampilkan toast notification untuk reminder
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Kami akan mengingatkan Anda nanti',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: 'var(--primary)',
                        color: 'white'
                    });
                }
            });
        }

        // Optional: Tambahkan function untuk reminder setelah beberapa waktu
        function setupProfileReminder() {
            setTimeout(() => {
                if (!localStorage.getItem('profile_reminder_shown')) {
                    Swal.fire({
                        title: 'Masih butuh bantuan?',
                        text: 'Ingin melengkapi profil Anda sekarang?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lengkapi Sekarang',
                        cancelButtonText: 'Tidak, Terima Kasih',
                        customClass: {
                            popup: 'swal-professional'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'profil.php?from_booking=true';
                        } else {
                            localStorage.setItem('profile_reminder_shown', 'true');
                        }
                    });
                }
            }, 30000); // Reminder setelah 30 detik
        }
    </script>
</body>
</html>

<?php include 'footer.php';?>