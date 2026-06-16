<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// MEMAKSIMALKAN DATABASE: Memanggil View dari Tahap 2
$query = "SELECT * FROM vw_tagihan_pasien ORDER BY tagihan_id DESC";
$result = mysqli_query($conn, $query);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-cash-stack text-amber-500 mr-2"></i> Pusat Kasir & Tagihan Pasien
            </h2>
            <p class="text-xs text-gray-400 mt-1">Kelola pembuatan invoice baru, rincian item layanan, kalkulasi nominal, dan status pelunasan.</p>
        </div>
        <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow flex items-center space-x-2 transition self-start sm:self-auto">
            <i class="bi bi-file-earmark-plus"></i>
            <span>Buat Invoice Baru</span>
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
                    <th class="p-4">No. Invoice</th>
                    <th class="p-4">Nama Pasien</th>
                    <th class="p-4">Dokter Pemeriksa</th>
                    <th class="p-4">Tanggal Invoice</th>
                    <th class="p-4">Total Netto</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-600">
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono font-bold text-gray-700">#INV-<?= str_pad($row['tagihan_id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td class="p-4 font-bold text-gray-800"><?= htmlspecialchars($row['nama_pasien']); ?></td>
                            <td class="p-4"><?= htmlspecialchars($row['nama_dokter']); ?></td>
                            <td class="p-4"><?= date('d M Y', strtotime($row['tanggal_tagihan'])); ?></td>
                            <td class="p-4 font-semibold text-gray-800">Rp <?= number_format(($row['total_tagihan'] - $row['diskon']), 0, ',', '.'); ?></td>
                            <td class="p-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border <?= $row['status'] === 'Lunas' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200 animate-pulse'; ?>">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="details.php?id=<?= $row['tagihan_id']; ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" title="Rincian Item"><i class="bi bi-eye-fill"></i></a>
                                    <a href="edit.php?id=<?= $row['tagihan_id']; ?>" class="text-amber-600 hover:bg-amber-50 p-2 rounded-lg transition" title="Ubah Pembayaran"><i class="bi bi-pencil-square"></i></a>
                                    <a href="delete.php?id=<?= $row['tagihan_id']; ?>" onclick="return confirm('Hapus berkas invoice ini?')" class="text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition" title="Hapus"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-400 italic">Data invoice tagihan masih kosong.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>