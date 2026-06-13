<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT
    d.dispensing_id,
    d.detail_id,
    o.nama_obat,
    dr.dosis,
    dr.jumlah,
    r.resep_id,
    r.tanggal_resep,
    r.status_resep,
    p.nama AS pasien,
    doc.nama AS dokter,
    d.edukasi_pasien,
    d.serah_terima,
    u.nama AS petugas
FROM dispensing d
JOIN detail_resep dr
    ON d.detail_id = dr.detail_id
JOIN obat o
    ON d.obat_id = o.obat_id
JOIN resep r
    ON dr.resep_id = r.resep_id
JOIN rekam_medis rm
    ON r.record_id = rm.record_id
JOIN kunjungan k
    ON rm.visit_id = k.visit_id
JOIN pasien p
    ON k.patient_id = p.patient_id
JOIN dokter doc
    ON r.doctor_id = doc.doctor_id
LEFT JOIN user u
    ON d.petugas_id = u.user_id
ORDER BY d.dispensing_id ASC
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Dispensing</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Data Dispensing</h1>
        <a href="create.php" class="btn btn-primary">Tambah Dispensing</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Resep</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Obat</th>
                    <th>Dosis</th>
                    <th>Jumlah</th>
                    <th>Serah Terima</th>
                    <th>Petugas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

            <?php while($row = mysqli_fetch_assoc($query)) : ?>

            <tr>
                <td><?= $row['dispensing_id'] ?></td>
                <td>
                    #<?= $row['resep_id'] ?>
                    <br>
                    <small class="text-muted"><?= $row['tanggal_resep'] ?> (<?= $row['status_resep'] ?>)</small>
                </td>
                <td><?= $row['pasien'] ?></td>
                <td><?= $row['dokter'] ?></td>
                <td><?= $row['nama_obat'] ?></td>
                <td><?= $row['dosis'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td><?= $row['serah_terima'] ?></td>
                <td><?= $row['petugas'] ?? '-' ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['dispensing_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a
                        href="delete.php?id=<?= $row['dispensing_id'] ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Yakin ingin menghapus data dispensing ini?')"
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
