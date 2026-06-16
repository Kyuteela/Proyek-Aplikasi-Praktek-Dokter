<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

// MAKSIMALKAN DATABASE: Tarik ringkasan laporan tagihan dari View vw_tagihan_pasien
$billing_logs = mysqli_query($conn, "SELECT * FROM vw_tagihan_pasien ORDER BY tagihan_id DESC");

// Hitung total finansial kasir
$summary = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_tagihan) AS bruto, SUM(diskon) AS potongan FROM tagihan"));
$omset_netto = $summary['bruto'] - $summary['potongan'];
?>

<?php include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php'; ?>

<div class="space-y-6">
    <div class="flex justify-between items-center border-b pb-4">
        <h2 class="text-base font-bold text-gray-800"><i class="bi bi-cash-stack text-amber-500 mr-1"></i> Rekapitulasi Neraca Omset Kasir Klinik</h2><a href="index.php" class="text-xs text-blue-500 hover:underline">Kembali</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex justify-between items-center">
            <div><span class="block text-xl font-bold text-gray-700">Rp <?= number_format($summary['bruto'], 0, ',', '.'); ?></span><span class="text-[11px] text-gray-400 font-bold uppercase">Pendapatan Bruto</span></div><i class="bi bi-wallet2 text-gray-200 text-3xl"></i>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex justify-between items-center">
            <div><span class="block text-xl font-bold text-rose-600">Rp <?= number_format($summary['potongan'], 0, ',', '.'); ?></span><span class="text-[11px] text-gray-400 font-bold uppercase">Akumulasi Diskon</span></div><i class="bi bi-tags text-gray-200 text-3xl"></i>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex justify-between items-center bg-gradient-to-br from-emerald-500 to-teal-600 text-white border-0">
            <div><span class="block text-xl font-extrabold">Rp <?= number_format($omset_netto, 0, ',', '.'); ?></span><span class="text-[11px] text-emerald-100 font-bold uppercase">Kas Netto Masuk</span></div><i class="bi bi-piggy-bank text-emerald-400/40 text-3xl"></i>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
        <div class="overflow-x-auto text-xs">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b font-bold text-gray-400 uppercase">
                        <th class="p-3">No. Invoice</th>
                        <th class="p-3">Nama Pasien</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3 text-right">Bruto</th>
                        <th class="p-3 text-right">Potongan</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-gray-600">
                    <?php while ($b = mysqli_fetch_assoc($billing_logs)): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-3 font-mono font-bold">#INV-<?= str_pad($b['tagihan_id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td class="p-3 font-bold text-gray-700"><?= htmlspecialchars($b['nama_pasien']); ?></td>
                            <td class="p-3"><?= date('d M Y', strtotime($b['tanggal_tagihan'])); ?></td>
                            <td class="p-3 text-right font-medium">Rp <?= number_format($b['total_tagihan'], 0, ',', '.'); ?></td>
                            <td class="p-3 text-right text-rose-500">Rp <?= number_format($b['diskon'], 0, ',', '.'); ?></td>
                            <td class="p-3 text-center"><span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $b['status'] === 'Lunas' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700'; ?>"><?= $b['status']; ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>