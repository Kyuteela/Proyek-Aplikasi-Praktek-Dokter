<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

// MAKSIMALKAN DATABASE: Menarik histori data dari View vw_kunjungan_pasien
$visits = mysqli_query($conn, "SELECT * FROM vw_kunjungan_pasien ORDER BY visit_id DESC");

// Query Group By & Having: Dokter dengan performa minimal 1 kunjungan
$doctor_perf = mysqli_query($conn, "SELECT d.nama, COUNT(k.visit_id) AS total_kunjungan FROM dokter d LEFT JOIN kunjungan k ON d.doctor_id = k.doctor_id GROUP BY d.doctor_id, d.nama HAVING total_kunjungan >= 1");

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-bold text-gray-800"><i class="bi bi-calendar3 text-yellow-500 mr-1"></i> Log Histori Kunjungan Pasien</h2><a href="index.php" class="text-xs text-blue-500 hover:underline">Kembali</a>
        </div>
        <div class="overflow-x-auto text-xs">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b font-bold text-gray-400 uppercase">
                        <th class="p-3">ID Sesi</th>
                        <th class="p-3">Nama Pasien</th>
                        <th class="p-3">Dokter</th>
                        <th class="p-3">Layanan</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-gray-600">
                    <?php while ($v = mysqli_fetch_assoc($visits)): ?>
                        <tr>
                            <td class="p-3 font-mono">#VIS-<?= $v['visit_id']; ?></td>
                            <td class="p-3 font-bold text-gray-700"><?= htmlspecialchars($v['nama_pasien']); ?></td>
                            <td class="p-3"><?= htmlspecialchars($v['nama_dokter']); ?></td>
                            <td class="p-3 font-medium"><?= htmlspecialchars($v['jenis_layanan']); ?></td>
                            <td class="p-3 text-center"><span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $v['status'] === 'Selesai' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700'; ?>"><?= $v['status']; ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm self-start">
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4"><i class="bi bi-person-badge mr-1"></i> Kuantitas Beban Kerja Dokter</h3>
        <div class="space-y-4 text-xs">
            <?php while ($d = mysqli_fetch_assoc($doctor_perf)): ?>
                <div class="flex justify-between items-center p-3 bg-slate-50 border rounded-xl">
                    <div><span class="block font-bold text-gray-700 text-sm"><?= htmlspecialchars($d['nama']); ?></span><span class="text-[10px] text-gray-400 mt-0.5">Otoritas Otoritas Medis Klinik</span></div>
                    <span class="bg-blue-600 text-white font-bold px-2.5 py-1 rounded-lg text-xs"><?= $d['total_kunjungan']; ?> Sesi</span>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>