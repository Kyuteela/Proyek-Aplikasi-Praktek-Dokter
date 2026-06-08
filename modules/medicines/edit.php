<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM obat WHERE obat_id = $id"
    )
);

if(isset($_POST['update'])){

    mysqli_query($conn,"
        UPDATE obat
        SET
            nama_obat='$_POST[nama_obat]',
            bentuk_sediaan='$_POST[bentuk_sediaan]',
            satuan='$_POST[satuan]',
            kategori='$_POST[kategori]'
        WHERE obat_id=$id
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Obat</title>
</head>
<body>

<h1>Edit Obat</h1>

<form method="POST">

    <p>Nama Obat</p>
    <input
        type="text"
        name="nama_obat"
        value="<?= $data['nama_obat'] ?>"
        required
    >

    <p>Bentuk Sediaan</p>
    <input
        type="text"
        name="bentuk_sediaan"
        value="<?= $data['bentuk_sediaan'] ?>"
    >

    <p>Satuan</p>
    <input
        type="text"
        name="satuan"
        value="<?= $data['satuan'] ?>"
    >

    <p>Kategori</p>
    <input
        type="text"
        name="kategori"
        value="<?= $data['kategori'] ?>"
    >

    <br><br>

    <button type="submit" name="update">
        Update
    </button>

</form>

<br>

<a href="index.php">Kembali</a>

</body>
</html>