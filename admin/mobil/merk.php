<?php
require '../../koneksi/koneksi.php';
$title_web = 'Kelola Merk Mobil';
include '../header.php';
?>
<style>
    :root {
        --primary: #1A237E;
        --primary-dark: #1A3CC9;
        --secondary: #FF6B35;
        --light: #F8F9FA;
        --dark: #212529;
        --gray: #6C757D;
    }

    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 1rem;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.5rem 2rem;
        border-bottom: none;
    }

    .card-body {
        padding: 2rem;
    }

    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
    }

    .btn-secondary:hover {
        background-color: #e55e2d;
        border-color: #e55e2d;
    }

    .table th {
        background-color: var(--primary);
        color: white;
        border: none;
    }

    .table td {
        vertical-align: middle;
    }

    .badge {
        font-size: 0.875rem;
    }
</style>

<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-tags me-2"></i>Kelola Merk Mobil
            </h5>
            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahMerkModal">
                <i class="fas fa-plus me-1"></i>Tambah Merk
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">No.</th>
                            <th scope="col" class="text-center">Merk Mobil</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM tbl_merk ORDER BY merk ASC";
                        $row = mysqli_prepare($koneksi, $sql);
                        mysqli_stmt_execute($row);
                        $result_stmt = mysqli_stmt_get_result($row);
                        $hasil = [];
                        while ($row_data = mysqli_fetch_assoc($result_stmt)) {
                            $hasil[] = $row_data;
                        }
                        mysqli_stmt_close($row);
                        $no = 1;

                        foreach($hasil as $isi) {
                        ?>
                        <tr>
                            <td class="text-center align-middle"><?= $no;?></td>
                            <td class="text-center align-middle"><?= htmlspecialchars($isi['merk']);?></td>
                            <td class="text-center align-middle">
                                <button class="btn btn-warning btn-sm" onclick="editMerk(<?= $isi['id']; ?>, '<?= htmlspecialchars($isi['merk']); ?>')">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="hapusMerk(<?= $isi['id']; ?>, '<?= htmlspecialchars($isi['merk']); ?>')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        <?php $no++; }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Merk -->
<div class="modal fade" id="tambahMerkModal" tabindex="-1" aria-labelledby="tambahMerkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahMerkModalLabel">Tambah Merk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="tambahMerkForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="merk" class="form-label">Nama Merk</label>
                        <input type="text" class="form-control" id="merk" name="merk" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Merk -->
<div class="modal fade" id="editMerkModal" tabindex="-1" aria-labelledby="editMerkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMerkModalLabel">Edit Merk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMerkForm">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_merk" class="form-label">Nama Merk</label>
                        <input type="text" class="form-control" id="edit_merk" name="merk" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tambah Merk
    document.getElementById('tambahMerkForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('merk_proses.php?aksi=tambah', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Sukses!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan data', 'error');
        });
    });

    // Edit Merk
    document.getElementById('editMerkForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('merk_proses.php?aksi=edit', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Sukses!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error!', 'Terjadi kesalahan saat mengupdate data', 'error');
        });
    });
});

function editMerk(id, merk) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_merk').value = merk;
    new bootstrap.Modal(document.getElementById('editMerkModal')).show();
}

function hapusMerk(id, merk) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Merk "${merk}" akan dihapus secara permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`merk_proses.php?aksi=hapus&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sukses!', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data', 'error');
            });
        }
    });
}
</script>

<?php include '../footer.php';?>
