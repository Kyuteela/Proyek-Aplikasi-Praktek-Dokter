<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location:login.php");
    exit;
}

require_once 'config/koneksi.php';

$total_pasien = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM pasien")
)['total'];

$total_dokter = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM dokter")
)['total'];

$total_kunjungan = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM kunjungan")
)['total'];

$total_obat = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM obat")
)['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Praktik Dokter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script>
        // Kustomisasi konfigurasi Tailwind jika diperlukan di kemudian hari
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        klinikPrimary: '#2563eb',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 text-gray-800 antialiased font-sans">

    <nav class="bg-blue-600 shadow-md text-white">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a class="flex items-center space-x-2 font-bold text-lg tracking-wide" href="index.php">
                <i class="bi bi-heart-pulse-fill text-xl text-red-200"></i>
                <span>Klinik Mandiri</span>
            </a>
            <div class="flex items-center space-x-4">
                <div class="hidden sm:block text-sm text-blue-100">
                    Halo, <span class="font-semibold text-white"><?= $_SESSION['nama']; ?></span>
                    <span class="ml-2 bg-blue-700 text-xs px-2 py-1 rounded-full border border-blue-400">Role ID: <?= $_SESSION['id_role']; ?></span>
                </div>
                <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1.5 rounded-lg font-medium transition duration-150 flex items-center space-x-1 shadow">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-700 mb-2">Sistem Informasi Praktik Dokter</h1>
            <p class="text-gray-500 text-sm md:text-base leading-relaxed">Selamat datang di dashboard pusat kendali operasional internal klinik. Silakan pilih modul kerja di bawah sesuai dengan otoritas akses Anda.</p>
        </div>

        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center">
            <i class="bi bi-speedometer2 mr-2 text-blue-500"></i> Metrik Data Master
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-blue-500 flex justify-between items-center">
                <div>
                    <span class="block text-2xl font-bold text-blue-600"><?= $total_pasien ?></span>
                    <span class="text-xs text-gray-400 font-medium uppercase">Total Pasien</span>
                </div>
                <i class="bi bi-people text-gray-300 text-3xl"></i>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-green-500 flex justify-between items-center">
                <div>
                    <span class="block text-2xl font-bold text-green-600"><?= $total_dokter ?></span>
                    <span class="text-xs text-gray-400 font-medium uppercase">Total Dokter</span>
                </div>
                <i class="bi bi-person-vcard text-gray-300 text-3xl"></i>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-yellow-500 flex justify-between items-center">
                <div>
                    <span class="block text-2xl font-bold text-yellow-600"><?= $total_kunjungan ?></span>
                    <span class="text-xs text-gray-400 font-medium uppercase">Total Kunjungan</span>
                </div>
                <i class="bi bi-calendar-check text-gray-300 text-3xl"></i>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-cyan-500 flex justify-between items-center">
                <div>
                    <span class="block text-2xl font-bold text-cyan-600"><?= $total_obat ?></span>
                    <span class="text-xs text-gray-400 font-medium uppercase">Total Obat</span>
                </div>
                <i class="bi bi-capsule text-gray-300 text-3xl"></i>
            </div>
        </div>

        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center">
            <i class="bi bi-grid-3x3-gap-fill mr-2 text-blue-500"></i> Modul Manajemen Kerja
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-10">

            <?php if ($_SESSION['id_role'] == 1) : ?>
                <a href="modules/patients/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-people-fill text-blue-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600">Kelola Pasien</span>
                </a>
                <a href="modules/doctors/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-person-vcard-fill text-green-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-green-600">Kelola Dokter</span>
                </a>
                <a href="modules/medicines/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-capsule text-red-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-red-600">Kelola Obat</span>
                </a>
                <a href="modules/visits/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-calendar3 text-yellow-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-yellow-600">Kelola Kunjungan</span>
                </a>
                <a href="modules/medical-records/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-file-earmark-medical-fill text-cyan-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-cyan-600">Kelola Rekam Medis</span>
                </a>
                <a href="modules/prescriptions/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-receipt-cutoff text-purple-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-purple-600">Kelola Resep</span>
                </a>
                <a href="modules/dispensing/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-box-seam-fill text-orange-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-orange-600">Kelola Dispensing</span>
                </a>
                <a href="modules/locations/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-geo-alt-fill text-pink-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-pink-600">Kelola Lokasi</span>
                </a>
                <a href="modules/batches/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-tags-fill text-indigo-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-indigo-600">Batch Obat</span>
                </a>
                <a href="modules/stock-transactions/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-arrow-left-right text-emerald-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-emerald-600">Transaksi Stok</span>
                </a>
                <a href="modules/billing/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-cash-stack text-amber-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-amber-600">Kelola Tagihan</span>
                </a>
                <a href="modules/users/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-person-gear text-slate-600 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-slate-600">Kelola User</span>
                </a>
                <div class="col-span-2 sm:col-span-1">
                    <a href="modules/reports/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center h-full hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                        <i class="bi bi-graph-up-arrow text-teal-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-teal-600">Reports</span>
                    </a>
                </div>

            <?php elseif ($_SESSION['id_role'] == 2) : ?>
                <a href="modules/visits/index.php" class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-calendar3 text-yellow-500 text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium text-gray-700 group-hover:text-blue-600">Kelola Kunjungan</span>
                </a>
                <a href="modules/medical-records/index.php" class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-file-earmark-medical-fill text-cyan-500 text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium text-gray-700 group-hover:text-blue-600">Kelola Rekam Medis</span>
                </a>
                <a href="modules/prescriptions/index.php" class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-receipt-cutoff text-purple-500 text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium text-gray-700 group-hover:text-blue-600">Kelola Resep</span>
                </a>
                <a href="modules/reports/index.php" class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-graph-up-arrow text-teal-500 text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium text-gray-700 group-hover:text-blue-600">Reports</span>
                </a>

            <?php elseif ($_SESSION['id_role'] == 3) : ?>
                <a href="modules/medicines/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-capsule text-red-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600">Kelola Obat</span>
                </a>
                <a href="modules/prescriptions/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-receipt-cutoff text-purple-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600">Kelola Resep</span>
                </a>
                <a href="modules/dispensing/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-box-seam-fill text-orange-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600">Kelola Dispensing</span>
                </a>
                <a href="modules/locations/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-geo-alt-fill text-pink-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600">Lokasi Penyimpanan</span>
                </a>
                <a href="modules/batches/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-tags-fill text-indigo-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600">Batch Obat</span>
                </a>
                <a href="modules/stock-transactions/index.php" class="group bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-arrow-left-right text-emerald-500 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600">Transaksi Stok</span>
                </a>

            <?php elseif ($_SESSION['id_role'] == 4) : ?>
                <a href="modules/billing/index.php" class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-cash-stack text-amber-500 text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium text-gray-700 group-hover:text-blue-600">Kelola Tagihan</span>
                </a>
                <a href="modules/reports/index.php" class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center justify-center hover:shadow-md hover:-translate-y-1 transition-all duration-150">
                    <i class="bi bi-graph-up-arrow text-teal-500 text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium text-gray-700 group-hover:text-blue-600">Reports</span>
                </a>
            <?php endif; ?>

        </div>
    </div>

</body>

</html>