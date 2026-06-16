<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_GET['id'])) {
    $batch_id = mysqli_real_escape_string($conn, $_GET['id']);

    try {
        $query = "DELETE FROM batch_obat WHERE batch_id = '$batch_id'";
        if (mysqli_query($conn, $query)) {
            header("Location: index.php?status=success&msg=" . urlencode("Batch log komoditas berhasil dihapus dari inventori!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap error jika item batch terikat dengan transaksi mutasi stok di gudang
        header("Location: index.php?status=error&msg=" . urlencode("Gagal menghapus: Item ini memiliki riwayat mutasi transaksi stok obat aktif!"));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
