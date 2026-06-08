<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM detail_tagihan WHERE detail_tagihan_id = $id"
    )
);

if(isset($_POST['update'])){

    mysqli_query($conn,"
    UPDATE detail_tagihan
    SET
        jenis_item='$_POST[jenis_item]',
        deskripsi='$_POST[deskripsi]',
        tanggal_tagihan='$_POST[tanggal_tagihan]',
        harga_satuan='$_POST[harga_satuan]',
        sisa_piutang='$_POST[sisa_piutang]'
    WHERE detail_tagihan_id=$id
    ");

    header("Location:details.php?tagihan_id=".$data['tagihan_id']);
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Detail Tagihan</title>
</head>
<body>

<h1>Edit Detail Tagihan</h1>

<form method="POST">

<p>Jenis Item</p>
<input
type="text"
name="jenis_item"
value="<?= $data['jenis_item'] ?>"
>

<p>Deskripsi</p>
<input
type="text"
name="deskripsi"
value="<?= $data['deskripsi'] ?>"
>

<p>Tanggal</p>
<input
type="date"
name="tanggal_tagihan"
value="<?= $data['tanggal_tagihan'] ?>"
>

<p>Harga</p>
<input
type="number"
name="harga_satuan"
value="<?= $data['harga_satuan'] ?>"
>

<p>Sisa Piutang</p>
<input
type="number"
name="sisa_piutang"
value="<?= $data['sisa_piutang'] ?>"
>

<br><br>

<button type="submit" name="update">
Update
</button>

</form>

<br>

<a href="details.php?tagihan_id=<?= $data['tagihan_id'] ?>">
Kembali
</a>

</body>
</html>