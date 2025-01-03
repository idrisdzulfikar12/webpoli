<?php
require 'koneksi.php';
session_start();

// Validasi akses admin


// Proses input pembayaran
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idPasien = $_POST['id_pasien'];
    $totalTagihan = $_POST['total_tagihan'];
    $jumlahBayar = $_POST['jumlah_bayar'];
    $status = ($jumlahBayar >= $totalTagihan) ? 'Lunas' : 'Belum Lunas';

    $query = "INSERT INTO pembayaran (id_pasien, total_tagihan, jumlah_bayar, status, tanggal) VALUES (?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($mysqli, $query);
    mysqli_stmt_bind_param($stmt, 'iiis', $idPasien, $totalTagihan, $jumlahBayar, $status);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo '<script>alert("Pembayaran berhasil ditambahkan!");</script>';
    } else {
        echo '<script>alert("Gagal menambahkan pembayaran!");</script>';
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
</head>
<body>
    <h2>Form Pembayaran</h2>
    <form method="POST">
        <label for="id_pasien">ID Pasien:</label>
        <input type="number" name="id_pasien" id="id_pasien" required><br>
        <label for="total_tagihan">Total Tagihan:</label>
        <input type="number" name="total_tagihan" id="total_tagihan" required><br>
        <label for="jumlah_bayar">Jumlah Bayar:</label>
        <input type="number" name="jumlah_bayar" id="jumlah_bayar" required><br>
        <button type="submit">Bayar</button>
    </form>
</body>
</html>
