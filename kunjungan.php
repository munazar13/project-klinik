<?php
include "session.php";

if ($_SESSION['role'] != 'admin') {
    echo "<script>
            alert('Akses ditolak! Halaman ini hanya untuk admin.');
            window.location='dashboard.php';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Kunjungan</title>

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
        <h1 class="fw-bold mb-2">Input Kunjungan Pasien</h1>
        <p class="mb-0">Catat tanggal kunjungan, keluhan pasien, tindakan petugas, dan obat yang diberikan.</p>
    </section>

    <section class="row g-4">
        <div class="col-lg-4">
            <div class="card-modern p-4">
                <h5 class="fw-bold mb-3">Form Kunjungan</h5>

                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Kunjungan</label>
                        <input type="date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jam Kunjungan</label>
                        <input type="time" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pasien</label>
                        <select class="form-select">
                            <option>Pilih pasien</option>
                            <option>Ahmad Fauzi</option>
                            <option>Nur Aisyah</option>
                            <option>Dr. Rahmat Hidayat</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keluhan</label>
                        <textarea class="form-control" rows="3" placeholder="Tuliskan keluhan pasien"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tindakan</label>
                        <textarea class="form-control" rows="3" placeholder="Tuliskan tindakan yang diberikan"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Obat</label>
                        <select class="form-select">
                            <option>Tidak ada obat</option>
                            <option>Paracetamol</option>
                            <option>Antasida</option>
                            <option>Vitamin C</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Obat</label>
                        <input type="number" class="form-control" placeholder="Contoh: 2">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Kunjungan</label>
                        <select class="form-select">
                            <option>Menunggu</option>
                            <option>Diproses</option>
                            <option>Selesai</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Petugas</label>
                        <input type="text" class="form-control" placeholder="Nama petugas">
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="button">Simpan Kunjungan</button>
                        <button class="btn btn-outline-secondary" type="reset">Reset Form</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card-modern p-4">
                <h5 class="fw-bold mb-3">Filter Tanggal Kunjungan</h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Tanggal Awal</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Tanggal Akhir</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100" type="button">Filter</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Keluhan</th>
                                <th>Tindakan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>01/07/2026</td>
                                <td>Ahmad Fauzi</td>
                                <td>Demam dan sakit kepala</td>
                                <td>Pemeriksaan dan pemberian obat</td>
                                <td><span class="badge badge-soft-success">Selesai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning action-btn" type="button">Edit</button>
                                    <button class="btn btn-sm btn-danger action-btn" type="button">Hapus</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>01/07/2026</td>
                                <td>Nur Aisyah</td>
                                <td>Nyeri lambung</td>
                                <td>Konsultasi dan pemberian antasida</td>
                                <td><span class="badge badge-soft-success">Selesai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning action-btn" type="button">Edit</button>
                                    <button class="btn btn-sm btn-danger action-btn" type="button">Hapus</button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>01/07/2026</td>
                                <td>Dr. Rahmat Hidayat</td>
                                <td>Pusing ringan</td>
                                <td>Pemeriksaan tekanan darah</td>
                                <td><span class="badge badge-soft-warning">Diproses</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning action-btn" type="button">Edit</button>
                                    <button class="btn btn-sm btn-danger action-btn" type="button">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="text-muted mb-0 small">Filter tanggal hanya tampilan rancangan karena tidak menggunakan proses backend.</p>
            </div>
        </div>
    </section>
</main>

<footer class="footer text-center">
    Sistem Informasi Klinik Kampus Sederhana
</footer>

</body>
</html>