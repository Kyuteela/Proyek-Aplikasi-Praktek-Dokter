<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

$resep_id = mysqli_real_escape_string($conn, $_GET['resep_id']);
$obat_list = mysqli_query($conn, "SELECT obat_id, nama_obat FROM obat ORDER BY nama_obat ASC");
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $obat_id = mysqli_real_escape_string($conn, $_POST['obat_id']);
    $dosis = mysqli_real_escape_string($conn, $_POST['dosis']);
    $rute = mysqli_real_escape_string($conn, $_POST['rute']);
    $frekuensi = mysqli_real_escape_string($conn, $_POST['frekuensi']);
    $durasi = mysqli_real_escape_string($conn, $_POST['durasi']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $instruksi_khusus = mysqli_real_escape_string($conn, $_POST['instruksi_khusus']);

    try {
        // Menyisipkan entitas butir obat resep
        $query = "INSERT INTO detail_resep (resep_id, obat_id, dosis, rute, frekuensi, durasi, jumlah, instruksi_khusus) 
                  VALUES ('$resep_id', '$obat_id', '$dosis', '$rute', '$frekuensi', '$durasi', '$jumlah', '$instruksi_khusus')";
        if (mysqli_query($conn, $query)) {
            header("Location: details.php?id=" . $resep_id);
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap error jika melanggar check constraint chk_jumlah_obat
        $error = "Aturan Bisnis Gagal: Jumlah kuantitas obat harus lebih besar dari 0 unit!";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-md mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
        <h2 class="text-sm font-bold text-gray-700">Tambah Komponen Obat</h2>
    </div>
    <?php if (!empty($error)): ?><div class="m-4 p-3 bg-rose-50 text-rose-700 text-xs border-l-4 border-rose-500 rounded-xl"><?= $error; ?></div><?php endif; ?>
    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pilih Obat Katalog</label>
            <select name="obat_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
                <?php while ($o = mysqli_fetch_assoc($obat_list)): ?><option value="<?= $o['obat_id'] ?>"><?= htmlspecialchars($o['nama_obat']) ?></option><?php endwhile; ?>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div><label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dosis (e.g. 500mg)</label><input type="text" name="dosis" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none"></div>
            <div><label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kuantitas Qty</label><input type="number" name="jumlah" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none" placeholder="0"></div>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <div><label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Rute</label><input type="text" name="rute" value="Oral" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none"></div>
            <div><label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Frekuensi</label><input type="text" name="frekuensi" value="3x sehari" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none"></div>
            <div><label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Durasi</label><input type="text" name="durasi" value="5 hari" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none"></div>
        </div>
        <div><label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Instruksi Khusus</label><input type="text" name="instruksi_khusus" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none" value="Sesudah makan"></div>
        <div class="pt-4 flex justify-end space-x-2"><a href="details.php?id=<?= $resep_id ?>" class="px-4 py-2 bg-gray-100 rounded-xl text-sm font-medium">Batal</a><button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-xl text-sm font-semibold shadow">Input Racikan</button></div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>