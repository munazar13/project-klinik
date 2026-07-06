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
            <li><a href="#">Logout</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <section class="page-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2">Dashboard Klinik Kampus</h1>
                <p class="mb-0">Pantau data pasien, kunjungan harian, keluhan, tindakan, dan rekap layanan kesehatan kampus.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-light text-primary fs-6 px-3 py-2">Hari ini: 01 Juli 2026</span>
            </div>
        </div>
    </section>

    <section class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Pasien</p>
                        <h3 class="fw-bold mb-0">128</h3>
                    </div>
                    <div class="stat-icon"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Kunjungan Hari Ini</p>
                        <h3 class="fw-bold mb-0">18</h3>
                    </div>
                    <div class="stat-icon"><i class="bi bi-calendar2-check"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Kunjungan Selesai</p>
                        <h3 class="fw-bold mb-0">14</h3>
                    </div>
                    <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Stok Obat</p>
                        <h3 class="fw-bold mb-0">350</h3>
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
                    <a href="kunjungan.php" class="btn btn-sm btn-primary">Input Kunjungan</a>
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
                            <tr>
                                <td>1</td>
                                <td>01/07/2026</td>
                                <td>Ahmad Fauzi</td>
                                <td>Demam dan sakit kepala</td>
                                <td><span class="badge badge-soft-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>01/07/2026</td>
                                <td>Nur Aisyah</td>
                                <td>Nyeri lambung</td>
                                <td><span class="badge badge-soft-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>01/07/2026</td>
                                <td>Rahmat Hidayat</td>
                                <td>Pusing ringan</td>
                                <td><span class="badge badge-soft-warning">Diproses</span></td>
                            </tr>
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
                        <strong>78%</strong>
                    </div>
                    <div class="progress rounded-pill">
                        <div class="progress-bar" style="width: 78%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Diproses</span>
                        <strong>15%</strong>
                    </div>
                    <div class="progress rounded-pill">
                        <div class="progress-bar bg-warning" style="width: 15%"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Menunggu</span>
                        <strong>7%</strong>
                    </div>
                    <div class="progress rounded-pill">
                        <div class="progress-bar bg-danger" style="width: 7%"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="footer text-center">
    Sistem Informasi Klinik Kampus Sederhana
</footer>
<script src="assets/js/app.js"></script>

</body>
</html>