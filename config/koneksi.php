<?php

$conn = mysqli_connect(
    "db",
    "root",
    "root123",
    "praktik_dokter"
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
