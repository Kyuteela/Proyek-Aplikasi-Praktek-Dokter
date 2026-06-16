<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_GET['id'])) {
    $transaksi_stok_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Melakukan penghapusan baris mutasi stok
    if (mysqli_query($conn, "DELETE FROM transaksi_stok WHERE transaksi_stok_id = '$transaksi_stok_id'")) {
        header("Location: index.php?status=success&msg=" . urlencode("Log transaksi mutasi stok berhasil dihapus dari sistem!"));
        exit;
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Gagal menghapus log transaksi mutasi internal gudang."));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
