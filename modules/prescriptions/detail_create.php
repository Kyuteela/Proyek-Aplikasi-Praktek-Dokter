<?php

require_once '../../config/koneksi.php';

$resep_id = $_GET['resep_id'];

$obat = mysqli_query(
    $conn,
    "SELECT * FROM obat"
);

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
    INSERT INTO detail_resep
    (
        resep_id,
        obat_id,
        dosis,
        rute,
        frekuensi,
        durasi,
        jumlah,
        instruksi_khusus
    )
    VALUES
    (
        '$resep_id',
        '$_POST[obat_id]',
        '$_POST[dosis]',
        '$_POST[rute]',
        '$_POST[frekuensi]',
        '$_POST[durasi]',
        '$_POST[jumlah]',
        '$_POST[instruksi_khusus]'
    )
    ");

    header("Location:details.php?resep_id=$resep_id");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Detail Resep</title>
</head>
<body>

<h1>Tambah Obat ke Resep</h1>

<form method="POST">

<p>Obat</p>

<select name="obat_id">

<?php while($o = mysqli_fetch_assoc($obat)) : ?>

<option value="<?= $o['obat_id'] ?>">
    <?= $o['nama_obat'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Dosis</p>
<input type="text" name="dosis">

<p>Rute</p>
<input type="text" name="rute">

<p>Frekuensi</p>
<input type="text" name="frekuensi">

<p>Durasi</p>
<input type="text" name="durasi">

<p>Jumlah</p>
<input type="number" name="jumlah">

<p>Instruksi Khusus</p>
<textarea name="instruksi_khusus"></textarea>

<br><br>

<button type="submit" name="simpan">
    Simpan
</button>

</form>

<br>

<a href="details.php?resep_id=<?= $resep_id ?>">
    Kembali
</a>

</body>
</html>