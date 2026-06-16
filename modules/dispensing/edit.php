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

$dispensing_id = mysqli_real_escape_string($conn, $_GET['id']);
$dispensing = mysqli_fetch_assoc(mysqli_query($conn, "SELECT d.*, o.nama_obat, p.nama AS nama_pasien 
      FROM dispensing d 
      INNER JOIN detail_resep dr ON d.detail_id = dr.detail_id
      INNER JOIN resep r ON dr.resep_id = r.resep_id
      INNER JOIN rekam_medis rm ON r.record_id = rm.record_id
      INNER JOIN kunjungan k ON rm.visit_id = k.visit_id
      INNER JOIN pasien p ON k.patient_id = p.patient_id
      INNER JOIN obat o ON d.obat_id = o.obat_id 
      WHERE d.dispensing_id = '$dispensing_id'"));

if (!$dispensing) {
    header("Location: index.php");
    exit;
}

$petugas_list = mysqli_query($conn, "SELECT user_id, nama FROM user WHERE id_role IN (1, 3) ORDER BY nama ASC");
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $edukasi_pasien = mysqli_real_escape_string($conn, $_POST['edukasi_pasien']);
    $serah_terima = mysqli_real_escape_string($conn, $_POST['serah_terima']);
    $petugas_id = mysqli_real_escape_string($conn, $_POST['petugas_id']);

    $update_query = "UPDATE dispensing SET 
                        edukasi_pasien = '$edukasi_pasien', 
                        serah_terima = '$serah_terima', 
                        petugas_id = '$petugas_id' 
                     WHERE dispensing_id = '$dispensing_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php?status=success&msg=" . urlencode("Log catatan dispensing berhasil diperbarui!"));
        exit;
    } else {
        $error = "Gagal memperbarui catatan log dispensing.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800"><i class="bi bi-pencil-shadow text-emerald-500 mr-2"></i> Koreksi Catatan Dispensing</h2>
        <a href="index.php" class="text-xs text-gray-500">Batal</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div class="p-4 bg-slate-50 border rounded-xl text-sm space-y-1 text-gray-600">
            <div>Nama Pasien: <strong class="text-gray-800"><?= htmlspecialchars($dispensing['nama_pasien']) ?></strong></div>
            <div>Komoditas Obat: <strong class="text-blue-600"><?= htmlspecialchars($dispensing['nama_obat']) ?></strong></div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Otoritas Petugas</label>
            <select name="petugas_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
                <?php while ($p = mysqli_fetch_assoc($petugas_list)): ?>
                    <option value="<?= $p['user_id'] ?>" <?= $dispensing['petugas_id'] == $p['user_id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nama']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Serah Terima</label>
            <input type="text" name="serah_terima" required value="<?= htmlspecialchars($dispensing['serah_terima']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Edukasi Informasi Pemakaian</label>
            <textarea name="edukasi_pasien" rows="3" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none"><?= htmlspecialchars($dispensing['edukasi_pasien']) ?></textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="w-full px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Perubahan Log</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>