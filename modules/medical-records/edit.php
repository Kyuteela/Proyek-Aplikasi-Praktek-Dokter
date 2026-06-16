<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$record_id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT rm.*, p.nama AS nama_pasien FROM rekam_medis rm 
          INNER JOIN kunjungan k ON rm.visit_id = k.visit_id 
          INNER JOIN pasien p ON k.patient_id = p.patient_id 
          WHERE rm.record_id = '$record_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit;
}

$rm = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anamnesa = mysqli_real_escape_string($conn, $_POST['anamnesa']);
    $pemeriksaan_fisik = mysqli_real_escape_string($conn, $_POST['pemeriksaan_fisik']);
    $catatan_klinis = mysqli_real_escape_string($conn, $_POST['catatan_klinis']);
    $riwayat_penyakit = mysqli_real_escape_string($conn, $_POST['riwayat_penyakit']);
    $alergi_obat_makanan = mysqli_real_escape_string($conn, $_POST['alergi_obat_makanan']);
    $tinggi_badan = mysqli_real_escape_string($conn, $_POST['tinggi_badan']);
    $berat_badan = mysqli_real_escape_string($conn, $_POST['berat_badan']);

    // Update query memicu jalannya trg_rekam_medis_update
    $update_query = "UPDATE rekam_medis SET 
                        anamnesa = '$anamnesa', pemeriksaan_fisik = '$pemeriksaan_fisik', 
                        catatan_klinis = '$catatan_klinis', riwayat_penyakit = '$riwayat_penyakit', 
                        alergi_obat_makanan = '$alergi_obat_makanan', tinggi_badan = '$tinggi_badan', 
                        berat_badan = '$berat_badan' 
                     WHERE record_id = '$record_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php?status=success&msg=" . urlencode("Data RME pasien berhasil diupdate & tercatat di audit log!"));
        exit;
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-2xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800"><i class="bi bi-pencil-square text-emerald-500 mr-2"></i> Edit Rekam Medis Pasien</h2>
        <a href="index.php" class="text-xs text-gray-500">Batal</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div class="p-4 bg-slate-50 border rounded-xl text-sm text-gray-700">
            Pasien: <strong class="text-gray-900"><?= htmlspecialchars($rm['nama_pasien']) ?></strong>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tinggi Badan (cm)</label>
                <input type="number" step="0.1" name="tinggi_badan" value="<?= $rm['tinggi_badan'] ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Berat Badan (kg)</label>
                <input type="number" step="0.1" name="berat_badan" value="<?= $rm['berat_badan'] ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Anamnesa</label>
            <textarea name="anamnesa" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none"><?= htmlspecialchars($rm['anamnesa']) ?></textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Hasil Pemeriksaan Fisik</label>
            <textarea name="pemeriksaan_fisik" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none"><?= htmlspecialchars($rm['pemeriksaan_fisik']) ?></textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Catatan Klinis</label>
            <textarea name="catatan_klinis" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none"><?= htmlspecialchars($rm['catatan_klinis']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Riwayat Penyakit Dahulu</label>
                <textarea name="riwayat_penyakit" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none"><?= htmlspecialchars($rm['riwayat_penyakit']) ?></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Alergi Obat / Makanan</label>
                <textarea name="alergi_obat_makanan" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none"><?= htmlspecialchars($rm['alergi_obat_makanan']) ?></textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="w-full px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow">Update Rekam Medis</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>