<!doctype html>
<html lang="en">
  <head>
    <title>Rental Mobil</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
      :root {
        --primary: #1A237E; 
        --primary-dark: #1A3CC9;
        --secondary: #FF6B35;
        --light: #F8F9FA;
        --dark: #212529;
        --gray: #6C757D;
      }
      
      body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        background-color: #f8f9fa;
        overflow-x: hidden;
      }
      
      /* Modern Header Styling */
      .modern-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.5rem 0; /* Increased padding */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px; /* Increased margin */
        position: relative;
        z-index: 1000;
      }

      .modern-header .row {
        align-items: center;
        justify-content: space-between; /* Added to distribute space */
      }
      
      .brand-name {
        font-weight: 700;
        font-size: 2rem; /* Slightly larger */
        letter-spacing: 0.5px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        margin-bottom: 0;
      }
      
      .brand-name span {
        color: var(--secondary);
      }
      
      .search-form {
        position: relative;
        width: 100%;
        max-width: 300px; /* Constrain search form width */
      }

      .search-form .form-control {
        border-radius: 50px;
        padding-left: 20px;
        padding-right: 95px; /* Added padding */
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        height: 45px;
        font-size: 0.9rem;
      }
      
      .search-form .btn {
        position: absolute;
        right: 5px;
        top: 5px;
        border-radius: 50px;
        background: var(--secondary);
        border: none;
        height: 35px;
        width: 80px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
      }
      
      .menu-toggle {
        display: none;
        background: var(--secondary);
        color: white;
        border: none;
        border-radius: 5px;
        padding: 0.5rem 0.8rem;
        font-size: 1.2rem;
      }
      
      /* Sidebar styling for mobile */
      .sidebar {
        position: fixed;
        top: 0;
        left: -280px;
        width: 280px;
        height: 100%;
        background: white;
        box-shadow: 5px 0 15px rgba(0,0,0,0.1);
        z-index: 2000;
        transition: left 0.3s ease;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
      }
      
      .sidebar.open {
        left: 0;
      }
      
      .sidebar-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1rem 1.5rem;
        position: relative;
        flex-shrink: 0;
      }
      
      .sidebar-close {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 2rem;
        background: none;
        border: none;
        cursor: pointer;
      }
      
      .sidebar-nav {
        padding: 1rem 0;
        margin: 0;
        list-style: none;
        flex-grow: 1;
      }
      
      .sidebar-nav .nav-link {
        display: block;
        padding: 0.8rem 1.5rem;
        color: var(--dark);
        font-weight: 500;
        border-bottom: 1px solid #f1f1f1;
        transition: all 0.3s ease;
        font-size: 1.1rem;
      }
      
      .sidebar-nav .nav-link:hover,
      .sidebar-nav .nav-link.active {
        background: var(--primary);
        color: white;
        padding-left: 2rem;
      }
      
      .sidebar-nav .nav-link i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
      }
      
      .sidebar-user {
        padding: 1.5rem;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        text-align: center;
      }
      
      .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1999;
        display: none;
      }
      
      /* Login Modal */
      .login-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 3000;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease;
      }

      .login-modal.open {
        opacity: 1;
        visibility: visible;
      }

      .login-modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 400px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        position: relative;
        transform: translateY(-20px);
        transition: transform 0.3s ease;
      }

      .login-modal.open .login-modal-content {
        transform: translateY(0);
      }

      .login-modal-close {
        position: absolute;
        right: 15px;
        top: 15px;
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--gray);
        cursor: pointer;
      }

      .login-modal-header h4 {
        color: var(--primary);
        font-weight: 600;
      }

      .login-modal-form .form-control {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
      }

      .login-modal-form .btn-login {
        background: var(--secondary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem;
        font-weight: 600;
        width: 100%;
      }

      .login-modal-footer a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
      }

      /* Register Modal */
      .register-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 3100;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease;
      }

      .register-modal.open {
        opacity: 1;
        visibility: visible;
      }

      .register-modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        position: relative;
        transform: translateY(-20px);
        transition: transform 0.3s ease;
      }

      .register-modal.open .register-modal-content {
        transform: translateY(0);
      }

      .register-modal-close {
        position: absolute;
        right: 15px;
        top: 15px;
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--gray);
        cursor: pointer;
      }

      .register-modal-header h4 {
        color: var(--primary);
        font-weight: 600;
      }

      .register-modal-form .form-control {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
      }

      .register-modal-form .btn-register {
        background: var(--secondary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem;
        font-weight: 600;
        width: 100%;
      }

      .register-modal-footer a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
      }

      .password-strength {
        margin-top: 0.5rem;
      }

      .strength-meter {
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 0.5rem;
      }

      .strength-fill {
        height: 100%;
        transition: all 0.3s ease;
      }

      .strength-weak { background: #dc3545; width: 33%; }
      .strength-medium { background: #ffc107; width: 66%; }
      .strength-strong { background: #28a745; width: 100%; }

      .strength-text {
        font-size: 0.8rem;
        font-weight: 500;
      }

      .strength-text.weak { color: #dc3545; }
      .strength-text.medium { color: #856404; }
      .strength-text.strong { color: #28a745; }

      /* Invalid feedback for modals */
      .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
      }

      .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
      }

      /* Loading spinner */
      .loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
        margin-right: 0.5rem;
      }

      @keyframes spin {
        to { transform: rotate(360deg); }
      }

      /* Modern Navigation for desktop */
      .navbar-modern {
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-radius: 10px;
        padding: 0.5rem 1rem;
        margin-bottom: 30px;
        display: block !important;
        position: sticky;
        top: 0;
        z-index: 1020;
      }
      
      .navbar-modern .navbar-nav {
        flex-wrap: wrap;
      }
      
      .navbar-modern .navbar-nav .nav-link {
        color: var(--dark) !important;
        font-weight: 500;
        padding: 0.6rem 0.9rem;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        white-space: nowrap;
        background: transparent !important;
      }

      .navbar-modern .navbar-nav .nav-link:hover,
      .navbar-modern .navbar-nav .nav-link.active {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
      }
      
      .user-area {
        display: flex;
        align-items: center;
        gap: 10px; /* Added gap for spacing */
      }

      .user-greeting {
        background: linear-gradient(135deg, var(--secondary) 0%, #e55a2b 100%);
        color: white;
        border-radius: 50px;
        padding: 0.4rem 1rem;
        font-weight: 500;
        font-size: 0.9rem;
        white-space: nowrap;
      }
      
      .logout-btn {
        background: #dc3545; /* Red background */
        color: white;
        border: 1px solid #dc3545; /* Red border */
        border-radius: 50px;
        padding: 0.4rem 1rem;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        white-space: nowrap;
      }
      
      .logout-btn:hover {
        background: #c82333; /* Darker red on hover */
        color: white;
        border-color: #c82333; /* Darker red border on hover */
      }
      
      #desktopLoginBtn {
          color: white !important;
      }

      .badge-notif {
        position: relative;
        top: -2px;
        font-size: 0.7rem;
        padding: 0.25rem 0.4rem;
        margin-left: 5px;
      }
      
      /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .modern-header .col-12.col-md-auto {
                margin-top: 1rem;
            }
            .modern-header .col-auto.mr-auto {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 991.98px) {
        .menu-toggle {
            display: block;
        }
        .navbar-modern {
            display: none;
        }
        }

        @media (min-width: 992px) {
        .navbar-modern .navbar-collapse {
            display: flex !important;
            justify-content: space-between;
        }
        .navbar-modern .navbar-nav {
            display: flex !important;
            flex-direction: row !important;
        }
        .navbar-modern .nav-item {
            display: block !important;
        }
        .sidebar, .sidebar-overlay, .menu-toggle {
            display: none !important;
        }
        }

    </style>
  </head>
  <body>
    <?php
        if(session_status() === PHP_SESSION_NONE) session_start();
        $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';

        // Ambil data info website
        $sql_web = "SELECT * FROM infoweb WHERE id = 1";
        $row_web = $koneksi->prepare($sql_web);
        $row_web->execute();
        $info_web = $row_web->fetch(PDO::FETCH_OBJ);

        // Ambil notifikasi
        $unread_notifications_count = 0;
        if (!empty($_SESSION['USER'])) {
            $id_login = $_SESSION['USER']['id_login'];
            $sql_notif = "SELECT COUNT(*) as total FROM notifikasi WHERE id_login = ? AND status_baca = 0";
            $row_notif = $koneksi->prepare($sql_notif);
            $row_notif->execute(array($id_login));
            $unread_notifications_count = $row_notif->fetch(PDO::FETCH_OBJ)->total;
        }
    ?>
    <!-- Sidebar for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="sidebar" id="sidebar">
      <div class="sidebar-header d-flex align-items-center justify-content-between">
        <span class="brand-name" style="font-size:1.2rem;margin-bottom:0;"><b><?= $info_web->nama_rental; ?></b> <span>RENTAL</span></span>
        <button class="sidebar-close" id="sidebarClose" aria-label="Tutup Sidebar"><i class="fas fa-times"></i></button>
      </div>
      <ul class="sidebar-nav">
        <?php if(empty($_SESSION['USER'])){?>
        <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? ' active' : '' ?>" href="index.php"><i class="fas fa-home"></i> Home</a></li>
        <?php }?>
        <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'blog.php' ? ' active' : '' ?>" href="blog.php"><i class="fas fa-car"></i> Daftar Mobil</a></li>
        <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'supir.php' ? ' active' : '' ?>" href="supir.php"><i class="fas fa-user"></i> Supir</a></li>
        <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'kontak.php' ? ' active' : '' ?>" href="kontak.php"><i class="fas fa-phone"></i> Kontak Kami</a></li>
        <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'cek_pesanan.php' ? ' active' : '' ?>" href="cek_pesanan.php"><i class="fas fa-receipt"></i> Cek Pesanan</a></li>
        <?php if(!empty($_SESSION['USER'])){?>
        <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'history.php' ? ' active' : '' ?>" href="history.php"><i class="fas fa-history"></i> Daftar Pesanan</a></li>
        <li class="nav-item">
          <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'notifikasi.php' ? ' active' : '' ?>" href="notifikasi.php">
            <i class="fas fa-bell"></i> Notifikasi
            <?php if ($unread_notifications_count > 0) { ?><span class="badge badge-danger badge-notif"><?= $unread_notifications_count ?></span><?php } ?>
          </a>
        </li>
        <?php } else { ?>
        <li class="nav-item"><a class="nav-link" id="sidebarLoginBtn" href="#"><i class="fas fa-sign-in-alt"></i> Login</a></li>
        <?php } ?>
      </ul>
      <?php if(!empty($_SESSION['USER'])){?>
      <div class="sidebar-user">
        <a href="profil.php" class="user-greeting mb-2" style="text-decoration: none; color: inherit;"><i class="fas fa-user"></i> Hallo, <?php echo $_SESSION['USER']['nama_pengguna'];?></a>
        <a class="logout-btn d-block text-center" id="sidebar-logout-link" href="<?php echo $url;?>admin/logout.php">Logout</a>
      </div>
      <?php }?>
    </div>

    <!-- Login Modal -->
    <div class="login-modal" id="headerLoginModal">
      <div class="login-modal-content">
        <button class="login-modal-close" id="loginModalClose"><i class="fas fa-times"></i></button>
        <div class="login-modal-header text-center mb-4">
          <h4><i class="fas fa-sign-in-alt"></i> Login Akun</h4>
        </div>
        <form class="login-modal-form" method="post" action="koneksi/proses.php?id=login">
          <div class="form-group mb-3">
            <label for="loginUsername">Username</label>
            <input type="text" name="user" id="loginUsername" class="form-control" placeholder="Masukkan username" required>
          </div>
          <div class="form-group mb-4">
            <label for="loginPassword">Password</label>
            <input type="password" name="pass" id="loginPassword" class="form-control" placeholder="Masukkan password" required>
          </div>
          <button type="submit" class="btn btn-login"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
        <div class="login-modal-footer text-center mt-4 pt-3 border-top">
          <p>Belum punya akun? <a href="#" id="registerLink">Daftar sekarang</a></p>
        </div>
      </div>
    </div>

    <!-- Register Modal -->
    <div class="register-modal" id="headerRegisterModal">
      <div class="register-modal-content">
        <button class="register-modal-close" id="registerModalClose"><i class="fas fa-times"></i></button>
        <div class="register-modal-header text-center mb-4">
          <h4><i class="fas fa-user-plus"></i> Daftar Akun Baru</h4>
        </div>
        <form class="register-modal-form" method="post" action="koneksi/proses.php?id=daftar">
          <div class="form-group mb-3">
            <label for="registerNama">Nama Lengkap</label>
            <input type="text" class="form-control" id="registerNama" name="nama" placeholder="Masukkan nama lengkap" required>
          </div>
          <div class="form-group mb-3">
            <label for="registerUsername">Username</label>
            <input type="text" class="form-control" id="registerUsername" name="user" placeholder="Masukkan username" required>
          </div>
          <div class="form-group mb-3">
            <label for="registerNoHp">Nomor HP</label>
            <input type="tel" class="form-control" id="registerNoHp" name="no_hp" placeholder="Masukkan nomor HP" required>
          </div>
          <div class="form-group mb-3">
            <label for="registerPassword">Password</label>
            <input type="password" class="form-control" id="registerPassword" name="pass" placeholder="Masukkan password" required>
            <div class="password-strength" id="registerPasswordStrength" style="display: none;">
              <div class="strength-meter">
                <div class="strength-fill" id="registerStrengthFill"></div>
              </div>
              <div class="strength-text" id="registerStrengthText">Kekuatan password</div>
            </div>
          </div>
          <div class="form-group mb-4">
            <label for="registerConfirmPassword">Konfirmasi Password</label>
            <input type="password" class="form-control" id="registerConfirmPassword" placeholder="Ulangi password" required>
          </div>
          <button type="submit" class="btn btn-register"><i class="fas fa-user-plus"></i> Daftar Sekarang</button>
        </form>
        <div class="register-modal-footer text-center mt-4 pt-3 border-top">
          <p>Sudah punya akun? <a href="#" id="loginLink">Login di sini</a></p>
        </div>
      </div>
    </div>

    <!-- Modern Header -->
    <header class="modern-header">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-auto d-flex align-items-center">
            <button class="menu-toggle mr-2" id="menuToggle"><i class="fas fa-bars"></i></button>
            <h2 class="brand-name">
                <b><?= $info_web->nama_rental; ?></b> <span>RENTAL</span>
            </h2>
          </div>
          <div class="col d-none d-md-block">
            <form class="search-form" method="get" action="blog.php">
              <input class="form-control" type="search" name="cari" placeholder="Cari Nama Mobil..." aria-label="Search">
              <button class="btn btn-success" type="submit">Cari</button>
            </form>
          </div>
          <div class="col-auto">
            <div class="user-area">
              <?php if(!empty($_SESSION['USER'])){?>
                <a href="profil.php" class="user-greeting" style="text-decoration: none; color: inherit;"><i class="fas fa-user"></i> Hallo, <?php echo $_SESSION['USER']['nama_pengguna'];?></a>
                <a class="logout-btn" id="logout-link" href="<?php echo $url;?>admin/logout.php">Logout</a>
              <?php } else { ?>
                <a class="nav-link" id="desktopLoginBtn" href="#"><i class="fas fa-sign-in-alt"></i> Login</a>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Modern Navigation for desktop -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-modern">
      <div class="container">
        <div class="collapse navbar-collapse show" id="navbarTogglerDemo01">
          <ul class="navbar-nav mr-auto">
            <?php if(empty($_SESSION['USER'])){?>
            <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? ' active' : '' ?>" href="index.php"><i class="fas fa-home"></i> Home</a></li>
            <?php }?>
            <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'blog.php' ? ' active' : '' ?>" href="blog.php"><i class="fas fa-car"></i> Daftar Mobil</a></li>
            <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'supir.php' ? ' active' : '' ?>" href="supir.php"><i class="fas fa-user"></i> Supir</a></li>
            <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'kontak.php' ? ' active' : '' ?>" href="kontak.php"><i class="fas fa-phone"></i> Kontak Kami</a></li>
            <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'cek_pesanan.php' ? ' active' : '' ?>" href="cek_pesanan.php"><i class="fas fa-receipt"></i> Cek Pesanan</a></li>
            <?php if(!empty($_SESSION['USER'])){?>
            <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'history.php' ? ' active' : '' ?>" href="history.php"><i class="fas fa-history"></i> Daftar Pesanan</a></li>
            <li class="nav-item">
              <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'notifikasi.php' ? ' active' : '' ?>" href="notifikasi.php">
                <i class="fas fa-bell"></i> Notifikasi
                <?php if ($unread_notifications_count > 0) { ?><span class="badge badge-danger badge-notif"><?= $unread_notifications_count ?></span><?php } ?>
              </a>
            </li>
            <?php }?>
          </ul>


        </div>
      </div>
    </nav>

    <!-- Main content -->
    <div class="container">
      <!-- Your car listing content here -->
    </div>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        // Sidebar functionality
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        function openSidebar() {
            sidebar.classList.add('open');
            sidebarOverlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            sidebar.classList.remove('open');
            sidebarOverlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        menuToggle.addEventListener('click', openSidebar);
        sidebarClose.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);
        
        document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
            if (link.id !== 'sidebarLoginBtn') {
                link.addEventListener('click', (e) => { if(e.target.href) closeSidebar(); });
            }
        });
        
        // Login Modal functionality
        const loginModal = document.getElementById('headerLoginModal');
        const loginModalClose = document.getElementById('loginModalClose');
        const sidebarLoginBtn = document.getElementById('sidebarLoginBtn');
        const desktopLoginBtn = document.getElementById('desktopLoginBtn');
        const registerLink = document.getElementById('registerLink');

        // Register Modal functionality
        const registerModal = document.getElementById('headerRegisterModal');
        const registerModalClose = document.getElementById('registerModalClose');
        const loginLink = document.getElementById('loginLink');

        function openLoginModal(e) {
            e.preventDefault();
            loginModal.classList.add('open');
            document.body.style.overflow = 'hidden';
            closeSidebar();
        }

        function closeLoginModal() {
            loginModal.classList.remove('open');
            document.body.style.overflow = 'auto';
        }

        function openRegisterModal(e) {
            e.preventDefault();
            registerModal.classList.add('open');
            document.body.style.overflow = 'hidden';
            closeSidebar();
            closeLoginModal();
        }

        function closeRegisterModal() {
            registerModal.classList.remove('open');
            document.body.style.overflow = 'auto';
        }

        if (sidebarLoginBtn) sidebarLoginBtn.addEventListener('click', openLoginModal);
        if (desktopLoginBtn) desktopLoginBtn.addEventListener('click', openLoginModal);
        if (loginModalClose) loginModalClose.addEventListener('click', closeLoginModal);

        loginModal.addEventListener('click', (e) => { if (e.target === loginModal) closeLoginModal(); });

        if (registerLink) registerLink.addEventListener('click', openRegisterModal);

        if (registerModalClose) registerModalClose.addEventListener('click', closeRegisterModal);
        registerModal.addEventListener('click', (e) => { if (e.target === registerModal) closeRegisterModal(); });

        if (loginLink) {
            loginLink.addEventListener('click', function(e) {
                e.preventDefault();
                closeRegisterModal();
                openLoginModal(e);
            });
        }

        // Password strength checker for register modal
        const registerPassword = document.getElementById('registerPassword');
        const registerPasswordStrength = document.getElementById('registerPasswordStrength');
        const registerStrengthFill = document.getElementById('registerStrengthFill');
        const registerStrengthText = document.getElementById('registerStrengthText');

        if (registerPassword) {
            registerPassword.addEventListener('input', function() {
                const password = this.value;
                if (password.length === 0) {
                    registerPasswordStrength.style.display = 'none';
                    return;
                }

                registerPasswordStrength.style.display = 'block';

                let strength = 0;
                let feedback = [];

                // Length check
                if (password.length >= 6) strength += 1;
                else feedback.push('minimal 6 karakter');

                // Lowercase check
                if (/[a-z]/.test(password)) strength += 1;
                else feedback.push('huruf kecil');

                // Uppercase check
                if (/[A-Z]/.test(password)) strength += 1;
                else feedback.push('huruf besar');

                // Number check
                if (/\d/.test(password)) strength += 1;
                else feedback.push('angka');

                // Special character check
                if (/[^A-Za-z0-9]/.test(password)) strength += 1;

                // Update UI based on strength
                registerStrengthFill.className = 'strength-fill';
                registerStrengthText.className = 'strength-text';

                if (strength <= 2) {
                    registerStrengthFill.classList.add('strength-weak');
                    registerStrengthText.classList.add('weak');
                    registerStrengthText.textContent = 'Lemah - ' + feedback.join(', ');
                } else if (strength <= 4) {
                    registerStrengthFill.classList.add('strength-medium');
                    registerStrengthText.classList.add('medium');
                    registerStrengthText.textContent = 'Sedang - Tambahkan variasi karakter';
                } else {
                    registerStrengthFill.classList.add('strength-strong');
                    registerStrengthText.classList.add('strong');
                    registerStrengthText.textContent = 'Kuat - Password aman!';
                }
            });
        }

        // Form validation for register modal
        const registerForm = document.querySelector('.register-modal-form');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const nama = document.getElementById('registerNama').value.trim();
                const user = document.getElementById('registerUsername').value.trim();
                const noHp = document.getElementById('registerNoHp').value.trim();
                const pass = document.getElementById('registerPassword').value;
                const confirmPass = document.getElementById('registerConfirmPassword').value;

                // Reset validation
                document.querySelectorAll('.register-modal-form .form-control').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                let isValid = true;

                // Validate nama
                if (nama.length < 2) {
                    document.getElementById('registerNama').classList.add('is-invalid');
                    isValid = false;
                }

                // Validate username
                if (user.length < 3) {
                    document.getElementById('registerUsername').classList.add('is-invalid');
                    isValid = false;
                }

                // Validate phone number
                const phoneRegex = /^(\+62|62|0)[8-9][0-9]{7,11}$/;
                if (!phoneRegex.test(noHp.replace(/\s/g, ''))) {
                    document.getElementById('registerNoHp').classList.add('is-invalid');
                    isValid = false;
                }

                // Validate password
                if (pass.length < 6) {
                    document.getElementById('registerPassword').classList.add('is-invalid');
                    isValid = false;
                }

                // Validate password confirmation
                if (pass !== confirmPass) {
                    document.getElementById('registerConfirmPassword').classList.add('is-invalid');
                    isValid = false;
                }

                if (!isValid) {
                    return false;
                }

                // Show loading state
                const submitBtn = registerForm.querySelector('.btn-register');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="loading"></span>Mendaftarkan...';

                // Submit form
                this.submit();
            });
        }
        
        // Logout confirmation
        ['logout-link', 'sidebar-logout-link'].forEach(id => {
            const logoutBtn = document.getElementById(id);
            if(logoutBtn) {
                logoutBtn.addEventListener('click', function(event) {
                    event.preventDefault();
                    const href = this.href;
                    Swal.fire({
                        title: 'Anda yakin mau logout?',
                        text: "Anda akan keluar dari sesi ini.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Logout!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                });
            }
        });
    </script>
  </body>
</html>