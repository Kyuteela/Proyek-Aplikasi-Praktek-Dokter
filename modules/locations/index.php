<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// Mengambil data master lokasi dari database
$query = "SELECT * FROM lokasi ORDER BY lokasi_id DESC";
$result = mysqli_query($conn, $query);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-geo-alt-fill text-pink-500 mr-2"></i> Master Lokasi & Zonasi Klinik
            </h2>
            <p class="text-xs text-gray-400 mt-1">Kelola data master ruang pemeriksaan medis, unit laboratorium, gudang logistik, hingga nomor sekat rak obat.</p>
        </div>
        <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow flex items-center justify-center space-x-2 transition self-start sm:self-auto">
            <i class="bi bi-plus-circle"></i>
            <span>Tambah Lokasi Baru</span>
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
                    <th class="p-4">ID Lokasi</th>
                    <th class="p-4">Nama Zona / Ruangan</th>
                    <th class="p-4">Tipe Kategori</th>
                    <th class="p-4">Deskripsi Otoritas</th>
                    <th class="p-4 text-center">Aksi Kontrol</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-600">
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono font-bold text-gray-700">#LOC-<?= str_pad($row['lokasi_id'], 3, '0', STR_PAD_LEFT); ?></td>
                            <td class="p-4 font-bold text-gray-800 text-base"><?= htmlspecialchars($row['nama_lokasi']); ?></td>
                            <td class="p-4">
                                <?php
                                // Pewarnaan label badge dinamis berdasarkan kategori tipe lokasi
                                $badgeStyle = 'bg-slate-100 text-slate-700 border-slate-200';
                                if ($row['tipe_lokasi'] === 'Gudang') $badgeStyle = 'bg-orange-50 text-orange-700 border-orange-200';
                                if ($row['tipe_lokasi'] === 'Rak') $badgeStyle = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                                if ($row['tipe_lokasi'] === 'Ruangan') $badgeStyle = 'bg-teal-50 text-teal-700 border-teal-200';
                                ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border <?= $badgeStyle; ?>">
                                    <?= htmlspecialchars($row['tipe_lokasi']); ?>
                                </span>
                            </td>
                            <td class="p-4 text-xs max-w-xs truncate"><?= htmlspecialchars($row['deskripsi'] ?: '-'); ?></td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="edit.php?id=<?= $row['lokasi_id']; ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" title="Ubah Parameter"><i class="bi bi-pencil-square"></i></a>
                                    <a href="delete.php?id=<?= $row['lokasi_id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus zonasi master lokasi ini?')" class="text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition" title="Hapus"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400 italic">Data master pemetaan lokasi masih kosong.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>