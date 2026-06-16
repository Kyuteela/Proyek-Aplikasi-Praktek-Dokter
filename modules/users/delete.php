<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_GET['id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Mencegah tindakan bunuh diri sistem (menghapus diri sendiri yang sedang login)
    if ($user_id == $_SESSION['user_id']) {
        header("Location: index.php?status=error&msg=" . urlencode("Aturan Proteksi: Anda dilarang menghapus akun sendiri saat sedang aktif masuk sistem!"));
        exit;
    }

    try {
        // Melakukan pengeksekusian eliminasi akun
        $query_delete = "DELETE FROM user WHERE user_id = '$user_id'";
        if (mysqli_query($conn, $query_delete)) {
            header("Location: index.php?status=success&msg=" . urlencode("Akun pengguna sukses dieliminasi permanen!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap kegagalan jika user terikat sebagai penanggung jawab penyerahan dispensing obat farmasi
        header("Location: index.php?status=error&msg=" . urlencode("Gagal menghapus: Staf ini memiliki record riwayat pekerjaan pelayanan medis/farmasi aktif!"));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
