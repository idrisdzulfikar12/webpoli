<?php
require '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_daftar_poli = $_POST['id'];
    $tanggal_periksa = $_POST['tanggal_periksa'];
    $catatan = $_POST['catatan'];
    $obat = $_POST['obat']; // Array obat
    $totalHarga = $_POST['totalHarga']; // Ambil total harga dari form

    // Update data di tabel periksa
    $queryPeriksa = "UPDATE periksa 
                     SET tgl_periksa = '$tanggal_periksa', catatan = '$catatan', biaya_periksa = '$totalHarga' 
                     WHERE id_daftar_poli = '$id_daftar_poli'";
    $result = mysqli_query($mysqli, $queryPeriksa);

    if ($result) {
        // Hapus data lama di detail_periksa
        $deleteObat = "DELETE FROM detail_periksa WHERE id_periksa = (SELECT id FROM periksa WHERE id_daftar_poli = '$id_daftar_poli')";
        mysqli_query($mysqli, $deleteObat);

        // Simpan data obat baru ke tabel detail_periksa
        if (!empty($obat)) {
            foreach ($obat as $id_obat) {
                $queryObat = "INSERT INTO detail_periksa (id_periksa, id_obat) 
                              VALUES ((SELECT id FROM periksa WHERE id_daftar_poli = '$id_daftar_poli'), '$id_obat')";
                mysqli_query($mysqli, $queryObat);
            }
        }

        if ($result) {
            echo '<script>alert("Data berhasil diubah");window.location.href="../../periksaPasien.php"</script>';
        } else {
            echo '<script>alert("Data gagal diubah");window.location.href="../../periksaPasien.php"</script>';
        }
    } 
}