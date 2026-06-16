<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
$error = '';

// Ambil data kunjungan yang BELUM memiliki catatan rekam medis (karena relasi 1-to-1)
$query_visit = "SELECT k.visit_id, p.nama AS nama_pasien, k.tgl_kunjungan 
                FROM kunjungan k 
                INNER JOIN pasien p ON k.patient_id = p.patient_id 
                LEFT JOIN rekam_medis rm ON k.visit_id = rm.visit_id 
                WHERE rm.record_id IS NULL ORDER BY k.visit_id DESC";
$available_visits = mysqli_query($conn, $query_visit);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visit_id = mysqli_real_escape_string($conn, $_POST['visit_id']);
    $anamnesa = mysqli_real_escape_string($conn, $_POST['anamnesa']);
    $pemeriksaan_fisik = mysqli_real_escape_string($conn, $_POST['pemeriksaan_fisik']);
    $catatan_klinis = mysqli_real_escape_string($conn, $_POST['catatan_klinis']);
    $riwayat_penyakit = mysqli_real_escape_string($conn, $_POST['riwayat_penyakit']);
    $alergi_obat_makanan = mysqli_real_escape_string($conn, $_POST['alergi_obat_makanan']);
    $tinggi_badan = mysqli_real_escape_string($conn, $_POST['tinggi_badan']);
    $berat_badan = mysqli_real_escape_string($conn, $_POST['berat_badan']);

    // Kolom tanggal_catatan sengaja dikosongkan (NULL) agar memicu otomatisasi trg_generate_rekam_medis
    $query_insert = "INSERT INTO rekam_medis (visit_id, anamnesa, pemeriksaan_fisik, catatan_klinis, riwayat_penyakit, alergi_obat_makanan, tanggal_catatan, tinggi_badan, berat_badan) 
                     VALUES ('$visit_id', '$anamnesa', '$pemeriksaan_fisik', '$catatan_klinis', '$riwayat_penyakit', '$alergi_obat_makanan', NULL, '$tinggi_badan', '$berat_badan')";

    if (mysqli_query($conn, $query_insert)) {
        header("Location: index.php?status=success&msg=" . urlencode("Catatan rekam medis berhasil disimpan!"));
        exit;
    } else {
        $error = "Gagal menyimpan rekam medis. Periksa kembali inputan Anda.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-2xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="bi bi-file-earmark-medical text-blue-500 mr-2"></i> Input Rekam Medis Baru
        </h2>
        <a href="index.php" class="text-xs text-gray-500 hover:text-gray-700"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pilih Sesi Kunjungan Pasien <span class="text-rose-500">*</span></label>
            <select name="visit_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">-- Pilih Antrian Pasien --</option>
                <?php while ($v = mysqli_fetch_assoc($available_visits)): ?>
                    <option value="<?= $v['visit_id'] ?>">#VISIT-<?= $v['visit_id'] ?> - <?= htmlspecialchars($v['nama_pasien']) ?> (<?= date('d M Y', strtotime($v['tgl_kunjungan'])) ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tinggi Badan (cm) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.1" name="tinggi_badan" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none" placeholder="Contoh: 165.5">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Berat Badan (kg) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.1" name="berat_badan" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none" placeholder="Contoh: 60.2">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Anamnesa (Keluhan Pasien) <span class="text-rose-500">*</span></label>
            <textarea name="anamnesa" rows="2" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none" placeholder="Tulis keluhan utama dan riwayat perkembangan gejala pasien..."></textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Hasil Pemeriksaan Fisik</label>
            <textarea name="pemeriksaan_fisik" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none" placeholder="Catatan kondisi fisik, tensi manual, dll..."></textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Catatan Klinis (Diagnosis / Assessment)</label>
            <textarea name="catatan_klinis" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none" placeholder="Kesimpulan medis atau diagnosis penyakit pasien..."></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Riwayat Penyakit Dahulu</label>
                <textarea name="riwayat_penyakit" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none" placeholder="Hipertensi, Diabetes, dll..."></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Alergi Obat / Makanan</label>
                <textarea name="alergi_obat_makanan" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none" placeholder="Tulis kontraindikasi alergi jika ada..."></textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Rekam Medis</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>