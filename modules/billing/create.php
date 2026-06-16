<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
$error = '';

// Ambil data kunjungan yang belum memiliki invoice tagihan
$query_visit = "SELECT k.visit_id, p.nama AS nama_pasien, k.tgl_kunjungan 
                FROM kunjungan k 
                INNER JOIN pasien p ON k.patient_id = p.patient_id 
                LEFT JOIN tagihan t ON k.visit_id = t.visit_id 
                WHERE t.tagihan_id IS NULL ORDER BY k.visit_id DESC";
$visits = mysqli_query($conn, $query_visit);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visit_id = mysqli_real_escape_string($conn, $_POST['visit_id']);
    $tanggal_tagihan = mysqli_real_escape_string($conn, $_POST['tanggal_tagihan']);
    $asuransi_id = mysqli_real_escape_string($conn, $_POST['asuransi_id']);
    $status_pembayaran = mysqli_real_escape_string($conn, $_POST['status']);

    $query_insert = "INSERT INTO tagihan (visit_id, tanggal_tagihan, total_tagihan, diskon, status, asuransi_id) 
                     VALUES ('$visit_id', '$tanggal_tagihan', 0, 0, '$status_pembayaran', '$asuransi_id')";

    if (mysqli_query($conn, $query_insert)) {
        $new_id = mysqli_insert_id($conn);
        header("Location: details.php?id=" . $new_id);
        exit;
    } else {
        $error = "Gagal menerbitkan lembar invoice baru.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center"><i class="bi bi-file-earmark-plus text-blue-500 mr-2"></i> Terbitkan Invoice Baru</h2>
        <a href="index.php" class="text-xs text-gray-500 hover:text-gray-700"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pilih Rekam Sesi Kunjungan Pasien <span class="text-rose-500">*</span></label>
            <select name="visit_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">-- Pilih Sesi Pasien --</option>
                <?php while ($v = mysqli_fetch_assoc($visits)): ?>
                    <option value="<?= $v['visit_id'] ?>">#VISIT-<?= $v['visit_id'] ?> - <?= htmlspecialchars($v['nama_pasien']) ?> (<?= date('d M Y', strtotime($v['tgl_kunjungan'])) ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Tagihan <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal_tagihan" required value="<?= date('Y-m-day'); ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">No. Klaim Jaminan (BPJS)</label>
                <input type="text" name="asuransi_id" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Kosongkan jika tunai">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Awal Invoice</label>
            <select name="status" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                <option value="Belum Lunas">Belum Lunas</option>
                <option value="Lunas">Lunas</option>
            </select>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow">Generate Invoice</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>