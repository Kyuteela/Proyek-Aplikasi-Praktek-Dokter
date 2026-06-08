<?php

require_once '../../config/koneksi.php';

$query = mysqli_query($conn,"
SELECT
    u.*,
    r.nama_role
FROM user u
JOIN role r
    ON u.id_role = r.id_role
ORDER BY u.user_id ASC
");

?>

<h1>Data User</h1>

<a href="create.php">Tambah User</a>

<br><br>

<table border="1" cellpadding="5">

<tr>
    <th>ID</th>
    <th>Nama</th>
    <th>Username</th>
    <th>Kontak</th>
    <th>Role</th>
    <th>Aksi</th>
</tr>

<?php while($row=mysqli_fetch_assoc($query)) : ?>

<tr>

<td><?= $row['user_id'] ?></td>
<td><?= $row['nama'] ?></td>
<td><?= $row['username'] ?></td>
<td><?= $row['kontak'] ?></td>
<td><?= $row['nama_role'] ?></td>

<td>

<a href="edit.php?id=<?= $row['user_id'] ?>">
Edit
</a>

|

<a
href="delete.php?id=<?= $row['user_id'] ?>"
onclick="return confirm('Yakin hapus user?')"
>
Hapus
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

<br>

<a href="../../index.php">
Kembali Dashboard
</a>