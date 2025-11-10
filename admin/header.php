<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(empty($_SESSION['USER'])){
        echo '<script>alert("Login Dulu !");window.location="../index.php";</script>';
        exit();
    }else{
        if($_SESSION['USER']['level'] != 'admin'){
            echo '<script>alert("Login Khusus Admin !");window.location="../index.php";</script>';
            exit();
        }
    }
 
    // select untuk panggil nama admin
    $id_login = $_SESSION['USER']['id_login'];
    
    $row = $koneksi->prepare("SELECT * FROM login WHERE id_login=?");
    $row->execute(array($id_login));
    $hasil_login = $row->fetch();

    $sql = "SELECT * FROM infoweb WHERE id = 1";
    $row = $koneksi->prepare($sql);
    $row->execute();
    $info_web = $row->fetch(PDO::FETCH_OBJ);
?>
<!doctype html>
<html lang="en">
  <head>
    <title><?php echo $title_web;?> | <?= $info_web->nama_rental;?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= $url;?>assets/css/bootstrap.css" >
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
        padding-top: 80px; /* Memberi ruang untuk header fixed */
      }
      
      /* Modern Header Styling */
      .modern-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1rem 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        border-radius: 0;
      }
      
      .brand-name {
        font-weight: 700;
        font-size: 1.8rem;
        letter-spacing: 0.5px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
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
      
      /* Modern Navigation */
      .navbar-modern {
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-radius: 10px;
        padding: 0.5rem 1rem;
        margin-bottom: 30px;
      }
      
      .navbar-modern .navbar-nav .nav-link {
        color: var(--dark);
        font-weight: 500;
        padding: 0.8rem 1.2rem;
        border-radius: 8px;
        margin: 0 5px;
        transition: all 0.3s ease;
      }
      
      .navbar-modern .navbar-nav .nav-link:hover,
      .navbar-modern .navbar-nav .nav-link.active {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
      }
      
      .navbar-modern .navbar-toggler {
        border: none;
        outline: none;
        color: white;
        font-size: 1.5rem;
      }
      
      .navbar-modern .navbar-toggler:focus {
        box-shadow: none;
      }
      
      .user-greeting {
        background: linear-gradient(135deg, var(--secondary) 0%, #e55a2b 100%);
        color: white;
        border-radius: 50px;
        padding: 0.4rem 1.2rem;
        margin-right: 10px;
        font-weight: 500;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }
      
      .logout-btn {
        background: transparent;
        color: var(--gray);
        border: 1px solid var(--gray);
        border-radius: 50px;
        padding: 0.4rem 1.2rem;
        font-weight: 500;
        transition: all 0.3s ease;
      }
      
      .logout-btn:hover {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
        transform: translateY(-2px);
      }
      
      /* Sidebar styling for mobile */
      .sidebar {
        position: fixed;
        top: 0;
        left: -280px;
        width: 280px;
        height: 100%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: #fff;
        z-index: 1050;
        transition: left 0.3s;
        box-shadow: 2px 0 12px rgba(0,0,0,0.15);
        overflow-y: auto;
      }
      .sidebar.open {
        left: 0;
      }
      .sidebar-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: #fff; /* Ensure text color is white */
        padding: 1rem 1.5rem; /* Default padding */
        flex-shrink: 0;
      }

      .sidebar-brand-name {
        font-size: 1.2rem; /* Smaller font size for sidebar brand */
        font-weight: 700;
        letter-spacing: 0.5px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
      }
      .sidebar-nav {
        padding: 1rem 0; /* Add some vertical padding */
        list-style: none; /* Remove default list bullets */
        margin: 0; /* Remove default margin */
      }

      .sidebar-nav .nav-item {
        margin-bottom: 0.5rem; /* Spacing between items */
      }

      .sidebar-nav .nav-link {
        display: block;
        padding: 0.8rem 1.5rem; /* Padding for clickable area */
        color: #fff; /* White text color for links */
        font-weight: 500;
        transition: background-color 0.3s ease;
        border-radius: 8px; /* Rounded corners */
      }

      .sidebar-nav .nav-link:hover,
      .sidebar-nav .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
      }

      .sidebar-nav .nav-link i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
      }
      .sidebar-overlay {
        display: none;
        position: fixed;
        z-index: 1049;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.3);
      }
      .sidebar-overlay.open {
        display: block;
      }
      .close-sidebar {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
      }
      
      /* Responsive adjustments */
      @media (max-width: 992px) {
        .desktop-nav {
          display: none;
        }
        
        .mobile-menu-btn {
          display: block;
        }
        
        .brand-name {
          font-size: 1.5rem;
        }
        
        .modern-header .col-md-4 h4 {
          display: none;
        }
      }
      
      @media (min-width: 993px) {
        .mobile-menu-btn {
          display: none !important;
        }
        
        .sidebar, .sidebar-overlay {
          display: none !important;
        }
      }
      
      @media (max-width: 768px) {
        .modern-header {
          padding: 0.8rem 0;
        }
        
        .brand-name {
          font-size: 1.4rem;
        }
        
        .search-form {
          margin-top: 10px;
        }
        
        .navbar-modern .navbar-nav .nav-link {
          margin: 3px 0;
        }
        
        .user-greeting, .logout-btn {
          margin: 5px 0;
          display: inline-block;
        }
      }
    </style>
  </head>
  <body>
    <!-- Modern Header -->
   <header class="modern-header">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-4 col-md-4 d-flex align-items-center">
        <button class="mobile-menu-btn d-block d-md-none" id="mobileMenuBtn" style="background:transparent;border:none;font-size:2rem;color:#fff;">
          <i class="fas fa-bars"></i>
        </button>
        <h4 class="d-none d-md-block text-light mb-0"><b>Admin Panel</b></h4>
      </div>
      <div class="col-8 col-md-8 d-flex justify-content-end align-items-center">
        <h2 class="brand-name mb-0"><b><?= $info_web->nama_rental; ?></b> <span>RENTAL</span></h2>
      </div>
    </div>
  </div>
</header>

    <!-- Sidebar for mobile -->
    <div class="sidebar" id="sidebar">
      <div class="sidebar-header d-flex align-items-center justify-content-between p-3">
        <h5 class="brand-name mb-0 sidebar-brand-name"><b><?= $info_web->nama_rental; ?></b> <span>RENTAL</span></h5>
        <button class="close-sidebar" id="closeSidebar" aria-label="Close Sidebar">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <ul class="sidebar-nav list-unstyled">
        <li class="nav-item">
          <a class="nav-link <?php if($title_web == 'Dashboard'){ echo 'active';}?>" href="<?php echo $url;?>admin/">
            <i class="fas fa-home"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($title_web == 'User'){ echo 'active';}?>" href="<?php echo $url;?>admin/user/index.php">
            <i class="fas fa-users"></i> User / Pelanggan
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($title_web == 'Daftar Mobil' || $title_web == 'Tambah Mobil' || $title_web == 'Edit Mobil'){ echo 'active';}?>" href="<?php echo $url;?>admin/mobil/mobil.php">
            <i class="fas fa-car"></i> Daftar Mobil
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($title_web == 'Daftar Booking' || $title_web == 'Konfirmasi'){ echo 'active';}?>" href="<?php echo $url;?>admin/booking/booking.php">
            <i class="fas fa-calendar-check"></i> Daftar Booking
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($title_web == 'Peminjaman'){ echo 'active';}?>" href="<?php echo $url;?>admin/peminjaman/peminjaman.php">
            <i class="fas fa-key"></i> Peminjaman
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($title_web == 'Daftar Supir' || $title_web == 'Tambah Supir' || $title_web == 'Edit Supir'){ echo 'active';}?>" href="<?php echo $url;?>admin/supir/supir.php">
            <i class="fas fa-user-tie"></i> Management Supir
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <i class="fas fa-user"></i> Hallo, <?php echo $hasil_login['nama_pengguna'];?>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link logout-btn-sidebar" id="logout-link-sidebar" href="<?php echo $url;?>admin/logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </li>
      </ul>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="container">
      <!-- Modern Navigation for desktop -->
      <nav class="navbar navbar-expand-lg navbar-modern desktop-nav">
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
          <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item <?php if($title_web == 'Dashboard'){ echo 'active';}?>">
              <a class="nav-link" href="<?php echo $url;?>admin/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item <?php if($title_web == 'User'){ echo 'active';}?>">
              <a class="nav-link" href="<?php echo $url;?>admin/user/index.php">User / Pelanggan</a>
            </li>
            <li class="nav-item <?php if($title_web == 'Daftar Mobil' || $title_web == 'Tambah Mobil' || $title_web == 'Edit Mobil'){ echo 'active';}?>">
              <a class="nav-link" href="<?php echo $url;?>admin/mobil/mobil.php">Daftar Mobil</a>
            </li>
            <li class="nav-item <?php if($title_web == 'Daftar Booking' || $title_web == 'Konfirmasi'){ echo 'active';}?>">
              <a class="nav-link" href="<?php echo $url;?>admin/booking/booking.php">Daftar Booking</a>
            </li>
            <li class="nav-item <?php if($title_web == 'Peminjaman'){ echo 'active';}?>">
              <a class="nav-link" href="<?php echo $url;?>admin/peminjaman/peminjaman.php">Peminjaman</a>
            </li>
            <li class="nav-item <?php if($title_web == 'Daftar Supir' || $title_web == 'Tambah Supir' || $title_web == 'Edit Supir'){ echo 'active';}?>">
              <a class="nav-link" href="<?php echo $url;?>admin/supir/supir.php">Management Supir</a>
            </li>
          </ul>
          <ul class="navbar-nav my-2 my-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="#">
                <i class="fas fa-user"></i> Hallo, <?php echo $hasil_login['nama_pengguna'];?>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link logout-btn" id="logout-link" href="<?php echo $url;?>admin/logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Konten utama -->
      <!-- Tempatkan konten halaman Anda di sini -->
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    
    <script>
      // Toggle sidebar
      document.getElementById('mobileMenuBtn').addEventListener('click', function() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('open');
      });
      
      document.getElementById('closeSidebar').addEventListener('click', function() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('open');
      });
      
      document.getElementById('sidebarOverlay').addEventListener('click', function() {
        document.getElementById('sidebar').classList.remove('open');
        this.classList.remove('open');
      });
      
      // Logout confirmation for both desktop and mobile
      const logoutLinks = document.querySelectorAll('#logout-link, #logout-link-sidebar');
      logoutLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
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
      });
    </script>
  </body>
</html>