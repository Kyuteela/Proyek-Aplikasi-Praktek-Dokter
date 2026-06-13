<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT *
FROM lokasi
ORDER BY lokasi_id ASC
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Lokasi Penyimpanan</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Data Lokasi Penyimpanan</h1>
        <a href="create.php" class="btn btn-primary">Tambah Lokasi</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Lokasi</th>
                    <th>Tipe Lokasi</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

            <?php while($row = mysqli_fetch_assoc($query)) : ?>

            <tr>
                <td><?= $row['lokasi_id'] ?></td>
                <td><?= $row['nama_lokasi'] ?></td>
                <td><?= $row['tipe_lokasi'] ?></td>
                <td><?= $row['deskripsi'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['lokasi_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a
                        href="delete.php?id=<?= $row['lokasi_id'] ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Yakin ingin menghapus lokasi ini?')"
                    >
                        Hapus
                    </a>
                </td>
            </tr>

            <?php endwhile; ?>

            </tbody>
        </table>
    </div>

    <a href="../../index.php" class="btn btn-secondary">Kembali ke Dashboard</a>

</div>

</body>
</html>
