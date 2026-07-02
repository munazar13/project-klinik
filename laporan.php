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
            <li><a href="#">Logout</a></li>
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
                <h3 class="fw-bold mb-0">245</h3>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Mahasiswa</p>
                <h3 class="fw-bold mb-0">190</h3>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Dosen / Pegawai</p>
                <h3 class="fw-bold mb-0">42</h3>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <p class="text-muted mb-1">Umum</p>
                <h3 class="fw-bold mb-0">13</h3>
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
            <button class="btn btn-outline-primary" type="button">
                <i class="bi bi-printer me-1"></i> Cetak Laporan
            </button>
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
                    <tr>
                        <td>1</td>
                        <td>01/07/2026</td>
                        <td>18</td>
                        <td>14</td>
                        <td>2</td>
                        <td>1</td>
                        <td>1</td>
                        <td><span class="badge badge-soft-success">14</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>30/06/2026</td>
                        <td>21</td>
                        <td>17</td>
                        <td>2</td>
                        <td>2</td>
                        <td>0</td>
                        <td><span class="badge badge-soft-success">20</span></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>29/06/2026</td>
                        <td>16</td>
                        <td>13</td>
                        <td>1</td>
                        <td>1</td>
                        <td>1</td>
                        <td><span class="badge badge-soft-success">16</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="alert alert-info mb-0">
            Halaman ini merupakan rancangan tampilan rekap. Untuk rekap otomatis dari database diperlukan bahasa backend seperti PHP.
        </div>
    </section>
</main>

<footer class="footer text-center">
    Sistem Informasi Klinik Kampus Sederhana
</footer>

</body>
</html>