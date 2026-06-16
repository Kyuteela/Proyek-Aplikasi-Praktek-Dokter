<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
$error = '';

// Ambil data pasien dan dokter untuk menu pilihan dropdown
$patients = mysqli_query($conn, "SELECT patient_id, nama, nik FROM pasien ORDER BY nama ASC");
$doctors = mysqli_query($conn, "SELECT doctor_id, nama, spesialisasi FROM dokter ORDER BY nama ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $jenis_layanan = mysqli_real_escape_string($conn, $_POST['jenis_layanan']);

    // INTEGRASI DATABASE: Memanggil Stored Procedure sp_transaksi_kunjungan dari Tahap 3
    $visit_id_out = 0;
    $query_sp = "CALL sp_transaksi_kunjungan('$patient_id', '$doctor_id', '$jenis_layanan', @visit_id_out)";

    if (mysqli_query($conn, $query_sp)) {
        header("Location: index.php?status=success&msg=" . urlencode("Antrian pendaftaran berhasil dibuat otomatis oleh sistem database!"));
        exit;
    } else {
        $error = "Gagal memproses pembuatan antrian baru.";
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800 flex items-center"><i class="bi bi-calendar-plus text-yellow-500 mr-2"></i> Daftarkan Kunjungan Baru</h2>
        <a href="index.php" class="text-xs text-gray-500 hover:text-gray-700"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <form action="" method="POST" class="p-6 space-y-4">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pilih Rekam Identitas Pasien <span class="text-rose-500">*</span></label>
            <select name="patient_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">-- Cari Nama Pasien --</option>
                <?php while ($p = mysqli_fetch_assoc($patients)): ?>
                    <option value="<?= $p['patient_id'] ?>"><?= htmlspecialchars($p['nama']) ?> (NIK: <?= $p['nik'] ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pilih Dokter & Poliklinik Tujuan <span class="text-rose-500">*</span></label>
            <select name="doctor_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                <option value="">-- Pilih Tenaga Medis --</option>
                <?php while ($d = mysqli_fetch_assoc($doctors)): ?>
                    <option value="<?= $d['doctor_id'] ?>"><?= htmlspecialchars($d['nama']) ?> [Spesialisasi: <?= htmlspecialchars($d['spesialisasi'] ?: 'Umum') ?>]</option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis Layanan Medis <span class="text-rose-500">*</span></label>
            <select name="jenis_layanan" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                <option value="Konsultasi">Konsultasi Umum</option>
                <option value="Pemeriksaan">Pemeriksaan Rutin / Check-up</option>
                <option value="Tindakan Medis">Tindakan Medis / Bedah Minor</option>
                <option value="Rawat Jalan">Rawat Jalan Kontrol</option>
            </select>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow">Generate Sesi Antrian</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>