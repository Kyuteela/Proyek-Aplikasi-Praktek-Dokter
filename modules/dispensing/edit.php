<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM dispensing WHERE dispensing_id = $id"
    )
);

$detail_resep = mysqli_query($conn,"
SELECT
    dr.detail_id,
    dr.resep_id,
    dr.obat_id,
    o.nama_obat,
    dr.jumlah,
    p.nama AS pasien,
    doc.nama AS dokter,
    r.tanggal_resep
FROM detail_resep dr
JOIN obat o
    ON dr.obat_id = o.obat_id
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
ORDER BY dr.detail_id ASC
");

$obat = mysqli_query($conn,"SELECT * FROM obat ORDER BY nama_obat ASC");

$petugas = mysqli_query($conn,"
SELECT user_id, nama
FROM user
ORDER BY nama ASC
");

if(isset($_POST['update'])){

    mysqli_query($conn,"
    UPDATE dispensing
    SET
        detail_id='$_POST[detail_id]',
        obat_id='$_POST[obat_id]',
        edukasi_pasien='$_POST[edukasi_pasien]',
        serah_terima='$_POST[serah_terima]',
        petugas_id='$_POST[petugas_id]'
    WHERE dispensing_id=$id
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Dispensing</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h1 class="mb-4">Edit Dispensing</h1>

    <form method="POST" class="col-md-8">

        <div class="mb-3">
            <label class="form-label">Detail Resep</label>
            <select name="detail_id" class="form-select" required>

                <?php while($dr = mysqli_fetch_assoc($detail_resep)) : ?>

                <option
                    value="<?= $dr['detail_id'] ?>"
                    <?= ($dr['detail_id'] == $data['detail_id']) ? 'selected' : '' ?>
                >
                    Detail #<?= $dr['detail_id'] ?> -
                    Resep #<?= $dr['resep_id'] ?> -
                    <?= $dr['pasien'] ?> -
                    <?= $dr['nama_obat'] ?>
                </option>

                <?php endwhile; ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Obat</label>
            <select name="obat_id" class="form-select" required>

                <?php while($o = mysqli_fetch_assoc($obat)) : ?>

                <option
                    value="<?= $o['obat_id'] ?>"
                    <?= ($o['obat_id'] == $data['obat_id']) ? 'selected' : '' ?>
                >
                    <?= $o['nama_obat'] ?>
                </option>

                <?php endwhile; ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Edukasi Pasien</label>
            <textarea name="edukasi_pasien" class="form-control" rows="3"><?= $data['edukasi_pasien'] ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Serah Terima</label>
            <input
                type="text"
                name="serah_terima"
                class="form-control"
                value="<?= $data['serah_terima'] ?>"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Petugas</label>
            <select name="petugas_id" class="form-select">
                <option value="">-- Pilih Petugas --</option>

                <?php while($p = mysqli_fetch_assoc($petugas)) : ?>

                <option
                    value="<?= $p['user_id'] ?>"
                    <?= ($p['user_id'] == $data['petugas_id']) ? 'selected' : '' ?>
                >
                    <?= $p['nama'] ?>
                </option>

                <?php endwhile; ?>

            </select>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>

    </form>

</div>

</body>
</html>
