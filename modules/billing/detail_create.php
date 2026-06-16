<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

$tagihan_id = mysqli_real_escape_string($conn, $_GET['tagihan_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis_item = mysqli_real_escape_string($conn, $_POST['jenis_item']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga_satuan = mysqli_real_escape_string($conn, $_POST['harga_satuan']);

    // Input baris baru ke detail_tagihan
    $query = "INSERT INTO detail_tagihan (tagihan_id, jenis_item, deskripsi, tanggal_tagihan, harga_satuan, sisa_piutang) 
              VALUES ('$tagihan_id', '$jenis_item', '$deskripsi', CURDATE(), '$harga_satuan', 0)";

    if (mysqli_query($conn, $query)) {
        header("Location: details.php?id=" . $tagihan_id);
        exit;
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-md mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
        <h2 class="text-sm font-bold text-gray-700">Tambah Item Biaya Invoice</h2>
    </div>
    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kategori Item Biaya</label>
            <select name="jenis_item" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
                <option value="Tindakan Medis">Jasa Tindakan Medis</option>
                <option value="Obat Farmasi">Resep Obat / Alkes</option>
                <option value="Laboratorium">Hasil Tes Penunjang / Lab</option>
                <option value="Administrasi">Biaya Administrasi Klinik</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Deskripsi Item</label>
            <input type="text" name="deskripsi" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none" placeholder="Contoh: Paracetamol Tablet 500mg">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Harga Satuan (Rp)</label>
            <input type="number" name="harga_satuan" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none" placeholder="0">
        </div>
        <div class="pt-4 flex justify-end space-x-2">
            <a href="details.php?id=<?= $tagihan_id ?>" class="px-4 py-2 bg-gray-100 rounded-xl text-sm font-medium">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">Input Item</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>