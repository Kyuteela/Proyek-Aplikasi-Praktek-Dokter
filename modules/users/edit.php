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

$user_id = mysqli_real_escape_string($conn, $_GET['id']);
$user_data = mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$user_id'");

if (mysqli_num_rows($user_data) === 0) {
    header("Location: index.php");
    exit;
}

$u = mysqli_fetch_assoc($user_data);
$roles = mysqli_query($conn, "SELECT id_role, nama_role FROM role ORDER BY id_role ASC");
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $kontak = mysqli_real_escape_string($conn, $_POST['kontak']);
    $id_role = mysqli_real_escape_string($conn, $_POST['id_role']);

    // Logika pengisian password: Jika form password dikosongkan, gunakan password lama
    $password = !empty($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : $u['password'];

    try {
        $update_query = "UPDATE user SET nama = '$nama', username = '$username', 
                                password = '$password', kontak = '$kontak', id_role = '$id_role' 
                         WHERE user_id = '$user_id'";

        if (mysqli_query($conn, $update_query)) {
            // Jika user mengedit akunnya sendiri, perbarui data session agar sinkron di topbar navbar
            if ($user_id == $_SESSION['user_id']) {
                $_SESSION['nama'] = $nama;
                $_SESSION['id_role'] = $id_role;
            }
            header("Location: index.php?status=success&msg=" . urlencode("Data pengguna berhasil diperbarui!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        $error = "Gagal memperbarui: Username '" . htmlspecialchars($username) . "' sudah terlanjur dipakai orang lain!";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800"><i class="bi bi-pencil-shadow text-emerald-500 mr-2"></i> Perbarui Konfigurasi User</h2>
        <a href="index.php" class="text-xs text-gray-500">Batal</a>
    </div>

    <?php if (!empty($error)) : ?>
        <div class="m-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl text-sm flex items-center space-x-2"><i class="bi bi-exclamation-octagon-fill text-rose-500"></i><span><?= $error; ?></span></div>
    <?php endif; ?>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap Staf <span class="text-rose-500">*</span></label>
            <input type="text" name="nama" required value="<?= htmlspecialchars($u['nama']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Username Kredensial <span class="text-rose-500">*</span></label>
                <input type="text" name="username" required value="<?= htmlspecialchars($u['username']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Ganti Password <span class="text-gray-400 font-normal">(Kosongkan jika tetap)</span></label>
                <input type="password" name="password" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Masukkan password baru">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Hak Akses Sistem Otoritas <span class="text-rose-500">*</span></label>
                <select name="id_role" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                    <?php while ($r = mysqli_fetch_assoc($roles)): ?>
                        <option value="<?= $r['id_role'] ?>" <?= $u['id_role'] == $r['id_role'] ? 'selected' : '' ?>><?= htmlspecialchars($r['nama_role']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nomor Kontak internal</label>
                <input type="text" name="kontak" value="<?= htmlspecialchars($u['kontak']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="w-full px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow">Simpan Perubahan Akun</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>