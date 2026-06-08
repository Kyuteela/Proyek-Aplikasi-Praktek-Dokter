<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM pasien");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pasien</title>
</head>
<body>

<h1>Data Pasien</h1>

<a href="create.php">Tambah Pasien</a>

<br><br>

<table border="1" cellpadding="5">

    <tr>
        <th>ID</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>Jenis Kelamin</th>
        <th>No Telepon</th>
        <th>Aksi</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($query)) : ?>

    <tr>
        <td><?= $row['patient_id'] ?></td>
        <td><?= $row['nik'] ?></td>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['jenis_kelamin'] ?></td>
        <td><?= $row['no_telepon'] ?></td>

        <td>
            <a href="edit.php?id=<?= $row['patient_id'] ?>">Edit</a>
            |
            <a
                href="delete.php?id=<?= $row['patient_id'] ?>"
                onclick="return confirm('Yakin ingin menghapus data pasien ini?')"
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