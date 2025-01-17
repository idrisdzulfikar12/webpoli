<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Periksa Pasien</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=home">Home</a></li>
                    <li class="breadcrumb-item active">Daftar Periksa</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>Keluhan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no = 1;
                                    require 'config/koneksi.php';
                                    $query = "SELECT pasien.nama, daftar_poli.keluhan, daftar_poli.status_periksa, daftar_poli.id FROM daftar_poli INNER JOIN 
                                    pasien ON daftar_poli.id_pasien = pasien.id INNER JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id 
                                    INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id WHERE dokter.id = '$id_dokter'";
                                    $result = mysqli_query($mysqli,$query);

                                    while ($data = mysqli_fetch_assoc($result)) {
                                        # code...
                                ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $data['nama']; ?></td>
                                    <td><?php echo $data['keluhan']; ?></td>
                                    <td>
                                        <?php if ($data['status_periksa']==1) {
                                        ?>
                                        <button type='button' class='btn btn-sm btn-warning edit-btn'
                                            data-toggle="modal"
                                            data-target="#editModal_<?php echo $data['id'] ?>">Edit</button>
                                            <div class="modal fade" id="editModal_<?php echo $data['id'] ?>" tabindex="-1"
                                            role="dialog" aria-labelledby="editModalLabel_<?php echo $data['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addModalLabel">Edit Periksa Pasien</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Form tambah data obat disini -->

                                                        <?php
                                                            $idDaftarPoli = $data['id'];
                                                            require 'config/koneksi.php';
                                                            $ambilDataPeriksa = mysqli_query($mysqli,"SELECT * FROM periksa INNER JOIN daftar_poli ON periksa.id_daftar_poli = daftar_poli.id WHERE daftar_poli.id = '$idDaftarPoli'");
                                                            $ambilData = mysqli_fetch_assoc($ambilDataPeriksa);
                                                        ?>
                                                        <form action="pages/periksaPasien/editPeriksa.php"
                                                            method="post">
                                                            <input type="hidden" name="id"
                                                                value="<?php echo $data['id'] ?>">
                                                            <div class="form-group">
                                                                <label for="nama">Nama Pasien</label>
                                                                <input type="text" class="form-control" id="nama"
                                                                    name="nama" required
                                                                    value="<?php echo $data['nama'] ?>" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tanggal_periksa">Tanggal Periksa</label>
                                                                <input type="datetime-local" class="form-control"
                                                                    id="tanggal_periksa" name="tanggal_periksa"
                                                                    required value="<?php echo $ambilData['tgl_periksa'] ?>">
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="catatan">Catatan</label>
                                                                <textarea class="form-control" rows="3" id="catatan"
                                                                    name="catatan" required><?php echo $ambilData['catatan'] ?></textarea>
                                                            </div>
                                                            <!-- Bagian Input Obat pada Modal Edit -->
                                                            <div class="form-group mb-3">
                                                                <label>Obat</label>
                                                                <select class="select2" multiple="multiple" data-placeholder="Pilih Obat" style="width: 100%;" 
                                                                    name="obat[]" onchange="hitungTotalEdit('editModal_<?php echo $data['id']; ?>')">
                                                                    <?php 
                                                                    // Ambil data obat yang sudah dipilih
                                                                    $selectedObatIds = [];
                                                                    $ambilObatDipilih = "SELECT id_obat FROM detail_periksa WHERE id_periksa = (SELECT id FROM periksa WHERE id_daftar_poli = '{$data['id']}')";
                                                                    $resultObatDipilih = mysqli_query($mysqli, $ambilObatDipilih);
                                                                    while ($row = mysqli_fetch_assoc($resultObatDipilih)) {
                                                                        $selectedObatIds[] = $row['id_obat'];
                                                                    }

                                                                    // Tampilkan semua obat
                                                                    $getObat = "SELECT * FROM obat";
                                                                    $queryObat = mysqli_query($mysqli, $getObat);
                                                                    while ($obat = mysqli_fetch_assoc($queryObat)) {
                                                                        $isSelected = in_array($obat['id'], $selectedObatIds) ? 'selected' : '';
                                                                    ?>
                                                                    <option value="<?php echo $obat['id']; ?>" data-harga="<?php echo $obat['harga']; ?>" <?php echo $isSelected; ?>>
                                                                        <?php echo $obat['nama_obat'] . ' ' . $obat['kemasan'] . ' - Rp.' . number_format($obat['harga'], 0, ',', '.'); ?>
                                                                    </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                            <!-- Bagian Total Harga pada Modal Edit -->
                                                            <div class="form-group mb-3">
                                                                <label>Total Harga</label>
                                                                <input type="text" class="form-control" id="totalHargaDisplayEdit<?php echo $data['id']; ?>" 
                                                                    value="Rp.<?php echo number_format($ambilData['biaya_periksa'], 0, ',', '.'); ?>" disabled>
                                                                <input type="hidden" name="totalHarga" id="totalHargaEdit<?php echo $data['id']; ?>" 
                                                                    value="<?php echo $ambilData['biaya_periksa']; ?>">
                                                            </div>

                                                            <button type="submit" class="btn btn-success">Simpan</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php  } else { ?>
                                        <button type='button' class='btn btn-sm btn-info edit-btn' data-toggle="modal"
                                            data-target="#periksaModal<?php echo $data['id'] ?>">Periksa</button>
                                        <div class="modal fade" id="periksaModal<?php echo $data['id'] ?>" tabindex="-1"
                                            role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addModalLabel">Periksa Pasien</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Form tambah data obat disini -->
                                                        <form action="pages/periksaPasien/periksaPasien.php"
                                                            method="post">
                                                            <input type="hidden" name="id"
                                                                value="<?php echo $data['id'] ?>">
                                                            <div class="form-group">
                                                                <label for="nama">Nama Pasien</label>
                                                                <input type="text" class="form-control" id="nama"
                                                                    name="nama" required
                                                                    value="<?php echo $data['nama'] ?>" disabled>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tanggal_periksa">Tanggal Periksa</label>
                                                                <input type="datetime-local" class="form-control"
                                                                    id="tanggal_periksa" name="tanggal_periksa"
                                                                    required>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="catatan">Catatan</label>
                                                                <textarea class="form-control" rows="3" id="catatan"
                                                                    name="catatan" required></textarea>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label>Obat</label>
                                                                <select class="select2" multiple="multiple" data-placeholder="Pilih Obat" style="width: 100%;" name="obat[]" onchange="hitungTotal(this)">
                                                                    <?php 
                                                                        require 'config/koneksi.php';
                                                                        $getObat = "SELECT * FROM obat";
                                                                        $queryObat = mysqli_query($mysqli,$getObat);
                                                                        while ($datas = mysqli_fetch_assoc($queryObat)) {
                                                                            $infoObat = "{$datas['nama_obat']} - {$datas['qty']} {$datas['kemasan']} - Rp.{$datas['harga']}";
                                                                    ?>
                                                                    <option value="<?php echo $datas['id'] ?>" data-harga="<?php echo $datas['harga'] ?>">
                                                                        <?php echo $infoObat ?>
                                                                    </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label>Total Harga</label>
                                                                <input type="text" class="form-control" id="totalHargaDisplay" value="Rp.150000" disabled>
                                                                <input type="hidden" name="totalHarga" id="totalHarga" value="150000">
                                                            </div>
                                                            <button type="submit" class="btn btn-info">Periksa</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<script>
    function hitungTotal(selectElement) {
    // Cari elemen modal terdekat dari elemen select yang berubah
    var modal = selectElement.closest('.modal');

    // Ambil semua option yang dipilih dari select dalam modal tersebut
    var selectedObat = modal.querySelectorAll('select[name="obat[]"] option:checked');

    var totalHarga = 150000; // Harga awal
    selectedObat.forEach(function(option) {
        // Tambahkan harga obat dari atribut data-harga
        totalHarga += parseInt(option.getAttribute('data-harga')) || 0;
    });

    // Format harga ke Rupiah dan perbarui tampilan
    document.getElementById('totalHargaDisplay').value = 'Rp.' + totalHarga.toLocaleString();
    document.getElementById('totalHarga').value = totalHarga; 
    }

    function hitungTotalEdit(modalId) {
    // Ambil modal berdasarkan ID
    var modal = document.getElementById(modalId);

    // Validasi apakah modal ditemukan
    if (!modal) {
        console.error('Modal tidak ditemukan:', modalId);
        return;
    }

    // Ambil semua option yang dipilih dari select dalam modal tersebut
    var selectedObat = modal.querySelectorAll('select[name="obat[]"] option:checked');

    var totalHarga = 150000; // Reset total harga

    // Hitung total harga dari obat yang dipilih
    selectedObat.forEach(function(option) {
        totalHarga += parseInt(option.getAttribute('data-harga')) || 0;
    });

    // Format harga ke Rupiah dan perbarui tampilan
    var totalHargaFormatted = 'Rp.' + totalHarga.toLocaleString();

    // Update elemen input dan tampilan total harga
    modal.querySelector(`[id^="totalHargaDisplayEdit"]`).value = totalHargaFormatted;
    modal.querySelector(`[id^="totalHargaEdit"]`).value = totalHarga;
}

</script>