<?php
session_start();
require 'koneksi/koneksi.php';

// Check login status
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari user
    $query = $koneksi->prepare("SELECT * FROM login WHERE username = ?");
    $query->execute([$username]);
    $user = $query->fetch();

    if ($user) {
        // Verifikasi password
        if (md5($password) == $user['password']) {
            $_SESSION['USER'] = $user;

            // Redirect berdasarkan level
            if ($user['level'] == 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            // Password salah
            header("Location: index.php?status=wrongpassword");
            exit();
        }
    } else {
        // Username tidak ditemukan
        header("Location: index.php?status=usernotfound");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Rental Mobil - Solusi Transportasi Terbaik</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Rental mobil terpercaya dengan berbagai pilihan kendaraan berkualitas. Layanan cepat, mudah, dan harga terjangkau.">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
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
            overflow-x: hidden;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
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
            transition: var(--transition);
        }
        
        .modern-header.scrolled {
            padding: 0.5rem 0;
            backdrop-filter: blur(10px);
            background: rgba(26, 35, 126, 0.95);
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
            position: relative;
        }
        
        .navbar-nav .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .navbar-nav .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--secondary);
            transition: var(--transition);
            transform: translateX(-50%);
        }
        
        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 80%;
        }

        /* Login Modal Styles */
        .login-modal .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .login-modal .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            border-bottom: none;
            padding: 1.5rem 2rem;
        }
        
        .login-modal .modal-body {
            padding: 2rem;
        }
        
        .login-modal .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .login-modal .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.25);
        }
        
        .login-modal .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .login-modal .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 35, 126, 0.3);
        }
        
        .login-links {
            text-align: center;
            margin-top: 1rem;
        }
        
        .login-links a {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .login-links a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, 
                rgba(26, 35, 126, 0.95) 0%, 
                rgba(13, 20, 82, 0.95) 100%),
                url('assets/image/all-new-xenia-exterior-tampak-perspektif-depan---varian-1.5r-ads.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 8rem 0 6rem;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,0 Q500,80 1000,0 L1000,100 L0,100 Z" fill="white"/></svg>');
            background-size: 100% 100px;
            background-repeat: no-repeat;
            background-position: bottom;
            opacity: 0.1;
            pointer-events: none;
        }

        /* Hero Badge */
        .hero-badge .badge-experience {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Hero Title */
        .hero-title {
            font-weight: 800;
            font-size: 3.2rem;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.9) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Hero Subtitle */
        .hero-subtitle {
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 90%;
        }

        /* Hero Stats */
        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .hero-stats .stat-item {
            text-align: center;
        }

        .hero-stats .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--secondary);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .hero-stats .stat-label {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        /* Hero Actions */
        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .btn-primary-hero {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        .btn-primary-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 107, 53, 0.4);
            color: white;
        }

        .btn-secondary-hero {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .btn-secondary-hero:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px);
            color: white;
        }

        /* Trust Indicators */
        .trust-indicators {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .trust-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .trust-item i {
            color: var(--secondary);
            font-size: 1rem;
        }

        /* Hero Visual - Featured Car Card */
        .hero-visual {
            position: relative;
            padding: 1rem;
        }

        .featured-car-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .featured-car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .car-image-container {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .car-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .featured-car-card:hover .car-image {
            transform: scale(1.05);
        }

        .car-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--secondary);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .car-info h4 {
            color: white;
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .car-details {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .car-details span {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .car-details i {
            color: var(--secondary);
        }

        .car-price {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .car-price .price {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--secondary);
        }

        .car-price .period {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-book-now {
            display: block;
            width: 100%;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-align: center;
            padding: 0.75rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-book-now:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }

        /* Floating Elements */
        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            padding: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: float 3s ease-in-out infinite;
        }

        .floating-element.rating {
            top: 10%;
            right: -10%;
            text-align: center;
        }

        .floating-element.availability {
            bottom: 20%;
            left: -10%;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .rating-stars {
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .rating-text {
            font-size: 0.8rem;
            color: white;
            font-weight: 600;
        }

        .availability-dot {
            width: 10px;
            height: 10px;
            background: var(--success);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }

        .availability-text {
            font-size: 0.8rem;
            color: white;
            font-weight: 600;
        }

        /* Scroll Indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
        }

        .scroll-text {
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }

        .scroll-arrow {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        /* Section Title */
        .section-title {
            position: relative;
            margin-bottom: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            text-align: center;
            font-size: 2.5rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--secondary);
            border-radius: 2px;
        }

        /* Carousel Styles */
        .carousel-modern {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            margin-bottom: 3rem;
        }
        
        .carousel-item img {
            height: 450px;
            object-fit: cover;
        }
        
        .carousel-caption-modern {
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
            bottom: 0;
            left: 0;
            right: 0;
            padding: 2rem;
            text-align: left;
        }
        
        .carousel-caption-modern h3 {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .carousel-caption-modern p {
            font-size: 1.2rem;
            margin-bottom: 0;
        }
        
        .carousel-control-prev-modern, 
        .carousel-control-next-modern {
            width: 50px;
            height: 50px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.7;
            transition: var(--transition);
        }
        
        .carousel-control-prev-modern { left: 15px; }
        .carousel-control-next-modern { right: 15px; }
        
        .carousel-control-prev-modern:hover, 
        .carousel-control-next-modern:hover {
            opacity: 0.9;
            background-color: var(--primary);
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

        /* Features Section */
        .features-section {
            padding: 5rem 0;
            background: white;
            border-radius: var(--border-radius);
            margin: 3rem 0;
        }
        
        .feature-card {
            text-align: center;
            padding: 2rem 1rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            height: 100%;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow);
            background: white;
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 1.8rem;
        }
        
        .feature-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary);
        }
        
        .feature-text {
            color: var(--gray);
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 4rem 0;
            border-radius: var(--border-radius);
            margin: 3rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .stat-number.counter-active {
            animation: pulse 0.5s ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .stat-text {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .stat-icon {
            margin-bottom: 0.5rem;
            color: var(--secondary);
            font-size: 2rem;
        }

        .stat-description {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 0.25rem;
        }

        /* Footer */
        .modern-footer {
            background: var(--dark);
            color: white;
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
        }
        
        .footer-logo {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        
        .footer-logo span {
            color: var(--secondary);
        }
        
        .footer-heading {
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .footer-heading:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--secondary);
        }
        
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            transition: var(--transition);
            text-decoration: none;
        }
        
        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            margin-right: 0.75rem;
            transition: var(--transition);
        }
        
        .social-links a:hover {
            background: var(--secondary);
            transform: translateY(-3px);
        }
        
        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            margin-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Floating Action Button */
        .floating-action {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        
        .floating-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--secondary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.5);
            transition: var(--transition);
            text-decoration: none;
        }
        
        .floating-btn:hover {
            transform: scale(1.1);
            color: white;
        }

        /* Contact Section */
        .contact-section {
            padding: 5rem 0;
            background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
        }

        .contact-info-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--box-shadow);
            height: 100%;
        }

        .contact-form-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--box-shadow);
            height: 100%;
        }

        .contact-item {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
        }

        .contact-item i {
            color: var(--secondary);
            margin-right: 1rem;
            margin-top: 0.25rem;
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }

        .social-links-footer {
            display: flex;
            gap: 0.75rem;
        }

        .social-links-footer a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            color: white;
            transition: var(--transition);
        }

        .social-links-footer a:hover {
            background: var(--secondary);
            transform: translateY(-3px);
        }

        .map-container {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            height: 400px;
        }

        /* Welcome Message */
        .welcome-message {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: var(--box-shadow);
        }
        
        .welcome-message h3 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 1200px) {
            .hero-title {
                font-size: 2.8rem;
            }
            
            .floating-element.rating {
                right: -5%;
            }
            
            .floating-element.availability {
                left: -5%;
            }
        }

        @media (max-width: 992px) {
            .hero-section {
                padding: 4rem 0 3rem;
                text-align: center;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                max-width: 100%;
                font-size: 1.1rem;
            }
            
            .hero-stats {
                justify-content: center;
            }
            
            .trust-indicators {
                justify-content: center;
            }
            
            .hero-visual {
                margin-top: 3rem;
            }
            
            .floating-element {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .hero-stats {
                gap: 1.5rem;
            }
            
            .hero-stats .stat-number {
                font-size: 1.5rem;
            }
            
            .hero-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-primary-hero,
            .btn-secondary-hero {
                width: 100%;
                justify-content: center;
            }
            
            .carousel-item img {
                height: 300px;
            }
            
            .carousel-caption-modern h3 {
                font-size: 1.4rem;
            }
            
            .carousel-caption-modern p {
                font-size: 1rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .hero-section {
                padding: 3rem 0 2rem;
            }
            
            .hero-title {
                font-size: 1.8rem;
            }
            
            .hero-stats {
                gap: 1rem;
            }
            
            .hero-stats .stat-number {
                font-size: 1.3rem;
            }
            
            .trust-indicators {
                gap: 1rem;
            }
            
            .floating-action {
                bottom: 20px;
                right: 20px;
            }
            
            .floating-btn {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="hero-content" data-aos="fade-right" data-aos-delay="200">
                        <!-- Badge Pengalaman -->
                        <div class="hero-badge mb-3">
                            <span class="badge-experience">
                                <i class="fas fa-award mr-2"></i>
                                Berpengalaman Sejak 2018
                            </span>
                        </div>

                        <!-- Judul Utama -->
                        <h1 class="hero-title">Sewa Mobil Terbaik dengan Harga Terjangkau</h1>
                        
                        <!-- Subtitle -->
                        <p class="hero-subtitle">
                            Temukan berbagai pilihan mobil berkualitas untuk kebutuhan perjalanan Anda. 
                            Proses cepat, mudah, dan aman dengan layanan 24/7.
                        </p>

                        <!-- Stats Ringkas -->
                        <div class="hero-stats mb-4">
                            <div class="stat-item">
                                <div class="stat-number">500+</div>
                                <div class="stat-label">Armada Mobil</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">50+</div>
                                <div class="stat-label">Kota Terjangkau</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">10K+</div>
                                <div class="stat-label">Pelanggan Puas</div>
                            </div>
                        </div>

                        <!-- Call to Action -->
                        <div class="hero-actions">
                            <a href="#cars-section" class="btn btn-primary-hero">
                                <i class="fas fa-car mr-2"></i>Lihat Mobil Tersedia
                            </a>
                            <a href="https://wa.me/6281234567890" class="btn btn-secondary-hero" target="_blank">
                                <i class="fab fa-whatsapp mr-2"></i>Konsultasi Gratis
                            </a>
                        </div>

                        <!-- Trust Indicators -->
                        <div class="trust-indicators mt-4">
                            <div class="trust-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>Terjamin & Aman</span>
                            </div>
                            <div class="trust-item">
                                <i class="fas fa-clock"></i>
                                <span>Proses Cepat</span>
                            </div>
                            <div class="trust-item">
                                <i class="fas fa-headset"></i>
                                <span>Support 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="hero-visual" data-aos="fade-left" data-aos-delay="400">
                        <!-- Featured Car Card -->
                        <div class="featured-car-card">
                            <div class="car-image-container">
                                <img src="assets/image/all-new-xenia-exterior-tampak-perspektif-depan---varian-1.5r-ads.jpg" 
                                     alt="Featured Car" class="car-image">
                                <div class="car-badge">POPULER</div>
                            </div>
                            <div class="car-info">
                                <h4 class="car-name">Daihatsu Xenia 2024</h4>
                                <div class="car-details">
                                    <span><i class="fas fa-users"></i> 7 Seater</span>
                                    <span><i class="fas fa-cogs"></i> Automatic</span>
                                    <span><i class="fas fa-snowflake"></i> AC</span>
                                </div>
                                <div class="car-price">
                                    <span class="price">Rp 350.000</span>
                                    <span class="period">/hari</span>
                                </div>
                                <a href="booking.php" class="btn-book-now">
                                    <i class="fas fa-calendar-check mr-2"></i>Booking Sekarang
                                </a>
                            </div>
                        </div>

                        <!-- Floating Elements -->
                        <div class="floating-element rating" data-aos="zoom-in" data-aos-delay="600">
                            <div class="rating-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <div class="rating-text">4.8/5 Rating</div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="scroll-indicator" data-aos="fade-up" data-aos-delay="1000">
            <div class="scroll-text">Scroll untuk menjelajahi</div>
            <div class="scroll-arrow">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Carousel Section -->
        <div class="carousel-modern" data-aos="fade-up">
            <div id="carouselId" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
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
                        class="img-fluid w-100">
                        <div class="carousel-caption carousel-caption-modern">
                            <h3><?= $isi['merk']; ?></h3>
                            <p>Rp. <?= number_format($isi['harga']); ?> /hari</p>
                        </div>
                    </div>
                    <?php $no++; endforeach; ?>
                </div>
                <a class="carousel-control-prev carousel-control-prev-modern" href="#carouselId" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next carousel-control-next-modern" href="#carouselId" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        
        <!-- Welcome Message for Logged In Users -->
        <?php if(isset($_SESSION['USER'])): ?>
            <div class="welcome-message" data-aos="fade-up">
                <h3>Selamat Datang, <?= htmlspecialchars($_SESSION['USER']); ?>!</h3>
                <p class="mb-0">Nikmati pengalaman rental mobil yang mudah dan menyenangkan.</p>
            </div>
        <?php endif; ?>
        
        <!-- Cars Section -->
        <section id="cars-section" class="py-5">
            <h2 class="section-title" data-aos="fade-up">Mobil Tersedia</h2>
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <p class="lead mb-4">Temukan berbagai pilihan mobil berkualitas untuk kebutuhan perjalanan Anda. Dari mobil keluarga hingga mobil mewah, semua tersedia dengan harga terjangkau.</p>
                <a href="mobil.php" class="btn btn-secondary-custom btn-lg">
                    <i class="fas fa-car mr-2"></i> Lihat Semua Mobil
                </a>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">Layanan Kami</h2>
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <h4 class="feature-title">Rental Harian/Mingguan</h4>
                            <p class="feature-text">Sewa mobil untuk kebutuhan harian atau mingguan dengan pilihan berbagai jenis kendaraan.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h4 class="feature-title">Dengan Driver</h4>
                            <p class="feature-text">Layanan rental dengan driver profesional yang berpengalaman dan mengenal rute dengan baik.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-route"></i>
                            </div>
                            <h4 class="feature-title">Perjalanan Jarak Jauh</h4>
                            <p class="feature-text">Mendukung perjalanan antar kota dengan kendaraan yang nyaman dan terawat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="container">
                <div class="row text-center">
                    <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-car"></i>
                            </div>
                            <div class="stat-number">500+</div>
                            <div class="stat-text">Armada Mobil</div>
                            <div class="stat-description">Tersedia berbagai jenis</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-number">10K+</div>
                            <div class="stat-text">Pelanggan Puas</div>
                            <div class="stat-description">Terlayani dengan baik</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="stat-number">50+</div>
                            <div class="stat-text">Kota Terjangkau</div>
                            <div class="stat-description">Seluruh Indonesia</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-number">24/7</div>
                            <div class="stat-text">Layanan</div>
                            <div class="stat-description">Support setiap saat</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="kontak" class="contact-section">
            <div class="container">
                <h2 class="section-title text-center mb-5" data-aos="fade-up">Hubungi Kami</h2>
                <div class="row">
                    <div class="col-lg-5 mb-4" data-aos="fade-right">
                        <div class="contact-info-card">
                            <h3 class="feature-title mb-4">Informasi Kontak</h3>
                            
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Alamat Kantor</strong>
                                    <p class="mb-0 text-muted"><?= htmlspecialchars($info_web->alamat ?? 'Jl. Contoh No. 123, Kota, Indonesia'); ?></p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Telepon & WhatsApp</strong>
                                    <a href="tel:<?= htmlspecialchars($info_web->telp ?? '+621234567890'); ?>" class="text-decoration-none text-dark d-block">
                                        <?= htmlspecialchars($info_web->telp ?? '+62 123 4567 890'); ?>
                                    </a>
                                </div>
                            </div>

                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <strong>Email Kami</strong>
                                    <a href="mailto:<?= htmlspecialchars($info_web->email ?? 'info@rentalmobil.com'); ?>" class="text-decoration-none text-dark d-block">
                                        <?= htmlspecialchars($info_web->email ?? 'info@rentalmobil.com'); ?>
                                    </a>
                                </div>
                            </div>

                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong>Jam Operasional</strong>
                                    <p class="mb-0 text-muted">24 Jam / 7 Hari Seminggu</p>
                                </div>
                            </div>
                            
                            <h5 class="mt-5 mb-3">Ikuti Kami</h5>
                            <div class="social-links-footer">
                                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7 mb-4" data-aos="fade-left">
                        <div class="contact-form-card">
                            <h3 class="feature-title mb-4">Kirim Pesan Cepat</h3>
                            <form id="contactForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control form-control-lg" id="contactName" placeholder="Nama Lengkap" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="email" class="form-control form-control-lg" id="contactEmail" placeholder="Email Aktif" required>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control form-control-lg" id="contactSubject" placeholder="Subjek Pesan" required>
                                </div>
                                <div class="form-group mb-3">
                                    <textarea class="form-control" id="contactMessage" rows="5" placeholder="Pesan/Permintaan Anda..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary-custom btn-lg w-100 mt-3">Kirim Pesan</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12" data-aos="fade-up">
                        <h3 class="text-center mb-3">Lokasi Kami di Peta</h3>
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1980.522843562489!2d109.6647342!3d-6.8851311!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e70251b5e1a6f57%3A0x73eb625318184632!2sSMK%20Negeri%202%20Pekalongan!5e0!3m2!1sid!2sid!4v1762314410836!5m2!1sid!2sid" 
                                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Floating Action Button -->
    <div class="floating-action">
        <a href="https://wa.me/6281234567890" class="floating-btn" target="_blank" data-aos="zoom-in" data-aos-delay="500">
            <i class="fab fa-whatsapp fa-lg"></i>
        </a>
    </div>

    <!-- Login Modal -->
    <div class="modal fade login-modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login ke Akun Anda
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-login mt-4">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </button>
                    </form>
                    <div class="login-links mt-3">
                        <a href="#" data-toggle="modal" data-target="#registerModal" data-dismiss="modal">Belum punya akun? Daftar di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade register-modal" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Akun Baru
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="registerForm" method="post" action="koneksi/proses.php?id=daftar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama" class="font-weight-bold">
                                        <i class="fas fa-user mr-1"></i>Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                           placeholder="Masukkan nama lengkap" required>
                                    <div class="invalid-feedback">
                                        Nama lengkap wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user" class="font-weight-bold">
                                        <i class="fas fa-at mr-1"></i>Username
                                    </label>
                                    <input type="text" class="form-control" id="user" name="user"
                                           placeholder="Masukkan username" required>
                                    <div class="invalid-feedback">
                                        Username wajib diisi dan minimal 3 karakter.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="no_hp" class="font-weight-bold">
                                <i class="fas fa-phone mr-1"></i>Nomor HP
                            </label>
                            <input type="tel" class="form-control" id="no_hp" name="no_hp"
                                   placeholder="Masukkan nomor HP (contoh: 081234567890)" required>
                            <div class="invalid-feedback">
                                Nomor HP wajib diisi dengan format yang benar.
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pass" class="font-weight-bold">
                                        <i class="fas fa-lock mr-1"></i>Password
                                    </label>
                                    <input type="password" class="form-control" id="pass" name="pass"
                                           placeholder="Masukkan password" required>
                                    <div class="password-strength" id="passwordStrength" style="display: none;">
                                        <div class="strength-meter">
                                            <div class="strength-fill" id="strengthFill"></div>
                                        </div>
                                        <div class="strength-text" id="strengthText">Kekuatan password</div>
                                    </div>
                                    <small class="form-text text-muted">
                                        Password minimal 6 karakter dengan kombinasi huruf dan angka.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_pass" class="font-weight-bold">
                                        <i class="fas fa-lock mr-1"></i>Konfirmasi Password
                                    </label>
                                    <input type="password" class="form-control" id="confirm_pass"
                                           placeholder="Ulangi password" required>
                                    <div class="invalid-feedback">
                                        Konfirmasi password tidak cocok.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary-custom btn-lg w-100 mt-3" id="submitBtn">
                            <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                        </button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="#" data-toggle="modal" data-target="#loginModal" data-dismiss="modal">Sudah punya akun? Login di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Header scroll effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.modern-header').addClass('scrolled');
            } else {
                $('.modern-header').removeClass('scrolled');
            }
        });

        $(document).ready(function() {
            // Counter Animation
            function animateCounter(element, target, duration = 2000) {
                const start = 0;
                const increment = target / (duration / 16);
                let current = start;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    element.textContent = Math.floor(current) + (element.textContent.includes('+') ? '+' : '');
                }, 16);
            }

            // Trigger counter animation when stats section is visible
            function startCounterAnimation() {
                $('.stat-number').each(function() {
                    const $this = $(this);
                    if (!$this.hasClass('animated')) {
                        $this.addClass('animated');
                        const text = $this.text();
                        const target = parseInt(text.replace(/[^\d]/g, ''));
                        const suffix = text.replace(/\d/g, '');
                        animateCounter(this, target);
                        setTimeout(() => {
                            this.textContent = target + suffix;
                        }, 2000);
                    }
                });
            }

            // Check if stats section is in viewport
            function isElementInViewport(el) {
                const rect = el.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }

            // Trigger animation on scroll
            $(window).on('scroll', function() {
                const statsSection = $('.stats-section')[0];
                if (statsSection && isElementInViewport(statsSection)) {
                    startCounterAnimation();
                }
            });

            // Trigger animation on page load if section is visible
            const statsSection = $('.stats-section')[0];
            if (statsSection && isElementInViewport(statsSection)) {
                setTimeout(startCounterAnimation, 500);
            }

            // Contact form submission
            $('#contactForm').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Pesan Terkirim!',
                    text: 'Terima kasih telah menghubungi kami. Kami akan merespons pesan Anda secepatnya.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                $('#contactForm')[0].reset();
            });

            // Password strength checker
            function checkPasswordStrength(password) {
                let strength = 0;
                let feedback = [];

                if (password.length >= 6) strength++;
                else feedback.push("Minimal 6 karakter");

                if (/[a-z]/.test(password)) strength++;
                else feedback.push("Huruf kecil");

                if (/[A-Z]/.test(password)) strength++;
                else feedback.push("Huruf besar");

                if (/[0-9]/.test(password)) strength++;
                else feedback.push("Angka");

                if (/[^A-Za-z0-9]/.test(password)) strength++;
                else feedback.push("Karakter khusus");

                return { strength, feedback };
            }

            // Update password strength indicator
            $('#pass').on('input', function() {
                const password = $(this).val();
                const { strength, feedback } = checkPasswordStrength(password);

                if (password.length > 0) {
                    $('#passwordStrength').show();
                    const percentage = (strength / 5) * 100;
                    $('#strengthFill').css('width', percentage + '%');

                    let color = '#dc3545'; // red
                    let text = 'Lemah';

                    if (strength >= 3) {
                        color = '#ffc107'; // yellow
                        text = 'Sedang';
                    }
                    if (strength >= 4) {
                        color = '#28a745'; // green
                        text = 'Kuat';
                    }

                    $('#strengthFill').css('background-color', color);
                    $('#strengthText').text(text + ' - ' + feedback.join(', '));
                } else {
                    $('#passwordStrength').hide();
                }
            });

            // Register form validation
            $('#registerForm').submit(function(e) {
                e.preventDefault();

                const nama = $('#nama').val().trim();
                const user = $('#user').val().trim();
                const no_hp = $('#no_hp').val().trim();
                const pass = $('#pass').val();
                const confirm_pass = $('#confirm_pass').val();

                // Reset validation
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').hide();

                let isValid = true;

                // Validate nama
                if (nama.length < 2) {
                    $('#nama').addClass('is-invalid');
                    $('#nama').next('.invalid-feedback').show();
                    isValid = false;
                }

                // Validate username
                if (user.length < 3) {
                    $('#user').addClass('is-invalid');
                    $('#user').next('.invalid-feedback').show();
                    isValid = false;
                }

                // Validate no_hp
                const phoneRegex = /^(\+62|62|0)[8-9][0-9]{7,11}$/;
                if (!phoneRegex.test(no_hp)) {
                    $('#no_hp').addClass('is-invalid');
                    $('#no_hp').next('.invalid-feedback').show();
                    isValid = false;
                }

                // Validate password
                if (pass.length < 6) {
                    $('#pass').addClass('is-invalid');
                    isValid = false;
                }

                // Validate confirm password
                if (pass !== confirm_pass) {
                    $('#confirm_pass').addClass('is-invalid');
                    $('#confirm_pass').next('.invalid-feedback').show();
                    isValid = false;
                }

                if (isValid) {
                    // Submit form
                    $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Mendaftarkan...');

                    // Use FormData for file uploads if needed, but here it's just form data
                    const formData = new FormData(this);

                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            // Since it's a redirect, we handle success via URL status
                            // But for AJAX, we can check response or assume success
                            window.location.href = 'index.php?status=registersuccess';
                        },
                        error: function(xhr, status, error) {
                            $('#submitBtn').prop('disabled', false).html('<i class="fas fa-user-plus mr-2"></i>Daftar Sekarang');
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });

            // Show status messages based on URL parameter
            function showStatusMessage() {
                const urlParams = new URLSearchParams(window.location.search);
                const status = urlParams.get('status');

                if (status === 'registersuccess') {
                    Swal.fire({
                        title: 'Pendaftaran Berhasil!',
                        html: '<i class="fas fa-check-circle fa-3x text-success mb-3"></i><br><strong>Selamat!</strong><br>Akun Anda telah berhasil didaftarkan. Silakan login untuk melanjutkan.',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Login Sekarang',
                        cancelButtonText: 'Kembali ke Beranda',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#loginModal').modal('show');
                        }
                    });
                } else if (status === 'registerfailed') {
                    Swal.fire({
                        title: 'Pendaftaran Gagal!',
                        html: '<i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i><br><strong>Username sudah digunakan</strong><br>Silakan gunakan username yang berbeda.',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonText: 'Coba Lagi',
                        cancelButtonText: 'Kembali ke Beranda',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#6c757d',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#registerModal').modal('show');
                        }
                    });
                } else if (status === 'usernotfound') {
                    Swal.fire({
                        title: 'Login Gagal!',
                        html: '<i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i><br><strong>Username tidak ditemukan</strong><br>Silakan periksa kembali username Anda atau daftar akun baru.',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonText: 'Coba Lagi',
                        cancelButtonText: 'Daftar Akun',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#6c757d',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#loginModal').modal('show');
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            $('#registerModal').modal('show');
                        }
                    });
                } else if (status === 'wrongpassword') {
                    Swal.fire({
                        title: 'Login Gagal!',
                        html: '<i class="fas fa-lock fa-3x text-danger mb-3"></i><br><strong>Password salah</strong><br>Password yang Anda masukkan tidak benar. Silakan coba lagi atau daftar akun baru.',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonText: 'Coba Lagi',
                        cancelButtonText: 'Daftar Akun',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#6c757d',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#loginModal').modal('show');
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            $('#registerModal').modal('show');
                        }
                    });
                } else if (status === 'loginfailed') {
                    Swal.fire({
                        title: 'Login Gagal',
                        text: 'Username atau password salah.',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonText: 'Coba Lagi',
                        cancelButtonText: 'Buat Akun'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // User clicked "Coba Lagi"
                            // You can add any additional logic here if needed
                        } else if (result.isDismissed) {
                            // User clicked "Buat Akun"
                            window.location.href = 'register.php'; // Redirect to register.php
                        }
                    });
                }

                // Remove status parameter from URL without page reload
                if (status) {
                    const newUrl = window.location.pathname;
                    window.history.replaceState({}, document.title, newUrl);
                }
            }

            // Call the function when page loads
            showStatusMessage();

            // Update "Daftar Akun" button in hero section to open register modal
            $('.btn-secondary-hero').click(function(e) {
                e.preventDefault();
                $('#registerModal').modal('show');
            });
        });
    </script>
</body>
</html>