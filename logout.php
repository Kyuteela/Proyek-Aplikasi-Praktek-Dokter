<?php
session_start();

// 1. Bersihkan semua variabel session di memori internal PHP
$_SESSION = array();

// 2. Hapus cookie session yang tertinggal di browser user (Best Practice Keamanan)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Hancurkan data session yang tersimpan di sisi server
session_destroy();

// 4. Redirect ke login dengan melempar parameter status untuk Flash Message Tailwind
header("Location: login.php?status=logout");
exit;
