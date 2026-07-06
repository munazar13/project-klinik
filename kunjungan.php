<?php
// Menghubungkan file ini dengan koneksi database
include "koneksi.php";

// Menentukan apakah sedang dalam mode edit atau tidak
$edit = false;

// Mengecek apakah tombol Edit ditekan
if(isset($_GET['edit'])){

    // Jika iya, ubah status menjadi mode edit
    $edit = true;

    // Mengambil id_kunjungan dari URL
    $id = $_GET['edit'];

    // Mengambil data kunjungan berdasarkan id
    $ambil = mysqli_query($conn,"SELECT * FROM kunjungan WHERE id_kunjungan='$id'");

    // Mengubah hasil query menjadi array
    $row = mysqli_fetch_assoc($ambil);
}

// Mengecek apakah tombol Hapus ditekan
if(isset($_GET['hapus'])){

    // Mengambil id yang akan dihapus
    $id = $_GET['hapus'];

    // Menghapus data berdasarkan id_kunjungan
    $hapus = mysqli_query($conn, "DELETE FROM kunjungan WHERE id_kunjungan='$id'");

    // Jika berhasil dihapus
    if($hapus){

        // Kembali ke halaman kunjungan dengan pesan hapus
        header("Location: kunjungan.php?pesan=hapus");
        exit;

    }else{

        // Menampilkan pesan error jika gagal
        die(mysqli_error($conn));
    }
}

// Mengecek apakah tombol Simpan ditekan
if(isset($_POST['simpan'])){

    // Mengambil data dari form
    $tanggal = $_POST['tanggal_kunjungan'];
    $jam = $_POST['jam_kunjungan'];
    $id_pasien = $_POST['id_pasien'];

    // Jika obat tidak dipilih maka nilainya NULL
    $id_obat = empty($_POST['id_obat']) ? "NULL" : $_POST['id_obat'];

    // Mengambil data keluhan
    $keluhan = $_POST['keluhan'];

    // Mengambil data tindakan
    $tindakan = $_POST['tindakan'];

    // Mengambil jumlah obat
    $jumlah = $_POST['jumlah_obat'];

    // Mengambil status kunjungan
    $status = $_POST['status_kunjungan'];

    // Mengambil nama petugas
    $petugas = $_POST['petugas'];

    // Mengecek apakah form sedang mengedit data
    $edit = isset($_POST['id_kunjungan']);

    // Jika mode edit
    if($edit){

        // Mengambil id yang akan diupdate
        $id = $_POST['id_kunjungan'];

        // Query untuk mengubah data kunjungan
        $query = "UPDATE kunjungan SET
        id_pasien='$id_pasien',
        id_obat=$id_obat,
        tanggal_kunjungan='$tanggal',
        jam_kunjungan='$jam',
        keluhan='$keluhan',
        tindakan='$tindakan',
        jumlah_obat='$jumlah',
        status_kunjungan='$status',
        petugas='$petugas'
        WHERE id_kunjungan='$id'";

    }else{

        // Query untuk menambahkan data kunjungan baru
        $query = "INSERT INTO kunjungan
        (id_pasien,id_obat,tanggal_kunjungan,jam_kunjungan,keluhan,tindakan,jumlah_obat,status_kunjungan,petugas)
        VALUES
        ('$id_pasien',$id_obat,'$tanggal','$jam','$keluhan','$tindakan','$jumlah','$status','$petugas')";
    }

    // Menjalankan query
    if(mysqli_query($conn, $query)){

        // Jika berhasil, kembali ke halaman dengan pesan berhasil
        header("Location: kunjungan.php?pesan=simpan");
        exit;

    }else{

        // Menampilkan error jika query gagal
        die(mysqli_error($conn));
    }
}

// Mengambil seluruh data pasien untuk dropdown
$pasien = mysqli_query($conn, "SELECT * FROM pasien");

// Mengambil seluruh data obat untuk dropdown
$obat = mysqli_query($conn, "SELECT * FROM obat");
?>

<!DOCTYPE html>
<!-- Menentukan bahwa dokumen ini menggunakan HTML5 -->

<html lang="id">
<!-- Bahasa halaman adalah Bahasa Indonesia -->

<head>
    <!-- Mengatur karakter agar mendukung huruf dan simbol -->
    <meta charset="UTF-8">

    <!-- Agar tampilan responsif di HP maupun komputer -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul yang tampil di tab browser -->
    <title>Input Kunjungan</title>

    <!-- Memanggil CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Memanggil ikon Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Memanggil file CSS buatan sendiri -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

<!-- Header website -->
<header class="topbar py-3">

    <!-- Container agar isi header rapi -->
    <div class="container d-flex justify-content-between align-items-center">

        <!-- Logo sekaligus link menuju Dashboard -->
        <a href="dashboard.php" class="brand-box">

            <!-- Icon rumah sakit -->
            <span class="brand-icon">
                <i class="bi bi-hospital"></i>
            </span>

            <!-- Nama website -->
            <span>Klinik Kampus</span>

        </a>

        <!-- Menu navigasi -->
        <ul class="nav-menu">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li class="dropdown-css">

                <a href="#">
                    Data Master
                    <i class="bi bi-chevron-down ms-1"></i>
                </a>

                <!-- Isi dropdown -->
                <div class="dropdown-css-menu">

                    <!-- Menu Data Pasien -->
                    <a href="pasien.php">Data Pasien</a>

                    <!-- Menu Data Obat -->
                    <a href="obat.php">Data Obat</a>

                </div>

            </li>

            <!-- Dropdown Transaksi -->
            <li class="dropdown-css">

                <a href="#">
                    Transaksi
                    <i class="bi bi-chevron-down ms-1"></i>
                </a>

                <!-- Isi dropdown -->
                <div class="dropdown-css-menu">
                    <a href="kunjungan.php">Input Kunjungan</a>
                    <a href="laporan.php">Rekap Kunjungan</a>

                </div>

            </li>

            <!-- Menu Logout -->
            <li>
                <a href="#">Logout</a>
            </li>

        </ul>

    </div>

</header>

<!-- Isi utama halaman -->
<main class="container">

    <!-- Bagian judul halaman -->
    <section class="page-header">

        <!-- Notifikasi jika data berhasil disimpan -->
        <?php if(isset($_GET['pesan']) && $_GET['pesan']=="simpan"){ ?>

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <strong>Berhasil!</strong>
            Data kunjungan berhasil disimpan.

            <!-- Tombol menutup notifikasi -->
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

        <?php } ?>

        <!-- Notifikasi jika data berhasil dihapus -->
        <?php if(isset($_GET['pesan']) && $_GET['pesan']=="hapus"){ ?>

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <strong>Berhasil!</strong>
            Data kunjungan berhasil dihapus.

            <!-- Tombol menutup notifikasi -->
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

        <?php } ?>

        <!-- Judul halaman -->
        <h1 class="fw-bold mb-2">Input Kunjungan Pasien</h1>

        <!-- Deskripsi singkat halaman -->
        <p class="mb-0">
            Catat tanggal kunjungan, keluhan pasien, tindakan petugas, dan obat yang diberikan.
        </p>

    </section>

    <section class="row g-4">
        <!-- Kolom sebelah kiri untuk form input -->
<div class="col-lg-4">

    <!-- Card sebagai wadah form -->
    <div class="card-modern p-4">

        <!-- Judul form -->
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