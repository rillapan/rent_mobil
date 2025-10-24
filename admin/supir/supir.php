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
    }
    .badge-close {
        background-color: #dc3545;
    }
    .badge i {
        margin-right: .3em;
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
                            $sql = "SELECT * FROM supir ORDER BY id_supir DESC";
                            $row = $koneksi->prepare($sql);
                            $row->execute();
                            $hasil = $row->fetchAll();
                            $no = 1;

                            foreach($hasil as $isi)
                            {
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
                                <?php if($isi['status'] == 'Tersedia') { ?>
                                    <span class="badge badge-available d-inline-flex"><i class="fas fa-check-circle me-1"></i> Tersedia</span>
                                <?php } elseif($isi['status'] == 'Sedang Digunakan') { ?>
                                    <span class="badge badge-used d-inline-flex"><i class="fas fa-clock me-1"></i> Sedang Digunakan</span>
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
