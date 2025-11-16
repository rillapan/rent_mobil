# TODO: Add Reject Refund Feature

## Steps to Complete

1. **Update admin/refund/index.php** ✅
   - Add a "Reject Refund" button next to the "Proses Refund" button for pending requests. ✅
   - Add a new modal for rejection, allowing admin to enter a rejection reason. ✅
   - Ensure the modal includes fields for refund_id, kode_booking, and rejection reason. ✅

2. **Update admin/refund/proses.php** ✅
   - Add handling for 'reject_refund' action in the GET parameter. ✅
   - Update the refund_requests table: set status to 'Refund Ditolak', store rejection reason in 'alasan_penolakan' field, set processed_at and admin_id. ✅
   - Update the booking table: set konfirmasi_pembayaran to 'Refund Ditolak'. ✅
   - Send a notification to the customer with rejection details. ✅

3. **Verify and Test** ⏳
   - Check that rejected refunds appear in the "Riwayat Refund Selesai" section.
   - Ensure notifications are sent correctly.
   - Test the UI for proper modal behavior and form submission.

## Dependent Files
- admin/refund/index.php ✅
- admin/refund/proses.php ✅

## Followup Steps
- After implementation, test the feature by creating a test refund request and rejecting it.
- If needed, update any related files or add logging.
