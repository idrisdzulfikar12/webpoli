<?php
include("../../config/koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai dari form
    $id = $_POST["id"];
    $nama = $_POST["nama"];
    $alamat = $_POST["alamat"];
    $no_hp = $_POST["no_hp"];
    if($_POST["password"] == '')
    {
        // Query untuk melakukan update data obat
            $query = "UPDATE dokter SET 
            nama = '$nama', 
            alamat = '$alamat',
            no_hp = '$no_hp'
            WHERE id = '$id'";

        // Eksekusi query
        if (mysqli_query($mysqli, $query)) {
            // Jika berhasil, redirect kembali ke halaman index atau sesuaikan dengan kebutuhan Anda
            echo '<script>';
            echo 'alert("Profile dokter berhasil diubah!");';
            echo 'window.location.href = "../../profileDokter.php";';
            echo '</script>';
            exit();
        } else {
            // Jika terjadi kesalahan, tampilkan pesan error
            echo "Error: " . $query . "<br>" . mysqli_error($mysqli);
        }
    }else{
        $password = md5($_POST["password"]);
        $query = "UPDATE dokter SET 
            nama = '$nama', 
            password = '$password',
            alamat = '$alamat',
            no_hp = '$no_hp'
            WHERE id = '$id'";

        if (mysqli_query($mysqli, $query)) {
            // Jika berhasil, redirect kembali ke halaman index atau sesuaikan dengan kebutuhan Anda
            echo '<script>';
            echo 'alert("Profile dokter berhasil diubah!");';
            echo 'window.location.href = "../../profileDokter.php";';
            echo '</script>';
            exit();
        } else {
            // Jika terjadi kesalahan, tampilkan pesan error
            echo "Error: " . $query . "<br>" . mysqli_error($mysqli);
        }
    }
    

    
}

// Tutup koneksi
mysqli_close($mysqli);
?>