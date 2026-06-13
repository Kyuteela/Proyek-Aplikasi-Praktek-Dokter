<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM transaksi_stok WHERE transaksi_stok_id = $id"
);

header("Location:index.php");
exit;
