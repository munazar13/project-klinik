<?php
<<<<<<< HEAD
$conn = mysqli_connect("localhost", "root", "", "db_klinik_kampus.sql");
=======
$conn = mysqli_connect("localhost", "root", "", "db_klinik_kampus");
>>>>>>> f9e3b5909b1dbd1396f6a3c4ea777301efc354cc

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>