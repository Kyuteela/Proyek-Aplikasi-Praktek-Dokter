<?php
session_start();

// Jika sudah login, langsung lempar ke dashboard utama
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'config/koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query disesuaikan dengan struktur kolom tabel user
    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Pengecekan password (plain-text)
        if ($password === $row['password']) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['id_role'] = $row['id_role'];

            header("Location: index.php");
            exit;
        } else {
            $error = 'Password yang Anda masukkan salah!';
        }
    } else {
        $error = 'Username tidak terdaftar di sistem!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Praktik Dokter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body class="bg-slate-100 flex items-center justify-center min-h-screen antialiased font-sans">

    <div class="w-full max-w-md p-4">
        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl text-blue-600 mb-4 shadow-inner">
                    <i class="bi bi-heart-pulse-fill text-3xl text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-800">Klinik Mandiri</h2>
                <p class="text-gray-400 text-sm mt-1">Sistem Informasi Praktik Dokter Mandiri</p>
            </div>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'logout') : ?>
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl mb-6 flex items-center space-x-3 text-sm">
                    <i class="bi bi-check-circle-fill text-lg text-emerald-500"></i>
                    <span>Anda telah berhasil keluar dari sistem dengan aman.</span>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)) : ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 flex items-center space-x-3 text-sm animate-pulse">
                    <i class="bi bi-exclamation-triangle-fill text-lg"></i>
                    <span><?= $error; ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-5">
                <div>
                    <label for="username" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" name="username" id="username" required autocomplete="off"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm"
                            placeholder="Masukkan username Anda">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password" id="password" required
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-150 flex items-center justify-center space-x-2 text-sm">
                        <span>Masuk ke Sistem</span>
                        <i class="bi bi-arrow-right-short text-lg"></i>
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <span class="text-xs text-gray-400">Gunakan akun testing yang terdaftar pada skrip data master untuk mencoba login.</span>
            </div>

        </div>
    </div>

</body>

</html>