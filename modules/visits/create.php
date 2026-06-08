<?php

require_once '../../config/koneksi.php';

$pasien = mysqli_query($conn,"SELECT * FROM pasien");
$dokter = mysqli_query($conn,"SELECT * FROM dokter");

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
    INSERT INTO kunjungan
    (
        patient_id,
        doctor_id,
        tgl_kunjungan,
        jenis_layanan,
        antrian_no,
        waktu_datang,
        waktu_selesai,
        status
    )
    VALUES
    (
        '$_POST[patient_id]',
        '$_POST[doctor_id]',
        '$_POST[tgl_kunjungan]',
        '$_POST[jenis_layanan]',
        '$_POST[antrian_no]',
        '$_POST[waktu_datang]',
        '$_POST[waktu_selesai]',
        '$_POST[status]'
    )
    ");

    header("Location:index.php");
    exit;
}

?>

<h1>Tambah Kunjungan</h1>

<form method="POST">

<p>Pasien</p>

<select name="patient_id" required>

<?php while($p=mysqli_fetch_assoc($pasien)): ?>

<option value="<?= $p['patient_id'] ?>">
    <?= $p['nama'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Dokter</p>

<select name="doctor_id" required>

<?php while($d=mysqli_fetch_assoc($dokter)): ?>

<option value="<?= $d['doctor_id'] ?>">
    <?= $d['nama'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Tanggal Kunjungan</p>
<input type="date" name="tgl_kunjungan" required>

<p>Jenis Layanan</p>
<input type="text" name="jenis_layanan" required>

<p>Nomor Antrian</p>
<input type="number" name="antrian_no" required>

<p>Waktu Datang</p>
<input type="datetime-local" name="waktu_datang">

<p>Waktu Selesai</p>
<input type="datetime-local" name="waktu_selesai">

<p>Status</p>

<select name="status">

<option value="Menunggu">Menunggu</option>
<option value="Selesai">Selesai</option>

</select>

<br><br>

<button type="submit" name="simpan">
    Simpan
</button>

</form>

<br>

<a href="index.php">Kembali</a>