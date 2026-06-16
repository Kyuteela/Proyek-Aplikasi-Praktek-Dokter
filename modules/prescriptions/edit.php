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

$resep_id = mysqli_real_escape_string($conn, $_GET['id']);
$resep = mysqli_fetch_assoc(mysqli_query($conn, "SELECT r.*, p.nama AS nama_pasien FROM resep r INNER JOIN rekam_medis rm ON r.record_id = rm.record_id INNER JOIN kunjungan k ON rm.visit_id = k.visit_id INNER JOIN pasien p ON k.patient_id = p.patient_id WHERE r.resep_id = '$resep_id'"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catatan_dokter = mysqli_real_escape_string($conn, $_POST['catatan_dokter']);
    $status_resep = mysqli_real_escape_string($conn, $_POST['status_resep']);

    $update = "UPDATE resep SET catatan_dokter = '$catatan_dokter', status_resep = '$status_resep' WHERE resep_id = '$resep_id'";
    if (mysqli_query($conn, $update)) {
        header("Location: index.php?status=success&msg=" . urlencode("Lembar header resep berhasil dimodifikasi!"));
        exit;
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-md mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
        <h2 class="text-sm font-bold text-gray-700">Koreksi Parameter Resep</h2>
        <a href="index.php" class="text-xs text-gray-500">Batal</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div class="p-3 bg-slate-50 border rounded-xl text-xs text-gray-600">
            Pasien: <strong class="text-gray-800"><?= htmlspecialchars($resep['nama_pasien']); ?></strong>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Catatan Tambahan Dokter</label>
            <textarea name="catatan_dokter" rows="3" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm"><?= htmlspecialchars($resep['catatan_dokter']); ?></textarea>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Resep</label>
            <select name="status_resep" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
                <option value="Aktif" <?= $resep['status_resep'] === 'Aktif' ? 'selected' : '' ?>>Aktif (Menunggu Apoteker)</option>
                <option value="Selesai" <?= $resep['status_resep'] === 'Selesai' ? 'selected' : '' ?>>Selesai Diproses / Diserahkan</option>
            </select>
        </div>
        <div class="pt-2">
            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm shadow">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>