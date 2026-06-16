<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_GET['id'])) {
    $tagihan_id = mysqli_real_escape_string($conn, $_GET['id']);
    try {
        // Hapus detail rincian tagihan terlebih dahulu
        mysqli_query($conn, "DELETE FROM detail_tagihan WHERE tagihan_id = '$tagihan_id'");
        mysqli_query($conn, "DELETE FROM tagihan WHERE tagihan_id = '$tagihan_id'");
        header("Location: index.php?status=success&msg=" . urlencode("Berkas berkas lembar invoice sukses dihapus!"));
        exit;
    } catch (mysqli_sql_exception $e) {
        header("Location: index.php?status=error&msg=" . urlencode("Gagal menghapus invoice: " . $e->getMessage()));
        exit;
    }
}
