<?php
include "koneksi.php";
include "session.php";

if ($_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak! Halaman laporan hanya untuk admin.'); window.location='dashboard.php';</script>";
    exit;
}

function e($teks) {
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function bersih($teks) {
    return trim($teks ?? '');
}

$tgl_awal = mysqli_real_escape_string($conn, bersih($_GET['tgl_awal'] ?? ''));
$tgl_akhir = mysqli_real_escape_string($conn, bersih($_GET['tgl_akhir'] ?? ''));

$where = '';
if ($tgl_awal != '' && $tgl_akhir != '') {
    $where = "WHERE k.tanggal_kunjungan BETWEEN '$tgl_awal' AND '$tgl_akhir'";
} elseif ($tgl_awal != '') {
    $where = "WHERE k.tanggal_kunjungan >= '$tgl_awal'";
} elseif ($tgl_akhir != '') {
    $where = "WHERE k.tanggal_kunjungan <= '$tgl_akhir'";
}

$ringkasan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*) AS total_kunjungan,
        SUM(CASE WHEN p.status_pasien='Mahasiswa' THEN 1 ELSE 0 END) AS mahasiswa,
        SUM(CASE WHEN p.status_pasien='Dosen' THEN 1 ELSE 0 END) AS dosen,
        SUM(CASE WHEN p.status_pasien='Pegawai' THEN 1 ELSE 0 END) AS pegawai,
        SUM(CASE WHEN p.status_pasien='Umum' THEN 1 ELSE 0 END) AS umum
    FROM kunjungan k
    JOIN pasien p ON k.id_pasien = p.id_pasien
    $where
"));

$query = mysqli_query($conn, "
    SELECT
        k.tanggal_kunjungan,
        COUNT(*) AS jumlah_kunjungan,
        SUM(CASE WHEN p.status_pasien='Mahasiswa' THEN 1 ELSE 0 END) AS mahasiswa,
        SUM(CASE WHEN p.status_pasien='Dosen' THEN 1 ELSE 0 END) AS dosen,
        SUM(CASE WHEN p.status_pasien='Pegawai' THEN 1 ELSE 0 END) AS pegawai,
        SUM(CASE WHEN p.status_pasien='Umum' THEN 1 ELSE 0 END) AS umum,
        SUM(CASE WHEN k.status_kunjungan='Selesai' THEN 1 ELSE 0 END) AS selesai
    FROM kunjungan k
    JOIN pasien p ON k.id_pasien = p.id_pasien
    $where
    GROUP BY k.tanggal_kunjungan
    ORDER BY k.tanggal_kunjungan DESC
");

$link_cetak = 'cetak_laporan.php';
if ($tgl_awal != '' || $tgl_akhir != '') {
    $link_cetak .= '?tgl_awal=' . urlencode($tgl_awal) . '&tgl_akhir=' . urlencode($tgl_akhir);
}
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="dropdown-css">
                <a href="#">Data Master <i class="bi bi-chevron-down ms-1"></i></a>
                <div class="dropdown-css-menu">
                    <a href="pasien.php">Data Pasien</a>
                </div>
            </li>
            <li class="dropdown-css">
                <a href="#" class="active">Transaksi <i class="bi bi-chevron-down ms-1"></i></a>
                <div class="dropdown-css-menu">
                    <a href="kunjungan.php">Input Kunjungan</a>
                    <a href="laporan.php" class="active">Rekap Kunjungan</a>
                </div>
            </li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <section class="page-header">
        <h1 class="fw-bold mb-2">Rekap Kunjungan Pasien</h1>
        <p class="mb-0">Lihat ringkasan jumlah kunjungan pasien berdasarkan tanggal dan jenis pasien.</p>
    </section>

    <section class="card-modern p-4 mb-4">
        <h5 class="fw-bold mb-3">Filter Tanggal Laporan</h5>
        <form method="GET" class="row g-3">
            <div class="col-md-5">
                <label class="form-label fw-semibold">Tanggal Awal</label>
                <input type="date" name="tgl_awal" class="form-control" value="<?= e($tgl_awal); ?>">
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" class="form-control" value="<?= e($tgl_akhir); ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100" type="submit">Tampilkan</button>
            </div>
            <?php if ($tgl_awal != '' || $tgl_akhir != '') { ?>
                <div class="col-12">
                    <a href="laporan.php" class="btn btn-outline-secondary btn-sm">Reset Filter</a>
                </div>
            <?php } ?>
        </form>
    </section>

    <section class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Total Kunjungan</p>
                <h3 class="fw-bold mb-0"><?= e($ringkasan['total_kunjungan'] ?? 0); ?></h3>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Mahasiswa</p>
                <h3 class="fw-bold mb-0"><?= e($ringkasan['mahasiswa'] ?? 0); ?></h3>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Dosen</p>
                <h3 class="fw-bold mb-0"><?= e($ringkasan['dosen'] ?? 0); ?></h3>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Pegawai</p>
                <h3 class="fw-bold mb-0"><?= e($ringkasan['pegawai'] ?? 0); ?></h3>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Umum</p>
                <h3 class="fw-bold mb-0"><?= e($ringkasan['umum'] ?? 0); ?></h3>
            </div>
        </div>
    </section>

    <section class="card-modern p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
            <h5 class="fw-bold mb-0">Tabel Rekap Kunjungan</h5>
            <a href="<?= e($link_cetak); ?>" target="_blank" class="btn btn-outline-primary">
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
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= e(date('d-m-Y', strtotime($row['tanggal_kunjungan']))); ?></td>
                        <td><?= e($row['jumlah_kunjungan']); ?></td>
                        <td><?= e($row['mahasiswa']); ?></td>
                        <td><?= e($row['dosen']); ?></td>
                        <td><?= e($row['pegawai']); ?></td>
                        <td><?= e($row['umum']); ?></td>
                        <td><span class="badge badge-soft-success"><?= e($row['selesai']); ?></span></td>
                    </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="8" class="text-center text-danger">Data rekap kunjungan belum tersedia.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<footer class="footer text-center">Sistem Informasi Klinik Kampus Sederhana</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
