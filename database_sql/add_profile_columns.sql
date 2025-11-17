ALTER TABLE `login`
ADD `email` VARCHAR(255) NULL AFTER `no_hp`,
ADD `foto` VARCHAR(255) NULL DEFAULT 'default.jpg' AFTER `email`,
ADD `provinsi` VARCHAR(255) NULL AFTER `foto`,
ADD `kota_kabupaten` VARCHAR(255) NULL AFTER `provinsi`,
ADD `jenis_kelamin` VARCHAR(50) NULL AFTER `kota_kabupaten`;
