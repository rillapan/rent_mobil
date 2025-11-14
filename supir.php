<?php
session_start();
require 'koneksi/koneksi.php';
include 'header.php';

// Ambil daftar supir yang tersedia
$result = mysqli_query($koneksi, "SELECT * FROM supir WHERE status = 'Tersedia' ORDER BY nama_supir ASC");
$supir = [];
while ($row = mysqli_fetch_assoc($result)) {
    $supir[] = $row;
}
?>

<div class="container my-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #1A237E;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-tie me-2"></i>Daftar Supir
                    </h5>
                </div>
                <div class="card-body">

                    <?php if (empty($supir)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Tidak ada supir yang tersedia saat ini.
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($supir as $s): ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body text-center">
                                            <?php if (!empty($s['foto'])): ?>
                                                <img src="assets/image/<?= htmlspecialchars($s['foto']) ?>" alt="Foto Supir" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                                                    <i class="fas fa-user-tie fa-3x text-secondary"></i>
                                                </div>
                                            <?php endif; ?>
                                            <h5 class="card-title"><?= htmlspecialchars($s['nama_supir']) ?></h5>
                                            <p class="card-text text-muted mb-2">
                                                <i class="fas fa-briefcase"></i> <?= htmlspecialchars($s['pengalaman']) ?> tahun pengalaman
                                            </p>
                                            <p class="card-text small text-truncate mb-3" style="max-height: 3em; overflow: hidden;">
                                                <?= htmlspecialchars($s['deskripsi']) ?>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> Tersedia
                                                </span>
                                                <span class="text-primary fw-bold">
                                                    Rp<?= number_format($s['harga_sewa']) ?>/hari
                                                </span>
                                            </div>
                                            <button type="button" class="btn btn-primary w-100 pilih-supir"
                                                    data-id="<?= $s['id_supir'] ?>"
                                                    data-nama="<?= htmlspecialchars($s['nama_supir']) ?>"
                                                    data-harga="<?= $s['harga_sewa'] ?>">
                                                <i class="fas fa-check me-1"></i>Pilih Supir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event listener untuk tombol pilih supir
    document.querySelectorAll('.pilih-supir').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            const harga = this.getAttribute('data-harga');

            // Simpan data supir ke sessionStorage
            const supirData = {
                id: id,
                nama: nama,
                harga: parseInt(harga)
            };
            sessionStorage.setItem('selectedSupir', JSON.stringify(supirData));

            // Redirect kembali ke halaman booking
            window.location.href = 'booking.php?id=<?= $_GET['id'] ?? '' ?>';
        });
    });
});
</script>

<?php include 'footer.php'; ?>
