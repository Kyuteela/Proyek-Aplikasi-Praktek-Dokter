<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_GET['id'])) {
    $obat_id = mysqli_real_escape_string($conn, $_GET['id']);

    try {
        // Mencoba melakukan eksekusi penghapusan baris data obat
        $query_delete = "DELETE FROM obat WHERE obat_id = '$obat_id'";
        if (mysqli_query($conn, $query_delete)) {
            header("Location: index.php?status=success&msg=" . urlencode("Item komoditas obat sukses dihapus dari katalog master!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap error jika relasi RESTRICT aktif karena id obat sudah digunakan di transaksi log
        header("Location: index.php?status=error&msg=" . urlencode("Gagal menghapus: Item obat ini tidak bisa dieliminasi karena data ID-nya masih terikat aktif dengan lot gudang batch atau riwayat resep pasien!"));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
