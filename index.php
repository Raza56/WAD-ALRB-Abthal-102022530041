<?php
require_once 'koneksi.php';

$pesan_sukses = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_rm       = $_POST['no_rm'];
    $input_nama     = $_POST['nama'];
    $input_domisili = $_POST['domisili'];
    $input_kontak   = $_POST['kontak'];
    
    if (!empty($_POST['id_pasien'])) {
        $id_edit = $_POST['id_pasien'];
        $query_update = "UPDATE tb_pasien SET no_rm='$input_rm', nama='$input_nama', domisili='$input_domisili', kontak='$input_kontak' WHERE id_pasien='$id_edit'";
        
        if (mysqli_query($koneksi, $query_update)) {
            $pesan_sukses = "Data pasien <b>$input_nama</b> berhasil diperbarui.";
        }
    } else {
        $query_insert = "INSERT INTO tb_pasien (no_rm, nama, domisili, kontak) VALUES ('$input_rm', '$input_nama', '$input_domisili', '$input_kontak')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            $pesan_sukses = "Data pasien baru <b>$input_nama</b> berhasil disimpan.";
        }
    }
}

if (isset($_GET['status']) && $_GET['status'] == 'hapus_sukses') {
    $pesan_sukses = "Data pasien berhasil dihapus dari sistem.";
}

$edit_id       = "";
$edit_rm       = "";
$edit_nama     = "";
$edit_domisili = "";
$edit_kontak   = "";

if (isset($_GET['edit'])) {
    $id_cari = $_GET['edit'];
    $query_edit = mysqli_query($koneksi, "SELECT * FROM tb_pasien WHERE id_pasien = '$id_cari'");
    $data_edit = mysqli_fetch_assoc($query_edit);
    
    if ($data_edit) {
        $edit_id       = $data_edit['id_pasien'];
        $edit_rm       = $data_edit['no_rm'];
        $edit_nama     = $data_edit['nama'];
        $edit_domisili = $data_edit['domisili'];
        $edit_kontak   = $data_edit['kontak'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    
    <div class="page-header">
        <div>
            <h1>Data Pasien</h1>
            <p>Kelola data rekam medis pasien</p>
        </div>
        <a href="index.php#form-pasien" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Pasien</a>
    </div>

    <?php if($pesan_sukses != ""): ?>
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i> <?php echo $pesan_sukses; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>Domisili</th>
                        <th>Kontak Darurat</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_tampil = mysqli_query($koneksi, "SELECT * FROM tb_pasien ORDER BY no_rm ASC");
                    
                    if (mysqli_num_rows($query_tampil) > 0) {
                        while ($pasien = mysqli_fetch_assoc($query_tampil)) {
                    ?>
                            <tr>
                                <td><span class="badge-rm"><?php echo $pasien['no_rm']; ?></span></td>
                                <td class="fw-bold text-dark">
                                    <div class="pasien-info">
                                        <div class="avatar"><i class="fa-solid fa-user"></i></div>
                                        <?php echo $pasien['nama']; ?>
                                    </div>
                                </td>
                                <td><i class="fa-solid fa-location-dot text-muted"></i> <?php echo $pasien['domisili']; ?></td>
                                <td><i class="fa-solid fa-phone text-muted"></i> <?php echo $pasien['kontak']; ?></td>
                                <td style="text-align: center;">
                                    <a href="index.php?edit=<?php echo $pasien['id_pasien']; ?>#form-pasien" class="btn-icon btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                    <a href="hapus.php?id=<?php echo $pasien['id_pasien']; ?>" class="btn-icon btn-delete" title="Hapus" onclick="return confirm('Yakin hapus pasien <?php echo $pasien['nama']; ?>?');"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                    <?php
                        } 
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; padding: 30px; color:#888;'>Belum ada data pasien.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="form-pasien" class="modal">
    <div class="modal-box">
        <div class="modal-header">
            <h2><?php echo ($edit_id != "") ? "<i class='fa-solid fa-pen-to-square'></i> Edit Data Pasien" : "<i class='fa-solid fa-user-plus'></i> Tambah Pasien Baru"; ?></h2>
            <a href="index.php" class="close-btn"><i class="fa-solid fa-xmark"></i></a>
        </div>
        
        <div class="modal-body">
            <form action="index.php" method="POST">
                <input type="hidden" name="id_pasien" value="<?php echo $edit_id; ?>">

                <div class="form-group">
                    <label>No. Rekam Medis</label>
                    <input type="text" name="no_rm" value="<?php echo $edit_rm; ?>" placeholder="Cth: RM-001" required>
                </div>

                <div class="form-group">
                    <label>Nama Pasien</label>
                    <input type="text" name="nama" value="<?php echo $edit_nama; ?>" placeholder="Nama lengkap pasien" required>
                </div>

                <div class="form-group">
                    <label>Domisili</label>
                    <input type="text" name="domisili" value="<?php echo $edit_domisili; ?>" placeholder="Kota / Alamat" required>
                </div>

                <div class="form-group">
                    <label>Kontak Darurat</label>
                    <input type="text" name="kontak" value="<?php echo $edit_kontak; ?>" placeholder="Nomor Telepon/HP" required>
                </div>

                <div class="form-actions">
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>