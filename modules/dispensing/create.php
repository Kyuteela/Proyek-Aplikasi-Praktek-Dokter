<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
$error = '';

// Mengambil item detail resep yang posisinya belum pernah di-dispense
$query_pending = "SELECT dr.detail_id, o.nama_obat, dr.jumlah, p.nama AS nama_pasien 
                  FROM detail_resep dr
                  INNER JOIN obat o ON dr.obat_id = o.obat_id
                  INNER JOIN resep r ON dr.resep_id = r.resep_id
                  INNER JOIN rekam_medis rm ON r.record_id = rm.record_id
                  INNER JOIN kunjungan k ON rm.visit_id = k.visit_id
                  INNER JOIN pasien p ON k.patient_id = p.patient_id
                  LEFT JOIN dispensing d ON dr.detail_id = d.detail_id
                  WHERE d.dispensing_id IS NULL ORDER BY dr.detail_id DESC";
$pending_items = mysqli_query($conn, $query_pending);

// Ambil list user dengan peran Apoteker (Role ID 3) atau Admin (Role ID 1)
$petugas_list = mysqli_query($conn, "SELECT user_id, nama FROM user WHERE id_role IN (1, 3) ORDER BY nama ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $detail_id = mysqli_real_escape_string($conn, $_POST['detail_id']);
    $edukasi_pasien = mysqli_real_escape_string($conn, $_POST['edukasi_pasien']);
    $serah_terima = mysqli_real_escape_string($conn, $_POST['serah_terima']);
    $petugas_id = mysqli_real_escape_string($conn, $_POST['petugas_id']);

    // Menarik parameter obat_id asli dari baris acuan detail_resep
    $get_obat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT obat_id FROM detail_resep WHERE detail_id = '$detail_id'"));

    if ($get_obat) {
        $obat_id = $get_obat['obat_id'];

        $query_insert = "INSERT INTO dispensing (detail_id, obat_id, edukasi_pasien, serah_terima, petugas_id) 
                         VALUES ('$detail_id', '$obat_id', '$edukasi_pasien', '$serah_terima', '$petugas_id')";

        if (mysqli_query($conn, $query_insert)) {
            header("Location: index.php?status=success&msg=" . urlencode("Penyerahan obat berhasil diproses!"));
            exit;
        } else {
            $error = "Gagal memproses penyerahan dispensing obat.";
        }
    } else {
        $error = "Item resep obat tidak valid.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="bi bi-box-seam-fill text-blue-500 mr-2"></i> Form Dispensing Obat
        </h2>
        <a href="index.php" class="text-xs text-gray-500 hover:text-gray-700"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if (!empty($error)) : ?>
        <div class="m-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl text-sm flex items-center space-x-2">
            <i class="bi bi-exclamation-octagon-fill text-rose-500"></i>
            <span><?= $error; ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pilih Item Resep Pasien <span class="text-rose-500">*</span></label>
            <select name="detail_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">-- Pilih Antrian Obat Pasien --</option>
                <?php while ($item = mysqli_fetch_assoc($pending_items)): ?>
                    <option value="<?= $item['detail_id'] ?>">Pasien: <?= htmlspecialchars($item['nama_pasien']) ?> | <?= htmlspecialchars($item['nama_obat']) ?> (<?= $item['jumlah'] ?> Pcs)</option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pilih Petugas Pelaksana <span class="text-rose-500">*</span></label>
            <select name="petugas_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                <option value="">-- Pilih Apoteker --</option>
                <?php while ($p = mysqli_fetch_assoc($petugas_list)): ?>
                    <option value="<?= $p['user_id'] ?>" <?= $_SESSION['user_id'] == $p['user_id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nama']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Keterangan Serah Terima</label>
            <input type="text" name="serah_terima" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" value="Sudah diterima pasien/keluarga">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Catatan Instruksi Edukasi Obat</label>
            <textarea name="edukasi_pasien" rows="3" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm" placeholder="Contoh: Diminum 3x sehari sesudah makan, obat antibiotik wajib dihabiskan..."></textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow">Konfirmasi Penyerahan</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>