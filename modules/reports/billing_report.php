<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT
    t.tagihan_id,
    p.nama AS pasien,
    t.tanggal_tagihan,
    t.total_tagihan,
    t.status
FROM tagihan t
JOIN kunjungan k
    ON t.visit_id = k.visit_id
JOIN pasien p
    ON k.patient_id = p.patient_id
ORDER BY t.tagihan_id ASC
");

?>

<h1>Laporan Tagihan</h1>

<table border="1" cellpadding="5">

<tr>
    <th>ID</th>
    <th>Pasien</th>
    <th>Tanggal</th>
    <th>Total</th>
    <th>Status</th>
</tr>

<?php while($row=mysqli_fetch_assoc($query)) : ?>

<tr>
    <td><?= $row['tagihan_id'] ?></td>
    <td><?= $row['pasien'] ?></td>
    <td><?= $row['tanggal_tagihan'] ?></td>
    <td><?= $row['total_tagihan'] ?></td>
    <td><?= $row['status'] ?></td>
</tr>

<?php endwhile; ?>

</table>

<br>

<a href="index.php">Kembali</a>