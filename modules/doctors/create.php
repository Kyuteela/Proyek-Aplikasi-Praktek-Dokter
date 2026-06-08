<?php

require_once '../../config/koneksi.php';

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
        INSERT INTO dokter
        (
            nama,
            sip_no,
            spesialisasi,
            jadwal_id
        )
        VALUES
        (
            '$_POST[nama]',
            '$_POST[sip_no]',
            '$_POST[spesialisasi]',
            '$_POST[jadwal_id]'
        )
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Dokter</title>
</head>
<body>

<h1>Tambah Dokter</h1>

<form method="POST">

    <p>Nama Dokter</p>
    <input type="text" name="nama" required>

    <p>No SIP</p>
    <input type="text" name="sip_no" required>

    <p>Spesialisasi</p>
    <input type="text" name="spesialisasi">

    <p>Jadwal ID</p>
    <input type="number" name="jadwal_id">

    <br><br>

    <button type="submit" name="simpan">
        Simpan
    </button>

</form>

<br>

<a href="index.php">Kembali</a>

</body>
</html>