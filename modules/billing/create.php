<?php

require_once '../../config/koneksi.php';

$kunjungan = mysqli_query($conn,"
SELECT
    k.visit_id,
    p.nama,
    d.nama AS dokter
FROM kunjungan k
JOIN pasien p
    ON k.patient_id = p.patient_id
JOIN dokter d
    ON k.doctor_id = d.doctor_id
");

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
    INSERT INTO tagihan
    (
        visit_id,
        tanggal_tagihan,
        total_tagihan,
        diskon,
        metode_pembayaran,
        asuransi_id,
        status
    )
    VALUES
    (
        '$_POST[visit_id]',
        '$_POST[tanggal_tagihan]',
        '$_POST[total_tagihan]',
        '$_POST[diskon]',
        '$_POST[metode_pembayaran]',
        '$_POST[asuransi_id]',
        '$_POST[status]'
    )
    ");

    header("Location:index.php");
    exit;
}

?>

<h1>Tambah Tagihan</h1>

<form method="POST">

<p>Kunjungan</p>

<select name="visit_id">

<?php while($k=mysqli_fetch_assoc($kunjungan)) : ?>

<option value="<?= $k['visit_id'] ?>">
Visit <?= $k['visit_id'] ?> -
<?= $k['nama'] ?> -
<?= $k['dokter'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Tanggal Tagihan</p>
<input type="date" name="tanggal_tagihan" required>

<p>Total Tagihan</p>
<input type="number" name="total_tagihan">

<p>Diskon</p>
<input type="number" name="diskon">

<p>Metode Pembayaran</p>
<input type="text" name="metode_pembayaran">

<p>Asuransi</p>
<input type="text" name="asuransi_id">

<p>Status</p>

<select name="status">
<option value="Belum Lunas">Belum Lunas</option>
<option value="Lunas">Lunas</option>
</select>

<br><br>

<button type="submit" name="simpan">
Simpan
</button>

</form>

<br>

<a href="index.php">Kembali</a>