<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// Mengambil data mutasi stok dengan join ke batch dan master katalog obat
$query = "SELECT ts.*, o.nama_obat, bo.lokasi_rak 
          FROM transaksi_stok ts
          INNER JOIN batch_obat bo ON ts.batch_id = bo.batch_id
          INNER JOIN obat o ON bo.obat_id = o.obat_id
          ORDER BY ts.transaksi_stok_id DESC";
$result = mysqli_query($conn, $query);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-arrow-left-right text-emerald-500 mr-2"></i> Log Transaksi Mutasi Stok Obat
            </h2>
            <p class="text-xs text-gray-400 mt-1">Audit log inventori pemantauan real-time sirkulasi keluar-masuk komoditas farmasi klinik.</p>
        </div>
        <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow flex items-center justify-center space-x-2 transition self-start sm:self-auto">
            <i class="bi bi-plus-circle"></i>
            <span>Pencatatan Stok Manual</span>
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
                    <th class="p-4">ID Transaksi</th>
                    <th class="p-4">Waktu & Tanggal</th>
                    <th class="p-4">Komoditas Obat (Batch)</th>
                    <th class="p-4 text-center">Jenis Mutasi</th>
                    <th class="p-4 text-center">Kuantitas Qty</th>
                    <th class="p-4">Nomor Referensi</th>
                    <th class="p-4">Keterangan Alasan</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-600">
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono text-xs">#STK-<?= str_pad($row['transaksi_stok_id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td class="p-4 text-xs"><?= date('d M Y H:i', strtotime($row['tanggal'])); ?></td>
                            <td class="p-4">
                                <span class="block font-bold text-gray-800"><?= htmlspecialchars($row['nama_obat']); ?></span>
                                <span class="text-[11px] text-gray-400 block mt-0.5">Kode Lot: <strong>#BCH-<?= $row['batch_id']; ?></strong> | Rak: <?= htmlspecialchars($row['lokasi_rak'] ?: '-'); ?></span>
                            </td>
                            <td class="p-4 text-center">
                                <?php if ($row['jenis_transaksi'] === 'MASUK' || $row['jenis_transaksi'] === 'Penerimaan barang') : ?>
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs px-2.5 py-0.5 rounded-full font-bold">MASUK</span>
                                <?php else : ?>
                                    <span class="bg-rose-50 text-rose-700 border border-rose-200 text-xs px-2.5 py-0.5 rounded-full font-bold">KELUAR</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-center font-mono font-bold text-gray-700"><?= number_format($row['jumlah'], 0, ',', '.'); ?> Pcs</td>
                            <td class="p-4 font-medium text-xs text-gray-700"><?= htmlspecialchars($row['referensi'] ?: '-'); ?></td>
                            <td class="p-4 text-xs max-w-xs truncate" title="<?= htmlspecialchars($row['keterangan']); ?>"><?= htmlspecialchars($row['keterangan'] ?: '-'); ?></td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="edit.php?id=<?= $row['transaksi_stok_id']; ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" title="Koreksi Log"><i class="bi bi-pencil-square"></i></a>
                                    <a href="delete.php?id=<?= $row['transaksi_stok_id']; ?>" onclick="return confirm('Hapus record log penyesuaian stok ini?')" class="text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition" title="Hapus"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="p-8 text-center text-gray-400 italic">Belum ada rekaman sirkulasi mutasi stok yang dicatat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>