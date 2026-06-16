<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
$error = '';

// Ambil data referensi untuk dropdown form
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

    try {
        // MAKSIMALKAN DATABASE: Memanggil Stored Procedure buatan Derryl
        $query_sp = "CALL sp_tambah_stok_obat('$obat_id', '$gr_id', '$lokasi_id', '$expiry_date', '$harga_beli', '$lokasi_rak')";

        if (mysqli_query($conn, $query_sp)) {
            header("Location: index.php?status=success&msg=" . urlencode("Batch obat baru sukses ditambahkan ke gudang!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap sinyal error dari trg_validasi_harga_beli jika harga <= 0
        $error = "Aturan Bisnis Gagal: " . $e->getMessage();
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-2xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="bi bi-tags-fill text-blue-500 mr-2"></i> Input Batch Obat Baru
        </h2>
        <a href="index.php" class="text-sm text-gray-500 hover:text-gray-700 flex items-center"><i class="bi bi-arrow-left mr-1"></i> Kembali</a>
    </div>

    <?php if (!empty($error)) : ?>
        <div class="m-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl flex items-center space-x-3 text-sm">
            <i class="bi bi-exclamation-octagon-fill text-xl text-rose-500"></i>
            <span><?= $error; ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pilih Nama Obat <span class="text-rose-500">*</span></label>
            <select name="obat_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">-- Pilih Obat --</option>
                <?php while ($o = mysqli_fetch_assoc($master_obat)): ?>
                    <option value="<?= $o['obat_id'] ?>"><?= htmlspecialchars($o['nama_obat']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">No. Faktur Penerimaan <span class="text-rose-500">*</span></label>
                <select name="gr_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <option value="">-- Pilih Faktur --</option>
                    <?php while ($g = mysqli_fetch_assoc($master_gr)): ?>
                        <option value="<?= $g['gr_id'] ?>"><?= htmlspecialchars($g['faktur_no']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Lokasi Gudang Utama <span class="text-rose-500">*</span></label>
                <select name="lokasi_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <option value="">-- Pilih Lokasi --</option>
                    <?php while ($l = mysqli_fetch_assoc($master_lokasi)): ?>
                        <option value="<?= $l['lokasi_id'] ?>"><?= htmlspecialchars($l['nama_lokasi']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Kedaluwarsa <span class="text-rose-500">*</span></label>
                <input type="date" name="expiry_date" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Harga Beli Unit <span class="text-rose-500">*</span></label>
                <input type="number" name="harga_beli" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: 15000">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Posisi Nomor Rak</label>
                <input type="text" name="lokasi_rak" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: A02">
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan ke Inventori</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>