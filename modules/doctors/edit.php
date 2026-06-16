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

$doctor_id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM dokter WHERE doctor_id = '$doctor_id'");

if (mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit;
}

$doctor = mysqli_fetch_assoc($result);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $sip_no = mysqli_real_escape_string($conn, $_POST['sip_no']);
    $spesialisasi = mysqli_real_escape_string($conn, $_POST['spesialisasi']);

    $update_query = "UPDATE dokter SET nama = '$nama', sip_no = '$sip_no', spesialisasi = '$spesialisasi' WHERE doctor_id = '$doctor_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php?status=success&msg=" . urlencode("Profil data dokter berhasil diperbarui!"));
        exit;
    } else {
        $error = "Gagal memperbarui data dokter.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800"><i class="bi bi-pencil-square text-emerald-500 mr-2"></i> Edit Data Dokter</h2>
        <a href="index.php" class="text-xs text-gray-500">Batal</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap Dokter <span class="text-rose-500">*</span></label>
            <input type="text" name="nama" required value="<?= htmlspecialchars($doctor['nama']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nomor SIP</label>
            <input type="text" name="sip_no" required value="<?= htmlspecialchars($doctor['sip_no']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Spesialisasi Klinis</label>
            <input type="text" name="spesialisasi" required value="<?= htmlspecialchars($doctor['spesialisasi']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="w-full px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>