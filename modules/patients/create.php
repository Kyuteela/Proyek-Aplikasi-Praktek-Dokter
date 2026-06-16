<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tgl_lahir = mysqli_real_escape_string($conn, $_POST['tgl_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $kontak_darurat = mysqli_real_escape_string($conn, $_POST['kontak_darurat']);
    $asuransi_id = mysqli_real_escape_string($conn, $_POST['asuransi_id']);

    try {
        // Query insert memicu trg_validasi_nik dan trg_pasien_insert
        $query = "INSERT INTO pasien (nik, nama, tgl_lahir, jenis_kelamin, alamat, no_telepon, email, kontak_darurat, asuransi_id) 
                  VALUES ('$nik', '$nama', '$tgl_lahir', '$jenis_kelamin', '$alamat', '$no_telepon', '$email', '$kontak_darurat', '$asuransi_id')";

        if (mysqli_query($conn, $query)) {
            header("Location: index.php?status=success&msg=" . urlencode("Registrasi pasien baru sukses disimpan & masuk ke log audit klinik!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap error MESSAGE_TEXT dari trg_validasi_nik database
        $error = "Aturan Bisnis Gagal: " . $e->getMessage();
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-2xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="bi bi-person-plus-fill text-blue-500 mr-2"></i> Formulir Registrasi Pasien Baru
        </h2>
        <a href="index.php" class="text-xs text-gray-500 hover:text-gray-700 flex items-center"><i class="bi bi-arrow-left mr-1"></i> Kembali</a>
    </div>

    <?php if (!empty($error)) : ?>
        <div class="m-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl flex items-center space-x-3 text-sm">
            <i class="bi bi-exclamation-octagon-fill text-xl text-rose-500"></i>
            <span><?= $error; ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nomor NIK Kependudukan <span class="text-rose-500">*</span></label>
                <input type="number" name="nik" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: 350101XXXXXXXXXX">
                <span class="text-[10px] text-gray-400 mt-1 block">Wajib bernilai 16 digit (Validasi aturan trigger database).</span>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap Pasien <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Nama lengkap sesuai KTP">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Lahir <span class="text-rose-500">*</span></label>
                <input type="date" name="tgl_lahir" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                <select name="jenis_kelamin" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                    <option value="">-- Pilih Gender --</option>
                    <option value="L">Laki-laki (L)</option>
                    <option value="P">Perempuan (P)</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap Rumah</label>
            <textarea name="alamat" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Alamat domisili lengkap saat ini..."></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">No. Kontak / HP</label>
                <input type="text" name="no_telepon" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Contoh: 08XXXXXXXXXX">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Alamat Email</label>
                <input type="email" name="email" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="[email protected]">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kontak Darurat (Kerabat)</label>
                <input type="text" name="kontak_darurat" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Nama & No. HP wali">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nomor Kartu Jaminan (BPJS)</label>
                <input type="text" name="asuransi_id" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Kosongkan jika pasien reguler umum">
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Registrasi</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>