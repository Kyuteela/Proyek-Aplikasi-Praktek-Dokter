<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

// MAKSIMALKAN DATABASE: Mengeksekusi query agregasi dari materi Tahap 2B
$agregat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total_pasien, MIN(tgl_lahir) AS pasien_tertua, MAX(tgl_lahir) AS pasien_termuda FROM pasien"));

$distribusi_gender = mysqli_query($conn, "SELECT jenis_kelamin, COUNT(*) AS jumlah FROM pasien GROUP BY jenis_kelamin");

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="space-y-6">
    <div class="flex justify-between items-center border-b pb-4">
        <h2 class="text-xl font-bold text-gray-800"><i class="bi bi-people-fill text-blue-500 mr-1"></i> Metrik Ringkasan Demografi Pasien</h2><a href="index.php" class="text-xs bg-gray-100 px-3 py-1.5 rounded-xl font-semibold"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div><span class="block text-2xl font-bold text-blue-600"><?= $agregat['total_pasien']; ?></span><span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Terregistrasi</span></div><i class="bi bi-people text-gray-200 text-4xl"></i>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div><span class="block text-sm font-mono font-bold text-gray-700"><?= date('d M Y', strtotime($agregat['pasien_tertua'])); ?></span><span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Tanggal Lahir Tertua</span></div><i class="bi bi-calendar-minus text-gray-200 text-4xl"></i>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div><span class="block text-sm font-mono font-bold text-gray-700"><?= date('d M Y', strtotime($agregat['pasien_termuda'])); ?></span><span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Tanggal Lahir Termuda</span></div><i class="bi bi-calendar-plus text-gray-200 text-4xl"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm max-w-md">
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Proporsi Sebaran Jenis Kelamin</h3>
        <div class="space-y-3 text-sm">
            <?php while ($g = mysqli_fetch_assoc($distribusi_gender)): ?>
                <div>
                    <div class="flex justify-between font-medium text-gray-700 mb-1"><span><?= $g['jenis_kelamin'] === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)'; ?></span><strong><?= $g['jumlah']; ?> Pasien</strong></div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: <?= ($g['jumlah'] / $agregat['total_pasien']) * 100; ?>%"></div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>