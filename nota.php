<?php
// Koneksi ke Database
require 'koneksi.php';
session_start();


// Periksa Koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil Data Nota
$id_nota = 1; // Ganti sesuai ID nota yang ingin ditampilkan
$sql_nota = "SELECT n.tanggal, n.biaya_poli, p.nama 
             FROM nota n 
             JOIN pasien p ON n.id_pasien = p.id 
             WHERE n.id = $id_nota";
$result_nota = $conn->query($sql_nota);
$nota = $result_nota->fetch_assoc();

// Ambil Data Obat
$sql_obat = "SELECT nama_obat, jumlah, harga_satuan FROM obat WHERE id_nota = $id_nota";
$result_obat = $conn->query($sql_obat);

// Hitung Total
$total = $nota['biaya_poli'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran Poliklinik</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .nota { max-width: 700px; margin: 0 auto; border: 1px solid #ccc; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="nota">
        <h2>Nota Pembayaran Poliklinik</h2>
        <p><strong>Nama Pasien:</strong> <?= $nota["nama"]; ?></p>
        <p><strong>Tanggal:</strong> <?= $nota["tanggal"]; ?></p>

        <h3>Biaya Poliklinik</h3>
        <p>Rp<?= number_format($nota["biaya_poli"], 0, ',', '.'); ?></p>

        <h3>Obat</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($obat = $result_obat->fetch_assoc()): ?>
                    <tr>
                        <td><?= $obat["nama_obat"]; ?></td>
                        <td><?= $obat["jumlah"]; ?></td>
                        <td>Rp<?= number_format($obat["harga_satuan"], 0, ',', '.'); ?></td>
                        <td>Rp<?= number_format($obat["jumlah"] * $obat["harga_satuan"], 0, ',', '.'); ?></td>
                    </tr>
                    <?php $total += $obat["jumlah"] * $obat["harga_satuan"]; ?>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Total Pembayaran</h3>
        <p class="total">Rp<?= number_format($total, 0, ',', '.'); ?></p>
    </div>
</body>
</html>

<?php
// Tutup Koneksi
$conn->close();
?>
