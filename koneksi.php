<?php
$localhost = "localhost";
$user = "root";       
$password = "";           
$db = "db_klinik";  

$koneksi = mysqli_connect($host, $user, $pass, $db);

if mysqli_connect(!$koneksi) {
    die("Gagal terhubung ke database: " . mysqli_connect_error());
}
?>