<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

$tagihan_id = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil metadata header invoice tagihan klinis
$header = mysqli_fetch_assoc(mysqli_query($conn, "SELECT t.*, p.nama AS nama_pasien, p.nik, d.nama AS nama_dokter 
    FROM tagihan t INNER JOIN kunjungan k ON t.visit_id = k.visit_id INNER JOIN pasien p ON k.patient_id = p.patient_id 
    INNER JOIN dokter d ON k.doctor_id = d.doctor_id WHERE t.tagihan_id = '$tagihan_id'"));

// Ambil deretan list rincian item baris invoice
$items = mysqli_query($conn, "SELECT * FROM detail_tagihan WHERE tagihan_id = '$tagihan_id' ORDER BY detail_tagihan_id ASC");

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm max-w-4xl mx-auto">
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Rincian Invoice #INV-<?= str_pad($header['tagihan_id'], 4, '0', STR_PAD_LEFT); ?></h2>
            <span class="text-xs text-gray-400">Tanggal Terbit: <?= date('d M Y', strtotime($header['tanggal_tagihan'])); ?></span>
        </div>
        <a href="index.php" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-2 px-4 rounded-xl"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-4 rounded-xl mb-6 text-sm">
        <div>
            <span class="block text-xs font-bold uppercase text-gray-400">Identitas Pasien</span>
            <span class="block font-bold text-gray-700 text-base mt-1"><?= htmlspecialchars($header['nama_pasien']); ?></span>
            <span class="block text-gray-500 font-mono text-xs mt-0.5">NIK: <?= htmlspecialchars($header['nik']); ?></span>
        </div>
        <div>
            <span class="block text-xs font-bold uppercase text-gray-400">Metode & Otoritas Medis</span>
            <span class="block text-gray-700 font-medium mt-1">Dokter: <?= htmlspecialchars($header['nama_dokter']); ?></span>
            <span class="block text-gray-500 mt-1">Pembayaran: <strong><?= $header['metode_pembayaran'] ?: 'Belum Memilih'; ?></strong></span>
        </div>
    </div>

    <div class="flex justify-between items-center mb-3">
        <h3 class="text-sm font-bold uppercase text-gray-400 tracking-wider">Daftar Rincian Item Invoice</h3>
        <a href="detail_create.php?tagihan_id=<?= $tagihan_id ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-1.5 px-3 rounded-lg"><i class="bi bi-plus-lg"></i> Tambah Item Biaya</a>
    </div>

    <div class="border rounded-xl overflow-hidden mb-6 text-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b text-xs font-bold text-gray-400 uppercase">
                    <th class="p-3">Kategori</th>
                    <th class="p-3">Deskripsi Layanan</th>
                    <th class="p-3 text-right">Harga Satuan</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y text-gray-600">
                <?php if (mysqli_num_rows($items) > 0): ?>
                    <?php while ($item = mysqli_fetch_assoc($items)): ?>
                        <tr>
                            <td class="p-3"><span class="bg-slate-100 text-slate-700 text-xs px-2 py-0.5 rounded font-medium"><?= htmlspecialchars($item['jenis_item']); ?></span></td>
                            <td class="p-3 font-medium text-gray-700"><?= htmlspecialchars($item['deskripsi']); ?></td>
                            <td class="p-3 text-right font-semibold">Rp <?= number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                            <td class="p-3 text-center">
                                <a href="detail_edit.php?id=<?= $item['detail_tagihan_id']; ?>" class="text-blue-500 hover:underline mx-1"><i class="bi bi-pencil"></i></a>
                                <a href="detail_delete.php?id=<?= $item['detail_tagihan_id']; ?>&tagihan_id=<?= $tagihan_id ?>" onclick="return confirm('Hapus item biaya ini?')" class="text-rose-500 hover:underline mx-1"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-400 italic">Belum ada rincian item biaya layanan pada invoice ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="max-w-xs ml-auto text-sm space-y-2 border-t pt-4">
        <div class="flex justify-between"><span>Subtotal Bruto:</span><span class="font-medium">Rp <?= number_format($header['total_tagihan'], 0, ',', '.'); ?></span></div>
        <div class="flex justify-between text-rose-600"><span>Potongan Diskon:</span><span>- Rp <?= number_format($header['diskon'], 0, ',', '.'); ?></span></div>
        <div class="flex justify-between text-base font-bold border-t pt-2 text-gray-800"><span>Total Netto:</span><span>Rp <?= number_format(($header['total_tagihan'] - $header['diskon']), 0, ',', '.'); ?></span></div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>