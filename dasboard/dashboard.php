<?php
include '../koneksi/db.php';

$query = mysqli_query($koneksi, "SELECT * FROM toko ORDER BY id DESC");
$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);


$totalPendapatan  = mysqli_query($koneksi, "SELECT SUM(harga) AS total FROM toko");
$pendapatan = mysqli_fetch_assoc($totalPendapatan);

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

        <div class="utama">
            <h1 class="title">Selamat Datang</h1>

            <section class="konten-utama">
                <div class="konten">
                    <h3 class="jml-produk">Jumlah Produk</h3>
                    <div class="box">
                        <h1><?php echo count($rows); ?></h1>
                    </div>
                </div>
                <div class="konten">
                    <h3 class="jml-produk">total pendapatan</h3>
                    <div class="box">
                        <h1><?php echo 'Rp.'. number_format($pendapatan['total']); ?></h1>
                    </div>
                </div>
            </section>
        </div>
    </div>


</body>

</html>