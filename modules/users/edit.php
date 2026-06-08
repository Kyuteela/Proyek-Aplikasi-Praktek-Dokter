<?php

require_once '../../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM user WHERE user_id = $id"
    )
);

$role = mysqli_query(
    $conn,
    "SELECT * FROM role"
);

if(isset($_POST['update'])){

    mysqli_query($conn,"
    UPDATE user
    SET
        nama='$_POST[nama]',
        username='$_POST[username]',
        password='$_POST[password]',
        kontak='$_POST[kontak]',
        id_role='$_POST[id_role]'
    WHERE user_id=$id
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>

<h1>Edit User</h1>

<form method="POST">

<p>Nama</p>
<input
type="text"
name="nama"
value="<?= $data['nama'] ?>"
required
>

<p>Username</p>
<input
type="text"
name="username"
value="<?= $data['username'] ?>"
required
>

<p>Password</p>
<input
type="text"
name="password"
value="<?= $data['password'] ?>"
required
>

<p>Kontak</p>
<input
type="text"
name="kontak"
value="<?= $data['kontak'] ?>"
>

<p>Role</p>

<select name="id_role">

<?php while($r = mysqli_fetch_assoc($role)) : ?>

<option
value="<?= $r['id_role'] ?>"
<?= ($r['id_role'] == $data['id_role']) ? 'selected' : '' ?>
>
<?= $r['nama_role'] ?>
</option>

<?php endwhile; ?>

</select>

<br><br>

<button type="submit" name="update">
    Update
</button>

</form>

<br>

<a href="index.php">
    Kembali
</a>

</body>
</html>