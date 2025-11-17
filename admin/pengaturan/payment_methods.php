<?php
    session_start();
    require '../../koneksi/koneksi.php';

    // Handle form submission BEFORE including header.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action === 'add' || $action === 'edit') {
                $payment_type = mysqli_real_escape_string($koneksi, $_POST['payment_type']);
                $provider_name = mysqli_real_escape_string($koneksi, $_POST['provider_name']);
                $account_number = mysqli_real_escape_string($koneksi, $_POST['account_number']);
                $account_name = mysqli_real_escape_string($koneksi, $_POST['account_name']);
                $is_active = isset($_POST['is_active']) ? 1 : 0;

                if ($action === 'add') {
                    $query = "INSERT INTO payment_methods (payment_type, provider_name, account_number, account_name, is_active)
                              VALUES ('$payment_type', '$provider_name', '$account_number', '$account_name', $is_active)";
                    $message = "Metode pembayaran berhasil ditambahkan";
                } else {
                    $id = (int)$_POST['id'];
                    $query = "UPDATE payment_methods SET
                              payment_type = '$payment_type',
                              provider_name = '$provider_name',
                              account_number = '$account_number',
                              account_name = '$account_name',
                              is_active = $is_active
                              WHERE id = $id";
                    $message = "Metode pembayaran berhasil diperbarui";
                }

                if (mysqli_query($koneksi, $query)) {
                    $_SESSION['success_message'] = $message;
                } else {
                    $_SESSION['error_message'] = "Terjadi kesalahan: " . mysqli_error($koneksi);
                }
            } elseif ($action === 'delete') {
                $id = (int)$_POST['id'];
                $query = "DELETE FROM payment_methods WHERE id = $id";

                if (mysqli_query($koneksi, $query)) {
                    $_SESSION['success_message'] = "Metode pembayaran berhasil dihapus";
                } else {
                    $_SESSION['error_message'] = "Gagal menghapus: " . mysqli_error($koneksi);
                }
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    $title_web = 'Kelola Metode Pembayaran';
    $url = '../../';
    include '../header.php';
    
    // Get all payment methods
    $payment_methods = mysqli_query($koneksi, "SELECT * FROM payment_methods ORDER BY payment_type, provider_name");
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Kelola Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?= $_SESSION['success_message'] ?>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?= $_SESSION['error_message'] ?>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
                
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add">
                            <i class="fas fa-plus me-2"></i>Tambah Metode Pembayaran
                        </button>
                    </div>
                        <table id="paymentMethodsTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tipe</th>
                                    <th>Nama Provider</th>
                                    <th>Nomor Rekening</th>
                                    <th>Atas Nama</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($method = mysqli_fetch_assoc($payment_methods)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= strtoupper($method['payment_type']) == 'BANK' ? 'Bank' : 'E-Wallet' ?></td>
                                        <td><?= htmlspecialchars($method['provider_name']) ?></td>
                                        <td><?= htmlspecialchars($method['account_number']) ?></td>
                                        <td><?= htmlspecialchars($method['account_name']) ?></td>
                                        <td>
                                            <?php if ($method['is_active']): ?>
                                                <span class="badge bg-info text-white">
                                                    <i class="fas fa-check-circle me-1"></i>Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger text-white">
                                                    <i class="fas fa-times-circle me-1"></i>Nonaktif
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning btn-edit" 
                                                    data-id="<?= $method['id'] ?>"
                                                    data-payment-type="<?= $method['payment_type'] ?>"
                                                    data-provider-name="<?= htmlspecialchars($method['provider_name']) ?>"
                                                    data-account-number="<?= htmlspecialchars($method['account_number']) ?>"
                                                    data-account-name="<?= htmlspecialchars($method['account_name']) ?>"
                                                    data-is-active="<?= $method['is_active'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="post" style="display: inline-block;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $method['id'] ?>">
                                                <button type="button" class="btn btn-sm btn-danger btn-delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>

<!-- Add/Edit Modal -->
<div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="paymentMethodForm">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="methodId">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLabel">Tambah Metode Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_type" class="form-label">Tipe Pembayaran</label>
                        <select class="form-select" id="payment_type" name="payment_type" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="provider_name" class="form-label">Nama Bank/E-Wallet</label>
                        <input type="text" class="form-control" id="provider_name" name="provider_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="account_number" class="form-label">Nomor Rekening/ID</label>
                        <input type="text" class="form-control" id="account_number" name="account_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="account_name" class="form-label">Atas Nama</label>
                        <input type="text" class="form-control" id="account_name" name="account_name" required>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DataTables  & Plugins -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $("#paymentMethodsTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        }
    });

    // Handle edit button click
    $('.btn-edit').click(function() {
        const id = $(this).data('id');
        const paymentType = $(this).data('payment-type');
        const providerName = $(this).data('provider-name');
        const accountNumber = $(this).data('account-number');
        const accountName = $(this).data('account-name');
        const isActive = $(this).data('is-active');

        $('#modalAddLabel').text('Edit Metode Pembayaran');
        $('#formAction').val('edit');
        $('#methodId').val(id);
        $('#payment_type').val(paymentType);
        $('#provider_name').val(providerName);
        $('#account_number').val(accountNumber);
        $('#account_name').val(accountName);
        $('#is_active').prop('checked', isActive == 1);

        $('#modal-add').modal('show');
    });

    // Handle add button click
    $('button[data-bs-target="#modal-add"]').click(function() {
        $('#modalAddLabel').text('Tambah Metode Pembayaran');
        $('#formAction').val('add');
        $('#paymentMethodForm')[0].reset();
        $('#methodId').val('');
    });

    // Handle delete button click
    $('.btn-delete').click(function() {
        const form = $(this).closest('form');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Show success/error messages with SweetAlert2
    <?php if (isset($_SESSION['success_message'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '<?= addslashes($_SESSION['success_message']) ?>',
            timer: 3000,
            showConfirmButton: false
        });
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= addslashes($_SESSION['error_message']) ?>',
            timer: 5000,
            showConfirmButton: true
        });
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
});
</script>
