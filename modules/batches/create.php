<?php

require_once '../../config/koneksi.php';

$obat = mysqli_query($conn,"SELECT * FROM obat ORDER BY nama_obat ASC");

$penerimaan = mysqli_query($conn,"
SELECT gr_id, faktur_no
FROM penerimaan_barang
ORDER BY gr_id ASC
");

$lokasi = mysqli_query($conn,"
SELECT lokasi_id, nama_lokasi, tipe_lokasi
FROM lokasi
ORDER BY nama_lokasi ASC
");

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
    INSERT INTO batch_obat
    (
        obat_id,
        gr_id,
        lokasi_id,
        expiry_date,
        harga_beli,
        lokasi_rak
    )
    VALUES
    (
        '$_POST[obat_id]',
        '$_POST[gr_id]',
        '$_POST[lokasi_id]',
        '$_POST[expiry_date]',
        '$_POST[harga_beli]',
        '$_POST[lokasi_rak]'
    )
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Batch Obat</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h1 class="mb-4">Tambah Batch Obat</h1>

    <form method="POST" class="col-md-8">

        <div class="mb-3">
            <label class="form-label">Obat</label>
            <select name="obat_id" class="form-select" required>

                <?php while($o = mysqli_fetch_assoc($obat)) : ?>

                <option value="<?= $o['obat_id'] ?>">
                    <?= $o['nama_obat'] ?>
                </option>

                <?php endwhile; ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Penerimaan Barang (GR)</label>
            <select name="gr_id" class="form-select" required>

                <?php while($gr = mysqli_fetch_assoc($penerimaan)) : ?>

                <option value="<?= $gr['gr_id'] ?>">
                    GR #<?= $gr['gr_id'] ?> - <?= $gr['faktur_no'] ?>
                </option>

                <?php endwhile; ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Lokasi Penyimpanan</label>
            <select name="lokasi_id" class="form-select" required>

                <?php while($l = mysqli_fetch_assoc($lokasi)) : ?>

                <option value="<?= $l['lokasi_id'] ?>">
                    <?= $l['nama_lokasi'] ?> (<?= $l['tipe_lokasi'] ?>)
                </option>

                <?php endwhile; ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Expired</label>
            <input type="date" name="expiry_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Harga Beli</label>
            <input type="number" name="harga_beli" class="form-control" step="0.01" min="0.01" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lokasi Rak</label>
            <input type="text" name="lokasi_rak" class="form-control" placeholder="Contoh: A01">
        </div>

        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>

    </form>

</div>

</body>
</html>
