<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_GET['id'])) {
    $visit_id = mysqli_real_escape_string($conn, $_GET['id']);

    try {
        // Eksekusi eliminasi baris kunjungan
        $query_delete = "DELETE FROM kunjungan WHERE visit_id = '$visit_id'";
        if (mysqli_query($conn, $query_delete)) {
            header("Location: index.php?status=success&msg=" . urlencode("Sesi antrian kunjungan pasien berhasil dieliminasi!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap error constraint RESTRICT jika kunjungan sudah terlanjur diinput rekam medisnya oleh dokter
        header("Location: index.php?status=error&msg=" . urlencode("Gagal mengeliminasi: Sesi kunjungan ini sudah memiliki berkas rekam medis klinis aktif dari dokter!"));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
