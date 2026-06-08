<?php

require_once '../../config/koneksi.php';

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
        INSERT INTO pasien
        (
            nik,
            nama,
            tgl_lahir,
            jenis_kelamin,
            alamat,
            no_telepon,
            email,
            kontak_darurat,
            asuransi_id
        )
        VALUES
        (
            '$_POST[nik]',
            '$_POST[nama]',
            '$_POST[tgl_lahir]',
            '$_POST[jenis_kelamin]',
            '$_POST[alamat]',
            '$_POST[no_telepon]',
            '$_POST[email]',
            '$_POST[kontak_darurat]',
            '$_POST[asuransi_id]'
        )
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pasien</title>
</head>
<body>

<h1>Tambah Pasien</h1>

<form method="POST">

    <p>NIK</p>
    <input type="text" name="nik" required>

    <p>Nama</p>
    <input type="text" name="nama" required>

    <p>Tanggal Lahir</p>
    <input type="date" name="tgl_lahir" required>

    <p>Jenis Kelamin</p>
    <select name="jenis_kelamin">
        <option value="L">Laki-Laki</option>
        <option value="P">Perempuan</option>
    </select>

    <p>Alamat</p>
    <textarea name="alamat"></textarea>

    <p>No Telepon</p>
    <input type="text" name="no_telepon">

    <p>Email</p>
    <input type="email" name="email">

    <p>Kontak Darurat</p>
    <input type="text" name="kontak_darurat">

    <p>Asuransi ID</p>
    <input type="text" name="asuransi_id">

    <br><br>

    <button type="submit" name="simpan">
        Simpan
    </button>

</form>

<br>

<a href="index.php">Kembali</a>

</body>
</html>