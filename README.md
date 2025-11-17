PROSES LOGIN
1.  <!-- Modal Login di index.php -->
<form method="POST" action="">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" name="login" class="btn btn-login">Login</button>
</form>

2. proses Login
 if (isset($_POST['login'])) { // Jika tombol login ditekan
    $username = $_POST['username'];  // Ambil data username
    $password = $_POST['password'];  // Ambil data password

    // Query untuk mencari user di database
    $query = mysqli_prepare($koneksi, "SELECT * FROM login WHERE username = ?");
    mysqli_stmt_bind_param($query, "s", $username);
    mysqli_stmt_execute($query);
    $result_stmt = mysqli_stmt_get_result($query);
    $user = mysqli_fetch_assoc($result_stmt); // Ambil data user
    mysqli_stmt_close($query);

    // Jika user ditemukan
    if ($user) {
        // Verifikasi password dengan MD5
        if (md5($password) == $user['password']) {
            $_SESSION['USER'] = $user; // Simpan data user ke session

            // Redirect berdasarkan level user
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

===================================================================================================================================

PROSES REGISTER

1. FORM REGISTRASI (Frontend - register.php)
<form id="registerForm" method="post" action="koneksi/proses.php?id=daftar">
    <!-- Input fields: nama, username, no_hp, email, password, confirm_password -->
    <button type="submit">Daftar Sekarang</button>
</form>
2. PROSES VALIDASI CLIENT-SIDE (JavaScript)
    $('#registerForm').on('submit', function(e) {
    e.preventDefault(); // Mencegah submit default
    
    // Validasi semua field:
    - Nama: minimal 2 karakter
    - Username: minimal 3 karakter  
    - No HP: format Indonesia (08xxx / +62)
    - Email: format email valid
    - Password: minimal 6 karakter
    - Konfirmasi: harus sama dengan password
});

3. PROSES REGISTRASI SERVER-SIDE (proses.php)
if($_GET['id'] == 'daftar') {
    // 1. AMBIL DATA DARI FORM
    $nama = $_POST['nama'];
    $user = $_POST['user'];
    $pass = md5($_POST['pass']); // Enkripsi MD5
    $level = 'pengguna'; // Default level
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];

    // 2. CEK DUPLIKAT USERNAME/EMAIL
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM login WHERE username = ? OR email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $user, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $hitung = mysqli_num_rows($result);

    if($hitung > 0) {
        // 3A. JIKA DUPLIKAT - GAGAL
        header('Location: ../register.php?status=registerfailed');
    } else {
        // 3B. JIKA TIDAK DUPLIKAT - SIMPAN KE DATABASE
        $sql = "INSERT INTO `login`(`nama_pengguna`, `username`, `password`, `level`, `no_hp`, `email`) 
                VALUES (?,?,?,?,?,?)";
        $stmt_insert = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt_insert, "ssssss", $nama, $user, $pass, $level, $no_hp, $email);
        mysqli_stmt_execute($stmt_insert);
        
        // 4. REDIRECT KE HALAMAN LOGIN
        header('Location: ../register.php?status=registersuccess');
    }
}

==============================================================================================================================================================

PROSES MANAGEMENT MOBIL
ALUR PROSES MENAMPILKAN DAFTAR USER

1. INISIALISASI & KONEKSI DATABASE
<?php
    require '../../koneksi/koneksi.php';  // Load koneksi database
    $title_web = 'User';                 // Set judul halaman
    $url = '../../';                     // Set base URL
    include '../header.php';             // Include header admin
?>
2. QUERY DATABASE UNTUK MENGAMBIL DATA USER
<?php
    $no = 1;
    // Query hanya mengambil user dengan level 'Pengguna'
    $sql = "SELECT * FROM login WHERE level = 'Pengguna' ORDER BY id_login DESC";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_execute($stmt);
    $result_stmt = mysqli_stmt_get_result($stmt);
    
    // Simpan hasil query ke array
    $hasil = [];
    while ($row = mysqli_fetch_assoc($result_stmt)) {
        $hasil[] = $row;
    }
    mysqli_stmt_close($stmt);
?>
3. STRUKTUR TABEL HTML
<table class="table table-striped table-hover table-bordered">
    <thead class="bg-light">
        <tr>
            <th scope="col">No.</th>
            <th scope="col">Nama Pengguna</th>
            <th scope="col">Username</th>
            <th scope="col">Foto Profil</th>
            <th scope="col">Detail Pelanggan</th>
            <th scope="col" class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <!-- Data user akan di-loop di sini -->
    </tbody>
</table>
4. PROSES LOOPING DATA USER
<?php foreach($hasil as $r): ?>
<tr>
    <!-- Kolom 1: Nomor Urut -->
    <td><?= $no;?></td>
    
    <!-- Kolom 2: Nama Pengguna -->
    <td><?= htmlspecialchars($r['nama_pengguna']);?></td>
    
    <!-- Kolom 3: Username -->
    <td><?= htmlspecialchars($r['username']);?></td>
    
    <!-- Kolom 4: Foto Profil -->
    <td class="text-center">
        <img src="../../assets/image/user/<?= htmlspecialchars($r['foto'] ?? 'default.jpg'); ?>" 
             alt="Foto Profil" width="50" height="50" class="img-thumbnail">
    </td>
    
    <!-- Kolom 5: Detail Pelanggan -->
    <td>
        <div class="customer-details">
            <small>
                <strong>Nama Lengkap:</strong> <?= htmlspecialchars($r['nama_pengguna'] ?? '-');?><br>
                <strong>No KTP/NIK:</strong> <?= htmlspecialchars($r['ktp'] ?? '-');?><br>
                <strong>Email:</strong> <?= htmlspecialchars($r['email'] ?? '-');?><br>
                <strong>No. HP:</strong> <?= htmlspecialchars($r['no_hp'] ?? '-');?><br>
                <strong>Provinsi:</strong> <?= htmlspecialchars($r['provinsi'] ?? '-');?><br>
                <strong>Kota/Kab:</strong> <?= htmlspecialchars($r['kota_kabupaten'] ?? '-');?><br>
                <strong>Jenis Kelamin:</strong> <?= htmlspecialchars($r['jenis_kelamin'] ?? '-');?>
            </small>
        </div>
    </td>
    
    <!-- Kolom 6: Aksi -->
    <td class="text-center">
        <a href="<?php echo $url;?>admin/booking/booking.php?id=<?= $r['id_login'];?>"
           class="btn btn-secondary btn-sm" title="Lihat Detail Transaksi">
            <i class="fa fa-info-circle"></i> Detail Transaksi
        </a>
    </td>
</tr>
<?php $no++; endforeach; ?>