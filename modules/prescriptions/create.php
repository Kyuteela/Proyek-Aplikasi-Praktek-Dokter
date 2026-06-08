<?php

require_once '../../config/koneksi.php';

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

$dokters = mysqli_query($conn,"
SELECT * FROM dokter
");

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
    INSERT INTO resep
    (
        record_id,
        doctor_id,
        tanggal_resep,
        catatan_dokter,
        status_resep
    )
    VALUES
    (
        '$_POST[record_id]',
        '$_POST[doctor_id]',
        '$_POST[tanggal_resep]',
        '$_POST[catatan_dokter]',
        '$_POST[status_resep]'
    )
    ");

    header("Location:index.php");
    exit;
}

?>

<h1>Tambah Resep</h1>

<form method="POST">

<p>Rekam Medis</p>

<select name="record_id">

<?php while($r=mysqli_fetch_assoc($records)) : ?>

<option value="<?= $r['record_id'] ?>">
    Record <?= $r['record_id'] ?> -
    <?= $r['nama'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Dokter</p>

<select name="doctor_id">

<?php while($d=mysqli_fetch_assoc($dokters)) : ?>

<option value="<?= $d['doctor_id'] ?>">
    <?= $d['nama'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Tanggal Resep</p>

<input
type="date"
name="tanggal_resep"
required
>

<p>Catatan Dokter</p>

<textarea
name="catatan_dokter"
></textarea>

<p>Status Resep</p>

<select name="status_resep">

<option value="Aktif">
Aktif
</option>

<option value="Selesai">
Selesai
</option>

</select>

<br><br>

<button
type="submit"
name="simpan"
>
Simpan
</button>

</form>

<br>

<a href="index.php">
Kembali
</a>