<?php
include "session.php";
include "koneksi.php";

$role = $_SESSION['role'];

// Total Pasien
$totalPasien = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM pasien"));

// Total Obat
$totalObat = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM obat"));

// Total Seluruh Kunjungan
$totalKunjungan = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM kunjungan"));

// Total Kunjungan Hari Ini
$kunjunganHariIni = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total
FROM kunjungan
WHERE tanggal_kunjungan = CURDATE()"));

// Total Kunjungan Selesai
$kunjunganSelesai = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total
FROM kunjungan
WHERE status_kunjungan='Selesai'"));

// Total Stok Obat
$totalStok = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(stok) AS total
FROM obat"));

//tabel kunjungan
$queryKunjungan = mysqli_query($conn,"
SELECT
kunjungan.*,
pasien.nama_pasien
FROM kunjungan
JOIN pasien
ON kunjungan.id_pasien=pasien.id_pasien
ORDER BY tanggal_kunjungan DESC,
jam_kunjungan DESC
LIMIT 5
");

// Rekap Status Kunjungan
$selesai = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM kunjungan WHERE status_kunjungan='Selesai'"));

$diproses = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM kunjungan WHERE status_kunjungan='Diproses'"));

$menunggu = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM kunjungan WHERE status_kunjungan='Menunggu'"));

$total = $totalKunjungan['total'];

$persenSelesai = ($total > 0) ? round(($selesai['total'] / $total) * 100) : 0;
$persenDiproses = ($total > 0) ? round(($diproses['total'] / $total) * 100) : 0;
$persenMenunggu = ($total > 0) ? round(($menunggu['total'] / $total) * 100) : 0;
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Klinik Kampus</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<header class="topbar py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="dashboard.php" class="brand-box">
            <span class="brand-icon"><i class="bi bi-hospital"></i></span>
            <span>Klinik Kampus</span>
        </a>

        <ul class="nav-menu">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>

            <?php if ($role == 'admin') { ?>
                <li class="dropdown-css">
                    <a href="#">Data Master <i class="bi bi-chevron-down ms-1"></i></a>
                    <div class="dropdown-css-menu">
                        <a href="pasien.php">Data Pasien</a>
                    </div>
                </li>
                <li class="dropdown-css">
                    <a href="#">Transaksi <i class="bi bi-chevron-down ms-1"></i></a>
                    <div class="dropdown-css-menu">
                        <a href="kunjungan.php">Input Kunjungan</a>
                        <a href="laporan.php">Rekap Kunjungan</a>
                    </div>
                </li>
            <?php } ?>

            <?php if ($role == 'petugas') { ?>
                <li><a href="obat.php">Data Obat</a></li>
            <?php } ?>

            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <section class="page-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2">Dashboard Klinik Kampus</h1>
                <p class="mb-0">
                    <i class="bi bi-clock"></i>
                    <span id="jam"></span>
                </p>
                <p class="mb-0">Pantau data pasien, kunjungan harian, keluhan, tindakan, dan rekap layanan kesehatan kampus.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-light text-primary fs-6 px-3 py-2">Hari ini: <?= date('d F Y'); ?></span>
            </div>
        </div>
    </section>

    <section class="row g-4 mb-4">
        <div class="col-md-6 col-xl-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Pasien</p>
                        <h3 class="fw-bold mb-0"><?= $totalPasien['total']; ?></h3>
                    </div>
                    <div class="stat-icon"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Obat</p>
                        <h3 class="fw-bold mb-0"><?= $totalObat['total']; ?></h3>
                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-capsule"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Kunjungan</p>
                        <h3 class="fw-bold mb-0"><?= $totalKunjungan['total']; ?></h3>
                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Kunjungan Hari Ini</p>
                        <h3 class="fw-bold mb-0"><?= $kunjunganHariIni['total']; ?></h3>
                    </div>
                    <div class="stat-icon"><i class="bi bi-calendar2-check"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Kunjungan Selesai</p>
                        <h3 class="fw-bold mb-0"><?= $kunjunganSelesai['total']; ?></h3>
                    </div>
                    <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Stok Obat</p>
                        <h3 class="fw-bold mb-0"><?= $totalStok['total']; ?></h3>
                    </div>
                    <div class="stat-icon"><i class="bi bi-capsule"></i></div>
                </div>
            </div>
        </div>



    </section>

    <section class="row g-4">
        <div class="col-lg-8">
            <div class="card-modern p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Kunjungan Terbaru</h5>
                    <?php if ($role == 'admin') { ?>
                        <a href="kunjungan.php" class="btn btn-sm btn-primary">Input Kunjungan</a>
                    <?php } else { ?>
                        <a href="obat.php" class="btn btn-sm btn-primary">Kelola Obat</a>
                    <?php } ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                        $no=1;

                        while($row=mysqli_fetch_assoc($queryKunjungan)){
                        ?>

                        <tr>

                        <td><?= $no++; ?></td>

                        <td><?= date('d-m-Y',strtotime($row['tanggal_kunjungan'])); ?></td>

                        <td><?= $row['nama_pasien']; ?></td>

                        <td><?= $row['keluhan']; ?></td>

                        <td>

                        <?php

                        if($row['status_kunjungan']=="Selesai"){
                            echo "<span class='badge badge-soft-success'>Selesai</span>";
                        }
                        elseif($row['status_kunjungan']=="Diproses"){
                            echo "<span class='badge badge-soft-warning'>Diproses</span>";
                        }
                        else{
                            echo "<span class='badge badge-soft-danger'>Menunggu</span>";
                        }

                        ?>

                        </td>

                        </tr>

                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-modern p-4">
                <h5 class="fw-bold mb-3">Rekap Status</h5>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Selesai</span>
                        <strong><?= $persenSelesai ?>%</strong>
                    </div>
                    <div class="progress rounded-pill">
                        <div class="progress-bar" style="width: <?= $persenSelesai ?>%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Diproses</span>
                        <strong><?= $persenDiproses ?>%</strong>
                    </div>
                    <div class="progress rounded-pill">
                        <div class="progress-bar bg-warning" style="width: <?= $persenDiproses ?>%"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Menunggu</span>
                        <strong><?= $persenMenunggu ?>%</strong>
                    </div>
                    <div class="progress rounded-pill">
                        <div class="progress-bar bg-danger" style="width: <?= $persenMenunggu ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="footer text-center">
    Sistem Informasi Klinik Kampus Sederhana
</footer>

<script>

function updateJam(){

    const sekarang = new Date();

    document.getElementById("jam").innerHTML =
    sekarang.toLocaleTimeString('id-ID') + " WIB";

}

updateJam();

setInterval(updateJam,1000);

</script>
</body>
</html>