<?php
// Menghubungkan file ini dengan database
include "koneksi.php";
include "session.php";
// ======================================================
// CREATE DATA PASIEN
// Bagian ini berfungsi untuk menyimpan data pasien baru
// ke dalam tabel pasien di database.
// ======================================================
if (isset($_POST['simpan'])) {

    // Mengambil semua data yang dikirim dari form menggunakan metode POST
    $no_rm          = $_POST['no_rm'];
    $nim_nip        = $_POST['nim_nip'];
    $nama_pasien    = $_POST['nama_pasien'];
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $tanggal_lahir  = $_POST['tgl_lahir'];
    $status_pasien  = $_POST['status_pasien'];
    $fakultas_unit  = $_POST['fakultas_unit'];
    $no_hp          = $_POST['no_hp'];
    $alamat         = $_POST['alamat'];

    // Validasi data penting agar tidak boleh kosong
    if (empty($no_rm) || empty($nama_pasien) || empty($jenis_kelamin)) {

        // Menampilkan pesan jika data wajib belum diisi
        echo "<script>alert('Gagal! No. RM, Nama Pasien, dan Jenis Kelamin wajib diisi.');</script>";

    } else {

        // Query INSERT untuk menambahkan data pasien ke database
        $query_tambah = "INSERT INTO pasien
        (no_rm, nim_nip, nama_pasien, jenis_kelamin, tanggal_lahir, status_pasien, fakultas_unit, no_hp, alamat)
        VALUES
        ('$no_rm', '$nim_nip', '$nama_pasien', '$jenis_kelamin', '$tanggal_lahir', '$status_pasien', '$fakultas_unit', '$no_hp', '$alamat')";

        // Menjalankan query INSERT
        $simpan_data = mysqli_query($conn, $query_tambah);

        // Jika berhasil maka kembali ke halaman pasien
        if ($simpan_data) {

            header("Location: pasien.php?pesan=tambah");
            exit;

        } else {

            // Jika gagal menampilkan pesan error
            echo "<script>alert('Gagal menyimpan data ke database.');</script>";
        }
    }
}

// ======================================================
// UPDATE DATA PASIEN
// Bagian ini digunakan untuk mengubah data pasien
// berdasarkan ID pasien yang dipilih.
// ======================================================
if (isset($_POST['update'])) {

    // Mengambil ID pasien yang akan diupdate
    $id = $_POST['id'];

    // Mengambil data terbaru dari form
    $no_rm = $_POST['no_rm'];
    $nim_nip = $_POST['nim_nip'];
    $nama_pasien = $_POST['nama_pasien'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $status_pasien = $_POST['status_pasien'];
    $fakultas_unit = $_POST['fakultas_unit'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];

    // Query UPDATE untuk memperbarui data pasien
    $query_update = mysqli_query($conn, "UPDATE pasien SET
        no_rm='$no_rm',
        nim_nip='$nim_nip',
        nama_pasien='$nama_pasien',
        jenis_kelamin='$jenis_kelamin',
        tanggal_lahir='$tgl_lahir',
        status_pasien='$status_pasien',
        fakultas_unit='$fakultas_unit',
        no_hp='$no_hp',
        alamat='$alamat'
        WHERE id_pasien='$id'");

    // Jika update berhasil
    if ($query_update) {

        header("Location: pasien.php?pesan=update");
        exit;

    } else {

        // Jika update gagal
        echo "<script>alert('Update data gagal!');</script>";
    }
}

// ======================================================
// DELETE DATA PASIEN
// Bagian ini berfungsi menghapus data pasien berdasarkan
// ID yang dipilih dari tombol Hapus.
// ======================================================
if (isset($_GET['hapus'])) {

    // Mengambil ID pasien dari URL
    $id = $_GET['hapus'];

    // Menjalankan query DELETE
    $hapus = mysqli_query($conn, "DELETE FROM pasien WHERE id_pasien='$id'");

    // Jika berhasil dihapus
    if ($hapus) {

        header("Location: pasien.php?pesan=hapus");
        exit;

    } else {

        // Jika gagal menghapus
        echo "<script>alert('Data pasien gagal dihapus!');</script>";
    }
}

// ======================================================
// READ DATA PASIEN
// Mengambil seluruh data pasien dari database.
// Data ini nantinya akan ditampilkan pada tabel.
// ======================================================
$query = mysqli_query($conn, "SELECT * FROM pasien");

// ======================================================
// EDIT DATA PASIEN
// Bagian ini mengambil data pasien berdasarkan ID,
// kemudian menampilkannya kembali ke dalam form
// agar dapat diperbarui.
// ======================================================

// Menandakan apakah form sedang dalam mode edit atau tidak
$edit = false;

// Mengecek apakah tombol Edit ditekan
if (isset($_GET['edit'])) {

    // Mengubah status menjadi mode edit
    $edit = true;

    // Mengambil ID pasien dari URL
    $id = $_GET['edit'];

    // Mengambil data pasien sesuai ID
    $ambil = mysqli_query($conn, "SELECT * FROM pasien WHERE id_pasien='$id'");

    // Mengubah hasil query menjadi array agar bisa dipanggil di form
    $data_edit = mysqli_fetch_assoc($ambil);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <!-- ======================================================
         BAGIAN HEAD
         Berisi pengaturan halaman, judul, dan file CSS
         ======================================================= -->

    <!-- Menentukan karakter yang digunakan -->
    <meta charset="UTF-8">

    <!-- Membuat tampilan responsive di berbagai ukuran layar -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul halaman yang tampil pada tab browser -->
    <title>Data Pasien</title>

    <!-- Memanggil Framework Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Memanggil Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Memanggil CSS buatan sendiri -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

<!--===============================================
     HEADER
     Berisi logo aplikasi dan menu navigasi
    =============================================== -->
<header class="topbar py-3">

    <div class="container d-flex justify-content-between align-items-center">

        <!-- Logo Sistem Klinik -->
        <a href="dashboard.php" class="brand-box">
            <span class="brand-icon">
                <i class="bi bi-hospital"></i>
            </span>

            <span>Klinik Kampus</span>
        </a>

        <!-- Menu Navigasi -->
        <ul class="nav-menu">

            <!-- Menu Dashboard -->
            <li>
                <a href="dashboard.php" class="active">
                    Dashboard
                </a>
            </li>

            <!-- Menu Dropdown Data Master -->
            <li class="dropdown-css">

                <a href="#">
                    Data Master
                    <i class="bi bi-chevron-down ms-1"></i>
                </a>

                <div class="dropdown-css-menu">
                    <a href="pasien.php">Data Pasien</a>
                    <a href="obat.php">Data Obat</a>
                </div>

            </li>

            <!-- Menu Dropdown Transaksi -->
            <li class="dropdown-css">

                <a href="#">
                    Transaksi
                    <i class="bi bi-chevron-down ms-1"></i>
                </a>

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

<!-- =======================================================
     HALAMAN UTAMA
     Berisi notifikasi, form CRUD, dan tabel data pasien
     ======================================================= -->
<main class="container">

    <!-- Menampilkan notifikasi setelah proses tambah, update, atau hapus -->
    <?php if(isset($_GET['pesan'])){ ?>

    <?php if($_GET['pesan']=="tambah"){ ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <strong>Berhasil!</strong> Data pasien berhasil ditambahkan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php if($_GET['pesan']=="update"){ ?>
        <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
            <strong>Berhasil!</strong> Data pasien berhasil diperbarui.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php if($_GET['pesan']=="hapus"){ ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <strong>Berhasil!</strong> Data pasien berhasil dihapus.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

<?php } ?>

<!-- Header Judul Halaman -->
<section class="page-header">
        <h1 class="fw-bold mb-2">CRUD Data Pasien</h1>
        <p class="mb-0">Kelola data pasien klinik kampus seperti mahasiswa, dosen, pegawai, dan umum.</p>
    </section>

    <!-- ===================================================
         BAGIAN FORM INPUT DATA PASIEN
         Form ini digunakan untuk menambah data pasien baru
         maupun mengubah data pasien yang sudah ada.
         =================================================== -->
    <section class="row g-4">

        <!-- ========================================
             KOLOM KIRI
             Berisi Form Input Data Pasien
             ======================================== -->
        <div class="col-lg-4">

            <!-- Card sebagai wadah form -->
            <div class="card-modern p-4">

                <!-- Judul Form -->
                <h5 class="fw-bold mb-3">Form Data Pasien</h5>

                <!-- Form menggunakan metode POST -->
                <!-- novalidate digunakan agar validasi memakai Bootstrap -->
                <form action="" method="POST" class="needs-validation" novalidate>

                    <!-- Menyimpan ID pasien ketika proses Edit -->
                    <input type="hidden" name="id" value="<?= $edit ? $data_edit['id_pasien'] : ''; ?>">

                    <!-- =========================
                         Input Nomor Rekam Medis
                         ========================= -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. Rekam Medis</label>

                        <input type="text" class="form-control" name="no_rm" placeholder="Contoh: RM001" required value="<?= $edit ? $data_edit['no_rm'] : ''; ?>">

                        <!-- Pesan validasi Bootstrap -->
                        <div class="invalid-feedback">
                            No. Rekam Medis wajib diisi.
                        </div>
                    </div>

                    <!-- =====================
                         Input NIM / NIP
                         ===================== -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            NIM / NIP
                        </label>

                        <input type="text" class="form-control"
                               name="nim_nip"
                               placeholder="Masukkan NIM atau NIP"
                               value="<?= $edit ? $data_edit['nim_nip'] : ''; ?>">

                    </div>

                    <!-- =======================
                         Input Nama Pasien
                         ======================= -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Nama Pasien
                        </label>

                        <input type="text"
                               class="form-control"
                               name="nama_pasien"
                               placeholder="Masukkan nama pasien"
                               required
                               value="<?= $edit ? $data_edit['nama_pasien'] : ''; ?>">

                        <!-- Pesan validasi -->
                        <div class="invalid-feedback">
                            Nama pasien wajib diisi.
                        </div>

                    </div>

                    <!-- ========================
                         Pilihan Jenis Kelamin
                         ======================== -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Jenis Kelamin
                        </label>

                        <select class="form-select" name="jenis_kelamin" required>

                            <option value="">
                                Pilih jenis kelamin
                            </option>

                            <option value="Laki-laki"
                            <?= ($edit && $data_edit['jenis_kelamin']=="Laki-laki") ? "selected" : ""; ?>>
                                Laki-laki
                            </option>

                            <option value="Perempuan"
                            <?= ($edit && $data_edit['jenis_kelamin']=="Perempuan") ? "selected" : ""; ?>>
                                Perempuan
                            </option>

                        </select>

                        <!-- Pesan validasi -->
                        <div class="invalid-feedback">
                            Silakan pilih jenis kelamin.
                        </div>

                    </div>

                    <!-- ========================
                         Input Tanggal Lahir
                         ======================== -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Tanggal Lahir
                        </label>

                        <input type="date"
                               class="form-control"
                               name="tgl_lahir"
                               value="<?= $edit ? $data_edit['tanggal_lahir'] : ''; ?>">

                    </div>

                    <!-- ========================
                         Pilihan Status Pasien
                         ======================== -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Status Pasien
                        </label>

                        <select class="form-select" name="status_pasien" required>

                            <option value="">
                                Pilih Status Pasien
                            </option>

                            <option value="Mahasiswa"
                            <?= ($edit && $data_edit['status_pasien']=="Mahasiswa") ? "selected" : ""; ?>>
                                Mahasiswa
                            </option>

                            <option value="Dosen"
                            <?= ($edit && $data_edit['status_pasien']=="Dosen") ? "selected" : ""; ?>>
                                Dosen
                            </option>

                            <option value="Pegawai"
                            <?= ($edit && $data_edit['status_pasien']=="Pegawai") ? "selected" : ""; ?>>
                                Pegawai
                            </option>

                            <option value="Umum"
                            <?= ($edit && $data_edit['status_pasien']=="Umum") ? "selected" : ""; ?>>
                                Umum
                            </option>

                        </select>

                        <!-- Pesan validasi -->
                        <div class="invalid-feedback">
                            Silakan pilih status pasien.
                        </div>

                    </div>

                    <!-- =========================
                         Input Fakultas / Unit
                         ========================= -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Fakultas / Unit
                        </label>

                        <input type="text"
                               class="form-control"
                               name="fakultas_unit"
                               placeholder="Contoh: FTK"
                               value="<?= $edit ? $data_edit['fakultas_unit'] : ''; ?>">

                    </div>

                    <!-- =========================
                         Input Nomor HP
                        ========================== -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            No. HP
                        </label>

                        <input type="text"
                               class="form-control"
                               name="no_hp"
                               placeholder="Masukkan nomor HP"
                               value="<?= $edit ? $data_edit['no_hp'] : ''; ?>">

                    </div>

                    <!-- =====================
                         Input Alamat
                         ===================== -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Alamat
                        </label>

                        <textarea class="form-control"
                                  rows="3"
                                  name="alamat"
                                  placeholder="Masukkan alamat"><?= $edit ? $data_edit['alamat'] : '';?></textarea>

                    </div>

                    <!--==============================================
                         Tombol Simpan / Update
                         Tombol berubah sesuai mode form.
                        ============================================== -->
                    <div class="d-grid gap-2">

                        <?php if($edit){ ?>

                            <!-- Tombol Update -->
                            <button class="btn btn-warning" type="submit" name="update">
                                Update Data
                            </button>

                        <?php } else { ?>

                            <!-- Tombol Simpan -->
                            <button class="btn btn-primary" type="submit" name="simpan">
                                Simpan Data
                            </button>

                        <?php } ?>

                        <!-- Tombol Reset -->
                        <button class="btn btn-outline-secondary" type="reset">
                            Reset Form
                        </button>

                    </div>

                </form>

            </div>

        </div>

        <!-- ======================================================
             KOLOM KANAN
             Menampilkan daftar seluruh data pasien yang tersimpan
             di dalam database.
             ====================================================== -->
        <div class="col-lg-8">

            <!-- Card untuk tabel data pasien -->
            <div class="card-modern p-4">

                <!-- ==================================================
                     Bagian Header Tabel
                     Berisi judul tabel dan fitur pencarian data.
                     ================================================== -->
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">

                    <!-- Judul tabel -->
                    <h5 class="fw-bold mb-0">
                        Daftar Pasien
                    </h5>

                    <!-- ==================================================
                         FORM SEARCH
                         Digunakan untuk mencari pasien berdasarkan
                         Nama, No. RM atau NIM/NIP.
                         ================================================== -->
                    <form method="GET" class="d-flex">

                        <!-- Input kata kunci pencarian -->
                        <input type="text"
                               name="cari"
                               class="form-control"
                               placeholder="Cari Nama, NIM/NIP atau No. RM"
                               value="<?= isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">

                        <!-- Tombol Cari -->
                        <button type="submit"
                                class="btn btn-primary ms-2">
                            Cari
                        </button>

                    </form>

                </div>

                <!-- ==================================================
                     TABEL DATA PASIEN
                     Menampilkan seluruh data pasien dari database.
                    =================================================== -->
                <div class="table-responsive">

                    <table class="table table-hover">

                        <!-- Header Kolom -->
                        <thead>

                            <tr>
                                <th>No</th>
                                <th>No. RM</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Unit</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

<?php

// Nomor urut tabel
$no = 1;

// =========================================
// FITUR SEARCH
// Jika user mengetik kata kunci,
// maka data akan difilter berdasarkan
// Nama, No. RM, atau NIM/NIP.
// =========================================
if(isset($_GET['cari'])){

    // Mengambil kata kunci pencarian
    $cari = $_GET['cari'];

    // Menjalankan Query pencarian data pasien
    $query = mysqli_query($conn,
    "SELECT * FROM pasien
    WHERE nama_pasien LIKE '%$cari%'
    OR no_rm LIKE '%$cari%'
    OR nim_nip LIKE '%$cari%'");

}else{

    // Jika tidak melakukan pencarian,
    // tampilkan seluruh data pasien.
    $query = mysqli_query($conn, "SELECT * FROM pasien");

}

// Mengecek apakah hasil  data ditemukan
if(mysqli_num_rows($query) > 0){

    // ===========================================
    // Menampilkan seluruh data pasien
    // satu per satu ke dalam tabel.
    // ===========================================
    while($row = mysqli_fetch_assoc($query)){
?>

<tr>

    <!-- Nomor urut -->
    <td><?= $no++; ?></td>

    <!-- Nomor Rekam Medis -->
    <td><?= $row['no_rm']; ?></td>

    <!-- Nama Pasien -->
    <td><?= $row['nama_pasien']; ?></td>

    <!-- Status Pasien -->
    <td>
        <span class="badge bg-primary"> <?= $row['status_pasien']; ?> </span>
    </td>

    <!-- Fakultas / Unit -->
    <td><?= $row['fakultas_unit']; ?></td>

    <!-- Nomor HP -->
    <td><?= $row['no_hp']; ?></td>

    <!-- =================================================
         Tombol Aksi
         Digunakan untuk Edit dan Hapus data pasien.
         ================================================= -->
    <td>
        <!-- Tombol Edit -->
        <a href="pasien.php?edit=<?= $row['id_pasien']; ?>"
           class="btn btn-sm btn-warning"> Edit </a>

        <!-- Tombol Hapus -->
        <a href="pasien.php?hapus=<?= $row['id_pasien']; ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('Yakin ingin menghapus data ini?')"> Hapus </a>
    </td>

</tr>

<?php
    }
}else{
?>

<!-- Data tidak ditemukan -->
 <tr>
    <td colspan="7" class="text-center text-danger"> Data pasien tidak ditemukan. </td>
 </tr>
<?php
}
?>
            </tbody>
               </table>
                 </div>
                   </div>
                     </div>
            </section>
</main>

<!--===========================================================
     FOOTER
     Menampilkan informasi nama sistem di bagian bawah halaman.
    =========================================================== -->
<footer class="footer text-center">
    Sistem Informasi Klinik Kampus Sederhana
</footer>

<!-- ======================================================
     JAVASCRIPT VALIDASI FORM
     Digunakan untuk mengaktifkan validasi Bootstrap
     sehingga form tidak dapat dikirim jika data wajib
     belum diisi.
     ====================================================== -->
<script>
(() => {
    'use strict';

    // Mengambil seluruh form yang menggunakan validasi Bootstrap
    const forms = document.querySelectorAll('.needs-validation');

    // Mengecek setiap form sebelum dikirim
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {

            // Jika ada input yang belum valid,
            // form tidak akan dikirim.
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            // Menampilkan tampilan validasi Bootstrap
            form.classList.add('was-validated');

        }, false);
    });

})();
</script>

<!-- Memanggil file JavaScript Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>