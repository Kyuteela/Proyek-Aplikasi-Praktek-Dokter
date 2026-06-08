-- TAHAP 1 : DATA OPERASIONAL SISTEM PRAKTIK DOKTER

USE praktik_dokter;


-- DATA KUNJUNGAN

INSERT INTO kunjungan
(
patient_id,
doctor_id,
tgl_kunjungan,
jenis_layanan,
antrian_no,
waktu_datang,
waktu_selesai,
status
)
VALUES
(1,1,CURDATE(),'Konsultasi',1,NOW(),NOW(),'Selesai'),
(2,2,CURDATE(),'Pemeriksaan',2,NOW(),NOW(),'Selesai'),
(3,3,CURDATE(),'Konsultasi',3,NOW(),NULL,'Menunggu');


-- DATA REKAM MEDIS

INSERT INTO rekam_medis
(
visit_id,
anamnesa,
pemeriksaan_fisik,
catatan_klinis,
riwayat_penyakit,
alergi_obat_makanan,
tanggal_catatan,
vital_summary,
tinggi_badan,
berat_badan
)
VALUES
(
1,
'Demam 3 hari',
'Keadaan umum baik',
'Infeksi ringan',
'Tidak ada',
'Tidak ada',
CURDATE(),
'Tekanan darah normal',
170,
65
);


-- DATA TRIAGE VITAL

INSERT INTO triage_vital
(
record_id,
tekanan_darah,
nadi,
suhu,
spO2,
keluhan_utama,
riwayat_alergi,
riwayat_obat
)
VALUES
(
1,
'120/80',
80,
36.7,
98,
'Demam',
'Tidak ada',
'Paracetamol'
);


-- DATA ORDER PENUNJANG

INSERT INTO order_penunjang
(
record_id,
jenis_pemeriksaan,
tanggal_order,
status_order
)
VALUES
(
1,
'Laboratorium',
CURDATE(),
'Selesai'
);


-- DATA HASIL PENUNJANG

INSERT INTO hasil_penunjang
(
order_id,
tanggal_hasil,
hasil,
satuan
)
VALUES
(
1,
CURDATE(),
'Normal',
'-'
);


-- DATA TINDAKAN

INSERT INTO tindakan
(
record_id,
jenis_tindakan,
tanggal_tindakan,
keterangan
)
VALUES
(
1,
'Pemeriksaan Umum',
CURDATE(),
'Pasien dalam kondisi stabil'
);


-- DATA RESEP

INSERT INTO resep
(
record_id,
doctor_id,
tanggal_resep,
catatan_dokter,
status_resep
)
VALUES
(
1,
1,
CURDATE(),
'Minum setelah makan',
'Aktif'
);


-- DATA DETAIL RESEP

INSERT INTO detail_resep
(
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
1,
1,
'500 mg',
'Oral',
'3x sehari',
'5 hari',
10,
'Setelah makan'
),
(
1,
2,
'500 mg',
'Oral',
'2x sehari',
'5 hari',
10,
'Harus dihabiskan'
);


-- DATA DISPENSING

INSERT INTO dispensing
(
detail_id,
obat_id,
edukasi_pasien,
serah_terima,
petugas_id
)
VALUES
(
1,
1,
'Obat diminum sesuai aturan',
'Sudah diterima pasien',
3
);


-- DATA PURCHASE ORDER

INSERT INTO purchase_order
(
supplier_id,
tanggal_po,
status_po,
total_po
)
VALUES
(1,CURDATE(),'Diterima',500000),
(2,CURDATE(),'Diterima',750000),
(3,CURDATE(),'Pending',1000000);


-- DATA PENERIMAAN BARANG

INSERT INTO penerimaan_barang
(
po_id,
faktur_no
)
VALUES
(1,'FKT-001'),
(2,'FKT-002'),
(3,'FKT-003');


-- DATA BATCH OBAT

INSERT INTO batch_obat
(
obat_id,
gr_id,
lokasi_id,
expiry_date,
harga_beli,
lokasi_rak
)
VALUES
(1,1,1,'2027-12-31',12000,'A01'),
(2,2,2,'2027-10-15',15000,'A02'),
(3,3,3,'2028-01-20',18000,'A03');


-- DATA TRANSAKSI STOK

INSERT INTO transaksi_stok
(
batch_id,
tanggal,
jenis_transaksi,
jumlah,
referensi,
keterangan
)
VALUES
(
1,
NOW(),
'MASUK',
100,
'GR-001',
'Penerimaan barang'
),
(
2,
NOW(),
'MASUK',
150,
'GR-002',
'Penerimaan barang'
),
(
3,
NOW(),
'MASUK',
200,
'GR-003',
'Penerimaan barang'
);


-- DATA TAGIHAN

INSERT INTO tagihan
(
visit_id,
tanggal_tagihan,
total_tagihan,
diskon,
metode_pembayaran,
asuransi_id,
status
)
VALUES
(
1,
CURDATE(),
150000,
10000,
'Tunai',
'BPJS001',
'Belum Lunas'
);


-- DATA DETAIL TAGIHAN

INSERT INTO detail_tagihan
(
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
'Obat resep pasien',
CURDATE(),
50000,
0
);


-- DATA REPORT AGREGASI

INSERT INTO report_agregasi
(
periode_mulai,
periode_akhir,
jenis_laporan,
tanggal_generate,
keterangan
)
VALUES
(
'2026-01-01',
'2026-12-31',
'Laporan Tahunan',
NOW(),
'Laporan otomatis sistem'
);


-- DATA LAPORAN EXTERNAL

INSERT INTO laporan_external
(
report_id,
jenis_laporan,
tujuan,
tanggal_kirim,
file_laporan,
status_kirim
)
VALUES
(
1,
'Laporan Tahunan',
'Dinas Kesehatan',
CURDATE(),
'laporan_tahunan.pdf',
'Terkirim'
);