<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
$error = '';

// Ambil rekam medis yang belum dibuatkan resep obatnya
$query_rm = "SELECT rm.record_id, p.nama AS nama_pasien 
             FROM rekam_medis rm 
             INNER JOIN kunjungan k ON rm.visit_id = k.visit_id 
             INNER JOIN pasien p ON k.patient_id = p.patient_id 
             LEFT JOIN resep r ON rm.record_id = r.record_id 
             WHERE r.resep_id IS NULL ORDER BY rm.record_id DESC";
$rm_list = mysqli_query($conn, $query_rm);

// Ambil list dokter
$doctors = mysqli_query($conn, "SELECT doctor_id, nama FROM dokter ORDER BY nama ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $record_id = mysqli_real_escape_string($conn, $_POST['record_id']);
    $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $catatan_dokter = mysqli_real_escape_string($conn, $_POST['catatan_dokter']);

    // INTEGRASI DATABASE: Menjalankan Stored Procedure sp_buat_resep
    $query_sp = "CALL sp_buat_resep('$record_id', '$doctor_id', '$catatan_dokter')";

    if (mysqli_query($conn, $query_sp)) {
        $new_resep_id = mysqli_insert_id($conn);
        header("Location: details.php?id=" . $new_resep_id);
        exit;
    } else {
        $error = "Terjadi kegagalan sistem saat menerbitkan lembaran resep baru.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center"><i class="bi bi-file-earmark-plus text-purple-500 mr-2"></i> Terbitkan Resep Medis</h2>
        <a href="index.php" class="text-xs text-gray-500 hover:text-gray-700"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pilih Rekam Medis Sesi Pasien <span class="text-rose-500">*</span></label>
            <select name="record_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">-- Pilih Rekam Medis Pasien --</option>
                <?php while ($rm = mysqli_fetch_assoc($rm_list)): ?>
                    <option value="<?= $rm['record_id'] ?>">#RME-<?= $rm['record_id'] ?> - Pasien: <?= htmlspecialchars($rm['nama_pasien']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dokter Pemberi Instruksi <span class="text-rose-500">*</span></label>
            <select name="doctor_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                <option value="">-- Pilih Tenaga Medis --</option>
                <?php while ($d = mysqli_fetch_assoc($doctors)): ?>
                    <option value="<?= $d['doctor_id'] ?>"><?= htmlspecialchars($d['nama']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Catatan Tambahan Dokter</label>
            <textarea name="catatan_dokter" rows="3" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Contoh: Aturan alergi, diminum sesudah makan..."></textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-semibold transition shadow">Generate Lembar Resep</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>