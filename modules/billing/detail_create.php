<?php

require_once '../../config/koneksi.php';

$tagihan_id = $_GET['tagihan_id'];

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
    INSERT INTO detail_tagihan
    (
        tagihan_id,
        jenis_item,
        deskripsi,
        tanggal_tagihan,
        harga_satuan,
        sisa_piutang
    )
    VALUES
    (
        '$tagihan_id',
        '$_POST[jenis_item]',
        '$_POST[deskripsi]',
        '$_POST[tanggal_tagihan]',
        '$_POST[harga_satuan]',
        '$_POST[sisa_piutang]'
    )
    ");

    header("Location:details.php?tagihan_id=$tagihan_id");
    exit;
}

?>

<h1>Tambah Detail Tagihan</h1>

<form method="POST">

<p>Jenis Item</p>
<input type="text" name="jenis_item">

<p>Deskripsi</p>
<input type="text" name="deskripsi">

<p>Tanggal</p>
<input type="date" name="tanggal_tagihan">

<p>Harga</p>
<input type="number" name="harga_satuan">

<p>Sisa Piutang</p>
<input type="number" name="sisa_piutang">

<br><br>

<button type="submit" name="simpan">
Simpan
</button>

</form>

<br>

<a href="details.php?tagihan_id=<?= $tagihan_id ?>">
Kembali
</a>