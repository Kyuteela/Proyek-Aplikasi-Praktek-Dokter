<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM rekam_medis WHERE record_id = $id"
    )
);

if(isset($_POST['update'])){

    mysqli_query($conn,"
    UPDATE rekam_medis
    SET
        anamnesa='$_POST[anamnesa]',
        pemeriksaan_fisik='$_POST[pemeriksaan_fisik]',
        catatan_klinis='$_POST[catatan_klinis]',
        riwayat_penyakit='$_POST[riwayat_penyakit]',
        alergi_obat_makanan='$_POST[alergi_obat_makanan]',
        tanggal_catatan='$_POST[tanggal_catatan]',
        vital_summary='$_POST[vital_summary]',
        tinggi_badan='$_POST[tinggi_badan]',
        berat_badan='$_POST[berat_badan]'
    WHERE record_id=$id
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Rekam Medis</title>
</head>
<body>

<h1>Edit Rekam Medis</h1>

<form method="POST">

<p>Anamnesa</p>
<textarea name="anamnesa"><?= $data['anamnesa'] ?></textarea>

<p>Pemeriksaan Fisik</p>
<textarea name="pemeriksaan_fisik"><?= $data['pemeriksaan_fisik'] ?></textarea>

<p>Catatan Klinis</p>
<textarea name="catatan_klinis"><?= $data['catatan_klinis'] ?></textarea>

<p>Riwayat Penyakit</p>
<textarea name="riwayat_penyakit"><?= $data['riwayat_penyakit'] ?></textarea>

<p>Alergi Obat/Makanan</p>
<textarea name="alergi_obat_makanan"><?= $data['alergi_obat_makanan'] ?></textarea>

<p>Tanggal Catatan</p>
<input
type="date"
name="tanggal_catatan"
value="<?= $data['tanggal_catatan'] ?>"
>

<p>Vital Summary</p>
<textarea name="vital_summary"><?= $data['vital_summary'] ?></textarea>

<p>Tinggi Badan</p>
<input
type="number"
step="0.01"
name="tinggi_badan"
value="<?= $data['tinggi_badan'] ?>"
>

<p>Berat Badan</p>
<input
type="number"
step="0.01"
name="berat_badan"
value="<?= $data['berat_badan'] ?>"
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