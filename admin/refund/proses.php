<?php
require '../../koneksi/koneksi.php';
session_start();

if (empty($_SESSION['USER']) || $_SESSION['USER']['level'] !== 'admin') {
    echo '<script>alert("Akses ditolak. Silakan login sebagai admin.");window.location="../index.php";</script>';
    exit();
}

$admin_id = $_SESSION['USER']['id_login'];

if (isset($_GET['id']) && $_GET['id'] === 'approve_refund' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $refund_id = isset($_POST['refund_id']) ? (int)$_POST['refund_id'] : 0;
    $nominal_refund = isset($_POST['nominal_refund']) ? (float)$_POST['nominal_refund'] : 0;
    $catatan_admin = isset($_POST['catatan_admin']) && $_POST['catatan_admin'] !== '' ? trim($_POST['catatan_admin']) : null;
    $kode_booking = $_POST['kode_booking'] ?? '';

    if ($refund_id <= 0 || $nominal_refund <= 0) {
        header('Location: index.php?status=refunderror');
        exit();
    }

    // Ambil data refund beserta booking
    $stmt_refund = $koneksi->prepare("SELECT rr.*, b.id_login AS booking_user, b.id_booking, b.kode_booking, b.total_harga, l.nama_pengguna, l.no_hp 
        FROM refund_requests rr
        JOIN booking b ON rr.id_booking = b.id_booking
        LEFT JOIN login l ON b.id_login = l.id_login
        WHERE rr.id = ?");
    $stmt_refund->execute([$refund_id]);
    $refund = $stmt_refund->fetch(PDO::FETCH_ASSOC);

    if (!$refund) {
        header('Location: index.php?status=refunderror');
        exit();
    }

    $status_refund = 'Refund Diterima / Uang Dikembalikan';
    $nominal_formatted = number_format($nominal_refund, 2, '.', '');

    // Update data refund
    $stmt_update = $koneksi->prepare("UPDATE refund_requests 
        SET status = ?, nominal_refund = ?, catatan_admin = ?, processed_at = NOW(), admin_id = ? 
        WHERE id = ?");
    $stmt_update->execute([
        $status_refund,
        $nominal_formatted,
        $catatan_admin,
        $admin_id,
        $refund_id
    ]);

    // Update status booking
    $stmt_update_booking = $koneksi->prepare("UPDATE booking SET konfirmasi_pembayaran = ? WHERE id_booking = ?");
    $stmt_update_booking->execute([$status_refund, $refund['id_booking']]);

    // Kirim notifikasi ke pelanggan
    $total_pembayaran = $refund['total_pembayaran'] ?: $refund['total_harga'];
    $total_formatted = 'Rp ' . number_format((float)$total_pembayaran, 0, ',', '.');
    $nominal_refund_formatted = 'Rp ' . number_format($nominal_refund, 0, ',', '.');

    $pesan_notifikasi = <<<HTML
<div style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h4 style="color: #2c3e50; margin-bottom: 10px;">
        <i class="fas fa-hand-holding-usd" style="color: #28a745;"></i> &nbsp; <strong>Refund Telah Diproses</strong>
    </h4>
    <p style="margin-bottom: 20px;">Permintaan refund Anda telah disetujui. Uang Anda telah dikembalikan ke rekening tujuan.</p>
    <div style="background-color: #f8f9fa; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px;">
        <h5 style="color: #2c3e50; margin-top: 0; margin-bottom: 10px;">
            <i class="fas fa-info-circle" style="color: #28a745;"></i> <strong style="color: #333;">Detail Refund</strong>
        </h5>
        <p style="margin: 5px 0;"><strong>Kode Booking :</strong> {$refund['kode_booking']}</p>
        <p style="margin: 5px 0;"><strong>Total Pembayaran :</strong> {$total_formatted}</p>
        <p style="margin: 5px 0;"><strong>Nominal Refund :</strong> {$nominal_refund_formatted}</p>
    </div>
    <p style="color: #666; font-size: 0.9em;">Terima kasih telah bersabar menunggu proses refund. Jika masih ada pertanyaan, silakan hubungi customer support kami.</p>
</div>
HTML;

    $stmt_notif = $koneksi->prepare("INSERT INTO notifikasi (id_login, id_booking, pesan, status_baca) VALUES (?, ?, ?, 0)");
    $stmt_notif->execute([
        $refund['id_login'],
        $refund['id_booking'],
        $pesan_notifikasi
    ]);

    header('Location: index.php?status=refundapproved');
    exit();
}

header('Location: index.php');
exit();

