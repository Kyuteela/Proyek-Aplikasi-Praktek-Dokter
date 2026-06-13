<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM lokasi WHERE lokasi_id = $id"
    )
);

if(isset($_POST['update'])){

    mysqli_query($conn,"
    UPDATE lokasi
    SET
        nama_lokasi='$_POST[nama_lokasi]',
        tipe_lokasi='$_POST[tipe_lokasi]',
        deskripsi='$_POST[deskripsi]'
    WHERE lokasi_id=$id
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Lokasi Penyimpanan</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h1 class="mb-4">Edit Lokasi Penyimpanan</h1>

    <form method="POST" class="col-md-8">

        <div class="mb-3">
            <label class="form-label">Nama Lokasi</label>
            <input
                type="text"
                name="nama_lokasi"
                class="form-control"
                value="<?= $data['nama_lokasi'] ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Tipe Lokasi</label>
            <input
                type="text"
                name="tipe_lokasi"
                class="form-control"
                value="<?= $data['tipe_lokasi'] ?>"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"><?= $data['deskripsi'] ?></textarea>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>

    </form>

</div>

</body>
</html>
