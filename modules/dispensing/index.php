<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// Mengambil log data penyerahan obat dengan teknik JOIN komprehensif
$query = "SELECT d.dispensing_id, o.nama_obat, dr.jumlah, dr.dosis, dr.frekuensi, 
                 d.edukasi_pasien, d.serah_terima, u.nama AS nama_petugas, p.nama AS nama_pasien
          FROM dispensing d
          INNER JOIN detail_resep dr ON d.detail_id = dr.detail_id
          INNER JOIN obat o ON d.obat_id = o.obat_id
          INNER JOIN resep r ON dr.resep_id = r.resep_id
          INNER JOIN rekam_medis rm ON r.record_id = rm.record_id
          INNER JOIN kunjungan k ON rm.visit_id = k.visit_id
          INNER JOIN pasien p ON k.patient_id = p.patient_id
          LEFT JOIN user u ON d.petugas_id = u.user_id
          ORDER BY d.dispensing_id DESC";
$result = mysqli_query($conn, $query);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-box-seam-fill text-orange-500 mr-2"></i> Log Dispensing & Penyerahan Obat
            </h2>
            <p class="text-xs text-gray-400 mt-1">Sistem pencatatan rekam penyerahan komoditas obat farmasi kepada pasien internal klinik.</p>
        </div>
        <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow flex items-center justify-center space-x-2 transition self-start sm:self-auto">
            <i class="bi bi-plus-circle"></i>
            <span>Proses Resep Baru</span>
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
                    <th class="p-4">No. Dispensing</th>
                    <th class="p-4">Nama Pasien</th>
                    <th class="p-4">Detail Resep Obat</th>
                    <th class="p-4">Instruksi Edukasi</th>
                    <th class="p-4">Status Terima</th>
                    <th class="p-4">Petugas</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-600">
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono font-bold text-gray-700">#DSP-<?= str_pad($row['dispensing_id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td class="p-4 font-bold text-gray-800"><?= htmlspecialchars($row['nama_pasien']); ?></td>
                            <td class="p-4">
                                <span class="block font-semibold text-blue-600 text-base"><?= htmlspecialchars($row['nama_obat']); ?></span>
                                <span class="text-xs text-gray-400 block mt-0.5">Jumlah: <strong><?= $row['jumlah']; ?> Qty</strong> | <?= htmlspecialchars($row['dosis']); ?> (<?= htmlspecialchars($row['frekuensi']); ?>)</span>
                            </td>
                            <td class="p-4 text-xs max-w-xs truncate" title="<?= htmlspecialchars($row['edukasi_pasien']); ?>"><?= htmlspecialchars($row['edukasi_pasien'] ?: '-'); ?></td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium border bg-slate-50 text-slate-700 border-slate-200">
                                    <?= htmlspecialchars($row['serah_terima'] ?: 'Diserahkan'); ?>
                                </span>
                            </td>
                            <td class="p-4 text-xs font-medium"><?= htmlspecialchars($row['nama_petugas'] ?: '-'); ?></td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="edit.php?id=<?= $row['dispensing_id']; ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" title="Ubah Catatan"><i class="bi bi-pencil-square"></i></a>
                                    <a href="delete.php?id=<?= $row['dispensing_id']; ?>" onclick="return confirm('Hapus log pencatatan dispensing ini?')" class="text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition" title="Hapus"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-400 italic">Belum ada rekam penyerahan obat (dispensing) yang dicatat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>