<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$tagihan_id = mysqli_real_escape_string($conn, $_GET['id']);
$invoice = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tagihan WHERE tagihan_id = '$tagihan_id'"));
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diskon = mysqli_real_escape_string($conn, $_POST['diskon']);
    $metode_pembayaran = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);
    $asuransi_id = mysqli_real_escape_string($conn, $_POST['asuransi_id']);
    $status_bayar = mysqli_real_escape_string($conn, $_POST['status']);

    try {
        // Eksekusi update data tagihan keuangan
        $update = "UPDATE tagihan SET diskon = '$diskon', metode_pembayaran = '$metode_pembayaran', 
                          asuransi_id = '$asuransi_id', status = '$status_bayar' WHERE tagihan_id = '$tagihan_id'";
        if (mysqli_query($conn, $update)) {
            header("Location: index.php?status=success&msg=" . urlencode("Status pelunasan invoice berhasil diperbarui!"));
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap sinyal kegagalan dari trg_validasi_tagihan di database
        $error = "Aturan Bisnis Gagal: " . $e->getMessage();
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="max-w-md mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800"><i class="bi bi-pencil-square text-blue-500 mr-2"></i> Proses Pelunasan Invoice</h2>
        <a href="index.php" class="text-xs text-gray-500">Batal</a>
    </div>

    <?php if (!empty($error)) : ?>
        <div class="m-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl text-sm flex items-center space-x-2"><i class="bi bi-exclamation-triangle-fill"></i><span><?= $error; ?></span></div>
    <?php endif; ?>

    <form action="" method="POST" class="p-6 space-y-4">
        <div class="p-4 bg-slate-50 border rounded-xl mb-4 text-sm space-y-1">
            <div class="flex justify-between"><span>Bruto Tagihan:</span><span class="font-bold">Rp <?= number_format($invoice['total_tagihan'], 0, ',', '.'); ?></span></div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nominal Diskon (Rp) <span class="text-rose-500">*</span></label>
            <input type="number" name="diskon" required value="<?= intval($invoice['diskon']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                <option value="Tunai" <?= $invoice['metode_pembayaran'] === 'Tunai' ? 'selected' : '' ?>>Tunai / Cash</option>
                <option value="Debit Card" <?= $invoice['metode_pembayaran'] === 'Debit Card' ? 'selected' : '' ?>>Debit Card</option>
                <option value="QRIS" <?= $invoice['metode_pembayaran'] === 'QRIS' ? 'selected' : '' ?>>QRIS / Electronic</option>
                <option value="Asuransi" <?= $invoice['metode_pembayaran'] === 'Asuransi' ? 'selected' : '' ?>>Klaim Jaminan Asuransi</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">No. Jaminan Asuransi</label>
            <input type="text" name="asuransi_id" value="<?= htmlspecialchars($invoice['asuransi_id']) ?>" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Pelunasan</label>
            <select name="status" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none text-sm">
                <option value="Belum Lunas" <?= $invoice['status'] === 'Belum Lunas' ? 'selected' : '' ?>>Belum Lunas</option>
                <option value="Lunas" <?= $invoice['status'] === 'Lunas' ? 'selected' : '' ?>>Lunas</option>
            </select>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="w-full px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow">Konfirmasi Pembayaran</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>