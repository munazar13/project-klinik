<?php
include "koneksi.php";
include "session.php";
/* ===============================
   SIMPAN DATA
=================================*/
if(isset($_POST['simpan'])){

    $kode_obat  = $_POST['kode_obat'];
    $nama_obat  = $_POST['nama_obat'];
    $kategori   = $_POST['kategori'];
    $satuan     = $_POST['satuan'];
    $stok       = $_POST['stok'];
    $keterangan = $_POST['keterangan'];

    if(empty($kode_obat) || empty($nama_obat)){

        echo "<script>alert('Kode dan Nama Obat wajib diisi');</script>";

    }else{

        $simpan = mysqli_query($conn,"INSERT INTO obat
        (kode_obat,nama_obat,kategori,satuan,stok,keterangan)
        VALUES
        ('$kode_obat','$nama_obat','$kategori','$satuan','$stok','$keterangan')");

        if($simpan){
            header("Location: obat.php?pesan=tambah");
            exit;
        }
    }
}

/* ===============================
   UPDATE DATA
=================================*/
if(isset($_POST['update'])){

    $id         = $_POST['id'];
    $kode_obat  = $_POST['kode_obat'];
    $nama_obat  = $_POST['nama_obat'];
    $kategori   = $_POST['kategori'];
    $satuan     = $_POST['satuan'];
    $stok       = $_POST['stok'];
    $keterangan = $_POST['keterangan'];

    $update = mysqli_query($conn,"UPDATE obat SET

        kode_obat='$kode_obat',
        nama_obat='$nama_obat',
        kategori='$kategori',
        satuan='$satuan',
        stok='$stok',
        keterangan='$keterangan'

        WHERE id_obat='$id'
    ");

    if($update){
        header("Location: obat.php?pesan=update");
        exit;
    }
}

/* ===============================
   HAPUS DATA
=================================*/
if(isset($_GET['hapus'])){

    $id=$_GET['hapus'];

    mysqli_query($conn,"DELETE FROM obat WHERE id_obat='$id'");

    header("Location: obat.php?pesan=hapus");
    exit;
}

/* ===============================
   MODE EDIT
=================================*/

$edit=false;

if(isset($_GET['edit'])){

    $edit=true;

    $id=$_GET['edit'];

    $ambil=mysqli_query($conn,"SELECT * FROM obat WHERE id_obat='$id'");

    $data_edit=mysqli_fetch_assoc($ambil);

}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Obat</title>

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
                    <a href="obat.php" class="active">Data Obat</a>
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
    <?php if(isset($_GET['pesan'])){ ?>
    <?php if($_GET['pesan']=="tambah"){ ?>
    <div class="alert alert-success mt-3">
    Data obat berhasil ditambahkan.
    </div>
    <?php } ?>
    <?php if($_GET['pesan']=="update"){ ?>
    <div class="alert alert-warning mt-3">
    Data obat berhasil diupdate.
    </div>
    <?php } ?>
    <?php if($_GET['pesan']=="hapus"){ ?>
    <div class="alert alert-danger mt-3">
    Data obat berhasil dihapus.
    </div>
    <?php } ?>
    <?php } ?>

    <section class="page-header">
        <h1 class="fw-bold mb-2">Data Obat</h1>
        <p class="mb-0">Kelola data obat klinik kampus sebagai data pendukung pencatatan kunjungan.</p>
    </section>

    <section class="row g-4">
        <div class="col-lg-4">
            <div class="card-modern p-4">
                <h5 class="fw-bold mb-3">Form Data Obat</h5>

                <!-- Form dengan class validasi -->
                <form action="" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="id" value="<?= $edit ? $data_edit['id_obat'] : ''; ?>">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Obat</label>
                        <input type="text"
                        name="kode_obat"
                        class="form-control"
                        placeholder="Contoh: OB001"
                        required
                        value="<?= $edit ? $data_edit['kode_obat'] : ''; ?>">
                        </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Obat</label>
                        <input type="text"
                        name="nama_obat"
                        class="form-control"
                        placeholder="Masukkan nama obat"
                        required
                        value="<?= $edit ? $data_edit['nama_obat'] : ''; ?>">
                    </div>
                    

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <input type="text"
                        name="kategori"
                        class="form-control"
                        placeholder="Contoh: Analgesik, Antibiotik"
                        required
                        value="<?= $edit ? $data_edit['kategori'] : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Satuan</label>
                        <select name="satuan" class="form-select" required>
                        <option value="">Pilih satuan</option>

                        <option value="Tablet"
                        <?= ($edit && $data_edit['satuan']=="Tablet")?"selected":"";?>>
                        Tablet
                        </option>

                        <option value="Kapsul"
                        <?= ($edit && $data_edit['satuan']=="Kapsul")?"selected":"";?>>
                        Kapsul
                        </option>

                        <option value="Botol"
                        <?= ($edit && $data_edit['satuan']=="Botol")?"selected":"";?>>
                        Botol
                        </option>

                        <option value="Sachet"
                        <?= ($edit && $data_edit['satuan']=="Sachet")?"selected":"";?>>
                        Sachet
                        </option>

                        <option value="Tube"
                        <?= ($edit && $data_edit['satuan']=="Tube")?"selected":"";?>>
                        Tube
                        </option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Stok</label>
                        <input type="number"
                        name="stok"
                        class="form-control"
                        placeholder="Contoh: 100"
                        required
                        value="<?= $edit ? $data_edit['stok'] : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea
                        name="keterangan"
                        class="form-control"
                        placeholder="Masukkan keterangan obat"
                        rows="3"><?= $edit ? $data_edit['keterangan'] : ''; ?></textarea>
                    </div>

                    <div class="d-grid gap-2">
                    <?php 
                    if($edit){ 
                    ?>
                    <button class="btn btn-warning"
                    name="update"
                    type="submit">
                    Update Data
                    </button>

                    <?php }else{ ?>

                    <button class="btn btn-primary"
                    name="simpan"
                    type="submit">
                    Simpan Obat
                    </button>

                    <?php } ?>

                    <button class="btn btn-outline-secondary"
                    type="reset">
                    Reset Form
                    </button>

                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card-modern p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                    <h5 class="fw-bold mb-0">Daftar Obat</h5>
                    <!-- Kolom cari tanpa required -->
                    <form method="GET" class="d-flex">

                    <input
                    type="text"
                    name="cari"
                    class="form-control"
                    placeholder="Cari kode atau nama obat"
                    value="<?= isset($_GET['cari'])?$_GET['cari']:'';?>">

                    <button class="btn btn-primary ms-2">
                    Cari
                    </button>

                    </form>
                </div>

                <div class="table-responsive">
                    <!-- Tabel dengan ID khusus -->
                    <table id="tableObat" class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Obat</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no=1;
                        if(isset($_GET['cari'])){
                        $cari=$_GET['cari'];
                        $query=mysqli_query($conn,"
                        SELECT * FROM obat
                        WHERE kode_obat LIKE '%$cari%'
                        OR nama_obat LIKE '%$cari%'
                        ");

                        }else{
                        $query=mysqli_query($conn,"SELECT * FROM obat");
                        }

                        if(mysqli_num_rows($query)>0){
                        while($row=mysqli_fetch_assoc($query)){
                        ?>

                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['kode_obat']; ?></td>
                            <td><?= $row['nama_obat']; ?></td>
                            <td><?= $row['kategori']; ?></td>
                            <td><?= $row['satuan']; ?></td>
                            <td><?= $row['stok']; ?></td>
                            <td>

                            <a href="obat.php?edit=<?= $row['id_obat'];?>" class="btn btn-warning btn-sm">
                            Edit
                            </a>

                            <a href="obat.php?hapus=<?= $row['id_obat'];?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
                            Hapus
                            </a>
                            </td>
                        </tr>
                        <?php
                        }
                        }else{
                        ?>

                        <tr>
                        <td colspan="7" class="text-center">
                        Data tidak ditemukan
                        </td>
                        </tr>

                        <?php
                        }
                        ?>

                        </tbody>
                    </table>
                </div>

                <p class="text-muted mb-0 small">Tombol hapus akan memunculkan konfirmasi dan menghapus baris data secara tampilan.</p>
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

