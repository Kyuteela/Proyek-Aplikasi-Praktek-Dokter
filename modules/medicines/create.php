<?php

require_once '../../config/koneksi.php';

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
        INSERT INTO obat
        (
            nama_obat,
            bentuk_sediaan,
            satuan,
            kategori
        )
        VALUES
        (
            '$_POST[nama_obat]',
            '$_POST[bentuk_sediaan]',
            '$_POST[satuan]',
            '$_POST[kategori]'
        )
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Obat</title>
</head>
<body>

<h1>Tambah Obat</h1>

<form method="POST">

    <p>Nama Obat</p>
    <input type="text" name="nama_obat" required>

    <p>Bentuk Sediaan</p>
    <input type="text" name="bentuk_sediaan">

    <p>Satuan</p>
    <input type="text" name="satuan">

    <p>Kategori</p>
    <input type="text" name="kategori">

    <br><br>

    <button type="submit" name="simpan">
        Simpan
    </button>

</form>

<br>

<a href="index.php">Kembali</a>

</body>
</html>