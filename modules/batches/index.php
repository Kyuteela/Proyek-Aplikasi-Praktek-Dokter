<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

// Gunakan __DIR__ agar penemuan file koneksi database terkunci dengan aman
require_once __DIR__ . '/../../config/koneksi.php';

// Menangkap kiriman flash message status dari file create.php / delete.php
$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// MAKSIMALKAN DATABASE: Mengambil data dari View vw_stok_obat buatan Derryl
$query = "SELECT * FROM vw_stok_obat ORDER BY batch_id DESC";
$result = mysqli_query($conn, $query);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-tags-fill text-blue-500 mr-2"></i> Manajemen Kendali Batch Obat
            </h2>
            <p class="text-xs text-gray-400 mt-1">Mengisolasikan alokasi stok komoditas obat, penempatan nomor rak, dan deteksi masa kedalwarsa gudang.</p>
        </div>
        <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow flex items-center justify-center space-x-2 transition-all self-start sm:self-auto">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Input Batch Baru</span>
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
                    <th class="p-4">No. Log Batch</th>
                    <th class="p-4">Kandungan Obat</th>
                    <th class="p-4">Alokasi Penyimpanan</th>
                    <th class="p-4">Masa Kedaluwarsa</th>
                    <th class="p-4">Harga Beli Unit</th>
                    <th class="p-4 text-center">Aksi Kontrol</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-600">
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <?php
                        // Menghitung masa kedaluwarsa obat (jika di bawah 3 bulan akan berkedip kuning)
                        $expiry_timestamp = strtotime($row['expiry_date']);
                        $three_months_buffer = time() + (3 * 30 * 24 * 60 * 60);
                        $is_near_expired = $expiry_timestamp <= $three_months_buffer;
                        ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono font-bold text-gray-700">#BCH-<?= str_pad($row['batch_id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td class="p-4 font-bold text-gray-800 text-base"><?= htmlspecialchars($row['nama_obat']); ?></td>
                            <td class="p-4">
                                <span class="block text-gray-700 font-medium"><?= htmlspecialchars($row['nama_lokasi']); ?></span>
                                <span class="text-xs text-gray-400 block mt-0.5"><i class="bi bi-layer-forward"></i> Posisi Rak: <?= htmlspecialchars($row['lokasi_rak'] ?: '-'); ?></span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border inline-flex items-center <?= $is_near_expired ? 'bg-amber-50 text-amber-700 border-amber-200 animate-pulse' : 'bg-blue-50 text-blue-700 border-blue-200'; ?>">
                                    <i class="bi bi-calendar-event mr-1"></i> <?= date('d M Y', $expiry_timestamp); ?>
                                </span>
                            </td>
                            <td class="p-4 font-medium text-gray-700">Rp <?= number_format($row['harga_beli'], 0, ',', '.'); ?></td>
                            <td class="p-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="edit.php?id=<?= $row['batch_id']; ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" title="Ubah Log">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="delete.php?id=<?= $row['batch_id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pencatatan batch lot obat ini dari gudang?')" class="text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition" title="Hapus Log">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400 italic">Data batch obat di gudang masih kosong.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>