-- TAHAP 1: PERANCANGAN DAN IMPLEMENTASI DATABASE

-- Setup Database
DROP DATABASE IF EXISTS praktik_dokter;

CREATE DATABASE praktik_dokter
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE praktik_dokter;


-- Drop Existing Tables
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS
laporan_external,
report_agregasi,
detail_tagihan,
tagihan,
transaksi_stok,
batch_obat,
penerimaan_barang,
purchase_order,
supplier,
dispensing,
detail_resep,
resep,
obat,
tindakan,
hasil_penunjang,
order_penunjang,
triage_vital,
rekam_medis,
kunjungan,
dokter,
pasien,
user,
role,
lokasi;

SET FOREIGN_KEY_CHECKS = 1;



-- TABEL MASTER
CREATE TABLE role (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    nama_role VARCHAR(50) NOT NULL UNIQUE,
    deskripsi VARCHAR(255)
);

CREATE TABLE user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    kontak VARCHAR(50),
    id_role INT NOT NULL,

    CONSTRAINT fk_user_role
        FOREIGN KEY (id_role)
        REFERENCES role(id_role)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE pasien (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    nik CHAR(16) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    tgl_lahir DATE NOT NULL,
    jenis_kelamin ENUM('L','P') NOT NULL,
    alamat TEXT,
    no_telepon VARCHAR(20),
    email VARCHAR(100),
    kontak_darurat VARCHAR(100),
    asuransi_id VARCHAR(50)
);

CREATE TABLE dokter (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    sip_no VARCHAR(50) NOT NULL UNIQUE,
    spesialisasi VARCHAR(100),
    jadwal_id INT
);

CREATE TABLE obat (
    obat_id INT AUTO_INCREMENT PRIMARY KEY,
    nama_obat VARCHAR(100) NOT NULL,
    bentuk_sediaan VARCHAR(50),
    satuan VARCHAR(30),
    kategori VARCHAR(50)
);

CREATE TABLE supplier (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(100) NOT NULL,
    alamat TEXT,
    kontak_supplier VARCHAR(100)
);

CREATE TABLE lokasi (
    lokasi_id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lokasi VARCHAR(100) NOT NULL,
    tipe_lokasi VARCHAR(50),
    deskripsi VARCHAR(255)
);



-- TABEL PELAYANAN MEDIS
CREATE TABLE kunjungan (
    visit_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,

    tgl_kunjungan DATE NOT NULL,
    jenis_layanan VARCHAR(50) NOT NULL,
    antrian_no INT NOT NULL,

    waktu_datang DATETIME,
    waktu_selesai DATETIME,

    status VARCHAR(30) NOT NULL,

    CONSTRAINT fk_kunjungan_pasien
        FOREIGN KEY (patient_id)
        REFERENCES pasien(patient_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_kunjungan_dokter
        FOREIGN KEY (doctor_id)
        REFERENCES dokter(doctor_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE rekam_medis (
    record_id INT AUTO_INCREMENT PRIMARY KEY,

    visit_id INT NOT NULL UNIQUE,

    anamnesa TEXT,
    pemeriksaan_fisik TEXT,
    catatan_klinis TEXT,

    riwayat_penyakit TEXT,
    alergi_obat_makanan TEXT,

    tanggal_catatan DATE,

    vital_summary TEXT,
    tinggi_badan DECIMAL(5,2),
    berat_badan DECIMAL(5,2),

    CONSTRAINT fk_rekam_kunjungan
        FOREIGN KEY (visit_id)
        REFERENCES kunjungan(visit_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE triage_vital (
    triage_id INT AUTO_INCREMENT PRIMARY KEY,

    record_id INT NOT NULL,

    tekanan_darah VARCHAR(20),
    nadi INT,
    suhu DECIMAL(4,1),
    spO2 DECIMAL(5,2),

    keluhan_utama TEXT,
    riwayat_alergi TEXT,
    riwayat_obat TEXT,

    CONSTRAINT fk_triage_rekam
        FOREIGN KEY (record_id)
        REFERENCES rekam_medis(record_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE order_penunjang (
    order_id INT AUTO_INCREMENT PRIMARY KEY,

    record_id INT NOT NULL,

    jenis_pemeriksaan VARCHAR(100) NOT NULL,
    tanggal_order DATE,
    status_order VARCHAR(30),

    CONSTRAINT fk_order_rekam
        FOREIGN KEY (record_id)
        REFERENCES rekam_medis(record_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE hasil_penunjang (
    hasil_id INT AUTO_INCREMENT PRIMARY KEY,

    order_id INT NOT NULL,

    tanggal_hasil DATE,
    hasil TEXT,
    satuan VARCHAR(30),

    CONSTRAINT fk_hasil_order
        FOREIGN KEY (order_id)
        REFERENCES order_penunjang(order_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE tindakan (
    tindakan_id INT AUTO_INCREMENT PRIMARY KEY,

    record_id INT NOT NULL,

    jenis_tindakan VARCHAR(100) NOT NULL,
    tanggal_tindakan DATE,
    keterangan TEXT,

    CONSTRAINT fk_tindakan_rekam
        FOREIGN KEY (record_id)
        REFERENCES rekam_medis(record_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);



-- TABEL FARMASI
CREATE TABLE resep (
    resep_id INT AUTO_INCREMENT PRIMARY KEY,

    record_id INT NOT NULL,
    doctor_id INT NOT NULL,

    tanggal_resep DATE NOT NULL,
    catatan_dokter TEXT,
    status_resep VARCHAR(30),

    CONSTRAINT fk_resep_rekam
        FOREIGN KEY (record_id)
        REFERENCES rekam_medis(record_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_resep_dokter
        FOREIGN KEY (doctor_id)
        REFERENCES dokter(doctor_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE detail_resep (
    detail_id INT AUTO_INCREMENT PRIMARY KEY,

    resep_id INT NOT NULL,
    obat_id INT NOT NULL,

    dosis VARCHAR(100),
    rute VARCHAR(50),
    frekuensi VARCHAR(50),
    durasi VARCHAR(50),

    jumlah INT NOT NULL,

    instruksi_khusus TEXT,

    CONSTRAINT fk_detail_resep
        FOREIGN KEY (resep_id)
        REFERENCES resep(resep_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_detail_obat
        FOREIGN KEY (obat_id)
        REFERENCES obat(obat_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE dispensing (
    dispensing_id INT AUTO_INCREMENT PRIMARY KEY,

    detail_id INT NOT NULL,
    obat_id INT NOT NULL,

    edukasi_pasien TEXT,
    serah_terima VARCHAR(100),

    petugas_id INT,

    CONSTRAINT fk_dispensing_detail
        FOREIGN KEY (detail_id)
        REFERENCES detail_resep(detail_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_dispensing_obat
        FOREIGN KEY (obat_id)
        REFERENCES obat(obat_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);



-- TABEL INVENTORY DAN MANAJEMEN STOK
CREATE TABLE purchase_order (
    po_id INT AUTO_INCREMENT PRIMARY KEY,

    supplier_id INT NOT NULL,

    tanggal_po DATE NOT NULL,
    status_po VARCHAR(30),
    total_po DECIMAL(15,2),

    CONSTRAINT fk_po_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES supplier(supplier_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE penerimaan_barang (
    gr_id INT AUTO_INCREMENT PRIMARY KEY,

    po_id INT NOT NULL,

    faktur_no VARCHAR(50) NOT NULL UNIQUE,

    CONSTRAINT fk_gr_po
        FOREIGN KEY (po_id)
        REFERENCES purchase_order(po_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE batch_obat (
    batch_id INT AUTO_INCREMENT PRIMARY KEY,

    obat_id INT NOT NULL,
    gr_id INT NOT NULL,
    lokasi_id INT NOT NULL,

    expiry_date DATE NOT NULL,
    harga_beli DECIMAL(15,2),

    lokasi_rak VARCHAR(50),

    CONSTRAINT fk_batch_obat
        FOREIGN KEY (obat_id)
        REFERENCES obat(obat_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_batch_gr
        FOREIGN KEY (gr_id)
        REFERENCES penerimaan_barang(gr_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_batch_lokasi
        FOREIGN KEY (lokasi_id)
        REFERENCES lokasi(lokasi_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE transaksi_stok (
    transaksi_stok_id INT AUTO_INCREMENT PRIMARY KEY,

    batch_id INT NOT NULL,

    tanggal DATETIME NOT NULL,

    jenis_transaksi VARCHAR(30) NOT NULL,

    jumlah INT NOT NULL,

    referensi VARCHAR(100),

    keterangan TEXT,

    CONSTRAINT fk_stok_batch
        FOREIGN KEY (batch_id)
        REFERENCES batch_obat(batch_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);



-- TABEL KEUANGAN
CREATE TABLE tagihan (
    tagihan_id INT AUTO_INCREMENT PRIMARY KEY,

    visit_id INT NOT NULL,

    tanggal_tagihan DATE NOT NULL,

    total_tagihan DECIMAL(15,2) DEFAULT 0,
    diskon DECIMAL(15,2) DEFAULT 0,

    metode_pembayaran VARCHAR(50),

    asuransi_id VARCHAR(50),

    status VARCHAR(30),

    CONSTRAINT fk_tagihan_kunjungan
        FOREIGN KEY (visit_id)
        REFERENCES kunjungan(visit_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
); 

CREATE TABLE detail_tagihan (
    detail_tagihan_id INT AUTO_INCREMENT PRIMARY KEY,

    tagihan_id INT NOT NULL,

    jenis_item VARCHAR(50),
    deskripsi VARCHAR(255),

    tanggal_tagihan DATE,

    harga_satuan DECIMAL(15,2),

    sisa_piutang DECIMAL(15,2),

    CONSTRAINT fk_detail_tagihan
        FOREIGN KEY (tagihan_id)
        REFERENCES tagihan(tagihan_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);



-- TABEL PELAPORAN DAN AUDIT
CREATE TABLE report_agregasi (
    report_id INT AUTO_INCREMENT PRIMARY KEY,

    periode_mulai DATE,
    periode_akhir DATE,

    jenis_laporan VARCHAR(100),

    tanggal_generate DATETIME,

    keterangan TEXT
);

CREATE TABLE laporan_external (
    laporan_id INT AUTO_INCREMENT PRIMARY KEY,

    report_id INT NOT NULL,

    jenis_laporan VARCHAR(100),
    tujuan VARCHAR(100),

    tanggal_kirim DATE,

    file_laporan VARCHAR(255),

    status_kirim VARCHAR(30),

    CONSTRAINT fk_laporan_external_report
        FOREIGN KEY (report_id)
        REFERENCES report_agregasi(report_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);



-- INDEX UNTUK OPTIMASI
CREATE INDEX idx_kunjungan_tanggal
ON kunjungan(tgl_kunjungan);

CREATE INDEX idx_resep_tanggal
ON resep(tanggal_resep);

CREATE INDEX idx_batch_expiry
ON batch_obat(expiry_date);

CREATE INDEX idx_tagihan_status
ON tagihan(status);

CREATE INDEX idx_obat_nama
ON obat(nama_obat);



-- CHECK CONSTRAINT
ALTER TABLE detail_resep
ADD CONSTRAINT chk_jumlah_obat
CHECK (jumlah > 0);

ALTER TABLE transaksi_stok
ADD CONSTRAINT chk_jumlah_stok
CHECK (jumlah > 0);

ALTER TABLE tagihan
ADD CONSTRAINT chk_total_tagihan
CHECK (total_tagihan >= 0);


-- Verifikasi Jumlah Tabel
SHOW TABLES; -- TOTAL TERDAPAT 24 TABEL


-- INSERT DATA
INSERT INTO role (nama_role, deskripsi) VALUES
('Admin', 'Administrator sistem'),
('Dokter', 'Dokter praktek'),
('Apoteker', 'Petugas farmasi'),
('Kasir', 'Petugas pembayaran'),
('Perawat', 'Perawat klinik'),
('Pendaftaran', 'Petugas registrasi pasien'),
('Laboratorium', 'Petugas laboratorium'),
('Gudang', 'Petugas gudang obat'),
('Supervisor', 'Supervisor operasional'),
('Manajer', 'Manajer klinik');

INSERT INTO lokasi (nama_lokasi, tipe_lokasi, deskripsi) VALUES
('Gudang Utama', 'Gudang', 'Gudang penyimpanan utama'),
('Gudang Cadangan', 'Gudang', 'Gudang cadangan'),
('Rak A1', 'Rak', 'Rak obat antibiotik'),
('Rak A2', 'Rak', 'Rak obat analgesik'),
('Rak B1', 'Rak', 'Rak vitamin'),
('Rak B2', 'Rak', 'Rak obat sirup'),
('Ruang Farmasi', 'Ruangan', 'Ruang dispensing'),
('Ruang Pemeriksaan', 'Ruangan', 'Ruang dokter umum'),
('Ruang Tindakan', 'Ruangan', 'Ruang tindakan medis'),
('Laboratorium', 'Ruangan', 'Ruang pemeriksaan penunjang');

INSERT INTO supplier (nama_supplier, alamat, kontak_supplier) VALUES
('PT Kimia Farma', 'Jakarta', '021111111'),
('PT Kalbe Farma', 'Jakarta', '021222222'),
('PT Dexa Medica', 'Tangerang', '021333333'),
('PT Sanbe Farma', 'Bandung', '022111111'),
('PT Soho Global Health', 'Jakarta', '021444444'),
('PT Tempo Scan', 'Jakarta', '021555555'),
('PT Novell Pharma', 'Bogor', '025111111'),
('PT Bernofarm', 'Sidoarjo', '031111111'),
('PT Indofarma', 'Bekasi', '021666666'),
('PT Pharos Indonesia', 'Jakarta', '021777777');

INSERT INTO obat (nama_obat, bentuk_sediaan, satuan, kategori) VALUES
('Paracetamol', 'Tablet', 'Strip', 'Analgesik'),
('Amoxicillin', 'Kapsul', 'Strip', 'Antibiotik'),
('Ibuprofen', 'Tablet', 'Strip', 'Analgesik'),
('Cetirizine', 'Tablet', 'Strip', 'Antihistamin'),
('Vitamin C', 'Tablet', 'Botol', 'Vitamin'),
('Omeprazole', 'Kapsul', 'Strip', 'Gastrointestinal'),
('Antasida', 'Sirup', 'Botol', 'Gastrointestinal'),
('Metformin', 'Tablet', 'Strip', 'Antidiabetes'),
('Amlodipine', 'Tablet', 'Strip', 'Antihipertensi'),
('Captopril', 'Tablet', 'Strip', 'Antihipertensi'),
('Salbutamol', 'Tablet', 'Strip', 'Respirasi'),
('Dexamethasone', 'Tablet', 'Strip', 'Kortikosteroid'),
('CTM', 'Tablet', 'Strip', 'Antihistamin'),
('Azithromycin', 'Tablet', 'Strip', 'Antibiotik'),
('Mefenamic Acid', 'Tablet', 'Strip', 'Analgesik'),
('Loratadine', 'Tablet', 'Strip', 'Antihistamin'),
('Ranitidine', 'Tablet', 'Strip', 'Gastrointestinal'),
('Zinc', 'Tablet', 'Botol', 'Suplemen'),
('ORS', 'Sachet', 'Kotak', 'Rehidrasi'),
('Domperidone', 'Tablet', 'Strip', 'Antiemetik');

INSERT INTO dokter (nama, sip_no, spesialisasi, jadwal_id) VALUES
('Dr. Andi Pratama', 'SIP001', 'Umum', 1),
('Dr. Budi Santoso', 'SIP002', 'Umum', 2),
('Dr. Citra Dewi', 'SIP003', 'Anak', 3),
('Dr. Dimas Saputra', 'SIP004', 'Penyakit Dalam', 4),
('Dr. Eka Putri', 'SIP005', 'Umum', 5),
('Dr. Farhan Rizki', 'SIP006', 'Kulit', 6),
('Dr. Gina Maharani', 'SIP007', 'THT', 7),
('Dr. Hendra Wijaya', 'SIP008', 'Saraf', 8),
('Dr. Intan Permata', 'SIP009', 'Gizi', 9),
('Dr. Joko Susilo', 'SIP010', 'Bedah', 10);

INSERT INTO user (nama, username, password, kontak, id_role) VALUES
('Administrator', 'admin', 'admin123', '0811111111', 1),
('Dokter Andi', 'dandi', 'dokter123', '0811111112', 2),
('Apoteker Sinta', 'asinta', 'apoteker123', '0811111113', 3),
('Kasir Rina', 'krina', 'kasir123', '0811111114', 4),
('Perawat Dodi', 'pdodi', 'perawat123', '0811111115', 5),
('Petugas Daftar', 'daftar1', 'daftar123', '0811111116', 6),
('Lab Ari', 'labari', 'lab123', '0811111117', 7),
('Gudang Bayu', 'gbayu', 'gudang123', '0811111118', 8),
('Supervisor Nia', 'snia', 'super123', '0811111119', 9),
('Manajer Rudi', 'mrudi', 'manager123', '0811111120', 10);

INSERT INTO pasien
(nik, nama, tgl_lahir, jenis_kelamin, alamat, no_telepon, email, kontak_darurat, asuransi_id)
VALUES
('3501010000000001','Ahmad Fauzi','1998-05-10','L','Malang','081200000001','[email protected]','081300000001','BPJS001'),
('3501010000000002','Siti Aminah','2000-03-15','P','Malang','081200000002','[email protected]','081300000002','BPJS002'),
('3501010000000003','Budi Hartono','1995-07-20','L','Blitar','081200000003','[email protected]','081300000003','BPJS003'),
('3501010000000004','Dewi Lestari','2001-01-11','P','Kediri','081200000004','[email protected]','081300000004','BPJS004'),
('3501010000000005','Rudi Kurniawan','1997-08-12','L','Malang','081200000005','[email protected]','081300000005','BPJS005'),
('3501010000000006','Nina Marlina','1999-11-25','P','Batu','081200000006','[email protected]','081300000006','BPJS006'),
('3501010000000007','Fajar Nugroho','2002-04-18','L','Malang','081200000007','[email protected]','081300000007','BPJS007'),
('3501010000000008','Rina Agustina','1996-09-30','P','Pasuruan','081200000008','[email protected]','081300000008','BPJS008'),
('3501010000000009','Agus Setiawan','1994-12-05','L','Probolinggo','081200000009','[email protected]','081300000009','BPJS009'),
('3501010000000010','Maya Sari','2003-06-22','P','Malang','081200000010','[email protected]','081300000010','BPJS010');