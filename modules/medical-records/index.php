<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT
    rm.record_id,
    rm.visit_id,
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

<!DOCTYPE html>
<html>
<head>
    <title>Rekam Medis</title>
</head>
<body>

<h1>Data Rekam Medis</h1>

<a href="create.php">Tambah Rekam Medis</a>

<br><br>

<table border="1" cellpadding="5">

<tr>
    <th>ID</th>
    <th>Visit ID</th>
    <th>Pasien</th>
    <th>Dokter</th>
    <th>Anamnesa</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) : ?>

<tr>

<td><?= $row['record_id'] ?></td>
<td><?= $row['visit_id'] ?></td>
<td><?= $row['pasien'] ?></td>
<td><?= $row['dokter'] ?></td>
<td><?= $row['anamnesa'] ?></td>
<td><?= $row['tanggal_catatan'] ?></td>

<td>

<a href="edit.php?id=<?= $row['record_id'] ?>">
    Edit
</a>

|

<a
href="delete.php?id=<?= $row['record_id'] ?>"
onclick="return confirm('Yakin ingin menghapus rekam medis ini?')"
>
Hapus
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

<br>

<a href="../../index.php">
Kembali ke Dashboard
</a>

</body>
</html>