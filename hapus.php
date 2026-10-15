<?php
require_once 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query_hapus = "DELETE FROM tb_pasien WHERE id_pasien = '$id'";

    if (mysqli_query($koneksi, $query_hapus)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    header("Location: index.php");
    exit;
}
?>