<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM resep WHERE resep_id = $id"
    )
);

$records = mysqli_query($conn,"
SELECT
    rm.record_id,
    p.nama
FROM rekam_medis rm
JOIN kunjungan k
    ON rm.visit_id = k.visit_id
JOIN pasien p
    ON k.patient_id = p.patient_id
");

$dokters = mysqli_query(
    $conn,
    "SELECT * FROM dokter"
);

if(isset($_POST['update'])){

    mysqli_query($conn,"
    UPDATE resep
    SET
        record_id='$_POST[record_id]',
        doctor_id='$_POST[doctor_id]',
        tanggal_resep='$_POST[tanggal_resep]',
        catatan_dokter='$_POST[catatan_dokter]',
        status_resep='$_POST[status_resep]'
    WHERE resep_id=$id
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Resep</title>
</head>
<body>

<h1>Edit Resep</h1>

<form method="POST">

<p>Rekam Medis</p>

<select name="record_id">

<?php while($r=mysqli_fetch_assoc($records)) : ?>

<option
value="<?= $r['record_id'] ?>"
<?= $r['record_id']==$data['record_id'] ? 'selected' : '' ?>
>
Record <?= $r['record_id'] ?> - <?= $r['nama'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Dokter</p>

<select name="doctor_id">

<?php while($d=mysqli_fetch_assoc($dokters)) : ?>

<option
value="<?= $d['doctor_id'] ?>"
<?= $d['doctor_id']==$data['doctor_id'] ? 'selected' : '' ?>
>
<?= $d['nama'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Tanggal Resep</p>

<input
type="date"
name="tanggal_resep"
value="<?= $data['tanggal_resep'] ?>"
required
>

<p>Catatan Dokter</p>

<textarea name="catatan_dokter"><?= $data['catatan_dokter'] ?></textarea>

<p>Status Resep</p>

<select name="status_resep">

<option
value="Aktif"
<?= $data['status_resep']=='Aktif' ? 'selected' : '' ?>
>
Aktif
</option>

<option
value="Selesai"
<?= $data['status_resep']=='Selesai' ? 'selected' : '' ?>
>
Selesai
</option>

</select>

<br><br>

<button type="submit" name="update">
Update
</button>

</form>

<br>

<a href="index.php">Kembali</a>

</body>
</html>