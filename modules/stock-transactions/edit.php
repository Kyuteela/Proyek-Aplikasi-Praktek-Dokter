<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM transaksi_stok WHERE transaksi_stok_id = $id"
    )
);

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

if(isset($_POST['update'])){

    $tanggal = str_replace('T', ' ', $_POST['tanggal']);

    mysqli_query($conn,"
    UPDATE transaksi_stok
    SET
        batch_id='$_POST[batch_id]',
        tanggal='$tanggal',
        jenis_transaksi='$_POST[jenis_transaksi]',
        jumlah='$_POST[jumlah]',
        referensi='$_POST[referensi]',
        keterangan='$_POST[keterangan]'
    WHERE transaksi_stok_id=$id
    ");

    header("Location:index.php");
    exit;
}

$tanggal_value = str_replace(' ', 'T', $data['tanggal']);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaksi Stok</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h1 class="mb-4">Edit Transaksi Stok</h1>

    <form method="POST" class="col-md-8">

        <div class="mb-3">
            <label class="form-label">Batch Obat</label>
            <select name="batch_id" class="form-select" required>

                <?php while($b = mysqli_fetch_assoc($batch)) : ?>

                <option
                    value="<?= $b['batch_id'] ?>"
                    <?= ($b['batch_id'] == $data['batch_id']) ? 'selected' : '' ?>
                >
                    Batch #<?= $b['batch_id'] ?> -
                    <?= $b['nama_obat'] ?> -
                    <?= $b['nama_lokasi'] ?> (<?= $b['lokasi_rak'] ?>)
                </option>

                <?php endwhile; ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input
                type="datetime-local"
                name="tanggal"
                class="form-control"
                value="<?= $tanggal_value ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Transaksi</label>
            <select name="jenis_transaksi" class="form-select" required>
                <option
                    value="Masuk"
                    <?= ($data['jenis_transaksi'] == 'Masuk') ? 'selected' : '' ?>
                >
                    Masuk
                </option>
                <option
                    value="Keluar"
                    <?= ($data['jenis_transaksi'] == 'Keluar') ? 'selected' : '' ?>
                >
                    Keluar
                </option>
                <option
                    value="Penyesuaian"
                    <?= ($data['jenis_transaksi'] == 'Penyesuaian') ? 'selected' : '' ?>
                >
                    Penyesuaian
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Jumlah</label>
            <input
                type="number"
                name="jumlah"
                class="form-control"
                min="1"
                value="<?= $data['jumlah'] ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Referensi</label>
            <input
                type="text"
                name="referensi"
                class="form-control"
                value="<?= $data['referensi'] ?>"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3"><?= $data['keterangan'] ?></textarea>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>

    </form>

</div>

</body>
</html>
