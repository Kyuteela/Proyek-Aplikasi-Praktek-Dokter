<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// Mengambil seluruh data user digabungkan dengan nama role mereka
$query = "SELECT u.user_id, u.nama, u.username, u.kontak, r.nama_role 
          FROM user u 
          INNER JOIN role r ON u.id_role = r.id_role 
          ORDER BY u.user_id DESC";
$result = mysqli_query($conn, $query);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-person-gear text-slate-600 mr-2"></i> Manajemen Akun Pengguna & Staf
            </h2>
            <p class="text-xs text-gray-400 mt-1">Kelola kredensial login, hak kases sistem, serta informasi kontak internal operasional klinik.</p>
        </div>
        <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow flex items-center justify-center space-x-2 transition self-start sm:self-auto">
            <i class="bi bi-person-plus"></i>
            <span>Tambah User Baru</span>
        </a>
    </div>

    <?php if ($status === 'success') : ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl mb-6 flex items-center space-x-3 text-sm">
            <i class="bi bi-check-circle-fill text-xl text-emerald-500"></i>
            <span><?= htmlspecialchars($msg); ?></span>
        </div>
    <?php elseif ($status === 'error') : ?>
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl mb-6 flex items-center space-x-3 text-sm">
            <i class="bi bi-exclamation-octagon-fill text-xl text-rose-500"></i>
            <span><?= htmlspecialchars($msg); ?></span>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto rounded-xl border border-gray-100">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="p-4">ID User</th>
                    <th class="p-4">Nama Lengkap</th>
                    <th class="p-4">Username Kredensial</th>
                    <th class="p-4">Hak Akses Role</th>
                    <th class="p-4">No. Kontak</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-600">
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono text-xs text-gray-400">#USR-<?= str_pad($row['user_id'], 3, '0', STR_PAD_LEFT); ?></td>
                            <td class="p-4 font-bold text-gray-800 text-base"><?= htmlspecialchars($row['nama']); ?></td>
                            <td class="p-4 font-mono text-xs text-blue-600 bg-blue-50/50 border rounded px-2 py-0.5 inline-block mt-3 ml-4"><?= htmlspecialchars($row['username']); ?></td>
                            <td class="p-4">
                                <span class="bg-slate-100 text-slate-800 border px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                    <i class="bi bi-shield-lock mr-1"></i> <?= htmlspecialchars($row['nama_role']); ?>
                                </span>
                            </td>
                            <td class="p-4 font-medium"><?= htmlspecialchars($row['kontak'] ?: '-'); ?></td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="edit.php?id=<?= $row['user_id']; ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" title="Edit Akun"><i class="bi bi-pencil-square"></i></a>
                                    <?php if ($row['user_id'] != $_SESSION['user_id']) : ?>
                                        <a href="delete.php?id=<?= $row['user_id']; ?>" onclick="return confirm('Hapus permanen akun pengguna ini?')" class="text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition" title="Hapus Akun"><i class="bi bi-trash"></i></a>
                                    <?php else : ?>
                                        <span class="text-gray-300 p-2 text-xs italic">(Aktif)</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400 italic">Belum ada data akun user terdaftar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>