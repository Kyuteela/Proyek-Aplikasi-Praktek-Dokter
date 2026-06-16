<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
$error = '';

// Mengambil list ketersediaan kode lot batch komoditas obat aktif
$query_batch = "SELECT b.batch_id, o.nama_obat, b.lokasi_rak, b.expiry_date 
                FROM batch_obat b 
                INNER JOIN obat o ON b.obat_id = o.obat_id 
                ORDER BY b.batch_id DESC";
$batches = mysqli_query($conn, $query_batch);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_id = mysqli_real_escape_string($conn, $_POST['batch_id']);
    $jenis_transaksi = mysqli_real_escape_string($conn, $_POST['jenis_transaksi']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $referensi = mysqli_real_escape_string($conn, $_POST['referensi']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    try {
        // Menyimpan log mutasi baru ke database
        $query_insert = "INSERT INTO transaksi_stok (batch_id, tanggal, jenis_transaksi, jumlah, referensi, keterangan) 
                         VALUES ('$batch_id', NOW(), '$jenis_transaksi', '$jumlah', '$referensi', '$keterangan')";

        if (mysqli_query($conn, $query_insert)) {
            header("Location: index.php?status=success&msg=" . urlencode("Log transaksi mutasi internal stok berhasil dicatat!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap sinyal kegagalan dari check constraint chk_jumlah_stok di database
        $error = "Aturan Bisnis Gagal: Kuantitas mutasi stok barang tidak boleh bernilai 0 atau negatif!";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center"><i class="bi bi-arrow-left-right text-blue-500 mr-2"></i> Input Transaksi Stok Manual</h2>
        <a href="index.php" class="text-xs text-gray-500 hover:text-gray-700"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if (!empty($error)) : ?>
        <div class="m-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl text-sm flex items-center space-x-2">
            <i class="bi bi-exclamation-octagon-fill text-rose-500"></i>
            <span><?= $error; ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pilih Item Lot Batch Obat <span class="text-rose-500">*</span></label>
            <select name="batch_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">-- Pilih Kode Lot Komoditas --</option>
                <?php while ($b = mysqli_fetch_assoc($batches)): ?>
                    <option value="<?= $b['batch_id'] ?>">#BCH-<?= $b['batch_id'] ?> - <?= htmlspecialchars($b['nama_obat']) ?> (Rak: <?= htmlspecialchars($b['lokasi_rak'] ?: '-'); ?> | Exp: <?= $b['expiry_date']; ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis Transaksi Alur Mutasi <span class="text-rose-500">*</span></label>
                <select name="jenis_transaksi" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                    <option value="MASUK">MASUK (Penambahan / Koreksi Gudang)</option>
                    <option value="KELUAR">KELUAR (Penyusutan / Obat Rusak & Expired)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jumlah Volume Qty <span class="text-rose-500">*</span></label>
                <input type="number" name="jumlah" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Contoh: 50">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kode Nomor Referensi (Opsional)</label>
            <input type="text" name="referensi" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Contoh: ADJ-20260616">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Keterangan Alasan Logistik <span class="text-rose-500">*</span></label>
            <textarea name="keterangan" rows="3" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Contoh: Pembuangan obat parasetamol yang hancur/rusak akibat lembab..."></textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Log Transaksi</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>