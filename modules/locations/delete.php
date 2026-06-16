<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_GET['id'])) {
    $lokasi_id = mysqli_real_escape_string($conn, $_GET['id']);

    try {
        // Eksekusi penghapusan baris lokasi
        $query_delete = "DELETE FROM lokasi WHERE lokasi_id = '$lokasi_id'";
        if (mysqli_query($conn, $query_delete)) {
            header("Location: index.php?status=success&msg=" . urlencode("Data master komponen lokasi berhasil dieliminasi!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap error jika constraint RESTRICT aktif karena lokasi digunakan di batch_obat
        header("Location: index.php?status=error&msg=" . urlencode("Gagal mengeliminasi: Unit lokasi ini sedang aktif digunakan untuk penempatan komoditas batch obat gudang!"));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
