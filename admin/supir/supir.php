<?php

    require '../../koneksi/koneksi.php';
    $title_web = 'Daftar Supir';
    $url = '../../';
    include '../header.php';
?>
<style>
    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
    }
    .btn-secondary:hover {
        background-color: #e55e2d;
        border-color: #e55e2d;
    }
     :root {
        --primary: #1A237E;
        --primary-dark: #1A3CC9;
        --secondary: #FF6B35;
        --light: #F8F9FA;
        --dark: #212529;
        --gray: #6C757D;
      }

      .card-header {
        background-color: var(--primary);
        color: white;
      }

    /* Custom badge styles for status */
    .badge-available,
    .badge-used,
    .badge-close {
        color: white !important;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: .5em .75em;
        font-size: 95%;
        border-radius: .3rem;
    }
    .badge-available {
        background-color: #28a745;
    }
    .badge-used {
        background-color: #17a2b8;

        /* tambahan untuk menampilkan identitas singkat tanpa merusak tata letak */
        max-width: 260px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .badge-close {
        background-color: #dc3545;
    }
    .badge i {
        margin-right: .3em;
    }

    .customer-info-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: .375rem;
        padding: 0;
        margin-top: .5rem;
        text-align: left;
        min-width: 280px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .customer-info-header {
        background-color: #e9ecef;
        padding: .5rem .75rem;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        font-size: 0.875rem;
        color: #495057;
        border-radius: .375rem .375rem 0 0;
    }
    .customer-info-content {
        padding: .75rem;
    }
    .info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: .5rem;
        font-size: 0.875rem;
    }
    .info-item:last-child {
        margin-bottom: 0;
    }
    .info-label {
        font-weight: 600;
        color: #495057;
        margin-right: .25rem;
        min-width: 60px;
    }
    .info-value {
        color: #212529;
        word-break: break-word;
    }

</style>
<div class="container my-4">
    <div class="card shadow-lg">
        <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-user-tie me-2"></i>Daftar Supir
            </h5>
            <a href="tambah.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-plus me-1"></i>Tambah Supir
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">No.</th>
                            <th scope="col" class="text-center">Foto</th>
                            <th scope="col" class="text-center">Nama Supir</th>
                            <th scope="col" class="text-center">Pengalaman</th>
                            <th scope="col" class="text-center">Harga Sewa</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Deskripsi</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT s.*, b.kode_booking, b.nama, b.no_tlp, b.tanggal, DATE_ADD(b.tanggal, INTERVAL b.lama_sewa DAY) AS tanggal_akhir
                                    FROM supir s
                                    LEFT JOIN booking b ON b.id_booking = (
                                        SELECT id_booking FROM booking
                                        WHERE id_supir = s.id_supir
                                        AND konfirmasi_pembayaran IN ('Pembayaran Diterima', 'Sedang Diproses')
                                        AND DATE_ADD(tanggal, INTERVAL lama_sewa DAY) > CURDATE()
                                        AND (status_pengembalian IS NULL OR status_pengembalian = '' OR status_pengembalian = 'Belum Dikembalikan')
                                        ORDER BY tanggal DESC LIMIT 1
                                    )
                                    ORDER BY s.id_supir DESC";
                            $row = mysqli_prepare($koneksi, $sql);
                            mysqli_stmt_execute($row);
                            $result_stmt = mysqli_stmt_get_result($row);
                            $hasil = [];
                            while ($row_data = mysqli_fetch_assoc($result_stmt)) {
                                $hasil[] = $row_data;
                            }
                            mysqli_stmt_close($row);
                            $no = 1;

                            foreach($hasil as $isi)
                            {
                                // Tentukan status berdasarkan adanya booking aktif
                                $status = (!empty($isi['kode_booking'])) ? 'Sedang Digunakan' : $isi['status'];
                        ?>
                        <tr>
                            <td class="text-center align-middle"><?= $no;?></td>
                            <td class="text-center align-middle">
                                <img src="../../assets/image/<?= htmlspecialchars($isi['foto']);?>" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;" alt="Foto Supir">
                            </td>
                            <td class="text-center align-middle"><?= htmlspecialchars($isi['nama_supir']);?></td>
                            <td class="text-center align-middle"><?= htmlspecialchars($isi['pengalaman']);?> tahun</td>
                            <td class="text-center align-middle">Rp<?= number_format(htmlspecialchars($isi['harga_sewa']), 0, ',', '.');?></td>
                            <td class="text-center align-middle">
                                <?php if($status == 'Tersedia') { ?>
                                    <span class="badge badge-available d-inline-flex"><i class="fas fa-check-circle me-1"></i> Tersedia</span>
                                <?php } elseif($status == 'Sedang Digunakan') { ?>
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge badge-used d-inline-flex mb-2">
                                            <i class="fas fa-clock me-1"></i> Sedang Digunakan
                                        </span>
                                        <?php if(!empty($isi['nama'])) { ?>
                                            <div class="customer-info-box">
                                                <div class="customer-info-header">
                                                    <i class="fas fa-user-circle me-1"></i> Detail Pelanggan
                                                </div>
                                                <div class="customer-info-content">
                                                    <div class="info-item">
                                                        <i class="fas fa-user me-2"></i>
                                                        <span class="info-label">Nama:</span>
                                                        <span class="info-value"><?= htmlspecialchars($isi['nama']); ?></span>
                                                    </div>
                                                    <div class="info-item">
                                                        <i class="fas fa-phone me-2"></i>
                                                        <span class="info-label">Telepon:</span>
                                                        <span class="info-value"><?= htmlspecialchars($isi['no_tlp']); ?></span>
                                                    </div>
                                                    <div class="info-item">
                                                        <i class="fas fa-calendar me-2"></i>
                                                        <span class="info-label">Periode:</span>
                                                        <span class="info-value"><?= date('d/m/Y', strtotime($isi['tanggal'])); ?> - <?= date('d/m/Y', strtotime($isi['tanggal_akhir'])); ?></span>
                                                    </div>
                                                    <div class="info-item">
                                                        <i class="fas fa-hashtag me-2"></i>
                                                        <span class="info-label">Booking:</span>
                                                        <span class="info-value"><?= htmlspecialchars($isi['kode_booking']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <span class="badge badge-close d-inline-flex"><i class="fas fa-times-circle me-1"></i> Close</span>
                                <?php } ?>
                            </td>
                            <td class="text-center align-middle"><?= htmlspecialchars($isi['deskripsi']);?></td>
                            <td class="text-center align-middle">
                                <a class="btn btn-warning btn-sm" href="edit.php?id=<?= $isi['id_supir'];?>" title="Edit Supir">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn btn-danger btn-sm" href="#" onclick="hapus(event, '<?= $isi['id_supir']; ?>', '<?= $isi['foto']; ?>')" title="Hapus Supir">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                               
                            </td>
                        </tr>
                        <?php $no++; }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Customer Details -->
<div class="modal fade" id="customerDetailsModal" tabindex="-1" aria-labelledby="customerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerDetailsModalLabel">Detail Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> <span id="modalNama"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>No. Telepon:</strong> <span id="modalNoTlp"></span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tanggal Sewa:</strong> <span id="modalTanggal"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tanggal Akhir:</strong> <span id="modalTanggalAkhir"></span></p>
                    </div>
                </div>
                <p><strong>Kode Booking:</strong> <span id="modalKodeBooking"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const pesan = urlParams.get('pesan');
        if (pesan) {
            let title, text, icon;
            switch (pesan) {
                case 'sukses-edit':
                    title = 'Sukses!';
                    text = 'Data supir berhasil diubah.';
                    icon = 'success';
                    break;
                case 'gagal-upload':
                    title = 'Gagal!';
                    text = 'Gagal mengunggah foto. Pastikan format dan ukuran file sesuai.';
                    icon = 'error';
                    break;
                case 'gagal-edit':
                    title = 'Gagal!';
                    text = 'Data supir gagal diubah.';
                    icon = 'error';
                    break;
                case 'sukses-tambah':
                    title = 'Sukses!';
                    text = 'Data supir berhasil ditambahkan.';
                    icon = 'success';
                    break;
                case 'gagal-tambah':
                    title = 'Gagal!';
                    text = 'Data supir gagal ditambahkan.';
                    icon = 'error';
                    break;
                case 'sukses-hapus':
                    title = 'Sukses!';
                    text = 'Data supir berhasil dihapus.';
                    icon = 'success';
                    break;
                case 'gagal-hapus':
                    title = 'Gagal!';
                    text = 'Data supir gagal dihapus.';
                    icon = 'error';
                    break;
                default:
                    return;
            }
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonText: 'OK'
            }).then(() => {
                history.replaceState(null, '', window.location.pathname);
            });
        }
    });

    function hapus(event, id, foto) {
        event.preventDefault();
        const href = `proses.php?aksi=hapus&id=${id}&foto=${foto}`;
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data supir akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    }


</script>

<?php include '../footer.php';?>
