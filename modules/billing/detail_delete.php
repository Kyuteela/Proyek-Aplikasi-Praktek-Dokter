<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];
$tagihan_id = $_GET['tagihan_id'];

mysqli_query(
    $conn,
    "DELETE FROM detail_tagihan WHERE detail_tagihan_id = $id"
);

header("Location:details.php?tagihan_id=$tagihan_id");
exit;