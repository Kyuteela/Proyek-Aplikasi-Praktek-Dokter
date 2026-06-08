<?php

require_once '../../config/koneksi.php';

$kunjungan = mysqli_query($conn,"
SELECT
    k.visit_id,
    p.nama AS pasien,
    d.nama AS dokter
FROM kunjungan k
JOIN pasien p
    ON k.patient_id = p.patient_id
JOIN dokter d
    ON k.doctor_id = d.doctor_id
WHERE k.visit_id NOT IN (
    SELECT visit_id
    FROM rekam_medis
)
");

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
    INSERT INTO rekam_medis
    (
        visit_id,
        anamnesa,
        pemeriksaan_fisik,
        catatan_klinis,
        riwayat_penyakit,
        alergi_obat_makanan,
        tanggal_catatan,
        vital_summary,
        tinggi_badan,
        berat_badan
    )
    VALUES
    (
        '$_POST[visit_id]',
        '$_POST[anamnesa]',
        '$_POST[pemeriksaan_fisik]',
        '$_POST[catatan_klinis]',
        '$_POST[riwayat_penyakit]',
        '$_POST[alergi_obat_makanan]',
        '$_POST[tanggal_catatan]',
        '$_POST[vital_summary]',
        '$_POST[tinggi_badan]',
        '$_POST[berat_badan]'
    )
    ");

    header("Location:index.php");
    exit;
}

?>

<h1>Tambah Rekam Medis</h1>

<form method="POST">

<p>Kunjungan</p>

<select name="visit_id" required>

<?php while($k = mysqli_fetch_assoc($kunjungan)) : ?>

<option value="<?= $k['visit_id'] ?>">
    Visit <?= $k['visit_id'] ?>
    -
    <?= $k['pasien'] ?>
    -
    <?= $k['dokter'] ?>
</option>

<?php endwhile; ?>

</select>

<p>Anamnesa</p>
<textarea name="anamnesa"></textarea>

<p>Pemeriksaan Fisik</p>
<textarea name="pemeriksaan_fisik"></textarea>

<p>Catatan Klinis</p>
<textarea name="catatan_klinis"></textarea>

<p>Riwayat Penyakit</p>
<textarea name="riwayat_penyakit"></textarea>

<p>Alergi Obat/Makanan</p>
<textarea name="alergi_obat_makanan"></textarea>

<p>Tanggal Catatan</p>
<input type="date" name="tanggal_catatan">

<p>Vital Summary</p>
<textarea name="vital_summary"></textarea>

<p>Tinggi Badan (cm)</p>
<input type="number" step="0.01" name="tinggi_badan">

<p>Berat Badan (kg)</p>
<input type="number" step="0.01" name="berat_badan">

<br><br>

<button type="submit" name="simpan">
    Simpan
</button>

</form>

<br>

<a href="index.php">Kembali</a>