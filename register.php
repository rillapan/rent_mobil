<?php
session_start();
require 'koneksi/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Akun - Rental Mobil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Daftar akun baru untuk rental mobil terpercaya">

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
            --secondary: #FF6B35;
            --light: #F8F9FA;
            --dark: #212529;
            --gray: #6C757D;
            --success: #28a745;
            --danger: #dc3545;
            --border-radius: 12px;
            --box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
            padding: 0 1rem;
        }

        .register-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .register-header h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        .register-header p {
            opacity: 0.9;
            margin-bottom: 0;
        }

        .register-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.25);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            display: block;
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .btn-register {
            background: linear-gradient(135deg, var(--secondary) 0%, #e55a2b 100%);
            border: none;
            color: white;
            padding: 0.875rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
            color: white;
        }

        .register-footer {
            background: var(--light);
            padding: 1.5rem 2rem;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .register-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .register-footer a:hover {
            text-decoration: underline;
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

        .strength-weak { background: var(--danger); width: 33%; }
        .strength-medium { background: #ffc107; width: 66%; }
        .strength-strong { background: var(--success); width: 100%; }

        .strength-text {
            font-size: 0.8rem;
            font-weight: 500;
        }

        .strength-text.weak { color: var(--danger); }
        .strength-text.medium { color: #856404; }
        .strength-text.strong { color: var(--success); }

        /* Responsive */
        @media (max-width: 576px) {
            .register-container {
                padding: 0 0.5rem;
            }

            .register-header {
                padding: 1.5rem;
            }

            .register-header h2 {
                font-size: 1.5rem;
            }

            .register-body {
                padding: 1.5rem;
            }

            .register-footer {
                padding: 1rem 1.5rem;
            }
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
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h2><i class="fas fa-user-plus mr-2"></i>Daftar Akun Baru</h2>
                <p>Buat akun untuk mulai menyewa mobil</p>
            </div>

            <div class="register-body">
                <form id="registerForm" method="post" action="koneksi/proses.php?id=daftar">
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

                    <div class="form-group">
                        <label for="email" class="font-weight-bold">
                            <i class="fas fa-envelope mr-1"></i>Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="Masukkan alamat email Anda" required>
                        <div class="invalid-feedback">
                            Email wajib diisi dengan format yang benar.
                        </div>
                    </div>

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

                    <button type="submit" class="btn btn-register" id="submitBtn">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                    </button>
                </form>
            </div>

            <div class="register-footer">
                <p class="mb-0">Sudah punya akun?
                    <a href="index.php"><i class="fas fa-sign-in-alt mr-1"></i>Login di sini</a>
                </p>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Password strength checker
            $('#pass').on('input', function() {
                const password = $(this).val();
                const strengthMeter = $('#passwordStrength');
                const strengthFill = $('#strengthFill');
                const strengthText = $('#strengthText');

                if (password.length === 0) {
                    strengthMeter.hide();
                    return;
                }

                strengthMeter.show();

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
                strengthFill.removeClass('strength-weak strength-medium strength-strong');

                if (strength <= 2) {
                    strengthFill.addClass('strength-weak');
                    strengthText.removeClass('medium strong').addClass('weak');
                    strengthText.text('Lemah - ' + feedback.join(', '));
                } else if (strength <= 4) {
                    strengthFill.addClass('strength-medium');
                    strengthText.removeClass('weak strong').addClass('medium');
                    strengthText.text('Sedang - Tambahkan variasi karakter');
                } else {
                    strengthFill.addClass('strength-strong');
                    strengthText.removeClass('weak medium').addClass('strong');
                    strengthText.text('Kuat - Password aman!');
                }
            });

            // Form validation
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                const nama = $('#nama').val().trim();
                const user = $('#user').val().trim();
                const noHp = $('#no_hp').val().trim();
                const email = $('#email').val().trim();
                const pass = $('#pass').val();
                const confirmPass = $('#confirm_pass').val();

                // Reset validation
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').hide();

                let isValid = true;

                // Validate nama
                if (nama.length < 2) {
                    $('#nama').addClass('is-invalid');
                    isValid = false;
                }

                // Validate username
                if (user.length < 3) {
                    $('#user').addClass('is-invalid');
                    isValid = false;
                }

                // Validate phone number (Indonesian format)
                const phoneRegex = /^(\+62|62|0)[8-9][0-9]{7,11}$/;
                if (!phoneRegex.test(noHp.replace(/\s/g, ''))) {
                    $('#no_hp').addClass('is-invalid');
                    isValid = false;
                }

                // Validate email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    $('#email').addClass('is-invalid');
                    isValid = false;
                }

                // Validate password
                if (pass.length < 6) {
                    $('#pass').addClass('is-invalid');
                    isValid = false;
                }

                // Validate password confirmation
                if (pass !== confirmPass) {
                    $('#confirm_pass').addClass('is-invalid');
                    isValid = false;
                }

                if (!isValid) {
                    return false;
                }

                // Show loading state
                const submitBtn = $('#submitBtn');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true);
                submitBtn.html('<span class="loading"></span>Mendaftarkan...');

                // Submit form
                this.submit();
            });

            // Format phone number input
            $('#no_hp').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.startsWith('0')) {
                    // Keep leading 0
                } else if (value.startsWith('62')) {
                    value = '0' + value.substring(2);
                }
                $(this).val(value);
            });

            // Status messages from URL parameters
            <?php if(isset($_GET['status'])): ?>
            const status = '<?php echo $_GET['status']; ?>';
            if (status === 'registersuccess') {
                Swal.fire({
                    title: 'Pendaftaran Berhasil!',
                    text: 'Akun Anda telah berhasil dibuat. Silakan login untuk melanjutkan.',
                    icon: 'success',
                    confirmButtonText: 'Ke Halaman Login',
                    showCancelButton: true,
                    cancelButtonText: 'Tutup'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php';
                    }
                });
            } else if (status === 'registerfailed') {
                Swal.fire({
                    title: 'Pendaftaran Gagal!',
                    text: 'Username sudah digunakan. Silakan pilih username lain.',
                    icon: 'error',
                    confirmButtonText: 'Coba Lagi'
                });
            }
            <?php endif; ?>
        });
    </script>
</body>
</html>
