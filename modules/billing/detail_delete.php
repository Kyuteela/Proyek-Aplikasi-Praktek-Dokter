<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../../config/koneksi.php';

$detail_tagihan_id = mysqli_real_escape_string($conn, $_GET['id']);
$tagihan_id = mysqli_real_escape_string($conn, $_GET['tagihan_id']);

$item = mysqli_fetch_assoc(mysqli_query($conn, "SELECT harga_satuan FROM detail_tagihan WHERE detail_tagihan_id = '$detail_tagihan_id'"));

if ($item) {
    $harga = $item['harga_satuan'];
    // Hapus baris item
    if (mysqli_query($conn, "DELETE FROM detail_tagihan WHERE detail_tagihan_id = '$detail_tagihan_id'")) {
        // Kurangi nilai total invoice utama agar saldo balance
        mysqli_query($conn, "UPDATE tagihan SET total_tagihan = total_tagihan - $harga WHERE tagihan_id = '$tagihan_id'");
    }
}

header("Location: details.php?id=" . $tagihan_id);
exit;
