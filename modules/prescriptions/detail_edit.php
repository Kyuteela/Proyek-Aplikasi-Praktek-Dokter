<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM detail_resep WHERE detail_id = $id"
    )
);

$obat = mysqli_query(
    $conn,
    "SELECT * FROM obat"
);

if(isset($_POST['update'])){

    mysqli_query($conn,"
    UPDATE detail_resep
    SET
        obat_id='$_POST[obat_id]',
        dosis='$_POST[dosis]',
        rute='$_POST[rute]',
        frekuensi='$_POST[frekuensi]',
        durasi='$_POST[durasi]',
        jumlah='$_POST[jumlah]',
        instruksi_khusus='$_POST[instruksi_khusus]'
    WHERE detail_id=$id
    ");

    header("Location:details.php?resep_id=".$data['resep_id']);
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Detail Resep</title>
</head>
<body>

<h1>Edit Detail Resep</h1>

<form method="POST">

<p>Obat</p>

<select name="obat_id">

<?php while($o = mysqli_fetch_assoc($obat)) : ?>

<option
value="<?= $o['obat_id'] ?>"
<?= ($o['obat_id'] == $data['obat_id']) ? 'selected' : '' ?>
>
<?= $o['nama_obat'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Dosis</p>
<input
type="text"
name="dosis"
value="<?= $data['dosis'] ?>"
>

<p>Rute</p>
<input
type="text"
name="rute"
value="<?= $data['rute'] ?>"
>

<p>Frekuensi</p>
<input
type="text"
name="frekuensi"
value="<?= $data['frekuensi'] ?>"
>

<p>Durasi</p>
<input
type="text"
name="durasi"
value="<?= $data['durasi'] ?>"
>

<p>Jumlah</p>
<input
type="number"
name="jumlah"
value="<?= $data['jumlah'] ?>"
>

<p>Instruksi Khusus</p>

<textarea name="instruksi_khusus"><?= $data['instruksi_khusus'] ?></textarea>

<br><br>

<button type="submit" name="update">
    Update
</button>

</form>

<br>

<a href="details.php?resep_id=<?= $data['resep_id'] ?>">
    Kembali
</a>

</body>
</html>