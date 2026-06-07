-- TAHAP 3: STORED PROGRAMS (PROCEDURES & FUNCTIONS)

USE praktik_dokter;

DROP PROCEDURE IF EXISTS sp_transaksi_kunjungan;

DELIMITER $$

CREATE PROCEDURE sp_transaksi_kunjungan(
    IN p_patient_id INT,
    IN p_doctor_id INT,
    IN p_jenis_layanan VARCHAR(50),
    OUT p_visit_id INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_visit_id = -1;
    END;

    START TRANSACTION;

    INSERT INTO kunjungan(
        patient_id,
        doctor_id,
        tgl_kunjungan,
        jenis_layanan,
        antrian_no,
        waktu_datang,
        status
    )
    VALUES(
        p_patient_id,
        p_doctor_id,
        CURDATE(),
        p_jenis_layanan,
        FLOOR(RAND()*100)+1,
        NOW(),
        'Menunggu'
    );

    SET p_visit_id = LAST_INSERT_ID();

    COMMIT;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_buat_resep;

DELIMITER $$

CREATE PROCEDURE sp_buat_resep(
    IN p_record_id INT,
    IN p_doctor_id INT,
    IN p_catatan VARCHAR(255)
)
BEGIN

    INSERT INTO resep(
        record_id,
        doctor_id,
        tanggal_resep,
        catatan_dokter,
        status_resep
    )
    VALUES(
        p_record_id,
        p_doctor_id,
        CURDATE(),
        p_catatan,
        'Aktif'
    );

END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_hitung_tagihan;

DELIMITER $$

CREATE PROCEDURE sp_hitung_tagihan(
    IN p_visit_id INT,
    OUT p_total_tagihan DECIMAL(12,2)
)
BEGIN

    DECLARE v_jumlah_item INT;

    SELECT COUNT(*)
    INTO v_jumlah_item
    FROM detail_tagihan dt
    INNER JOIN tagihan t
        ON dt.tagihan_id = t.tagihan_id
    WHERE t.visit_id = p_visit_id;

    SET p_total_tagihan = v_jumlah_item * 50000;

END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_generate_report;

DELIMITER $$

CREATE PROCEDURE sp_generate_report(
    IN p_periode_mulai DATE,
    IN p_periode_akhir DATE
)
BEGIN

    INSERT INTO report_agregasi(
        periode_mulai,
        periode_akhir,
        jenis_laporan,
        tanggal_generate,
        keterangan
    )
    VALUES(
        p_periode_mulai,
        p_periode_akhir,
        'Laporan Kunjungan',
        NOW(),
        'Generated otomatis melalui stored procedure'
    );

END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_tambah_stok_obat;

DELIMITER $$

CREATE PROCEDURE sp_tambah_stok_obat(
    IN p_obat_id INT,
    IN p_gr_id INT,
    IN p_lokasi_id INT,
    IN p_expiry_date DATE,
    IN p_harga_beli DECIMAL(12,2),
    IN p_lokasi_rak VARCHAR(50)
)
BEGIN

    INSERT INTO batch_obat(
        obat_id,
        gr_id,
        lokasi_id,
        expiry_date,
        harga_beli,
        lokasi_rak
    )
    VALUES(
        p_obat_id,
        p_gr_id,
        p_lokasi_id,
        p_expiry_date,
        p_harga_beli,
        p_lokasi_rak
    );

END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_penerimaan_barang;

DELIMITER $$

CREATE PROCEDURE sp_penerimaan_barang(
    IN p_po_id INT,
    IN p_faktur_no VARCHAR(50)
)
BEGIN

    INSERT INTO penerimaan_barang(
        po_id,
        faktur_no
    )
    VALUES(
        p_po_id,
        p_faktur_no
    );

END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_update_status_kunjungan;

DELIMITER $$

CREATE PROCEDURE sp_update_status_kunjungan(
    IN p_visit_id INT,
    IN p_status VARCHAR(50)
)
BEGIN

    UPDATE kunjungan
    SET status = p_status
    WHERE visit_id = p_visit_id;

END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_cari_pasien;

DELIMITER $$

CREATE PROCEDURE sp_cari_pasien(
    INOUT p_nama_pasien VARCHAR(100)
)
BEGIN

    SELECT nama
    INTO p_nama_pasien
    FROM pasien
    WHERE nama = p_nama_pasien
    LIMIT 1;

END$$

DELIMITER ;





DROP FUNCTION IF EXISTS fn_hitung_usia;

DELIMITER $$

CREATE FUNCTION fn_hitung_usia(
    p_patient_id INT
)
RETURNS INT
DETERMINISTIC
BEGIN

    DECLARE v_usia INT;

    SELECT
        TIMESTAMPDIFF(
            YEAR,
            tgl_lahir,
            CURDATE()
        )
    INTO v_usia
    FROM pasien
    WHERE patient_id = p_patient_id;

    RETURN v_usia;

END$$

DELIMITER ;

DROP FUNCTION IF EXISTS fn_total_tagihan;

DELIMITER $$

CREATE FUNCTION fn_total_tagihan(
    p_tagihan_id INT
)
RETURNS DECIMAL(12,2)
DETERMINISTIC
BEGIN

    DECLARE v_total DECIMAL(12,2);

    SELECT
        IFNULL(total_tagihan,0) - IFNULL(diskon,0)
    INTO v_total
    FROM tagihan
    WHERE tagihan_id = p_tagihan_id;

    RETURN v_total;

END$$

DELIMITER ;

DROP FUNCTION IF EXISTS fn_cek_stok_obat;

DELIMITER $$

CREATE FUNCTION fn_cek_stok_obat(
    p_obat_id INT
)
RETURNS VARCHAR(20)
DETERMINISTIC
BEGIN

    DECLARE v_jumlah INT;

    SELECT COUNT(*)
    INTO v_jumlah
    FROM batch_obat
    WHERE obat_id = p_obat_id;

    IF v_jumlah > 0 THEN
        RETURN 'Tersedia';
    ELSE
        RETURN 'Kosong';
    END IF;

END$$

DELIMITER ;

DROP FUNCTION IF EXISTS fn_validasi_nik;

DELIMITER $$

CREATE FUNCTION fn_validasi_nik(
    p_nik CHAR(16)
)
RETURNS VARCHAR(20)
DETERMINISTIC
BEGIN

    IF LENGTH(p_nik) = 16 THEN
        RETURN 'VALID';
    ELSE
        RETURN 'TIDAK VALID';
    END IF;

END$$

DELIMITER ;

DROP FUNCTION IF EXISTS fn_jumlah_kunjungan;

DELIMITER $$

CREATE FUNCTION fn_jumlah_kunjungan(
    p_patient_id INT
)
RETURNS INT
DETERMINISTIC
BEGIN

    DECLARE v_total INT;

    SELECT COUNT(*)
    INTO v_total
    FROM kunjungan
    WHERE patient_id = p_patient_id;

    RETURN v_total;

END$$

DELIMITER ;

-- Pengujian Parameter
-- OUT
SET @visit_id = 0;

CALL sp_transaksi_kunjungan(
    1,
    1,
    'Konsultasi',
    @visit_id
);

SELECT @visit_id;

-- INOUT
SET @nama = 'Budi Santoso';

CALL sp_cari_pasien(@nama);

SELECT @nama;

