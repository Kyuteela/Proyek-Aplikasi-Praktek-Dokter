<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT *
FROM pasien
ORDER BY patient_id ASC
");

?>

<h1>Laporan Pasien</h1>

<table border="1" cellpadding="5">

<tr>
    <th>ID</th>
    <th>Nama</th>
    <th>NIK</th>
    <th>No Telepon</th>
</tr>

<?php while($row=mysqli_fetch_assoc($query)) : ?>

<tr>
    <td><?= $row['patient_id'] ?></td>
    <td><?= $row['nama'] ?></td>
    <td><?= $row['nik'] ?></td>
    <td><?= $row['no_telepon'] ?></td>
</tr>

<?php endwhile; ?>

</table>

<br>

<a href="index.php">Kembali</a>