<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $sip_no = mysqli_real_escape_string($conn, $_POST['sip_no']);
    $spesialisasi = mysqli_real_escape_string($conn, $_POST['spesialisasi']);

    $query = "INSERT INTO dokter (nama, sip_no, spesialisasi) VALUES ('$nama', '$sip_no', '$spesialisasi')";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php?status=success&msg=" . urlencode("Data dokter baru berhasil didaftarkan!"));
        exit;
    } else {
        $error = "Gagal menyimpan data dokter ke sistem.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="bi bi-person-plus-fill text-blue-500 mr-2"></i> Tambah Dokter Baru
        </h2>
        <a href="index.php" class="text-xs text-gray-500 hover:text-gray-700 flex items-center"><i class="bi bi-arrow-left mr-1"></i> Kembali</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap Dokter <span class="text-rose-500">*</span></label>
            <input type="text" name="nama" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Dr. Andi Pratama">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nomor SIP (Surat Izin Praktik) <span class="text-rose-500">*</span></label>
            <input type="text" name="sip_no" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: SIP001">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Spesialisasi Klinis <span class="text-rose-500">*</span></label>
            <input type="text" name="spesialisasi" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Umum, Anak, Mata, Bedah">
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Dokter</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>