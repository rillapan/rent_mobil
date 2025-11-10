<?php
$current_page = basename($_SERVER['PHP_SELF']);
$steps = [
    ['file' => 'booking.php', 'title' => 'Isi Data Diri', 'icon' => 'fas fa-user'],
    ['file' => 'bayar.php', 'title' => 'Konfirmasi Pembayaran', 'icon' => 'fas fa-credit-card'],
    ['file' => 'konfirmasi.php', 'title' => 'Isi Informasi Pembayaran', 'icon' => 'fas fa-file-invoice-dollar']
];

$current_step = 0;
foreach ($steps as $index => $step) {
    if ($step['file'] === $current_page) {
        $current_step = $index + 1;
        break;
    }
}
?>

<style>
/* Style yang sudah diperbaiki agar transparan */
.checkout-progress {
    margin-bottom: 30px;
    background: transparent;
    border-radius: 0;
    padding: 20px 0;
    box-shadow: none;
}

.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 25px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e9ecef;
    z-index: 1;
}

.progress-steps::after {
    content: '';
    position: absolute;
    top: 25px;
    left: 0;
    height: 2px;
    background: var(--secondary);
    z-index: 2;
    /* Perhitungan lebar: (Total langkah - 1) * (indeks langkah saat ini - 1) */
    width: calc((100% / <?php echo count($steps) - 1; ?>) * (<?php echo $current_step - 1; ?>));
    transition: width 0.3s ease;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 3;
    flex: 1;
}

/* --- Perbaikan/Tambahan CSS untuk Fungsionalitas Klik --- */
/* Menonaktifkan interaksi klik untuk langkah aktif dan yang akan datang */
.step.active, .step.pending {
    pointer-events: none;
}

/* Styling untuk tautan agar terlihat seperti tombol yang bisa diklik */
.step.completed a {
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    display: flex; /* Mengatur flex untuk menampung circle dan title */
    flex-direction: column;
    align-items: center;
}

.step-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
    margin-bottom: 8px;
    transition: all 0.3s ease;
    border: 3px solid #e9ecef;
}

.step.completed .step-circle {
    background: var(--secondary);
    border-color: var(--secondary);
}

.step.active .step-circle {
    background: var(--primary);
    border-color: var(--primary);
    animation: pulse 2s infinite;
}

.step.pending .step-circle {
    background: white;
    color: #6c757d;
}

.step-title {
    font-size: 12px;
    font-weight: 500;
    text-align: center;
    transition: color 0.3s ease;
}

.step.completed .step-title {
    color: var(--secondary);
}

.step.active .step-title {
    color: var(--primary);
    font-weight: 600;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

@media (max-width: 768px) {
    .step-title {
        font-size: 10px;
    }
    .step-circle {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }
}
</style>

<div class="checkout-progress">
    <div class="progress-steps">
        <?php foreach ($steps as $index => $step): ?>
            <?php
            $step_number = $index + 1;
            $class = 'pending';
            $is_clickable = false; // Default: tidak dapat diklik
            
            if ($step_number < $current_step) {
                $class = 'completed';
                $is_clickable = true; // Langkah yang sudah dilewati (Completed) dapat diklik
            } elseif ($step_number == $current_step) {
                $class = 'active';
            }
            ?>
            
            <?php if ($is_clickable): ?>
                <a href="<?php echo $step['file']; ?>" class="step <?php echo $class; ?>" onclick="preserveFormData()">
            <?php else: ?>
                <div class="step <?php echo $class; ?>">
            <?php endif; ?>

                <div class="step-circle">
                    <i class="<?php echo $step['icon']; ?>"></i>
                </div>
                <div class="step-title"><?php echo $step['title']; ?></div>

            <?php if ($is_clickable): ?>
                </a>
            <?php else: ?>
                </div>
            <?php endif; ?>
            
        <?php endforeach; ?>
    </div>
</div>

<script>
function preserveFormData() {
    const currentPage = '<?php echo basename($_SERVER['PHP_SELF']); ?>';
    const formData = {};

    // Collect form data based on current page
    if (currentPage === 'booking.php') {
        const inputs = document.querySelectorAll('input[name], select[name], textarea[name]');
        inputs.forEach(input => {
            if (input.name && input.value && input.value.trim() !== '') {
                formData[input.name] = input.value;
            }
        });
        // Store selected supir data
        const selectedSupir = sessionStorage.getItem('selectedSupir');
        if (selectedSupir) {
            formData.selectedSupir = selectedSupir;
        }
    }

    // Store in sessionStorage with page-specific key
    if (Object.keys(formData).length > 0) {
        sessionStorage.setItem('formData_' + currentPage, JSON.stringify(formData));
    }
}

function restoreFormData() {
    const currentPage = '<?php echo basename($_SERVER['PHP_SELF']); ?>';
    const storedData = sessionStorage.getItem('formData_' + currentPage);

    if (storedData) {
        const formData = JSON.parse(storedData);

        // Restore form inputs
        Object.keys(formData).forEach(name => {
            if (name !== 'selectedSupir') {
                const input = document.querySelector(`[name="${name}"]`);
                if (input) {
                    input.value = formData[name];
                }
            }
        });

        // Restore supir selection if exists
        if (formData.selectedSupir) {
            sessionStorage.setItem('selectedSupir', formData.selectedSupir);
            const supir = JSON.parse(formData.selectedSupir);
            const idSupirEl = document.getElementById('id_supir');
            const hargaSupirEl = document.getElementById('harga_supir');
            const namaSupirEl = document.getElementById('nama_supir');
            const hargaSupirDisplayEl = document.getElementById('harga_supir_display');
            const supirInfoEl = document.getElementById('supir_info');
            const hapusSupirEl = document.getElementById('hapus_supir');

            if (idSupirEl) idSupirEl.value = supir.id;
            if (hargaSupirEl) hargaSupirEl.value = supir.harga;
            if (namaSupirEl) namaSupirEl.textContent = supir.nama;
            if (hargaSupirDisplayEl) hargaSupirDisplayEl.textContent = supir.harga.toLocaleString();
            if (supirInfoEl) supirInfoEl.style.display = 'block';
            if (hapusSupirEl) hapusSupirEl.style.display = 'inline-block';
        }

        // Trigger total calculation if on booking page
        if (currentPage === 'booking.php' && typeof updateTotalHarga === 'function') {
            updateTotalHarga();
        }
    }
}

// Restore data when page loads
document.addEventListener('DOMContentLoaded', function() {
    restoreFormData();
});
</script>
