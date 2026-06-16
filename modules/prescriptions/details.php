<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

$resep_id = mysqli_real_escape_string($conn, $_GET['id']);

$header = mysqli_fetch_assoc(mysqli_query($conn, "SELECT r.*, p.nama AS nama_pasien, p.nik, d.nama AS nama_dokter 
    FROM resep r INNER JOIN rekam_medis rm ON r.record_id = rm.record_id INNER JOIN kunjungan k ON rm.visit_id = k.visit_id 
    INNER JOIN pasien p ON k.patient_id = p.patient_id INNER JOIN dokter d ON r.doctor_id = d.doctor_id WHERE r.resep_id = '$resep_id'"));

// Ambil baris detail resep
$items = mysqli_query($conn, "SELECT dr.*, o.nama_obat FROM detail_resep dr INNER JOIN obat o ON dr.obat_id = o.obat_id WHERE dr.resep_id = '$resep_id' ORDER BY dr.detail_id ASC");

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm max-w-4xl mx-auto">
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Lembar Racikan Obat #RSP-<?= str_pad($header['resep_id'], 4, '0', STR_PAD_LEFT); ?></h2>
            <span class="text-xs text-gray-400">Tanggal Terbit: <?= date('d M Y', strtotime($header['tanggal_resep'])); ?></span>
        </div>
        <a href="index.php" class="text-xs bg-gray-100 text-gray-600 font-semibold py-2 px-4 rounded-xl"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl mb-6 text-sm text-gray-600">
        <div>Identitas Pasien: <strong class="text-gray-800 block text-base mt-0.5"><?= htmlspecialchars($header['nama_pasien']); ?></strong><span>NIK: <?= htmlspecialchars($header['nik']); ?></span></div>
        <div>Dokter Pemberi Otoritas: <strong class="text-gray-800 block text-base mt-0.5"><?= htmlspecialchars($header['nama_dokter']); ?></strong><span>Catatan: <?= htmlspecialchars($header['catatan_dokter'] ?: '-'); ?></span></div>
    </div>

    <div class="flex justify-between items-center mb-3">
        <h3 class="text-xs font-bold uppercase text-gray-400 tracking-wider">Butir Komoditas Obat</h3>
        <a href="detail_create.php?resep_id=<?= $resep_id ?>" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold py-1.5 px-3 rounded-lg"><i class="bi bi-plus"></i> Tambah Item Obat</a>
    </div>

    <div class="border rounded-xl overflow-hidden text-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b text-xs font-bold text-gray-400 uppercase">
                    <th class="p-3">Nama Obat</th>
                    <th class="p-3">Aturan Pakai & Dosis</th>
                    <th class="p-3">Rute & Durasi</th>
                    <th class="p-3 text-center">Jumlah Qty</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y text-gray-600">
                <?php if (mysqli_num_rows($items) > 0): ?>
                    <?php while ($item = mysqli_fetch_assoc($items)): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-3 font-bold text-gray-800"><?= htmlspecialchars($item['nama_obat']); ?></td>
                            <td class="p-3">
                                <span class="block font-medium text-blue-600"><?= htmlspecialchars($item['dosis']); ?></span>
                                <span class="block text-[11px] text-gray-400 mt-0.5">Frekuensi: <?= htmlspecialchars($item['frekuensi']); ?></span>
                            </td>
                            <td class="p-3">Jalur: <?= htmlspecialchars($item['rute']); ?><span class="block text-xs text-gray-400 mt-0.5">Durasi: <?= htmlspecialchars($item['durasi']); ?></span></td>
                            <td class="p-3 text-center font-mono font-bold text-gray-700"><?= $item['jumlah']; ?> Unit</td>
                            <td class="p-3 text-center">
                                <a href="detail_edit.php?id=<?= $item['detail_id']; ?>" class="text-blue-500 mx-1"><i class="bi bi-pencil"></i></a>
                                <a href="detail_delete.php?id=<?= $item['detail_id']; ?>&resep_id=<?= $resep_id ?>" onclick="return confirm('Hapus racikan obat ini dari lembar resep?')" class="text-rose-500 mx-1"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-400 italic">Belum ada butir komponen racikan obat pada resep ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>