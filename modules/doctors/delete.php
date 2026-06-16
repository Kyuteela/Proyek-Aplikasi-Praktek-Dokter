<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_GET['id'])) {
    $doctor_id = mysqli_real_escape_string($conn, $_GET['id']);

    try {
        $query_delete = "DELETE FROM dokter WHERE doctor_id = '$doctor_id'";
        if (mysqli_query($conn, $query_delete)) {
            header("Location: index.php?status=success&msg=" . urlencode("Data rekam dokter berhasil dihapus dari sistem!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap error foreign key restrict jika dokter sudah melayani kunjungan pasien
        header("Location: index.php?status=error&msg=" . urlencode("Gagal menghapus: Dokter ini tidak bisa dihapus karena sudah memiliki riwayat melayani kunjungan antrian pasien!"));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
