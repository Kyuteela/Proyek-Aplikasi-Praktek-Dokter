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

$visit_id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT k.*, p.nama AS nama_pasien FROM kunjungan k INNER JOIN pasien p ON k.patient_id = p.patient_id WHERE k.visit_id = '$visit_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit;
}

$visit = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis_layanan = mysqli_real_escape_string($conn, $_POST['jenis_layanan']);
    $status_antrian = mysqli_real_escape_string($conn, $_POST['status']);

    // INTEGRASI DATABASE: Menjalankan Stored Procedure sp_update_status_kunjungan dari Tahap 3
    $query_sp = "CALL sp_update_status_kunjungan('$visit_id', '$status_antrian')";

    // Perbarui juga data jenis layanan dengan query pendukung
    $query_update_layanan = "UPDATE kunjungan SET jenis_layanan = '$jenis_layanan' WHERE visit_id = '$visit_id'";

    if (mysqli_query($conn, $query_sp) && mysqli_query($conn, $query_update_layanan)) {
        header("Location: index.php?status=success&msg=" . urlencode("Status antrian kunjungan sukses diperbarui!"));
        exit;
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-md mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-sm font-bold text-gray-700"><i class="bi bi-pencil-square text-emerald-500 mr-2"></i> Update Sesi Antrian</h2>
        <a href="index.php" class="text-xs text-gray-500">Batal</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div class="p-4 bg-slate-50 border rounded-xl text-sm text-gray-600 space-y-1">
            <div>Nama Pasien: <strong class="text-gray-900"><?= htmlspecialchars($visit['nama_pasien']) ?></strong></div>
            <div>No. Antrian Asli: <strong class="text-gray-900">#<?= $visit['antrian_no'] ?></strong></div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis Layanan Medis</label>
            <select name="jenis_layanan" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
                <option value="Konsultasi" <?= $visit['jenis_layanan'] === 'Konsultasi' ? 'selected' : '' ?>>Konsultasi Umum</option>
                <option value="Pemeriksaan" <?= $visit['jenis_layanan'] === 'Pemeriksaan' ? 'selected' : '' ?>>Pemeriksaan Rutin / Check-up</option>
                <option value="Tindakan Medis" <?= $visit['jenis_layanan'] === 'Tindakan Medis' ? 'selected' : '' ?>>Tindakan Medis / Bedah Minor</option>
                <option value="Rawat Jalan" <?= $visit['jenis_layanan'] === 'Rawat Jalan' ? 'selected' : '' ?>>Rawat Jalan Kontrol</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Alur Alur Antrian</label>
            <select name="status" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
                <option value="Menunggu" <?= $visit['status'] === 'Menunggu' ? 'selected' : '' ?>>Menunggu (Dalam Antrian)</option>
                <option value="Selesai" <?= $visit['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai (Sudah Diperiksa)</option>
            </select>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="w-full px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Perubahan Sesi</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>