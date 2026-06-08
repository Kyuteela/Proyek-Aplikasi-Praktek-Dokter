<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM dokter");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Dokter</title>
</head>
<body>

<h1>Data Dokter</h1>

<a href="create.php">Tambah Dokter</a>

<br><br>

<table border="1" cellpadding="5">

<tr>
    <th>ID</th>
    <th>Nama</th>
    <th>SIP</th>
    <th>Spesialisasi</th>
    <th>Jadwal ID</th>
    <th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) : ?>

<tr>
    <td><?= $row['doctor_id'] ?></td>
    <td><?= $row['nama'] ?></td>
    <td><?= $row['sip_no'] ?></td>
    <td><?= $row['spesialisasi'] ?></td>
    <td><?= $row['jadwal_id'] ?></td>

    <td>
        <a href="edit.php?id=<?= $row['doctor_id'] ?>">Edit</a>
        |
        <a
            href="delete.php?id=<?= $row['doctor_id'] ?>"
            onclick="return confirm('Yakin ingin menghapus data dokter ini?')"
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