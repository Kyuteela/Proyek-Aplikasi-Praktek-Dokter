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

$batch_id = mysqli_real_escape_string($conn, $_GET['id']);
$batch_data = mysqli_query($conn, "SELECT * FROM batch_obat WHERE batch_id = '$batch_id'");

if (mysqli_num_rows($batch_data) === 0) {
    header("Location: index.php");
    exit;
}

$batch = mysqli_fetch_assoc($batch_data);
$error = '';

$master_obat = mysqli_query($conn, "SELECT obat_id, nama_obat FROM obat ORDER BY nama_obat ASC");
$master_gr = mysqli_query($conn, "SELECT gr_id, faktur_no FROM penerimaan_barang ORDER BY gr_id DESC");
$master_lokasi = mysqli_query($conn, "SELECT lokasi_id, nama_lokasi FROM lokasi ORDER BY nama_lokasi ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $obat_id = mysqli_real_escape_string($conn, $_POST['obat_id']);
    $gr_id = mysqli_real_escape_string($conn, $_POST['gr_id']);
    $lokasi_id = mysqli_real_escape_string($conn, $_POST['lokasi_id']);
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);
    $harga_beli = mysqli_real_escape_string($conn, $_POST['harga_beli']);
    $lokasi_rak = mysqli_real_escape_string($conn, $_POST['lokasi_rak']);

    $update_query = "UPDATE batch_obat SET 
                        obat_id = '$obat_id', gr_id = '$gr_id', lokasi_id = '$lokasi_id', 
                        expiry_date = '$expiry_date', harga_beli = '$harga_beli', lokasi_rak = '$lokasi_rak' 
                     WHERE batch_id = '$batch_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php?status=success&msg=" . urlencode("Data informasi batch obat berhasil diperbarui!"));
        exit;
    } else {
        $error = "Terjadi kegagalan sistem saat memperbarui data.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-2xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="bi bi-pencil-square text-blue-500 mr-2"></i> Ubah Data Batch Obat
        </h2>
        <a href="index.php" class="text-sm text-gray-500 hover:text-gray-700 flex items-center"><i class="bi bi-arrow-left mr-1"></i> Kembali</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Kandungan Obat</label>
            <select name="obat_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                <?php while ($o = mysqli_fetch_assoc($master_obat)): ?>
                    <option value="<?= $o['obat_id'] ?>" <?= $batch['obat_id'] == $o['obat_id'] ? 'selected' : '' ?>><?= htmlspecialchars($o['nama_obat']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">No. Faktur Penerimaan</label>
                <select name="gr_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                    <?php while ($g = mysqli_fetch_assoc($master_gr)): ?>
                        <option value="<?= $g['gr_id'] ?>" <?= $batch['gr_id'] == $g['gr_id'] ? 'selected' : '' ?>><?= htmlspecialchars($g['faktur_no']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Lokasi Penyimpanan</label>
                <select name="lokasi_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                    <?php while ($l = mysqli_fetch_assoc($master_lokasi)): ?>
                        <option value="<?= $l['lokasi_id'] ?>" <?= $batch['lokasi_id'] == $l['lokasi_id'] ? 'selected' : '' ?>><?= htmlspecialchars($l['nama_lokasi']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Expired</label>
                <input type="date" name="expiry_date" required value="<?= $batch['expiry_date'] ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Harga Beli Unit</label>
                <input type="number" name="harga_beli" required value="<?= intval($batch['harga_beli']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Lokasi Kode Rak</label>
                <input type="text" name="lokasi_rak" value="<?= htmlspecialchars($batch['lokasi_rak']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>