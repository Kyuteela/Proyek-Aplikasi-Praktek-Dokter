<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM dispensing WHERE dispensing_id = $id"
);

header("Location:index.php");
exit;
