<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM pasien WHERE patient_id = $id"
    )
);

if(isset($_POST['update'])){

    mysqli_query($conn,"
        UPDATE pasien
        SET
            nik='$_POST[nik]',
            nama='$_POST[nama]',
            tgl_lahir='$_POST[tgl_lahir]',
            jenis_kelamin='$_POST[jenis_kelamin]',
            alamat='$_POST[alamat]',
            no_telepon='$_POST[no_telepon]',
            email='$_POST[email]',
            kontak_darurat='$_POST[kontak_darurat]',
            asuransi_id='$_POST[asuransi_id]'
        WHERE patient_id=$id
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pasien</title>
</head>
<body>

<h1>Edit Pasien</h1>

<form method="POST">

    <p>NIK</p>
    <input
        type="text"
        name="nik"
        value="<?= $data['nik'] ?>"
        required
    >

    <p>Nama</p>
    <input
        type="text"
        name="nama"
        value="<?= $data['nama'] ?>"
        required
    >

    <p>Tanggal Lahir</p>
    <input
        type="date"
        name="tgl_lahir"
        value="<?= $data['tgl_lahir'] ?>"
        required
    >

    <p>Jenis Kelamin</p>

    <select name="jenis_kelamin">

        <option
            value="L"
            <?= $data['jenis_kelamin']=='L' ? 'selected' : '' ?>
        >
            Laki-Laki
        </option>

        <option
            value="P"
            <?= $data['jenis_kelamin']=='P' ? 'selected' : '' ?>
        >
            Perempuan
        </option>

    </select>

    <p>Alamat</p>
    <textarea name="alamat"><?= $data['alamat'] ?></textarea>

    <p>No Telepon</p>
    <input
        type="text"
        name="no_telepon"
        value="<?= $data['no_telepon'] ?>"
    >

    <p>Email</p>
    <input
        type="email"
        name="email"
        value="<?= $data['email'] ?>"
    >

    <p>Kontak Darurat</p>
    <input
        type="text"
        name="kontak_darurat"
        value="<?= $data['kontak_darurat'] ?>"
    >

    <p>Asuransi ID</p>
    <input
        type="text"
        name="asuransi_id"
        value="<?= $data['asuransi_id'] ?>"
    >

    <br><br>

    <button type="submit" name="update">
        Update
    </button>

</form>

<br>

<a href="index.php">Kembali</a>

</body>
</html>