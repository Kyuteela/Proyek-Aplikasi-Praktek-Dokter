<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

// MAKSIMALKAN DATABASE: Mengambil data ringkas rekam medis dari View vw_rekam_medis
$records = mysqli_query($conn, "SELECT * FROM vw_rekam_medis ORDER BY record_id DESC");

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="flex justify-between items-center border-b pb-4 mb-4">
        <h2 class="text-base font-bold text-gray-800"><i class="bi bi-file-earmark-medical-fill text-cyan-500 mr-1"></i> Rangkuman Lembar Diagnosa Pasien</h2><a href="index.php" class="text-xs bg-gray-100 px-3 py-1.5 rounded-xl font-semibold">Kembali</a>
    </div>
    <div class="overflow-x-auto text-xs">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b font-bold text-gray-400 uppercase">
                    <th class="p-3">No. Record</th>
                    <th class="p-3">Nama Pasien</th>
                    <th class="p-3">Dokter Pemeriksa</th>
                    <th class="p-3">Anamnesa Masalah</th>
                    <th class="p-3">Catatan Klinis Dokter</th>
                </tr>
            </thead>
            <tbody class="divide-y text-gray-600">
                <?php while ($r = mysqli_fetch_assoc($records)): ?>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-3 font-mono font-bold">#RME-<?= $r['record_id']; ?></td>
                        <td class="p-3 font-bold text-gray-800"><?= htmlspecialchars($r['nama_pasien']); ?></td>
                        <td class="p-3 font-medium"><?= htmlspecialchars($r['nama_dokter']); ?></td>
                        <td class="p-3 italic text-gray-500">"<?= htmlspecialchars($r['anamnesa']); ?>"</td>
                        <td class="p-3 font-semibold text-blue-600"><?= htmlspecialchars($r['catatan_klinis']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>