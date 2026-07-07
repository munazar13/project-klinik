<?php
include "koneksi.php";
include "session.php";

if ($_SESSION['role'] != 'admin') {
    echo "<script>
            alert('Akses ditolak! Halaman ini hanya untuk admin.');
            window.location='dashboard.php';
          </script>";
    exit;
}

// Total seluruh kunjungan
$totalKunjungan = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM kunjungan"));

// Jumlah Mahasiswa
$totalMahasiswa = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total
FROM kunjungan k
JOIN pasien p ON k.id_pasien = p.id_pasien
WHERE p.status_pasien='Mahasiswa'"));

// Total Dosen
$totalDosen = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM kunjungan k
JOIN pasien p ON k.id_pasien=p.id_pasien
WHERE p.status_pasien='Dosen'
"));

// Total Pegawai
$totalPegawai = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM kunjungan k
JOIN pasien p ON k.id_pasien=p.id_pasien
WHERE p.status_pasien='Pegawai'
"));

// Jumlah Umum
$totalUmum = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total
FROM kunjungan k
JOIN pasien p ON k.id_pasien = p.id_pasien
WHERE p.status_pasien='Umum'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kunjungan</title>

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
            <li class="dropdown-css">
                <a href="#">Data Master <i class="bi bi-chevron-down ms-1"></i></a>
                <div class="dropdown-css-menu">
                    <a href="pasien.php">Data Pasien</a>
                    <a href="obat.php">Data Obat</a>
                </div>
            </li>
            <li class="dropdown-css">
                <a href="#">Transaksi <i class="bi bi-chevron-down ms-1"></i></a>
                <div class="dropdown-css-menu">
                    <a href="kunjungan.php">Input Kunjungan</a>
                    <a href="laporan.php">Rekap Kunjungan</a>
                </div>
            </li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <section class="page-header">
        <h1 class="fw-bold mb-2">Rekap Kunjungan Pasien</h1>
        <p class="mb-0">Lihat ringkasan jumlah kunjungan pasien berdasarkan tanggal, status, dan jenis pasien.</p>
    </section>

    <section class="row g-4 mb-4">

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Total Kunjungan</p>
                <h3 class="fw-bold mb-0"><?= $totalKunjungan['total']; ?></h3>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Mahasiswa</p>
                <h3 class="fw-bold mb-0"><?= $totalMahasiswa['total']; ?></h3>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Dosen</p>
                <h3 class="fw-bold mb-0"><?= $totalDosen['total']; ?></h3>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Pegawai</p>
                <h3 class="fw-bold mb-0"><?= $totalPegawai['total']; ?></h3>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Umum</p>
                <h3 class="fw-bold mb-0"><?= $totalUmum['total']; ?></h3>
            </div>
        </div>

    </section>

    <section class="card-modern p-4 mb-4">
        <h5 class="fw-bold mb-3">Filter Laporan</h5>

        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tanggal Awal</label>
                <input type="date" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Tanggal Akhir</label>
                <input type="date" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Status Pasien</label>
                <select class="form-select">
                    <option>Semua Status</option>
                    <option>Mahasiswa</option>
                    <option>Dosen</option>
                    <option>Pegawai</option>
                    <option>Umum</option>
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100" type="button">Tampilkan</button>
            </div>
        </div>
    </section>

    <section class="card-modern p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
            <h5 class="fw-bold mb-0">Tabel Rekap Kunjungan</h5>
            <a href="cetak_laporan.php" target="_blank" class="btn btn-outline-primary">
                <i class="bi bi-printer me-1"></i> Cetak Laporan
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jumlah Kunjungan</th>
                        <th>Mahasiswa</th>
                        <th>Dosen</th>
                        <th>Pegawai</th>
                        <th>Umum</th>
                        <th>Selesai</th>
                    </tr>
                </thead>
                <tbody>

                <?php

                $no = 1;

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

                while($row = mysqli_fetch_assoc($query)){

                ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td><?= date('d-m-Y', strtotime($row['tanggal_kunjungan'])); ?></td>

                    <td><?= $row['jumlah_kunjungan']; ?></td>

                    <td><?= $row['mahasiswa']; ?></td>

                    <td><?= $row['dosen']; ?></td>

                    <td><?= $row['pegawai']; ?></td>

                    <td><?= $row['umum']; ?></td>

                    <td>
                        <span class="badge badge-soft-success">
                            <?= $row['selesai']; ?>
                        </span>
                    </td>

                </tr>

                <?php } ?>

                </tbody>
            </table>
        </div>

        
    </section>
</main>

<footer class="footer text-center">
    Sistem Informasi Klinik Kampus Sederhana
</footer>

</body>
</html>