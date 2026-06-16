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

$obat_id = mysqli_real_escape_string($conn, $_GET['id']);
$query_fetch = "SELECT * FROM obat WHERE obat_id = '$obat_id'";
$result_fetch = mysqli_query($conn, $query_fetch);

if (mysqli_num_rows($result_fetch) === 0) {
    header("Location: index.php");
    exit;
}

$medicine = mysqli_fetch_assoc($result_fetch);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_obat = mysqli_real_escape_string($conn, $_POST['nama_obat']);
    $bentuk_sediaan = mysqli_real_escape_string($conn, $_POST['bentuk_sediaan']);
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);

    $update_query = "UPDATE obat SET nama_obat = '$nama_obat', bentuk_sediaan = '$bentuk_sediaan', 
                            satuan = '$satuan', kategori = '$kategori' WHERE obat_id = '$obat_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php?status=success&msg=" . urlencode("Detail katalog obat berhasil diperbarui!"));
        exit;
    } else {
        $error = "Gagal memperbarui data katalog obat.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800"><i class="bi bi-pencil-square text-emerald-500 mr-2"></i> Perbarui Detail Obat</h2>
        <a href="index.php" class="text-xs text-gray-500">Batal</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap Obat <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_obat" required value="<?= htmlspecialchars($medicine['nama_obat']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Bentuk Sediaan <span class="text-rose-500">*</span></label>
                <input type="text" name="bentuk_sediaan" required value="<?= htmlspecialchars($medicine['bentuk_sediaan']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Satuan Unit <span class="text-rose-500">*</span></label>
                <input type="text" name="satuan" required value="<?= htmlspecialchars($medicine['satuan']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kategori Obat <span class="text-rose-500">*</span></label>
            <input type="text" name="kategori" required value="<?= htmlspecialchars($medicine['kategori']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="w-full px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Perubahan Katalog</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>