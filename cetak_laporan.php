<?php
include "koneksi.php";
include "session.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

$query = mysqli_query($conn,"
SELECT
    k.tanggal_kunjungan,

    COUNT(*) AS jumlah_kunjungan,

    SUM(CASE WHEN p.status_pasien='Mahasiswa' THEN 1 ELSE 0 END) AS mahasiswa,

    SUM(CASE WHEN p.status_pasien='Dosen' THEN 1 ELSE 0 END) AS dosen,

    SUM(CASE WHEN p.status_pasien='Pegawai' THEN 1 ELSE 0 END) AS pegawai,

    SUM(CASE WHEN p.status_pasien='Umum' THEN 1 ELSE 0 END) AS umum,

    SUM(CASE WHEN k.status_kunjungan='Selesai' THEN 1 ELSE 0 END) AS selesai

FROM kunjungan k

JOIN pasien p
ON k.id_pasien = p.id_pasien

GROUP BY k.tanggal_kunjungan

ORDER BY k.tanggal_kunjungan DESC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<title> </title>

<style>

body{
    font-family:Arial,sans-serif;
    margin:40px;
}

h2,h3,p{
    text-align:center;
    margin:4px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:25px;
}

table th,
table td{

    border:1px solid black;
    padding:8px;
    text-align:center;

}

table th{
    background:#f2f2f2;
}

.ttd{

    width:300px;
    float:right;
    text-align:center;
    margin-top:60px;

}

</style>

</head>

<body>

<h2>KLINIK KAMPUS</h2>

<h3>Laporan Rekap Kunjungan Pasien</h3>

<p>
Tanggal Cetak :
<?= date('d F Y H:i'); ?>
</p>

<hr>

<table>

<thead>

<tr>

<th>No</th>

<th>Tanggal</th>

<th>Total</th>

<th>Mahasiswa</th>

<th>Dosen</th>

<th>Pegawai</th>

<th>Umum</th>

<th>Selesai</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($row=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?= $no++; ?></td>

<td><?= date('d-m-Y',strtotime($row['tanggal_kunjungan'])); ?></td>

<td><?= $row['jumlah_kunjungan']; ?></td>

<td><?= $row['mahasiswa']; ?></td>

<td><?= $row['dosen']; ?></td>

<td><?= $row['pegawai']; ?></td>

<td><?= $row['umum']; ?></td>

<td><?= $row['selesai']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

<div class="ttd">

<p>Banda Aceh, <?= date('d F Y'); ?></p>

<br>

<p>Administrator Klinik</p>

<br><br><br>

<b><?= $_SESSION['username']; ?></b>

</div>

<script>

window.onload = function(){

    window.print();

}

</script>

</body>
</html>