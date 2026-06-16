<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_GET['id'])) {
    $record_id = mysqli_real_escape_string($conn, $_GET['id']);

    try {
        // Hapus record berdasarkan ID
        $query_delete = "DELETE FROM rekam_medis WHERE record_id = '$record_id'";
        if (mysqli_query($conn, $query_delete)) {
            header("Location: index.php?status=success&msg=" . urlencode("Catatan rekam medis berhasil dihapus!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap error jika rekam medis sudah terlanjur memiliki resep obat aktif
        header("Location: index.php?status=error&msg=" . urlencode("Gagal menghapus: Rekam medis ini sudah memiliki resep obat yang diterbitkan!"));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
