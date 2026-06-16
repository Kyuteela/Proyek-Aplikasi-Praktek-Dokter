<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// MAKSIMALKAN DATABASE: Mengambil data dari View vw_kunjungan_pasien dari Tahap 2
$query = "SELECT k.*, real_k.antrian_no 
          FROM vw_kunjungan_pasien k
          INNER JOIN kunjungan real_k ON k.visit_id = real_k.visit_id
          ORDER BY k.visit_id DESC";
$result = mysqli_query($conn, $query);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-calendar3 text-yellow-500 mr-2"></i> Monitor Antrian & Kunjungan Pasien
            </h2>
            <p class="text-xs text-gray-400 mt-1">Sistem kendali pendaftaran pasien, pembagian nomor urut antrian, dan distribusi poliklinik dokter.</p>
        </div>
        <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow flex items-center justify-center space-x-2 transition self-start sm:self-auto">
            <i class="bi bi-plus-circle"></i>
            <span>Daftarkan Antrian</span>
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
                    <th class="p-4 text-center">No. Antrian</th>
                    <th class="p-4">Nama Pasien</th>
                    <th class="p-4">Dokter yang Menangani</th>
                    <th class="p-4">Tanggal Sesi</th>
                    <th class="p-4">Jenis Layanan</th>
                    <th class="p-4 text-center">Status Antrian</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-600">
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 text-gray-700 font-mono font-black text-base border">
                                    <?= str_pad($row['antrian_no'], 2, '0', STR_PAD_LEFT); ?>
                                </span>
                            </td>
                            <td class="p-4 font-bold text-gray-800 text-base"><?= htmlspecialchars($row['nama_pasien']); ?></td>
                            <td class="p-4 text-xs font-medium text-gray-700"><i class="bi bi-person-vcard text-gray-400 mr-1"></i> <?= htmlspecialchars($row['nama_dokter']); ?></td>
                            <td class="p-4 text-xs"><?= date('d M Y', strtotime($row['tgl_kunjungan'])); ?></td>
                            <td class="p-4"><span class="bg-blue-50 text-blue-700 border border-blue-100 px-2.5 py-0.5 rounded-md text-xs font-medium"><?= htmlspecialchars($row['jenis_layanan']); ?></span></td>
                            <td class="p-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold border <?= $row['status'] === 'Selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200 animate-pulse'; ?>">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="edit.php?id=<?= $row['visit_id']; ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" title="Ubah Status / Layanan"><i class="bi bi-pencil-square"></i></a>
                                    <a href="delete.php?id=<?= $row['visit_id']; ?>" onclick="return confirm('Hapus antrian kunjungan ini secara permanen?')" class="text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition" title="Hapus Sesi"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-400 italic">Belum ada antrian pendaftaran kunjungan hari ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>