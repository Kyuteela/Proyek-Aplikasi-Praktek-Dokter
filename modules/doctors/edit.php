<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM dokter WHERE doctor_id = $id"
    )
);

if(isset($_POST['update'])){

    mysqli_query($conn,"
        UPDATE dokter
        SET
            nama='$_POST[nama]',
            sip_no='$_POST[sip_no]',
            spesialisasi='$_POST[spesialisasi]',
            jadwal_id='$_POST[jadwal_id]'
        WHERE doctor_id=$id
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Dokter</title>
</head>
<body>

<h1>Edit Dokter</h1>

<form method="POST">

    <p>Nama Dokter</p>
    <input
        type="text"
        name="nama"
        value="<?= $data['nama'] ?>"
        required
    >

    <p>No SIP</p>
    <input
        type="text"
        name="sip_no"
        value="<?= $data['sip_no'] ?>"
        required
    >

    <p>Spesialisasi</p>
    <input
        type="text"
        name="spesialisasi"
        value="<?= $data['spesialisasi'] ?>"
    >

    <p>Jadwal ID</p>
    <input
        type="number"
        name="jadwal_id"
        value="<?= $data['jadwal_id'] ?>"
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