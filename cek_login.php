<?php

session_start();
include "koneksi.php";

$username = trim($_POST['username']);
$password = trim($_POST['password']);

$query = mysqli_query(
    $conn,
    "SELECT * FROM users
     WHERE username='$username'
     AND password='$password'"
);

if(mysqli_num_rows($query) == 1){

    $user = mysqli_fetch_assoc($query);

    $_SESSION['login']    = true;
    $_SESSION['id_user']  = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama']     = $user['nama_lengkap'];
    $_SESSION['role']     = $user['role'];

    header("Location: dashboard.php");
    exit;
}

$_SESSION['error'] = "Username atau Password salah!";
header("Location: index.php");
exit;