<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_GET['id'])) {
    $resep_id = mysqli_real_escape_string($conn, $_GET['id']);
    try {
        // Hapus detail obat resep terlebih dahulu agar terhindar dari foreign key restraint
        mysqli_query($conn, "DELETE FROM detail_resep WHERE resep_id = '$resep_id'");
        mysqli_query($conn, "DELETE FROM resep WHERE resep_id = '$resep_id'");
        header("Location: index.php?status=success&msg=" . urlencode("Berkas resep medis sukses dieliminasi dari sistem!"));
        exit;
    } catch (mysqli_sql_exception $e) {
        header("Location: index.php?status=error&msg=" . urlencode("Gagal menghapus: " . $e->getMessage()));
        exit;
    }
}
