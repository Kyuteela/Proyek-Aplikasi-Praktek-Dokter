<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM kunjungan WHERE visit_id = $id"
    )
);

$pasien = mysqli_query($conn,"SELECT * FROM pasien");
$dokter = mysqli_query($conn,"SELECT * FROM dokter");

if(isset($_POST['update'])){

    mysqli_query($conn,"
        UPDATE kunjungan
        SET
            patient_id='$_POST[patient_id]',
            doctor_id='$_POST[doctor_id]',
            tgl_kunjungan='$_POST[tgl_kunjungan]',
            jenis_layanan='$_POST[jenis_layanan]',
            antrian_no='$_POST[antrian_no]',
            waktu_datang='$_POST[waktu_datang]',
            waktu_selesai='$_POST[waktu_selesai]',
            status='$_POST[status]'
        WHERE visit_id=$id
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Kunjungan</title>
</head>
<body>

<h1>Edit Kunjungan</h1>

<form method="POST">

<p>Pasien</p>

<select name="patient_id">

<?php while($p = mysqli_fetch_assoc($pasien)) : ?>

<option
value="<?= $p['patient_id'] ?>"
<?= ($p['patient_id'] == $data['patient_id']) ? 'selected' : '' ?>
>
<?= $p['nama'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Dokter</p>

<select name="doctor_id">

<?php while($d = mysqli_fetch_assoc($dokter)) : ?>

<option
value="<?= $d['doctor_id'] ?>"
<?= ($d['doctor_id'] == $data['doctor_id']) ? 'selected' : '' ?>
>
<?= $d['nama'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Tanggal Kunjungan</p>

<input
type="date"
name="tgl_kunjungan"
value="<?= $data['tgl_kunjungan'] ?>"
required
>

<p>Jenis Layanan</p>

<input
type="text"
name="jenis_layanan"
value="<?= $data['jenis_layanan'] ?>"
required
>

<p>Nomor Antrian</p>

<input
type="number"
name="antrian_no"
value="<?= $data['antrian_no'] ?>"
required
>

<p>Waktu Datang</p>

<input
type="datetime-local"
name="waktu_datang"
value="<?= str_replace(' ','T',$data['waktu_datang']) ?>"
>

<p>Waktu Selesai</p>

<input
type="datetime-local"
name="waktu_selesai"
value="<?= str_replace(' ','T',$data['waktu_selesai']) ?>"
>

<p>Status</p>

<select name="status">

<option
value="Menunggu"
<?= ($data['status']=='Menunggu') ? 'selected' : '' ?>
>
Menunggu
</option>

<option
value="Selesai"
<?= ($data['status']=='Selesai') ? 'selected' : '' ?>
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