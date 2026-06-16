<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
$error = '';

// Mengambil data master role untuk pilihan jabatan di form
$roles = mysqli_query($conn, "SELECT id_role, nama_role FROM role ORDER BY id_role ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Menggunakan plain text sesuai skrip pendaftaran awal kelompok
    $kontak = mysqli_real_escape_string($conn, $_POST['kontak']);
    $id_role = mysqli_real_escape_string($conn, $_POST['id_role']);

    try {
        $query_insert = "INSERT INTO user (nama, username, password, kontak, id_role) 
                         VALUES ('$nama', '$username', '$password', '$kontak', '$id_role')";

        if (mysqli_query($conn, $query_insert)) {
            header("Location: index.php?status=success&msg=" . urlencode("Akun pengguna baru berhasil didaftarkan!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap error jika username kembar (Unique Constraint Violation)
        $error = "Gagal mendaftarkan: Username '" . htmlspecialchars($username) . "' sudah digunakan oleh staf lain!";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center"><i class="bi bi-person-plus text-blue-500 mr-2"></i> Pendaftaran Akun Pengguna</h2>
        <a href="index.php" class="text-xs text-gray-500 hover:text-gray-700"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if (!empty($error)) : ?>
        <div class="m-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl text-sm flex items-center space-x-2">
            <i class="bi bi-exclamation-octagon-fill text-rose-500"></i>
            <span><?= $error; ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap Staf <span class="text-rose-500">*</span></label>
            <input type="text" name="nama" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Masukkan nama lengkap staf">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Username Kredensial <span class="text-rose-500">*</span></label>
                <input type="text" name="username" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Contoh: sinta_apoteker">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kata Sandi / Password <span class="text-rose-500">*</span></label>
                <input type="password" name="password" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="••••••••">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Hak Akses Sistem Otoritas <span class="text-rose-500">*</span></label>
                <select name="id_role" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                    <option value="">-- Pilih Hak Akses --</option>
                    <?php while ($r = mysqli_fetch_assoc($roles)): ?>
                        <option value="<?= $r['id_role'] ?>"><?= htmlspecialchars($r['nama_role']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nomor Kontak internal</label>
                <input type="text" name="kontak" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="08XXXXXXXXXX">
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan User</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>