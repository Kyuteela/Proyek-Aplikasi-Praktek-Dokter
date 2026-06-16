<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

$detail_tagihan_id = mysqli_real_escape_string($conn, $_GET['id']);
$item = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM detail_tagihan WHERE detail_tagihan_id = '$detail_tagihan_id'"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis_item = mysqli_real_escape_string($conn, $_POST['jenis_item']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga_satuan = mysqli_real_escape_string($conn, $_POST['harga_satuan']);

    // Mengambil nilai selisih harga baru dan lama untuk mengkoreksi total invoice utama
    $selisih = $harga_satuan - $item['harga_satuan'];

    $update_detail = "UPDATE detail_tagihan SET jenis_item = '$jenis_item', deskripsi = '$deskripsi', harga_satuan = '$harga_satuan' WHERE detail_tagihan_id = '$detail_tagihan_id'";
    if (mysqli_query($conn, $update_detail)) {
        mysqli_query($conn, "UPDATE tagihan SET total_tagihan = total_tagihan + $selisih WHERE tagihan_id = '{$item['tagihan_id']}'");
        header("Location: details.php?id=" . $item['tagihan_id']);
        exit;
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-md mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
        <h2 class="text-sm font-bold text-gray-700">Ubah Rincian Item Biaya</h2>
    </div>
    <form action="" method="POST" class="p-6 space-y-4">
        <div><label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kategori</label>
            <select name="jenis_item" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
                <option value="Tindakan Medis" <?= $item['jenis_item'] === 'Tindakan Medis' ? 'selected' : '' ?>>Jasa Tindakan Medis</option>
                <option value="Obat Farmasi" <?= $item['jenis_item'] === 'Obat Farmasi' ? 'selected' : '' ?>>Resep Obat / Alkes</option>
                <option value="Laboratorium" <?= $item['jenis_item'] === 'Laboratorium' ? 'selected' : '' ?>>Hasil Tes Penunjang / Lab</option>
                <option value="Administrasi" <?= $item['jenis_item'] === 'Administrasi' ? 'selected' : '' ?>>Biaya Administrasi Klinik</option>
            </select>
        </div>
        <div><label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Deskripsi</label>
            <input type="text" name="deskripsi" required value="<?= htmlspecialchars($item['deskripsi']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
        </div>
        <div><label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Harga Satuan (Rp)</label>
            <input type="number" name="harga_satuan" required value="<?= intval($item['harga_satuan']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
        </div>
        <div class="pt-4 flex justify-end space-x-2">
            <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold transition">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>