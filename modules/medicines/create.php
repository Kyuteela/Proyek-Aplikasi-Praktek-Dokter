<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_obat = mysqli_real_escape_string($conn, $_POST['nama_obat']);
    $bentuk_sediaan = mysqli_real_escape_string($conn, $_POST['bentuk_sediaan']);
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);

    $query_insert = "INSERT INTO obat (nama_obat, bentuk_sediaan, satuan, kategori) 
                     VALUES ('$nama_obat', '$bentuk_sediaan', '$satuan', '$kategori')";

    if (mysqli_query($conn, $query_insert)) {
        header("Location: index.php?status=success&msg=" . urlencode("Komoditas obat baru berhasil ditambahkan!"));
        exit;
    } else {
        $error = "Gagal mendaftarkan item obat baru.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="bi bi-capsule mr-2 text-blue-500"></i> Tambah Katalog Obat Baru
        </h2>
        <a href="index.php" class="text-xs text-gray-500 hover:text-gray-700"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap Obat <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_obat" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Paracetamol">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Bentuk Sediaan <span class="text-rose-500">*</span></label>
                <input type="text" name="bentuk_sediaan" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Contoh: Tablet, Kapsul, Sirup">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Satuan Unit <span class="text-rose-500">*</span></label>
                <input type="text" name="satuan" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Contoh: Strip, Botol, Sachet">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kategori Obat <span class="text-rose-500">*</span></label>
            <input type="text" name="kategori" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Contoh: Analgesik, Antibiotik, Vitamin">
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan ke Katalog</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>