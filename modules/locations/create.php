<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lokasi = mysqli_real_escape_string($conn, $_POST['nama_lokasi']);
    $tipe_lokasi = mysqli_real_escape_string($conn, $_POST['tipe_lokasi']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Query penambahan data lokasi
    $query_insert = "INSERT INTO lokasi (nama_lokasi, tipe_lokasi, deskripsi) VALUES ('$nama_lokasi', '$tipe_lokasi', '$deskripsi')";

    if (mysqli_query($conn, $query_insert)) {
        header("Location: index.php?status=success&msg=" . urlencode("Zonasi lokasi baru berhasil didaftarkan ke sistem!"));
        exit;
    } else {
        $error = "Gagal memproses pendaftaran komponen lokasi baru.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="bi bi-geo-fill text-blue-500 mr-2"></i> Daftarkan Komponen Lokasi Baru
        </h2>
        <a href="index.php" class="text-xs text-gray-500 hover:text-gray-700"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Unit Lokasi / Ruangan <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_lokasi" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Rak C3, Ruang Tindakan II">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tipe Klasifikasi Kategori <span class="text-rose-500">*</span></label>
            <select name="tipe_lokasi" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                <option value="">-- Pilih Tipe Kategori --</option>
                <option value="Ruangan">Ruangan (Unit Operasional Medis)</option>
                <option value="Gudang">Gudang (Penyimpanan Logistik Makro)</option>
                <option value="Rak">Rak (Sekat Inventori Obat Mikro)</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Catatan Keterangan Tambahan</label>
            <textarea name="deskripsi" rows="3" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Tulis deskripsi fungsional penempatan lokasi..."></textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Parameter</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>