-- TAHAP 4: TRIGGERS DAN OTOMATISASI

-- Tabel Audit Log

DROP TABLE IF EXISTS audit_log;

CREATE TABLE audit_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    nama_tabel VARCHAR(50),
    aksi VARCHAR(20),
    keterangan VARCHAR(255),
    waktu_log TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Trigger 1: Audit Insert Pasien

DROP TRIGGER IF EXISTS trg_pasien_insert;

DELIMITER $$

CREATE TRIGGER trg_pasien_insert
AFTER INSERT ON pasien
FOR EACH ROW
BEGIN

    INSERT INTO audit_log(
        nama_tabel,
        aksi,
        keterangan
    )
    VALUES(
        'pasien',
        'INSERT',
        CONCAT('Pasien baru : ', NEW.nama)
    );

END$$

DELIMITER ;


-- Trigger 2: Audit Update Rekam Medis

DROP TRIGGER IF EXISTS trg_rekam_medis_update;

DELIMITER $$

CREATE TRIGGER trg_rekam_medis_update
AFTER UPDATE ON rekam_medis
FOR EACH ROW
BEGIN

    INSERT INTO audit_log(
        nama_tabel,
        aksi,
        keterangan
    )
    VALUES(
        'rekam_medis',
        'UPDATE',
        CONCAT('Record ID ', NEW.record_id, ' diperbarui')
    );

END$$

DELIMITER ;


-- Trigger 3: Audit Delete Obat

DROP TRIGGER IF EXISTS trg_obat_delete;

DELIMITER $$

CREATE TRIGGER trg_obat_delete
AFTER DELETE ON obat
FOR EACH ROW
BEGIN

    INSERT INTO audit_log(
        nama_tabel,
        aksi,
        keterangan
    )
    VALUES(
        'obat',
        'DELETE',
        CONCAT('Obat dihapus : ', OLD.nama_obat)
    );

END$$

DELIMITER ;


-- Trigger 4: Validasi NIK Pasien

DROP TRIGGER IF EXISTS trg_validasi_nik;

DELIMITER $$

CREATE TRIGGER trg_validasi_nik
BEFORE INSERT ON pasien
FOR EACH ROW
BEGIN

    IF LENGTH(NEW.nik) <> 16 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'NIK harus 16 digit';
    END IF;

END$$

DELIMITER ;


-- Trigger 5: Validasi Diskon Tagihan

DROP TRIGGER IF EXISTS trg_validasi_tagihan;

DELIMITER $$

CREATE TRIGGER trg_validasi_tagihan
BEFORE INSERT ON tagihan
FOR EACH ROW
BEGIN

    IF NEW.diskon > NEW.total_tagihan THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Diskon tidak boleh melebihi total tagihan';
    END IF;

END$$

DELIMITER ;


-- Trigger 6: Auto Update Total Tagihan

DROP TRIGGER IF EXISTS trg_update_total_tagihan;

DELIMITER $$

CREATE TRIGGER trg_update_total_tagihan
AFTER INSERT ON detail_tagihan
FOR EACH ROW
BEGIN

    UPDATE tagihan
    SET total_tagihan =
        IFNULL(total_tagihan,0)
        + IFNULL(NEW.harga_satuan,0)
    WHERE tagihan_id = NEW.tagihan_id;

END$$

DELIMITER ;


-- Trigger 7: Auto Generate Tanggal Rekam Medis

DROP TRIGGER IF EXISTS trg_generate_rekam_medis;

DELIMITER $$

CREATE TRIGGER trg_generate_rekam_medis
BEFORE INSERT ON rekam_medis
FOR EACH ROW
BEGIN

    IF NEW.tanggal_catatan IS NULL THEN
        SET NEW.tanggal_catatan = CURDATE();
    END IF;

END$$

DELIMITER ;


-- Trigger 8: Validasi Harga Beli Obat

DROP TRIGGER IF EXISTS trg_validasi_harga_beli;

DELIMITER $$

CREATE TRIGGER trg_validasi_harga_beli
BEFORE INSERT ON batch_obat
FOR EACH ROW
BEGIN

    IF NEW.harga_beli <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Harga beli harus lebih besar dari 0';
    END IF;

END$$

DELIMITER ;


