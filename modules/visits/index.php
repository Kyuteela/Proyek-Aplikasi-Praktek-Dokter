<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT
    k.visit_id,
    p.nama AS nama_pasien,
    d.nama AS nama_dokter,
    k.tgl_kunjungan,
    k.jenis_layanan,
    k.antrian_no,
    k.status
FROM kunjungan k
INNER JOIN pasien p
    ON k.patient_id = p.patient_id
INNER JOIN dokter d
    ON k.doctor_id = d.doctor_id
ORDER BY k.visit_id ASC
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Kunjungan</title>
</head>
<body>

<h1>Data Kunjungan</h1>

<a href="create.php">Tambah Kunjungan</a>

<br><br>

<table border="1" cellpadding="5">

<tr>
    <th>ID</th>
    <th>Pasien</th>
    <th>Dokter</th>
    <th>Tanggal</th>
    <th>Layanan</th>
    <th>Antrian</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) : ?>

<tr>

<td><?= $row['visit_id'] ?></td>
<td><?= $row['nama_pasien'] ?></td>
<td><?= $row['nama_dokter'] ?></td>
<td><?= $row['tgl_kunjungan'] ?></td>
<td><?= $row['jenis_layanan'] ?></td>
<td><?= $row['antrian_no'] ?></td>
<td><?= $row['status'] ?></td>

<td>
    <a href="edit.php?id=<?= $row['visit_id'] ?>">
        Edit
    </a>

    |

    <a
        href="delete.php?id=<?= $row['visit_id'] ?>"
        onclick="return confirm('Yakin ingin menghapus kunjungan ini?')"
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