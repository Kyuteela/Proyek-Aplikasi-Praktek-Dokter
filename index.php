<?php

session_start();

if(!isset($_SESSION['user_id'])){

    header("Location:login.php");
    exit;
}

require_once 'config/koneksi.php';

$total_pasien = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM pasien")
)['total'];

$total_dokter = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM dokter")
)['total'];

$total_kunjungan = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM kunjungan")
)['total'];

$total_obat = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM obat")
)['total'];

?>

<!DOCTYPE html>
<html>
<head>

    <title>Sistem Informasi Praktik Dokter</title>

    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

</head>

<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center">

        <div>

            <h1>Sistem Informasi Praktik Dokter</h1>

            <p>
                Login sebagai :
                <strong><?= $_SESSION['nama']; ?></strong>
            </p>

            <p>
                Role ID :
                <strong><?= $_SESSION['id_role']; ?></strong>
            </p>

        </div>

        <div>

            <a
            href="logout.php"
            class="btn btn-danger">
                Logout
            </a>

        </div>

    </div>

    <hr>

    <h2>Dashboard</h2>

    <div class="row">

        <div class="col-md-3">

            <div class="card text-center mb-3">

                <div class="card-body">

                    <h3><?= $total_pasien ?></h3>

                    <p>Total Pasien</p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card text-center mb-3">

                <div class="card-body">

                    <h3><?= $total_dokter ?></h3>

                    <p>Total Dokter</p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card text-center mb-3">

                <div class="card-body">

                    <h3><?= $total_kunjungan ?></h3>

                    <p>Total Kunjungan</p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card text-center mb-3">

                <div class="card-body">

                    <h3><?= $total_obat ?></h3>

                    <p>Total Obat</p>

                </div>

            </div>

        </div>

    </div>

    <hr>

    <h2>Menu</h2>

    <ul>

    <?php if($_SESSION['id_role'] == 1) : ?>

        <li><a href="modules/patients/index.php">Kelola Pasien</a></li>
        <li><a href="modules/doctors/index.php">Kelola Dokter</a></li>
        <li><a href="modules/medicines/index.php">Kelola Obat</a></li>
        <li><a href="modules/visits/index.php">Kelola Kunjungan</a></li>
        <li><a href="modules/medical-records/index.php">Kelola Rekam Medis</a></li>
        <li><a href="modules/prescriptions/index.php">Kelola Resep</a></li>
        <li><a href="modules/billing/index.php">Kelola Tagihan</a></li>
        <li><a href="modules/users/index.php">Kelola User</a></li>
        <li><a href="modules/reports/index.php">Reports</a></li>

    <?php elseif($_SESSION['id_role'] == 2) : ?>

        <li><a href="modules/visits/index.php">Kelola Kunjungan</a></li>
        <li><a href="modules/medical-records/index.php">Kelola Rekam Medis</a></li>
        <li><a href="modules/prescriptions/index.php">Kelola Resep</a></li>
        <li><a href="modules/reports/index.php">Reports</a></li>

    <?php elseif($_SESSION['id_role'] == 3) : ?>

        <li><a href="modules/medicines/index.php">Kelola Obat</a></li>
        <li><a href="modules/prescriptions/index.php">Kelola Resep</a></li>

    <?php elseif($_SESSION['id_role'] == 4) : ?>

        <li><a href="modules/billing/index.php">Kelola Tagihan</a></li>
        <li><a href="modules/reports/index.php">Reports</a></li>

    <?php endif; ?>

    </ul>

</div>

</body>
</html>