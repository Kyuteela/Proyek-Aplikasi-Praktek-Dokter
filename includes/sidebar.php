<aside class="w-64 bg-white border-r border-gray-200 min-h-full hidden md:block flex-shrink-0 shadow-sm">
    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Menu Otoritas</span>
    </div>
    <nav class="p-3 space-y-1 text-sm">
        <?php if (isset($_SESSION['id_role'])) : ?>

            <?php if ($_SESSION['id_role'] == 1) : ?>
                <a href="../../modules/patients/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-people"></i><span>Kelola Pasien</span></a>
                <a href="../../modules/doctors/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-person-vcard"></i><span>Kelola Dokter</span></a>
                <a href="../../modules/medicines/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-capsule"></i><span>Kelola Obat</span></a>
                <a href="../../modules/visits/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-calendar3"></i><span>Kelola Kunjungan</span></a>
                <a href="../../modules/medical-records/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-file-earmark-medical"></i><span>Rekam Medis</span></a>
                <a href="../../modules/prescriptions/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-receipt-cutoff"></i><span>Kelola Resep</span></a>
                <a href="../../modules/dispensing/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-box-seam"></i><span>Dispensing</span></a>
                <a href="../../modules/billing/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-cash-stack"></i><span>Tagihan & Kasir</span></a>
                <a href="../../modules/reports/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-graph-up-arrow"></i><span>Laporan Internal</span></a>

            <?php elseif ($_SESSION['id_role'] == 2) : ?>
                <a href="../../modules/visits/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-calendar3"></i><span>Kelola Kunjungan</span></a>
                <a href="../../modules/medical-records/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-file-earmark-medical"></i><span>Rekam Medis</span></a>
                <a href="../../modules/prescriptions/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-receipt-cutoff"></i><span>Kelola Resep</span></a>

            <?php elseif ($_SESSION['id_role'] == 4) : ?>
                <a href="../../modules/billing/index.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition"><i class="bi bi-cash-stack"></i><span>Kelola Tagihan</span></a>
            <?php endif; ?>

        <?php endif; ?>
        <hr class="border-gray-100 my-2">
        <a href="../../logout.php" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-rose-600 hover:bg-rose-50 transition"><i class="bi bi-box-arrow-right"></i><span>Keluar Sistem</span></a>
    </nav>
</aside>

<main class="flex-1 p-6 overflow-y-auto">