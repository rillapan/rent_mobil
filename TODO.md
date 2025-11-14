# TODO: Implementasi Otomatis Update Status Supir Ketika Sewa Selesai

## Completed Tasks

- [x] Tambahkan opsi 'Dibatalkan' di filter status sewa di admin/booking/booking.php
- [x] Update query filter untuk menangani kondisi dibatalkan (konfirmasi_pembayaran = 'Refund Diterima / Uang Dikembalikan')
- [x] Tambahkan statistik untuk status dibatalkan di query stats_sewa
- [x] Tambahkan card statistik untuk status Dibatalkan
- [x] Tambahkan badge dan logika tampilan untuk status dibatalkan di tabel
- [x] Test perubahan filter dan tampilan (syntax check passed)

## Pending Tasks

- [x] Tambahkan logika otomatis update status supir menjadi 'Tersedia' ketika status sewa 'Selesai'
- [x] Test automasi update supir (syntax check passed)
- [x] Pastikan tidak ada konflik dengan status supir lainnya (sudah dicek, hanya update ketika status 'Sedang Digunakan' dan booking sudah selesai)
