<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_GET['id'])) {
    $dispensing_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Melakukan eksekusi penghapusan record data dispensing farmasi
    if (mysqli_query($conn, "DELETE FROM dispensing WHERE dispensing_id = '$dispensing_id'")) {
        header("Location: index.php?status=success&msg=" . urlencode("Log catatan dispensing berhasil dihapus dari sistem!"));
        exit;
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Gagal menghapus catatan dispensing internal."));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
