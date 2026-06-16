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

$lokasi_id = mysqli_real_escape_string($conn, $_GET['id']);
$query_fetch = "SELECT * FROM lokasi WHERE lokasi_id = '$lokasi_id'";
$result_fetch = mysqli_query($conn, $query_fetch);

if (mysqli_num_rows($result_fetch) === 0) {
    header("Location: index.php");
    exit;
}

$location = mysqli_fetch_assoc($result_fetch);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lokasi = mysqli_real_escape_string($conn, $_POST['nama_lokasi']);
    $tipe_lokasi = mysqli_real_escape_string($conn, $_POST['tipe_lokasi']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $update_query = "UPDATE lokasi SET nama_lokasi = '$nama_lokasi', tipe_lokasi = '$tipe_lokasi', deskripsi = '$deskripsi' WHERE lokasi_id = '$lokasi_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php?status=success&msg=" . urlencode("Informasi parameter lokasi sukses diperbarui!"));
        exit;
    } else {
        $error = "Gagal memperbarui konfigurasi data lokasi.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800"><i class="bi bi-pencil-square text-emerald-500 mr-2"></i> Edit Parameter Komponen Lokasi</h2>
        <a href="index.php" class="text-xs text-gray-500">Batal</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Unit Lokasi / Ruangan <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_lokasi" required value="<?= htmlspecialchars($location['nama_lokasi']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tipe Klasifikasi Kategori <span class="text-rose-500">*</span></label>
            <select name="tipe_lokasi" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                <option value="Ruangan" <?= $location['tipe_lokasi'] === 'Ruangan' ? 'selected' : '' ?>>Ruangan (Unit Operasional Medis)</option>
                <option value="Gudang" <?= $location['tipe_lokasi'] === 'Gudang' ? 'selected' : '' ?>>Gudang (Penyimpanan Logistik Makro)</option>
                <option value="Rak" <?= $location['tipe_lokasi'] === 'Rak' ? 'selected' : '' ?>>Rak (Sekat Inventori Obat Mikro)</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Catatan Keterangan Tambahan</label>
            <textarea name="deskripsi" rows="3" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm"><?= htmlspecialchars($location['deskripsi']) ?></textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="w-full px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Perubahan Data</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>