<?php
include '../koneksi/db.php';
session_start();

$query = mysqli_query($koneksi, "SELECT * FROM toko ORDER BY id DESC");
$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);

if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];
    $caridata = mysqli_query($koneksi, "SELECT * FROM toko WHERE name LIKE '%$cari%'  OR kategori LIKE '%$cari%'");
    $rows = mysqli_fetch_all($caridata, MYSQLI_ASSOC); 
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM toko ORDER BY id DESC");
    $rows = mysqli_fetch_all($query, MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../inc/head.php'; ?>
</head>

<body>
    <div class="wrapper">
        <nav class="navbar">
            <div class="content">
                <h1 class="logo">Toko Elektronik</h1>
                <ul>
                    <li><a href="dashboard.php"><i class="fa-solid fa-chart-simple"></i> Dashboard</a></li>
                    <li><a href="produk.php"><i class="fa-solid fa-box"></i> Produk</a></li>
                </ul>
            </div>
        </nav>
        <div class="produk">
            <a href="tambah-produk.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus fa-lg"></i> Tambah Produk</a>
            <form action="" method="get">
                <div class="d-flex justify-content-end mb-3">
                    <input type="text" name="cari" class="form-control" placeholder="Cari Produk">
                    <button type="submit" class="btn btn-primary me-2 ms-2">Cari</button>
                    <a href="produk.php" class="btn btn-danger">reset</a>
                </div>
            </form>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>gambar</th>
                        <th>Harga</th>
                        <th>kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <?php if (empty($rows)) : ?>
                    <div class="alert alert-danger" role="alert">
                        tidak ada data
                    </div>
                <?php endif; ?>
                <tbody>
                    <?php foreach ($rows as $key => $row) : ?>
                        <tr>
                            <td><?= $key + 1; ?></td>
                            <td><?= $row['name']; ?></td>
                            <td><img src="uploads/<?= $row['gambar']; ?>" alt="gambar" width="100"></td>
                            <td><?= 'Rp' . number_format($row['harga']); ?></td>
                            <td><?= $row['kategori']; ?></td>
                            <td>
                                <a href="tambah-produk.php?edit=<?= $row['id']; ?>" class="btn btn-primary">Edit</a>
                                <a href="tambah-produk.php?hapus=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</body>

</html>