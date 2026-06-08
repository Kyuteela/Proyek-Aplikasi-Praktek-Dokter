<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM tagihan WHERE tagihan_id = $id"
    )
);

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

if(isset($_POST['update'])){

    mysqli_query($conn,"
    UPDATE tagihan
    SET
        visit_id='$_POST[visit_id]',
        tanggal_tagihan='$_POST[tanggal_tagihan]',
        total_tagihan='$_POST[total_tagihan]',
        diskon='$_POST[diskon]',
        metode_pembayaran='$_POST[metode_pembayaran]',
        asuransi_id='$_POST[asuransi_id]',
        status='$_POST[status]'
    WHERE tagihan_id=$id
    ");

    header("Location:index.php");
    exit;
}

?>

<h1>Edit Tagihan</h1>

<form method="POST">

<p>Kunjungan</p>

<select name="visit_id">

<?php while($k=mysqli_fetch_assoc($kunjungan)) : ?>

<option
value="<?= $k['visit_id'] ?>"
<?= ($k['visit_id']==$data['visit_id']) ? 'selected' : '' ?>
>
Visit <?= $k['visit_id'] ?> -
<?= $k['nama'] ?> -
<?= $k['dokter'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Tanggal Tagihan</p>
<input
type="date"
name="tanggal_tagihan"
value="<?= $data['tanggal_tagihan'] ?>"
>

<p>Total Tagihan</p>
<input
type="number"
name="total_tagihan"
value="<?= $data['total_tagihan'] ?>"
>

<p>Diskon</p>
<input
type="number"
name="diskon"
value="<?= $data['diskon'] ?>"
>

<p>Metode Pembayaran</p>
<input
type="text"
name="metode_pembayaran"
value="<?= $data['metode_pembayaran'] ?>"
>

<p>Asuransi</p>
<input
type="text"
name="asuransi_id"
value="<?= $data['asuransi_id'] ?>"
>

<p>Status</p>

<select name="status">

<option
value="Belum Lunas"
<?= ($data['status']=='Belum Lunas') ? 'selected' : '' ?>
>
Belum Lunas
</option>

<option
value="Lunas"
<?= ($data['status']=='Lunas') ? 'selected' : '' ?>
>
Lunas
</option>

</select>

<br><br>

<button type="submit" name="update">
Update
</button>

</form>

<br>

<a href="index.php">Kembali</a>