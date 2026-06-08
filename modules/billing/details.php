<?php

require_once '../../config/koneksi.php';

$tagihan_id = $_GET['tagihan_id'];

$query = mysqli_query($conn,"
SELECT *
FROM detail_tagihan
WHERE tagihan_id = $tagihan_id
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Tagihan</title>
</head>
<body>

<h1>Detail Tagihan #<?= $tagihan_id ?></h1>

<a href="detail_create.php?tagihan_id=<?= $tagihan_id ?>">
    Tambah Detail
</a>

<br><br>

<table border="1" cellpadding="5">

<tr>
    <th>ID</th>
    <th>Jenis Item</th>
    <th>Deskripsi</th>
    <th>Tanggal</th>
    <th>Harga</th>
    <th>Sisa Piutang</th>
    <th>Aksi</th>
</tr>

<?php while($row=mysqli_fetch_assoc($query)) : ?>

<tr>

<td><?= $row['detail_tagihan_id'] ?></td>
<td><?= $row['jenis_item'] ?></td>
<td><?= $row['deskripsi'] ?></td>
<td><?= $row['tanggal_tagihan'] ?></td>
<td><?= $row['harga_satuan'] ?></td>
<td><?= $row['sisa_piutang'] ?></td>

<td>

<a href="detail_edit.php?id=<?= $row['detail_tagihan_id'] ?>">
    Edit
</a>

|

<a
href="detail_delete.php?id=<?= $row['detail_tagihan_id'] ?>&tagihan_id=<?= $tagihan_id ?>"
onclick="return confirm('Yakin ingin menghapus detail tagihan?')"
>
Hapus
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

<br>

<a href="index.php">
Kembali
</a>

</body>
</html>