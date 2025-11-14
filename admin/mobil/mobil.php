<?php

    require '../../koneksi/koneksi.php';
    $title_web = 'Daftar Mobil';
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
    .badge-unavailable {
        color: white !important;
        text-align: center;
        display: inline-flex; /* Use flexbox for better vertical alignment of icon and text */
        align-items: center;
        justify-content: center;
        padding: .5em .75em; /* Slightly more padding */
        font-size: 95%; /* Slightly larger font */
        border-radius: .3rem; /* Slightly more rounded corners */
    }
    .badge-available {
        background-color: #28a745; /* Bootstrap success green */
    }

    .badge-unavailable {
        background-color: #dc3545; /* Bootstrap danger red */
    }
    .badge i {
        margin-right: .3em; /* Space between icon and text */
    }
</style>
<div class="container my-4">
    <div class="card shadow-lg">
        <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-car-side me-2"></i>Daftar Mobil
            </h5>
            <a href="tambah.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-plus me-1"></i>Tambah Mobil
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">No.</th>
                            <th scope="col" class="text-center">Gambar</th>
                            <th scope="col" class="text-center">Merk</th>
                            <th scope="col" class="text-center">No. Plat</th>
                            <th scope="col" class="text-center">Harga</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Deskripsi</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT * FROM mobil ORDER BY id_mobil DESC";
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
                        ?>
                        <tr>
                            <td class="text-center align-middle"><?= $no;?></td>
                            <td class="text-center align-middle">
                                <img src="../../assets/image/<?= htmlspecialchars($isi['gambar']);?>" class="img-thumbnail" style="width: 120px;" alt="Gambar Mobil">
                            </td>
                            <td class="text-center align-middle"><?= htmlspecialchars($isi['merk']);?></td>
                            <td class="text-center align-middle"><?= htmlspecialchars($isi['no_plat']);?></td>
                            <td class="text-center align-middle">Rp<?= number_format(htmlspecialchars($isi['harga']), 0, ',', '.');?></td>
                            <td class="text-center align-middle">
                                <?php if($isi['status'] == 'Tersedia') { ?>
                                    <span class="badge badge-available d-inline-flex"><i class="fas fa-check-circle me-1"></i> Tersedia</span>
                                <?php } else { ?>
                                    <span class="badge badge-unavailable d-inline-flex"><i class="fas fa-times-circle me-1"></i> Tidak Tersedia</span>
                                <?php } ?>
                            </td>
                            <td class="text-center align-middle"><?= htmlspecialchars($isi['deskripsi']);?></td>
                            <td class="text-center align-middle">
                                <a class="btn btn-warning btn-sm" href="edit.php?id=<?= $isi['id_mobil'];?>" title="Edit Mobil">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn btn-danger btn-sm" href="#" onclick="hapus(event, '<?= $isi['id_mobil']; ?>', '<?= $isi['gambar']; ?>')" title="Hapus Mobil">
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
                    text = 'Data mobil berhasil diubah.';
                    icon = 'success';
                    break;
                case 'gagal-upload':
                    title = 'Gagal!';
                    text = 'Gagal mengunggah gambar. Pastikan format dan ukuran file sesuai.';
                    icon = 'error';
                    break;
                case 'gagal-edit':
                    title = 'Gagal!';
                    text = 'Data mobil gagal diubah.';
                    icon = 'error';
                    break;
                case 'sukses-tambah':
                    title = 'Sukses!';
                    text = 'Data mobil berhasil ditambahkan.';
                    icon = 'success';
                    break;
                case 'gagal-tambah':
                    title = 'Gagal!';
                    text = 'Data mobil gagal ditambahkan.';
                    icon = 'error';
                    break;
                case 'sukses-hapus':
                    title = 'Sukses!';
                    text = 'Data mobil berhasil dihapus.';
                    icon = 'success';
                    break;
                case 'gagal-hapus':
                    title = 'Gagal!';
                    text = 'Data mobil tidak bisa dihapus karena sedang dipinjam.';
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

    function hapus(event, id, gambar) {
        event.preventDefault();
        const href = `proses.php?aksi=hapus&id=${id}&gambar=${gambar}`;
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data mobil akan dihapus secara permanen!",
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
