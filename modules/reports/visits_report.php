<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT
    k.visit_id,
    p.nama AS pasien,
    d.nama AS dokter,
    k.tgl_kunjungan,
    k.status
FROM kunjungan k
JOIN pasien p
    ON k.patient_id = p.patient_id
JOIN dokter d
    ON k.doctor_id = d.doctor_id
ORDER BY k.visit_id ASC
");

?>

<h1>Laporan Kunjungan</h1>

<table border="1" cellpadding="5">

<tr>
    <th>ID</th>
    <th>Pasien</th>
    <th>Dokter</th>
    <th>Tanggal</th>
    <th>Status</th>
</tr>

<?php while($row=mysqli_fetch_assoc($query)) : ?>

<tr>
    <td><?= $row['visit_id'] ?></td>
    <td><?= $row['pasien'] ?></td>
    <td><?= $row['dokter'] ?></td>
    <td><?= $row['tgl_kunjungan'] ?></td>
    <td><?= $row['status'] ?></td>
</tr>

<?php endwhile; ?>

</table>

<br>

<a href="index.php">Kembali</a>