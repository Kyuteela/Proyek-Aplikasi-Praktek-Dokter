<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM user WHERE user_id=$id"
);

header("Location:index.php");
exit;