<?php
// Menghubungkan file ini dengan koneksi database
include "koneksi.php";
include "session.php";
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

            <!-- Menu Dashboard -->
            <li>
                <a href="dashboard.php" class="active">Dashboard</a>
            </li>

            <!-- Dropdown Data Master -->
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

                    <!-- Menu Input Kunjungan -->
                    <a href="kunjungan.php">Input Kunjungan</a>

                    <!-- Menu Rekap Kunjungan -->
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

        <!-- Form input kunjungan -->
        <form action="" method="POST" class="needs-validation" novalidate>
            <!-- Jika sedang edit, kirim id_kunjungan secara tersembunyi -->
            <?php if($edit){ ?>
                <input type="hidden" name="id_kunjungan" value="<?= $row['id_kunjungan']; ?>">
            <?php } ?>

            <!-- Input tanggal kunjungan -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Kunjungan</label>

                <!-- Input tanggal kunjungan (wajib diisi) -->
                 <input type="date"
                   name="tanggal_kunjungan"
                   class="form-control"
                   value="<?= $edit ? $row['tanggal_kunjungan'] : ''; ?>" required >

                <!-- Pesan akan muncul otomatis jika kosong -->
                 <div class="invalid-feedback">
                   Tanggal kunjungan wajib diisi.
                 </div>
            </div>

            <!-- Input jam kunjungan -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Jam Kunjungan</label>

               <!-- Input jam kunjungan (wajib diisi) -->
                <input type="time"
                  name="jam_kunjungan"
                  class="form-control"
                  value="<?= $edit ? $row['jam_kunjungan'] : ''; ?>" required>

            <!-- Pesan validasi -->
            <div class="invalid-feedback">
             Jam kunjungan wajib diisi.
            </div>
            </div>

            <!-- Dropdown memilih pasien -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Pasien</label>

                <select name="id_pasien" class="form-select">

                    <!-- Pilihan default -->
                    <option value="">Pilih Pasien</option>

                    <!-- Menampilkan semua pasien dari database -->
                    <?php while($p = mysqli_fetch_assoc($pasien)){ ?>

                        <option value="<?= $p['id_pasien']; ?>"
                            <?= ($edit && $row['id_pasien'] == $p['id_pasien']) ? 'selected' : ''; ?>>

                            <?= $p['nama_pasien']; ?>

                        </option>

                    <?php } ?>

                </select>
            </div>

            <!-- Input keluhan pasien -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Keluhan</label>

                <!-- Menampilkan keluhan lama jika edit -->
                <textarea name="keluhan" class="form-control"><?= $edit ? $row['keluhan'] : ''; ?></textarea>
            </div>

            <!-- Input tindakan -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Tindakan</label>

                <!-- Menampilkan tindakan lama jika edit -->
                <textarea name="tindakan" class="form-control"><?= $edit ? $row['tindakan'] : ''; ?></textarea>
            </div>

            <!-- Dropdown memilih obat -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Obat</label>

                <select name="id_obat" class="form-select">

                    <!-- Jika pasien tidak mendapatkan obat -->
                    <option value="">Tidak ada obat</option>

                    <!-- Menampilkan daftar obat dari database -->
                    <?php while($o = mysqli_fetch_assoc($obat)){ ?>

                        <option value="<?= $o['id_obat']; ?>"
                            <?= ($edit && $row['id_obat'] == $o['id_obat']) ? 'selected' : ''; ?>>

                            <?= $o['nama_obat']; ?>

                        </option>

                    <?php } ?>

                </select>
            </div>

            <!-- Input jumlah obat -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Jumlah Obat</label>

                <!-- Menampilkan jumlah lama jika edit -->
                <input type="number"
                       name="jumlah_obat"
                       class="form-control"
                       value="<?= $edit ? $row['jumlah_obat'] : ''; ?>">
            </div>

            <!-- Dropdown status kunjungan -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Status Kunjungan</label>

                <select name="status_kunjungan" class="form-select">

                    <!-- Pilihan status -->
                    <option value="Menunggu">Menunggu</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Selesai">Selesai</option>

                </select>
            </div>

            <!-- Input nama petugas -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Petugas</label>

                <!-- Menampilkan nama petugas lama jika edit -->
                <input type="text"
                       name="petugas"
                       class="form-control"
                       placeholder="Nama petugas"
                       value="<?= $edit ? $row['petugas'] : ''; ?>">
            </div>

            <!-- Tombol aksi -->
            <div class="d-grid gap-2">

                <!-- Tombol menyimpan data -->
                <button class="btn btn-primary"
                        type="submit"
                        name="simpan">
                    Simpan Kunjungan
                </button>

                <!-- Tombol mengosongkan form -->
                <button class="btn btn-outline-secondary"
                        type="reset">
                    Reset Form
                </button>

            </div>

        </form>

    </div>
</div>
<!-- Card untuk filter dan tabel data -->
<div class="col-lg-8">
    <div class="card-modern p-4">

        <!-- Judul filter -->
        <h5 class="fw-bold mb-3">Filter Tanggal Kunjungan</h5>

        <!-- Form filter menggunakan metode GET -->
        <form method="GET">

            <!-- Baris input filter -->
            <div class="row g-3 mb-4">

                <!-- Input tanggal awal -->
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Tanggal Awal</label>

                    <!-- Menampilkan tanggal awal yang dipilih -->
                    <input type="date"
                           name="tgl_awal"
                           class="form-control"
                           value="<?= isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : ''; ?>">
                </div>

                <!-- Input tanggal akhir -->
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Tanggal Akhir</label>

                    <!-- Menampilkan tanggal akhir yang dipilih -->
                    <input type="date"
                           name="tgl_akhir"
                           class="form-control"
                           value="<?= isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : ''; ?>">
                </div>

                <!-- Tombol filter -->
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100" type="submit">
                        Filter
                    </button>
                </div>

            </div>
        </form>

        <!-- Membuat tabel responsif -->
        <div class="table-responsive">

            <!-- Tabel daftar kunjungan -->
            <table class="table table-hover">

                <!-- Header tabel -->
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

                <!-- Isi tabel -->
                <tbody>

                    <?php

                    // Nomor urut tabel
                    $no = 1;

                    // Query mengambil data kunjungan dan nama pasien
                    $sql = "SELECT k.*, p.nama_pasien
                    FROM kunjungan k
                    JOIN pasien p ON k.id_pasien = p.id_pasien";

                    // Jika filter tanggal diisi
                    if(isset($_GET['tgl_awal']) &&
                       isset($_GET['tgl_akhir']) &&
                       $_GET['tgl_awal'] != "" &&
                       $_GET['tgl_akhir'] != ""){

                        // Menyimpan tanggal awal
                        $tgl_awal = $_GET['tgl_awal'];

                        // Menyimpan tanggal akhir
                        $tgl_akhir = $_GET['tgl_akhir'];

                        // Menambahkan kondisi filter ke query
                        $sql .= " WHERE k.tanggal_kunjungan BETWEEN '$tgl_awal' AND '$tgl_akhir'";
                    }

                    // Mengurutkan data terbaru di atas
                    $sql .= " ORDER BY k.id_kunjungan DESC";

                    // Menjalankan query
                    $data = mysqli_query($conn, $sql);

                    // Menampilkan semua data
                    while($d = mysqli_fetch_assoc($data)){
                    ?>
                       <!-- Perulangan untuk menampilkan setiap data -->
                    <tr>

                        <!-- Nomor urut -->
                        <td><?= $no++; ?></td>

                        <!-- Tanggal kunjungan -->
                        <td><?= $d['tanggal_kunjungan']; ?></td>

                        <!-- Nama pasien -->
                        <td><?= $d['nama_pasien']; ?></td>

                        <!-- Keluhan pasien -->
                        <td><?= $d['keluhan']; ?></td>

                        <!-- Tindakan yang diberikan -->
                        <td><?= $d['tindakan']; ?></td>

                        <!-- Status kunjungan -->
                        <td><?= $d['status_kunjungan']; ?></td>

                        <!-- Tombol aksi -->
                        <td>

                            <!-- Tombol edit data -->
                            <a href="kunjungan.php?edit=<?= $d['id_kunjungan']; ?>"
                               class="btn btn-sm btn-warning"
                               onclick="return confirm('Yakin ingin mengedit data ini?')">
                               Edit
                            </a>

                            <!-- Tombol hapus data -->
                            <a href="kunjungan.php?hapus=<?= $d['id_kunjungan']; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Yakin ingin menghapus data ini?')">
                               Hapus
                            </a>

                        </td>

                    </tr>

                    <!-- Akhir perulangan while -->
                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</section>

</main>

<!-- Footer website -->
<footer class="footer text-center">
    Sistem Informasi Klinik Kampus Sederhana
</footer>

<!-- Memanggil Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script validasi Bootstrap -->
<script>
(() => {
  'use strict';

  const forms = document.querySelectorAll('.needs-validation');

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {

      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }

      form.classList.add('was-validated');

    }, false);
  });
})();
</script>
</body>
</html>