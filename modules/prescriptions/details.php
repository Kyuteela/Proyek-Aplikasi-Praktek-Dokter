<?php

require_once '../../config/koneksi.php';

$resep_id = $_GET['resep_id'];

$query = mysqli_query($conn,"
SELECT
    dr.detail_id,
    o.nama_obat,
    dr.dosis,
    dr.rute,
    dr.frekuensi,
    dr.durasi,
    dr.jumlah,
    dr.instruksi_khusus
FROM detail_resep dr
JOIN obat o
    ON dr.obat_id = o.obat_id
WHERE dr.resep_id = $resep_id
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Resep</title>
</head>
<body>

<h1>Detail Resep #<?= $resep_id ?></h1>

<a href="detail_create.php?resep_id=<?= $resep_id ?>">
    Tambah Obat
</a>

<br><br>

<table border="1" cellpadding="5">

<tr>
    <th>ID</th>
    <th>Obat</th>
    <th>Dosis</th>
    <th>Rute</th>
    <th>Frekuensi</th>
    <th>Durasi</th>
    <th>Jumlah</th>
    <th>Instruksi</th>
    <th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) : ?>

<tr>

<td><?= $row['detail_id'] ?></td>
<td><?= $row['nama_obat'] ?></td>
<td><?= $row['dosis'] ?></td>
<td><?= $row['rute'] ?></td>
<td><?= $row['frekuensi'] ?></td>
<td><?= $row['durasi'] ?></td>
<td><?= $row['jumlah'] ?></td>
<td><?= $row['instruksi_khusus'] ?></td>

<td>

<a href="detail_edit.php?id=<?= $row['detail_id'] ?>">
    Edit
</a>

|

<a
href="detail_delete.php?id=<?= $row['detail_id'] ?>&resep_id=<?= $resep_id ?>"
onclick="return confirm('Yakin ingin menghapus obat ini?')"
>
Hapus
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

<br>

<a href="index.php">
    Kembali ke Resep
</a>

</body>
</html>