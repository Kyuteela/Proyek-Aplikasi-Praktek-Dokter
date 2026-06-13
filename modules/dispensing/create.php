<?php

require_once '../../config/koneksi.php';

$detail_id_param = isset($_GET['detail_id']) ? $_GET['detail_id'] : '';

$detail_resep = mysqli_query($conn,"
SELECT
    dr.detail_id,
    dr.resep_id,
    dr.obat_id,
    o.nama_obat,
    dr.dosis,
    dr.jumlah,
    dr.frekuensi,
    p.nama AS pasien,
    doc.nama AS dokter,
    r.tanggal_resep,
    r.status_resep
FROM detail_resep dr
JOIN obat o
    ON dr.obat_id = o.obat_id
JOIN resep r
    ON dr.resep_id = r.resep_id
JOIN rekam_medis rm
    ON r.record_id = rm.record_id
JOIN kunjungan k
    ON rm.visit_id = k.visit_id
JOIN pasien p
    ON k.patient_id = p.patient_id
JOIN dokter doc
    ON r.doctor_id = doc.doctor_id
WHERE dr.detail_id NOT IN (
    SELECT detail_id FROM dispensing
)
ORDER BY dr.detail_id ASC
");

$petugas = mysqli_query($conn,"
SELECT user_id, nama
FROM user
ORDER BY nama ASC
");

if(isset($_POST['simpan'])){

    mysqli_query($conn,"
    INSERT INTO dispensing
    (
        detail_id,
        obat_id,
        edukasi_pasien,
        serah_terima,
        petugas_id
    )
    VALUES
    (
        '$_POST[detail_id]',
        '$_POST[obat_id]',
        '$_POST[edukasi_pasien]',
        '$_POST[serah_terima]',
        '$_POST[petugas_id]'
    )
    ");

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Dispensing</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h1 class="mb-4">Tambah Dispensing</h1>

    <form method="POST" class="col-md-8">

        <div class="mb-3">
            <label class="form-label">Detail Resep</label>
            <select name="detail_id" id="detail_id" class="form-select" required>

                <option value="">-- Pilih Detail Resep --</option>

                <?php while($dr = mysqli_fetch_assoc($detail_resep)) : ?>

                <option
                    value="<?= $dr['detail_id'] ?>"
                    data-obat-id="<?= $dr['obat_id'] ?>"
                    data-info="Resep #<?= $dr['resep_id'] ?> | <?= $dr['pasien'] ?> | <?= $dr['dokter'] ?> | <?= $dr['nama_obat'] ?> (<?= $dr['jumlah'] ?>)"
                    <?= ($detail_id_param == $dr['detail_id']) ? 'selected' : '' ?>
                >
                    Detail #<?= $dr['detail_id'] ?> -
                    Resep #<?= $dr['resep_id'] ?> -
                    <?= $dr['pasien'] ?> -
                    <?= $dr['nama_obat'] ?>
                </option>

                <?php endwhile; ?>

            </select>
        </div>

        <div class="alert alert-info" id="resep-info">
            Pilih detail resep untuk melihat informasi resep terkait.
        </div>

        <input type="hidden" name="obat_id" id="obat_id" value="">

        <div class="mb-3">
            <label class="form-label">Edukasi Pasien</label>
            <textarea name="edukasi_pasien" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Serah Terima</label>
            <input type="text" name="serah_terima" class="form-control" placeholder="Contoh: Sudah diterima pasien">
        </div>

        <div class="mb-3">
            <label class="form-label">Petugas</label>
            <select name="petugas_id" class="form-select">
                <option value="">-- Pilih Petugas --</option>

                <?php while($p = mysqli_fetch_assoc($petugas)) : ?>

                <option value="<?= $p['user_id'] ?>">
                    <?= $p['nama'] ?>
                </option>

                <?php endwhile; ?>

            </select>
        </div>

        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>

    </form>

</div>

<script>
const detailSelect = document.getElementById('detail_id');
const obatInput = document.getElementById('obat_id');
const resepInfo = document.getElementById('resep-info');

function updateDetailInfo() {
    const option = detailSelect.options[detailSelect.selectedIndex];

    if (option.value) {
        obatInput.value = option.dataset.obatId;
        resepInfo.textContent = option.dataset.info;
    } else {
        obatInput.value = '';
        resepInfo.textContent = 'Pilih detail resep untuk melihat informasi resep terkait.';
    }
}

detailSelect.addEventListener('change', updateDetailInfo);
updateDetailInfo();
</script>

</body>
</html>
