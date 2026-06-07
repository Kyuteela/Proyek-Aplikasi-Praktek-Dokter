-- TAHAP 1: DATA MASTER

USE praktik_dokter;

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