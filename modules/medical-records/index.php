<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// MAKSIMALKAN DATABASE: Memanggil View rekam medis dari Tahap 2
$query = "SELECT * FROM vw_rekam_medis ORDER BY record_id DESC";
$result = mysqli_query($conn, $query);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-file-earmark-medical-fill text-cyan-500 mr-2"></i> Rekam Medis Elektronik (RME)
            </h2>
            <p class="text-xs text-gray-400 mt-1">Pusat pencatatan anamnesa, hasil pemeriksaan fisik, dan keputusan klinis dokter.</p>
        </div>
        <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow flex items-center justify-center space-x-2 transition self-start sm:self-auto">
            <i class="bi bi-plus-circle"></i>
            <span>Input Catatan Medis</span>
        </a>
    </div>

    <?php if ($status === 'success') : ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl mb-6 flex items-center space-x-3 text-sm">
            <i class="bi bi-check-circle-fill text-xl text-emerald-500"></i>
            <span><?= htmlspecialchars($msg); ?></span>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto rounded-xl border border-gray-100">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="p-4">ID Record</th>
                    <th class="p-4">Pasien & Tanggal</th>
                    <th class="p-4">Dokter Pemeriksa</th>
                    <th class="p-4">Anamnesa & Keluhan</th>
                    <th class="p-4">Fisik (TB/BB)</th>
                    <th class="p-4">Catatan Klinis</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-600">
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono font-bold text-gray-700">#RME-<?= str_pad($row['record_id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td class="p-4">
                                <span class="block font-bold text-gray-800"><?= htmlspecialchars($row['nama_pasien']); ?></span>
                                <span class="text-xs text-gray-400 block mt-0.5"><i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($row['tgl_kunjungan'])); ?></span>
                            </td>
                            <td class="p-4 text-xs font-medium"><?= htmlspecialchars($row['nama_dokter']); ?></td>
                            <td class="p-4 text-xs max-w-xs truncate" title="<?= htmlspecialchars($row['anamnesa']); ?>"><?= htmlspecialchars($row['anamnesa']); ?></td>
                            <td class="p-4 text-xs">
                                <span class="block">TB: <strong><?= $row['tinggi_badan']; ?> cm</strong></span>
                                <span class="block mt-0.5">BB: <strong><?= $row['berat_badan']; ?> kg</strong></span>
                            </td>
                            <td class="p-4 text-xs max-w-xs truncate font-medium text-blue-600"><?= htmlspecialchars($row['catatan_klinis'] ?: '-'); ?></td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="edit.php?id=<?= $row['record_id']; ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" title="Ubah RME"><i class="bi bi-pencil-square"></i></a>
                                    <a href="delete.php?id=<?= $row['record_id']; ?>" onclick="return confirm('Hapus permanen rekam medis ini?')" class="text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition" title="Hapus"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-400 italic">Belum ada data rekam medis yang dicatat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>