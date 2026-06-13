<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM batch_obat WHERE batch_id = $id"
);

header("Location:index.php");
exit;
