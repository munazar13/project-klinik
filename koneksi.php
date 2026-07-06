<?php


$conn = mysqli_connect("localhost", "root", "", "db_klinik_kampus");


if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>