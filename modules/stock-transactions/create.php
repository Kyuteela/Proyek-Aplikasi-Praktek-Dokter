<?php

require_once '../../config/koneksi.php';

$batch = mysqli_query($conn,"
SELECT
    b.batch_id,
    o.nama_obat,
    l.nama_lokasi,
    b.lokasi_rak
FROM batch_obat b
JOIN obat o
    ON b.obat_id = o.obat_id
JOIN lokasi l
    ON b.lokasi_id = l.lokasi_id
ORDER BY b.batch_id ASC
");

if(isset($_POST['simpan'])){

    $tanggal = str_replace('T', ' ', $_POST['tanggal']);

    mysqli_query($conn,"
    INSERT INTO transaksi_stok
    (
        batch_id,
        tanggal,
        jenis_transaksi,
        jumlah,
        referensi,
        keterangan
    )
    VALUES
    (
        '$_POST[batch_id]',
        '$tanggal',
        '$_POST[jenis_transaksi]',
        '$_POST[jumlah]',
        '$_POST[referensi]',
        '$_POST[keterangan]'
    )
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Transaksi Stok</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h1 class="mb-4">Tambah Transaksi Stok</h1>

    <form method="POST" class="col-md-8">

        <div class="mb-3">
            <label class="form-label">Batch Obat</label>
            <select name="batch_id" class="form-select" required>

                <?php while($b = mysqli_fetch_assoc($batch)) : ?>

                <option value="<?= $b['batch_id'] ?>">
                    Batch #<?= $b['batch_id'] ?> -
                    <?= $b['nama_obat'] ?> -
                    <?= $b['nama_lokasi'] ?> (<?= $b['lokasi_rak'] ?>)
                </option>

                <?php endwhile; ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="datetime-local" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Transaksi</label>
            <select name="jenis_transaksi" class="form-select" required>
                <option value="Masuk">Masuk</option>
                <option value="Keluar">Keluar</option>
                <option value="Penyesuaian">Penyesuaian</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Jumlah</label>
            <input type="number" name="jumlah" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Referensi</label>
            <input type="text" name="referensi" class="form-control" placeholder="Contoh: DISP-001, GR-001">
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>

    </form>

</div>

</body>
</html>
