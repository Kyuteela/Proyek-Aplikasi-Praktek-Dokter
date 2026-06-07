-- TAHAP 2 MANIPULASI DATA DAN QUERY KOMPLEKS

USE praktik_dokter;

-- TAHAP 2A - CRUD
INSERT INTO pasien (
nik,
nama,
tgl_lahir,
jenis_kelamin,
alamat,
no_telepon,
email,
kontak_darurat,
asuransi_id
)
VALUES (
'3501010000000011',
'Bambang Setiawan',
'1999-01-01',
'L',
'Malang',
'081234567890',
'[email protected]',
'081298765432',
'BPJS011'
);

SELECT *
FROM pasien;

UPDATE pasien
SET alamat = 'Kota Batu'
WHERE patient_id = 1;

DELETE FROM pasien
WHERE patient_id = 11;



INSERT INTO dokter
(nama,sip_no,spesialisasi,jadwal_id)
VALUES
('Dr. Test','SIP999','Umum',99);

SELECT *
FROM dokter;

UPDATE dokter
SET spesialisasi = 'Penyakit Dalam'
WHERE doctor_id = 1;

DELETE FROM dokter
WHERE doctor_id = 11;



INSERT INTO obat
(nama_obat,bentuk_sediaan,satuan,kategori)
VALUES
('Vitamin D','Tablet','Botol','Vitamin');

SELECT *
FROM obat;

UPDATE obat
SET kategori='Suplemen'
WHERE obat_id=1;

DELETE FROM obat
WHERE obat_id=21;



INSERT INTO kunjungan (
patient_id,
doctor_id,
tgl_kunjungan,
jenis_layanan,
antrian_no,
waktu_datang,
status
)
VALUES (
1,
1,
CURDATE(),
'Rawat Jalan',
1,
NOW(),
'Menunggu'
);

SELECT *
FROM kunjungan;

UPDATE kunjungan
SET status='Selesai'
WHERE visit_id=1;

DELETE FROM kunjungan
WHERE visit_id=1;



INSERT INTO supplier (
nama_supplier,
alamat,
kontak_supplier
)
VALUES (
'PT Sehat Sentosa',
'Surabaya',
'031888888'
);

SELECT *
FROM supplier;

UPDATE supplier
SET alamat = 'Malang'
WHERE supplier_id = 1;

DELETE FROM supplier
WHERE supplier_id = 11;



-- TAHAP 2B - Query Kompleks

-- Buat 1 skenario transaksi
INSERT INTO kunjungan (
patient_id,
doctor_id,
tgl_kunjungan,
jenis_layanan,
antrian_no,
waktu_datang,
status
)
VALUES
(1,1,CURDATE(),'Rawat Jalan',1,NOW(),'Selesai');

INSERT INTO rekam_medis (
    visit_id,
    anamnesa,
    pemeriksaan_fisik,
    catatan_klinis,
    riwayat_penyakit,
    alergi_obat_makanan,
    tanggal_catatan,
    tinggi_badan,
    berat_badan
)
VALUES (
    2,
    'Demam 3 hari',
    'Tekanan darah normal',
    'Infeksi ringan',
    'Tidak ada',
    'Tidak ada',
    CURDATE(),
    170,
    65
);

INSERT INTO resep (
    record_id,
    doctor_id,
    tanggal_resep,
    catatan_dokter,
    status_resep
)
VALUES (
    2,
    1,
    CURDATE(),
    'Minum setelah makan',
    'Aktif'
);

INSERT INTO detail_resep (
    resep_id,
    obat_id,
    dosis,
    rute,
    frekuensi,
    durasi,
    jumlah,
    instruksi_khusus
)
VALUES
(
    2,
    1,
    '500mg',
    'Oral',
    '3x sehari',
    '5 hari',
    10,
    'Sesudah makan'
),
(
    2,
    2,
    '250mg',
    'Oral',
    '2x sehari',
    '5 hari',
    10,
    'Sebelum makan'
);

INSERT INTO dispensing (
    detail_id,
    obat_id,
    edukasi_pasien,
    serah_terima,
    petugas_id
)
VALUES (
    1,
    1,
    'Obat diminum sesuai dosis',
    'Pasien menerima obat',
    1
);

INSERT INTO tagihan (
    visit_id,
    tanggal_tagihan,
    total_tagihan,
    diskon,
    metode_pembayaran,
    asuransi_id,
    status
)
VALUES (
    2,
    CURDATE(),
    150000,
    10000,
    'Tunai',
    'BPJS001',
    'Lunas'
);

INSERT INTO detail_tagihan (
    tagihan_id,
    jenis_item,
    deskripsi,
    tanggal_tagihan,
    harga_satuan,
    sisa_piutang
)
VALUES
(
    1,
    'Konsultasi',
    'Biaya konsultasi dokter',
    CURDATE(),
    100000,
    0
),
(
    1,
    'Obat',
    'Biaya obat resep',
    CURDATE(),
    50000,
    0
);

-- Query 1: Inner Join
SELECT
    p.patient_id,
    p.nama,
    k.visit_id,
    k.tgl_kunjungan
FROM pasien p
INNER JOIN kunjungan k
ON p.patient_id = k.patient_id;

-- Query 2: Inner Join 3 Tabel
SELECT
    p.nama AS pasien,
    d.nama AS dokter,
    k.tgl_kunjungan
FROM kunjungan k
INNER JOIN pasien p
ON k.patient_id = p.patient_id
INNER JOIN dokter d
ON k.doctor_id = d.doctor_id;

-- Query 3: Left Join
SELECT
    p.nama,
    k.visit_id
FROM pasien p
LEFT JOIN kunjungan k
ON p.patient_id = k.patient_id;

-- Query 4: Right Join
SELECT
    p.nama,
    k.visit_id
FROM pasien p
RIGHT JOIN kunjungan k
ON p.patient_id = k.patient_id;

-- Query 5: Aggregate 
SELECT
    COUNT(*) AS total_pasien,
    MIN(tgl_lahir) AS pasien_tertua,
    MAX(tgl_lahir) AS pasien_termuda
FROM pasien;

-- Query 6: Group By
SELECT
    d.doctor_id,
    d.nama,
    COUNT(k.visit_id) AS total_kunjungan
FROM dokter d
LEFT JOIN kunjungan k
ON d.doctor_id = k.doctor_id
GROUP BY d.doctor_id, d.nama;

-- Query 7: Having
SELECT
    d.doctor_id,
    d.nama,
    COUNT(k.visit_id) AS total_kunjungan
FROM dokter d
LEFT JOIN kunjungan k
ON d.doctor_id = k.doctor_id
GROUP BY d.doctor_id, d.nama
HAVING COUNT(k.visit_id) >= 1;

-- Query 8: Non-Correlated Subquery
SELECT *
FROM pasien
WHERE patient_id IN (
    SELECT patient_id
    FROM kunjungan
);

-- Query 9: Correlated Subquery
SELECT
    p.patient_id,
    p.nama
FROM pasien p
WHERE EXISTS (
    SELECT 1
    FROM kunjungan k
    WHERE k.patient_id = p.patient_id
);

-- Query 10: Union
SELECT nama, 'Pasien' AS sumber
FROM pasien

UNION

SELECT nama, 'Dokter' AS sumber
FROM dokter;

-- Query 11: Intersect
SELECT nama
FROM pasien

INTERSECT

SELECT nama
FROM dokter;

-- Query 12: Except
SELECT nama
FROM pasien

EXCEPT

SELECT nama
FROM dokter;



-- TAHAP 2C - Views

DROP VIEW IF EXISTS vw_kunjungan_pasien;
DROP VIEW IF EXISTS vw_rekam_medis;
DROP VIEW IF EXISTS vw_resep_pasien;
DROP VIEW IF EXISTS vw_tagihan_pasien;
DROP VIEW IF EXISTS vw_stok_obat;

-- View 1: Informasi Kunjungan Pasien
CREATE VIEW vw_kunjungan_pasien AS
SELECT
    k.visit_id,
    p.nama AS nama_pasien,
    d.nama AS nama_dokter,
    k.tgl_kunjungan,
    k.jenis_layanan,
    k.status
FROM kunjungan k
INNER JOIN pasien p
    ON k.patient_id = p.patient_id
INNER JOIN dokter d
    ON k.doctor_id = d.doctor_id;

SELECT * FROM vw_kunjungan_pasien;


-- View 2: Laporan Rekam Medis
CREATE VIEW vw_rekam_medis AS
SELECT
    rm.record_id,
    p.nama AS nama_pasien,
    d.nama AS nama_dokter,
    k.tgl_kunjungan,
    rm.anamnesa,
    rm.pemeriksaan_fisik,
    rm.catatan_klinis,
    rm.tinggi_badan,
    rm.berat_badan
FROM rekam_medis rm
INNER JOIN kunjungan k
    ON rm.visit_id = k.visit_id
INNER JOIN pasien p
    ON k.patient_id = p.patient_id
INNER JOIN dokter d
    ON k.doctor_id = d.doctor_id;

SELECT * FROM vw_rekam_medis;


-- View 3: Laporan Resep Pasien
CREATE VIEW vw_resep_pasien AS
SELECT
    r.resep_id,
    p.nama AS nama_pasien,
    d.nama AS nama_dokter,
    o.nama_obat,
    dr.dosis,
    dr.frekuensi,
    dr.durasi,
    dr.jumlah
FROM resep r
INNER JOIN rekam_medis rm
    ON r.record_id = rm.record_id
INNER JOIN kunjungan k
    ON rm.visit_id = k.visit_id
INNER JOIN pasien p
    ON k.patient_id = p.patient_id
INNER JOIN dokter d
    ON r.doctor_id = d.doctor_id
INNER JOIN detail_resep dr
    ON r.resep_id = dr.resep_id
INNER JOIN obat o
    ON dr.obat_id = o.obat_id;

SELECT * FROM vw_resep_pasien;


-- View 4: Laporan Tagihan Pasien
CREATE VIEW vw_tagihan_pasien AS
SELECT
    t.tagihan_id,
    p.nama AS nama_pasien,
    d.nama AS nama_dokter,
    t.tanggal_tagihan,
    t.total_tagihan,
    t.diskon,
    t.metode_pembayaran,
    t.status
FROM tagihan t
INNER JOIN kunjungan k
    ON t.visit_id = k.visit_id
INNER JOIN pasien p
    ON k.patient_id = p.patient_id
INNER JOIN dokter d
    ON k.doctor_id = d.doctor_id;

SELECT * FROM vw_tagihan_pasien;


-- View 5: Laporan Stok Obat
CREATE VIEW vw_stok_obat AS
SELECT
    b.batch_id,
    o.nama_obat,
    l.nama_lokasi,
    b.expiry_date,
    b.harga_beli,
    b.lokasi_rak
FROM batch_obat b
INNER JOIN obat o
    ON b.obat_id = o.obat_id
INNER JOIN lokasi l
    ON b.lokasi_id = l.lokasi_id;

SELECT * FROM vw_stok_obat;