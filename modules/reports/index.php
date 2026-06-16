<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// Jika tombol generate otomatis diklik, jalankan Stored Procedure
if (isset($_POST['action_generate'])) {
    try {
        $periode_mulai = date('Y-01-01');
        $periode_akhir = date('Y-12-31');

        $query_sp = "CALL sp_generate_report('$periode_mulai', '$periode_akhir')";
        if (mysqli_query($conn, $query_sp)) {
            header("Location: index.php?status=success&msg=" . urlencode("Sistem database berhasil melakukan generate kompilasi laporan tahunan!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        header("Location: index.php?status=error&msg=" . urlencode("Gagal generate: " . $e->getMessage()));
        exit;
    }
}

// Ambil riwayat log laporan yang pernah digenerate
$history_reports = mysqli_query($conn, "SELECT * FROM report_agregasi ORDER BY report_id DESC LIMIT 5");

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-graph-up-arrow text-teal-500 mr-2"></i> Pusat Laporan & Analisis Eksekutif
            </h2>
            <p class="text-xs text-gray-400 mt-1">Sistem kompilasi data transaksi operasional, keuangan kasir, dan peninjauan indikator klinis dokter.</p>
        </div>
        <form method="POST" action="">
            <button type="submit" name="action_generate" class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow flex items-center justify-center space-x-2 transition">
                <i class="bi bi-cpu-fill"></i>
                <span>Generate Log Laporan</span>
            </button>
        </form>
    </div>

    <?php if ($status === 'success') : ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl flex items-center space-x-3 text-sm">
            <i class="bi bi-check-circle-fill text-xl text-emerald-500"></i>
            <span><?= htmlspecialchars($msg); ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="patients_report.php" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col items-start">
            <div class="p-3 bg-blue-50 rounded-xl text-blue-600 mb-4 group-hover:scale-110 transition-transform">
                <i class="bi bi-people-fill text-2xl"></i>
            </div>
            <span class="font-bold text-gray-700 text-base">Laporan Pasien</span>
            <span class="text-xs text-gray-400 mt-1">Analisis demografi kependudukan, distribusi usia, dan kepesertaan jaminan.</span>
        </a>

        <a href="visits_report.php" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col items-start">
            <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600 mb-4 group-hover:scale-110 transition-transform">
                <i class="bi bi-calendar3 text-2xl"></i>
            </div>
            <span class="font-bold text-gray-700 text-base">Laporan Kunjungan</span>
            <span class="text-xs text-gray-400 mt-1">Rekap kuantitas antrian, jenis layanan medis, dan produktivitas dokter harian.</span>
        </a>

        <a href="medical_report.php" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col items-start">
            <div class="p-3 bg-cyan-50 rounded-xl text-cyan-600 mb-4 group-hover:scale-110 transition-transform">
                <i class="bi bi-file-earmark-medical-fill text-2xl"></i>
            </div>
            <span class="font-bold text-gray-700 text-base">Analitik Rekam Medis</span>
            <span class="text-xs text-gray-400 mt-1">Pemantauan diagnosis klinis, riwayat penyakit, dan rekap penulisan resep.</span>
        </a>

        <a href="billing_report.php" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col items-start">
            <div class="p-3 bg-amber-50 rounded-xl text-amber-600 mb-4 group-hover:scale-110 transition-transform">
                <i class="bi bi-cash-stack text-2xl"></i>
            </div>
            <span class="font-bold text-gray-700 text-base">Laporan Keuangan</span>
            <span class="text-xs text-gray-400 mt-1">Kalkulasi omset bruto kasir, klaim jaminan asuransi, dan akumulasi pemberian diskon.</span>
        </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4"><i class="bi bi-clock-history mr-1"></i> Log Aktivitas Generate Laporan Terakhir</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b text-xs font-bold text-gray-400 uppercase">
                        <th class="p-3">ID Log</th>
                        <th class="p-3">Jenis Kompilasi</th>
                        <th class="p-3">Cakupan Periode</th>
                        <th class="p-3">Waktu Eksekusi</th>
                        <th class="p-3">Keterangan Sumber</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-gray-600">
                    <?php if (mysqli_num_rows($history_reports) > 0): ?>
                        <?php while ($h = mysqli_fetch_assoc($history_reports)): ?>
                            <tr>
                                <td class="p-3 font-mono text-xs">#REP-<?= $h['report_id']; ?></td>
                                <td class="p-3 font-bold text-gray-700"><?= htmlspecialchars($h['jenis_laporan']); ?></td>
                                <td class="p-3 text-xs"><?= $h['periode_mulai']; ?> s/d <?= $h['periode_akhir']; ?></td>
                                <td class="p-3 text-xs"><i class="bi bi-calendar-check mr-1"></i> <?= date('d M Y H:i', strtotime($h['tanggal_generate'])); ?></td>
                                <td class="p-3 text-xs text-gray-400 font-medium"><?= htmlspecialchars($h['keterangan']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-400 italic">Belum ada rekaman log report_agregasi di database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>