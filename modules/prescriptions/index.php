<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT
    r.resep_id,
    p.nama AS pasien,
    d.nama AS dokter,
    r.tanggal_resep,
    r.catatan_dokter,
    r.status_resep
FROM resep r

JOIN rekam_medis rm
    ON r.record_id = rm.record_id

JOIN kunjungan k
    ON rm.visit_id = k.visit_id

JOIN pasien p
    ON k.patient_id = p.patient_id

JOIN dokter d
    ON r.doctor_id = d.doctor_id

ORDER BY r.resep_id ASC
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Resep</title>
</head>
<body>

<h1>Data Resep</h1>

<a href="create.php">Tambah Resep</a>

<br><br>

<table border="1" cellpadding="5">

<tr>
    <th>ID</th>
    <th>Pasien</th>
    <th>Dokter</th>
    <th>Tanggal</th>
    <th>Status</th>
    <th>Catatan Dokter</th>
    <th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) : ?>

<tr>

<td><?= $row['resep_id'] ?></td>
<td><?= $row['pasien'] ?></td>
<td><?= $row['dokter'] ?></td>
<td><?= $row['tanggal_resep'] ?></td>
<td><?= $row['status_resep'] ?></td>
<td><?= $row['catatan_dokter'] ?></td>

<td>

<a href="edit.php?id=<?= $row['resep_id'] ?>">
    Edit
</a>

|

<a
href="delete.php?id=<?= $row['resep_id'] ?>"
onclick="return confirm('Yakin ingin menghapus resep ini?')"
>
Hapus
</a>

|

<a href="details.php?resep_id=<?= $row['resep_id'] ?>">
    Detail
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