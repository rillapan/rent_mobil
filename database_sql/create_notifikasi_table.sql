CREATE TABLE IF NOT EXISTS notifikasi (
    id INT(11) NOT NULL AUTO_INCREMENT,
    id_login INT(11) NOT NULL,
    id_booking INT(11) NOT NULL,
    pesan TEXT NOT NULL,
    status_baca TINYINT(1) DEFAULT 0, -- 0 = belum dibaca, 1 = sudah dibaca
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);