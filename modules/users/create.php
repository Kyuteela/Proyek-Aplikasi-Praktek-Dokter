<?php

require_once '../../config/koneksi.php';

$role = mysqli_query(
    $conn,
    "SELECT * FROM role"
);

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
    INSERT INTO user
    (
        nama,
        username,
        password,
        kontak,
        id_role
    )
    VALUES
    (
        '$_POST[nama]',
        '$_POST[username]',
        '$_POST[password]',
        '$_POST[kontak]',
        '$_POST[id_role]'
    )
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah User</title>
</head>
<body>

<h1>Tambah User</h1>

<form method="POST">

<p>Nama</p>
<input
type="text"
name="nama"
required
>

<p>Username</p>
<input
type="text"
name="username"
required
>

<p>Password</p>
<input
type="text"
name="password"
required
>

<p>Kontak</p>
<input
type="text"
name="kontak"
>

<p>Role</p>

<select name="id_role">

<?php while($r = mysqli_fetch_assoc($role)) : ?>

<option value="<?= $r['id_role'] ?>">
    <?= $r['nama_role'] ?>
</option>

<?php endwhile; ?>

</select>

<br><br>

<button type="submit" name="simpan">
    Simpan
</button>

</form>

<br>

<a href="index.php">
    Kembali
</a>

</body>
</html>