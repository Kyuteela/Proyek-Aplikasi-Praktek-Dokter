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

$transaksi_stok_id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT ts.*, o.nama_obat FROM transaksi_stok ts 
          INNER JOIN batch_obat bo ON ts.batch_id = bo.batch_id 
          INNER JOIN obat o ON bo.obat_id = o.obat_id 
          WHERE ts.transaksi_stok_id = '$transaksi_stok_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit;
}

$ts = mysqli_fetch_assoc($result);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis_transaksi = mysqli_real_escape_string($conn, $_POST['jenis_transaksi']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $referensi = mysqli_real_escape_string($conn, $_POST['referensi']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    try {
        $update = "UPDATE transaksi_stok SET 
                        jenis_transaksi = '$jenis_transaksi', jumlah = '$jumlah', 
                        referensi = '$referensi', keterangan = '$keterangan' 
                   WHERE transaksi_stok_id = '$transaksi_stok_id'";

        if (mysqli_query($conn, $update)) {
            header("Location: index.php?status=success&msg=" . urlencode("Log koreksi mutasi stok berhasil disesuaikan!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        $error = "Aturan Bisnis Gagal: Kuantitas volume tidak boleh bernilai 0 atau bernilai minus!";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800"><i class="bi bi-pencil-square text-emerald-500 mr-2"></i> Koreksi Log Mutasi Stok</h2>
        <a href="index.php" class="text-xs text-gray-500">Batal</a>
    </div>

    <?php if (!empty($error)) : ?>
        <div class="m-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl text-sm flex items-center space-x-2"><i class="bi bi-exclamation-octagon-fill text-rose-500"></i><span><?= $error; ?></span></div>
    <?php endif; ?>

    <form action="" method="POST" class="p-6 space-y-4">
        <div class="p-4 bg-slate-50 border rounded-xl text-sm text-gray-600">
            Komoditas Obat Terikat: <strong class="text-gray-800"><?= htmlspecialchars($ts['nama_obat']) ?> (Lot #BCH-<?= $ts['batch_id'] ?>)</strong>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis Transaksi Alur Mutasi</label>
                <select name="jenis_transaksi" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
                    <option value="MASUK" <?= $ts['jenis_transaksi'] === 'MASUK' ? 'selected' : '' ?>>MASUK (Penambahan / Koreksi Gudang)</option>
                    <option value="KELUAR" <?= $ts['jenis_transaksi'] === 'KELUAR' ? 'selected' : '' ?>>KELUAR (Penyusutan / Obat Rusak & Expired)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jumlah Volume Qty</label>
                <input type="number" name="jumlah" value="<?= $ts['jumlah'] ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kode Nomor Referensi</label>
            <input type="text" name="referensi" value="<?= htmlspecialchars($ts['referensi']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Keterangan Alasan Logistik</label>
            <textarea name="keterangan" rows="3" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none"><?= htmlspecialchars($ts['keterangan']) ?></textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="w-full px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Koreksi Transaksi</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>