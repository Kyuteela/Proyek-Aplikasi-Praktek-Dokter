<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT
    t.tagihan_id,
    p.nama AS pasien,
    d.nama AS dokter,
    t.tanggal_tagihan,
    t.total_tagihan,
    t.diskon,
    t.metode_pembayaran,
    t.status

FROM tagihan t

JOIN kunjungan k
    ON t.visit_id = k.visit_id

JOIN pasien p
    ON k.patient_id = p.patient_id

JOIN dokter d
    ON k.doctor_id = d.doctor_id

ORDER BY t.tagihan_id ASC
");

?>

<!DOCTYPE html>
<html>
<head>
<title>Data Tagihan</title>
</head>
<body>

<h1>Data Tagihan</h1>

<a href="create.php">
Tambah Tagihan
</a>

<br><br>

<table border="1" cellpadding="5">

<tr>
<th>ID</th>
<th>Pasien</th>
<th>Dokter</th>
<th>Tanggal</th>
<th>Total</th>
<th>Diskon</th>
<th>Metode</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php while($row=mysqli_fetch_assoc($query)) : ?>

<tr>

<td><?= $row['tagihan_id'] ?></td>
<td><?= $row['pasien'] ?></td>
<td><?= $row['dokter'] ?></td>
<td><?= $row['tanggal_tagihan'] ?></td>
<td><?= $row['total_tagihan'] ?></td>
<td><?= $row['diskon'] ?></td>
<td><?= $row['metode_pembayaran'] ?></td>
<td><?= $row['status'] ?></td>

<td>

<a href="edit.php?id=<?= $row['tagihan_id'] ?>">
Edit
</a>

|

<a
href="delete.php?id=<?= $row['tagihan_id'] ?>"
onclick="return confirm('Yakin hapus tagihan?')"
>
Hapus
</a>

|

<a href="details.php?tagihan_id=<?= $row['tagihan_id'] ?>">
Detail
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

<br>

<a href="../../index.php">
Kembali Dashboard
</a>

</body>
</html>