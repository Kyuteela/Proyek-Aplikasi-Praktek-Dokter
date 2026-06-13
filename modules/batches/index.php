<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT
    b.batch_id,
    o.nama_obat,
    pb.faktur_no,
    l.nama_lokasi,
    b.expiry_date,
    b.harga_beli,
    b.lokasi_rak
FROM batch_obat b
JOIN obat o
    ON b.obat_id = o.obat_id
JOIN penerimaan_barang pb
    ON b.gr_id = pb.gr_id
JOIN lokasi l
    ON b.lokasi_id = l.lokasi_id
ORDER BY b.batch_id ASC
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Batch Obat</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Data Batch Obat</h1>
        <a href="create.php" class="btn btn-primary">Tambah Batch Obat</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Obat</th>
                    <th>Faktur GR</th>
                    <th>Lokasi</th>
                    <th>Expiry Date</th>
                    <th>Harga Beli</th>
                    <th>Lokasi Rak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

            <?php while($row = mysqli_fetch_assoc($query)) : ?>

            <tr>
                <td><?= $row['batch_id'] ?></td>
                <td><?= $row['nama_obat'] ?></td>
                <td><?= $row['faktur_no'] ?></td>
                <td><?= $row['nama_lokasi'] ?></td>
                <td><?= $row['expiry_date'] ?></td>
                <td><?= number_format($row['harga_beli'], 0, ',', '.') ?></td>
                <td><?= $row['lokasi_rak'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['batch_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a
                        href="delete.php?id=<?= $row['batch_id'] ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Yakin ingin menghapus batch obat ini?')"
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
