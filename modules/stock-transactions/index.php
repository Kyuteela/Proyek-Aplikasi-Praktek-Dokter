<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT
    ts.transaksi_stok_id,
    ts.batch_id,
    o.nama_obat,
    l.nama_lokasi,
    ts.tanggal,
    ts.jenis_transaksi,
    ts.jumlah,
    ts.referensi,
    ts.keterangan
FROM transaksi_stok ts
JOIN batch_obat b
    ON ts.batch_id = b.batch_id
JOIN obat o
    ON b.obat_id = o.obat_id
JOIN lokasi l
    ON b.lokasi_id = l.lokasi_id
ORDER BY ts.transaksi_stok_id ASC
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Transaksi Stok</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Data Transaksi Stok</h1>
        <a href="create.php" class="btn btn-primary">Tambah Transaksi Stok</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Batch</th>
                    <th>Obat</th>
                    <th>Lokasi</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Referensi</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

            <?php while($row = mysqli_fetch_assoc($query)) : ?>

            <tr>
                <td><?= $row['transaksi_stok_id'] ?></td>
                <td>#<?= $row['batch_id'] ?></td>
                <td><?= $row['nama_obat'] ?></td>
                <td><?= $row['nama_lokasi'] ?></td>
                <td><?= $row['tanggal'] ?></td>
                <td><?= $row['jenis_transaksi'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td><?= $row['referensi'] ?></td>
                <td><?= $row['keterangan'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['transaksi_stok_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a
                        href="delete.php?id=<?= $row['transaksi_stok_id'] ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Yakin ingin menghapus transaksi stok ini?')"
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
