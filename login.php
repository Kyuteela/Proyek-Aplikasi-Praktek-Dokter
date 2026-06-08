<?php

session_start();
require_once 'config/koneksi.php';

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM user
         WHERE username='$username'
         AND password='$password'"
    );

    if(mysqli_num_rows($query) > 0){

        $user = mysqli_fetch_assoc($query);

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['id_role'] = $user['id_role'];

        header("Location:index.php");
        exit;

    } else {

        echo "<p>Username atau Password salah!</p>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Klinik</title>
</head>
<body>

<h1>Login Sistem Klinik</h1>

<form method="POST">

<p>Username</p>
<input type="text" name="username" required>

<p>Password</p>
<input type="password" name="password" required>

<br><br>

<button type="submit" name="login">
    Login
</button>

</form>

</body>
</html>