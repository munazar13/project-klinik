<?php
$conn = mysqli_connect("localhost", "root", "", "db_klinik_kampus.sql");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>