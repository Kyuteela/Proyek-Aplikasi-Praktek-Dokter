<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_GET['id'])) {
    $patient_id = mysqli_real_escape_string($conn, $_GET['id']);

    try {
        // Melakukan pengeksekusian penghapusan baris entitas pasien
        $query_delete = "DELETE FROM pasien WHERE patient_id = '$patient_id'";
        if (mysqli_query($conn, $query_delete)) {
            header("Location: index.php?status=success&msg=" . urlencode("Rekam master identitas pasien berhasil dieliminasi dari sistem!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap kekeliruan constraint RESTRICT jika pasien sudah memiliki histori rekam kunjungan
        header("Location: index.php?status=error&msg=" . urlencode("Gagal mengeliminasi: Rekam data pasien ini tidak dapat dihapus karena memiliki riwayat antrian kunjungan medis aktif!"));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
