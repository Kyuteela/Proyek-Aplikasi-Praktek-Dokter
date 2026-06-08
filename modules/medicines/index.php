<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM obat");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Obat</title>
</head>
<body>

<h1>Data Obat</h1>

<a href="create.php">Tambah Obat</a>

<br><br>

<table border="1" cellpadding="5">

<tr>
    <th>ID</th>
    <th>Nama Obat</th>
    <th>Bentuk Sediaan</th>
    <th>Satuan</th>
    <th>Kategori</th>
    <th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) : ?>

<tr>
    <td><?= $row['obat_id'] ?></td>
    <td><?= $row['nama_obat'] ?></td>
    <td><?= $row['bentuk_sediaan'] ?></td>
    <td><?= $row['satuan'] ?></td>
    <td><?= $row['kategori'] ?></td>

    <td>
        <a href="edit.php?id=<?= $row['obat_id'] ?>">Edit</a>
        |
        <a
            href="delete.php?id=<?= $row['obat_id'] ?>"
            onclick="return confirm('Yakin ingin menghapus data obat ini?')"
        >
            Hapus
        </a>
    </td>

</tr>

<?php endwhile; ?>

</table>

<br>

<a href="../../index.php">Kembali ke Dashboard</a>

</body>
</html>