<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// 1. FITUR PENCARIAN DATA PASIEN
$search = '';
$where_clause = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where_clause = "WHERE nama LIKE '%$search%' OR nik LIKE '%$search%'";
}

// MAKSIMALKAN DATABASE: Panggil fungsi fn_hitung_usia dan fn_jumlah_kunjungan dari Tahap 3
$query = "SELECT *, 
                 fn_hitung_usia(patient_id) AS usia,
                 fn_jumlah_kunjungan(patient_id) AS total_kunjungan 
          FROM pasien $where_clause ORDER BY patient_id DESC";
$result = mysqli_query($conn, $query);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-people-fill text-blue-500 mr-2"></i> Manajemen Registrasi Pasien
            </h2>
            <p class="text-xs text-gray-400 mt-1">Kelola pencatatan identitas kependudukan, rekam demografi, pelacakan kepesertaan jaminan, dan rekap mutasi antrian.</p>
        </div>
        <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow flex items-center justify-center space-x-2 transition self-start sm:self-auto">
            <i class="bi bi-person-plus-fill"></i>
            <span>Daftarkan Pasien</span>
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

    <div class="mb-4 max-w-md relative">
        <form method="GET" action="">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                class="w-full pl-10 pr-20 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white text-sm transition"
                placeholder="Cari nama atau nomor NIK...">
            <?php if (!empty($search)): ?>
                <a href="index.php" class="absolute inset-y-0 right-14 flex items-center text-xs text-gray-400 hover:text-gray-600">Clear</a>
            <?php endif; ?>
            <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 bg-gray-800 hover:bg-gray-900 text-white text-xs px-3 rounded-lg transition">Cari</button>
        </form>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-100">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="p-4">No. RM</th>
                    <th class="p-4">Nama Lengkap & Demografi</th>
                    <th class="p-4">Nomor NIK kependudukan</th>
                    <th class="p-4">Informasi Kontak</th>
                    <th class="p-4">Klaim Jaminan</th>
                    <th class="p-4 text-center">Aksi Kontrol</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-600">
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono font-bold text-gray-700">#PAM-<?= str_pad($row['patient_id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td class="p-4">
                                <span class="block font-bold text-gray-800 text-base"><?= htmlspecialchars($row['nama']); ?></span>
                                <span class="text-xs text-gray-400 block mt-0.5">
                                    <?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?> |
                                    Usia: <strong><?= $row['usia']; ?> Tahun</strong>
                                </span>
                                <span class="text-xs text-gray-500 mt-1 block max-w-xs truncate"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($row['alamat'] ?: '-'); ?></span>
                            </td>
                            <td class="p-4 font-mono text-xs tracking-wider text-gray-700 bg-gray-50 border rounded px-2 py-1 inline-block mt-4"><?= htmlspecialchars($row['nik']); ?></td>
                            <td class="p-4">
                                <span class="block text-gray-700"><i class="bi bi-telephone text-gray-400 mr-1"></i> <?= htmlspecialchars($row['no_telepon'] ?: '-'); ?></span>
                                <span class="block text-xs text-gray-400 mt-1"><i class="bi bi-envelope text-gray-400 mr-1"></i> <?= htmlspecialchars($row['email'] ?: '-'); ?></span>
                            </td>
                            <td class="p-4">
                                <?php if (!empty($row['asuransi_id'])): ?>
                                    <span class="bg-blue-50 text-blue-700 border border-blue-200 text-xs px-2.5 py-1 rounded-full font-medium inline-flex items-center mb-1">
                                        <i class="bi bi-shield-check mr-1"></i> <?= htmlspecialchars($row['asuransi_id']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="bg-slate-50 text-slate-600 border border-slate-200 text-xs px-2.5 py-1 rounded-full font-medium inline-flex items-center mb-1">Umum / Mandiri</span>
                                <?php endif; ?>
                                <span class="block text-[10px] text-gray-400">Kunjungan: <strong><?= $row['total_kunjungan']; ?>x</strong></span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="edit.php?id=<?= $row['patient_id']; ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" title="Ubah Profil"><i class="bi bi-pencil-square"></i></a>
                                    <a href="delete.php?id=<?= $row['patient_id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus mutasi data pasien ini?')" class="text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition" title="Eliminasi"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400 italic">Data pencatatan rekam identitas pasien masih kosong.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>