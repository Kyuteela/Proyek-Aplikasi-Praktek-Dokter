<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

$detail_id = mysqli_real_escape_string($conn, $_GET['id']);
$resep_id = mysqli_real_escape_string($conn, $_GET['resep_id']);

// Melakukan penghapusan baris item racikan detail resep
if (mysqli_query($conn, "DELETE FROM detail_resep WHERE detail_id = '$detail_id'")) {
    header("Location: details.php?id=" . $resep_id);
    exit;
}
