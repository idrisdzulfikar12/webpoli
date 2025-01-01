<?php

// Mendapatkan id dokter dari sesi
$id_dokter = $_SESSION['id'];

// Koneksi ke database
require 'config/koneksi.php';

// Query untuk mendapatkan data dokter
$query_dokter = "SELECT dokter.*, poli.nama_poli FROM dokter 
                 LEFT JOIN poli ON dokter.id_poli = poli.id 
                 WHERE dokter.id = ?";
$stmt = $mysqli->prepare($query_dokter);
$stmt->bind_param("i", $id_dokter);
$stmt->execute();
$result = $stmt->get_result();
$dokter = $result->fetch_assoc();

// Query untuk mendapatkan semua poli untuk dropdown
$query_poli = "SELECT * FROM poli";
$result_poli = $mysqli->query($query_poli);
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 fw-bold">Profile Dokter</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-lg-12">
                <div class="card w-100" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <div class="card-header" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); background-color: #0284c7;">
                        <h3 class="card-title fw-bold text-white">Edit Profile Dokter</h3>
                    </div>
                    <!-- /.card-header -->

                    <form method="POST" action="pages/profileDokter/updateProfile.php">
                        <input type="hidden" class="form-control" id="id" name="id"
                        value="<?php echo $dokter['id'] ?>" required>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($dokter['nama']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo htmlspecialchars($dokter['alamat']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="no_hp">No HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($dokter['no_hp']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="poli">Poli</label>
                                <select class="form-control" id="poli" name="id_poli" disabled>
                                    <?php while ($row = $result_poli->fetch_assoc()) { ?>
                                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $dokter['id_poli']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($row['nama_poli']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru jika ingin mengganti">
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-center">
                            <button type="submit" class="btn text-white" style="background-color: #0284c7;">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->