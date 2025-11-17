-- Create payment_methods table
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_type` enum('bank','ewallet') NOT NULL,
  `provider_name` varchar(100) NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add payment_method_id and payment_proof to booking table
ALTER TABLE `booking`
ADD COLUMN `payment_method_id` INT NULL AFTER `total_harga`,
ADD COLUMN `payment_proof` VARCHAR(255) NULL AFTER `payment_method_id`,
ADD COLUMN `payment_status` ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending' AFTER `payment_proof`,
ADD CONSTRAINT `fk_booking_payment_method` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL;
