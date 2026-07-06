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
            <li><a href="dashboard.php">Dashboard</a></li>
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
                    <a href="kunjungan.php" class="active">Input Kunjungan</a>
                    <a href="laporan.php">Rekap Kunjungan</a>
                </div>
            </li>
            <li><a href="#">Logout</a></li>
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

                <!-- Form utama dengan class validasi -->
                <form class="validate-form">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Kunjungan</label>
                        <input type="date" name="tgl_kunjungan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jam Kunjungan</label>
                        <input type="time" name="jam_kunjungan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pasien</label>
                        <select name="nama_pasien" class="form-select" required>
                            <option value="" selected disabled>Pilih pasien</option>
                            <option value="Ahmad Fauzi">Ahmad Fauzi</option>
                            <option value="Nur Aisyah">Nur Aisyah</option>
                            <option value="Dr. Rahmat Hidayat">Dr. Rahmat Hidayat</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keluhan</label>
                        <textarea name="keluhan" class="form-control" required rows="3" placeholder="Tuliskan keluhan pasien"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tindakan</label>
                        <textarea name="tindakan" class="form-control" required rows="3" placeholder="Tuliskan tindakan yang diberikan"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Obat</label>
                        <select name="obat" class="form-select" required>
                            <option value="" selected disabled>Pilih obat</option>
                            <option value="Tidak ada obat">Tidak ada obat</option>
                            <option value="Paracetamol">Paracetamol</option>
                            <option value="Antasida">Antasida</option>
                            <option value="Vitamin C">Vitamin C</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Obat</label>
                        <input type="number" name="jumlah_obat" class="form-control" min="1" required placeholder="Contoh: 2">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Kunjungan</label>
                        <select name="status_kunjungan" class="form-select" required>
                            <option value="" selected disabled>Pilih status</option>
                            <option value="Menunggu">Menunggu</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Petugas</label>
                        <input type="text" name="petugas" class="form-control" required placeholder="Nama petugas yang menangani">
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Simpan Kunjungan</button>
                        <button class="btn btn-outline-secondary" type="reset">Reset Form</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card-modern p-4">
                <h5 class="fw-bold mb-3">Filter Tanggal Kunjungan</h5>

                <!-- Form filter dengan class validasi -->
                <form class="validate-form">
                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Tanggal Awal</label>
                            <input type="date" name="tgl_awal" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" class="form-control" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100" type="submit">Filter</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="tableKunjungan" class="table table-hover">
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
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning action-btn" type="button">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm action-btn btn-delete">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>01/07/2026</td>
                                <td>Nur Aisyah</td>
                                <td>Nyeri lambung</td>
                                <td>Konsultasi dan pemberian antasida</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning action-btn" type="button">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm action-btn btn-delete">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>01/07/2026</td>
                                <td>Dr. Rahmat Hidayat</td>
                                <td>Pusing ringan</td>
                                <td>Pemeriksaan tekanan darah</td>
                                <td><span class="badge bg-warning text-dark">Diproses</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning action-btn" type="button">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm action-btn btn-delete">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="text-muted mb-0 small">Data kunjungan bersifat simulasi dan belum terhubung ke database.</p>
            </div>
        </div>
    </section>
</main>

<footer class="footer text-center mt-4">
    Sistem Informasi Klinik Kampus Sederhana &copy; 2026
</footer>

<script src="assets/js/app.js"></script>
</body>
</html>