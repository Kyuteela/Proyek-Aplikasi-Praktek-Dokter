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

$patient_id = mysqli_real_escape_string($conn, $_GET['id']);
$query_fetch = "SELECT * FROM pasien WHERE patient_id = '$patient_id'";
$result_fetch = mysqli_query($conn, $query_fetch);

if (mysqli_num_rows($result_fetch) === 0) {
    header("Location: index.php");
    exit;
}

$patient = mysqli_fetch_assoc($result_fetch);
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

    $update_query = "UPDATE pasien SET 
                        nik = '$nik', nama = '$nama', tgl_lahir = '$tgl_lahir', 
                        jenis_kelamin = '$jenis_kelamin', alamat = '$alamat', 
                        no_telepon = '$no_telepon', email = '$email', 
                        kontak_darurat = '$kontak_darurat', asuransi_id = '$asuransi_id' 
                     WHERE patient_id = '$patient_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php?status=success&msg=" . urlencode("Profil metadata identitas pasien berhasil diperbarui!"));
        exit;
    } else {
        $error = "Gagal memperbarui konfigurasi data kependudukan.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-2xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800"><i class="bi bi-pencil-square text-emerald-500 mr-2"></i> Perbarui Profil Pasien</h2>
        <a href="index.php" class="text-xs text-gray-500">Batal</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nomor NIK Kependudukan</label>
                <input type="number" name="nik" required value="<?= htmlspecialchars($patient['nik']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap Pasien</label>
                <input type="text" name="nama" required value="<?= htmlspecialchars($patient['nama']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" required value="<?= $patient['tgl_lahir'] ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                    <option value="L" <?= $patient['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki (L)</option>
                    <option value="P" <?= $patient['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan (P)</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Alamat Rumah</label>
            <textarea name="alamat" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm"><?= htmlspecialchars($patient['alamat']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">No. Telepon / HP</label>
                <input type="text" name="no_telepon" value="<?= htmlspecialchars($patient['no_telepon']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($patient['email']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kontak Darurat Wali</label>
                <input type="text" name="kontak_darurat" value="<?= htmlspecialchars($patient['kontak_darurat']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">No Jaminan (BPJS)</label>
                <input type="text" name="asuransi_id" value="<?= htmlspecialchars($patient['asuransi_id']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="w-full px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Sinkronisasi</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>