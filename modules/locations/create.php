<?php

require_once '../../config/koneksi.php';

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
    INSERT INTO lokasi
    (
        nama_lokasi,
        tipe_lokasi,
        deskripsi
    )
    VALUES
    (
        '$_POST[nama_lokasi]',
        '$_POST[tipe_lokasi]',
        '$_POST[deskripsi]'
    )
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Lokasi Penyimpanan</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h1 class="mb-4">Tambah Lokasi Penyimpanan</h1>

    <form method="POST" class="col-md-8">

        <div class="mb-3">
            <label class="form-label">Nama Lokasi</label>
            <input type="text" name="nama_lokasi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipe Lokasi</label>
            <input type="text" name="tipe_lokasi" class="form-control" placeholder="Contoh: Gudang, Rak, Ruangan">
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>

    </form>

</div>

</body>
</html>
