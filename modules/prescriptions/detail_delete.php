<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];
$resep_id = $_GET['resep_id'];

mysqli_query(
    $conn,
    "DELETE FROM detail_resep WHERE detail_id = $id"
);

header("Location:details.php?resep_id=$resep_id");
exit;