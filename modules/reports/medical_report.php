<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT
    rm.record_id,
    p.nama AS pasien,
    d.nama AS dokter,
    rm.anamnesa,
    rm.tanggal_catatan
FROM rekam_medis rm
JOIN kunjungan k
    ON rm.visit_id = k.visit_id
JOIN pasien p
    ON k.patient_id = p.patient_id
JOIN dokter d
    ON k.doctor_id = d.doctor_id
ORDER BY rm.record_id ASC
");

?>

<h1>Laporan Rekam Medis</h1>

<table border="1" cellpadding="5">

<tr>
    <th>ID</th>
    <th>Pasien</th>
    <th>Dokter</th>
    <th>Anamnesa</th>
    <th>Tanggal</th>
</tr>

<?php while($row=mysqli_fetch_assoc($query)) : ?>

<tr>
    <td><?= $row['record_id'] ?></td>
    <td><?= $row['pasien'] ?></td>
    <td><?= $row['dokter'] ?></td>
    <td><?= $row['anamnesa'] ?></td>
    <td><?= $row['tanggal_catatan'] ?></td>
</tr>

<?php endwhile; ?>

</table>

<br>

<a href="index.php">Kembali</a>