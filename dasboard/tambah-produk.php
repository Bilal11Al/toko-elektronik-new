<?php
include '../koneksi/db.php';
session_start();

$edit = isset($_GET['edit']) ? $_GET['edit'] : null;
$query = mysqli_query($koneksi, "SELECT * FROM toko WHERE id = '$edit'");
$rowedit = mysqli_fetch_assoc($query);
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];
    $gambar = null;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $name_foto = $_FILES['gambar']['name'];
        $tmp_foto = $_FILES['gambar']['tmp_name'];
        $size_foto = $_FILES['gambar']['size'];
        $type_foto = mime_content_type($tmp_foto);

        $file = ['image/jpeg', 'image/png', 'image/jpg'];

        if (in_array($type_foto, $file)) {
            $path_upload = "uploads/";
            if (!is_dir($path_upload)) {
                mkdir($path_upload, 0777, true);
            }
            $fileunique = time() . '-' . basename($name_foto);
            $file_upload = $path_upload . $fileunique;

            if (move_uploaded_file($tmp_foto, $file_upload)) {
                $gambar = $fileunique;
            } else {
                echo "gagal upload";
                exit;
            }
        } else {
            echo "<script>alert('file tidak sesuai'); document.location.href = 'tambah-produk.php';</script>";
            exit;
        }
    }
    if ($edit) {
        if (!$gambar) {
            $gambar = $rowedit['gambar'];
        } else {
            // Kalau upload baru, hapus gambar lama
            if ($rowedit['gambar'] && file_exists('uploads/' . $rowedit['gambar'])) {
                unlink('uploads/' . $rowedit['gambar']);
            }
        }
        $update = mysqli_query($koneksi, "UPDATE toko SET name = '$nama', harga = '$harga', kategori = '$kategori', gambar = '$gambar' WHERE id = '$edit'");
        if ($update) {
            echo "<script>alert('data berhasil di update'); document.location.href = 'produk.php';</script>";
            exit;
        } else {
            die("Gagal update: " . mysqli_error($koneksi));
        }
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO toko (name, harga, kategori, gambar) VALUES ('$nama', '$harga', '$kategori', '$gambar')");
        if ($insert) {
            echo "<script>alert('data berhasil di tambahkan'); document.location.href = 'produk.php';</script>";
        } else {
            die("Gagal insert: " . mysqli_error($koneksi));
        }
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Ambil dulu data gambar sebelum dihapus
    $result = mysqli_query($koneksi, "SELECT gambar FROM toko WHERE id = '$id'");
    if ($result && mysqli_num_rows($result) > 0) {
        $rowdelete = mysqli_fetch_assoc($result);
        $gambar = $rowdelete['gambar'];

        // Hapus file gambar dari folder
        if (!empty($gambar) && file_exists("uploads/$gambar")) {
            unlink("uploads/$gambar");
        }

        // Baru hapus dari database
        $hapus = mysqli_query($koneksi, "DELETE FROM toko WHERE id = '$id'");
    }

    header("Location: produk.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../inc/head.php'; ?>
</head>

<body>

    <div class="container">
        <div class="card mt-5">
            <h1 class="mt-3 ms-3">
                <?php echo isset($_GET['edit']) ? 'edit ' : 'Tambah '; ?> Produk
            </h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3 ms-3">
                    <label for="" class="form-label"> Nama</label>
                    <input type="text" class="form-control" name="nama" required value="<?= ($edit) ? $rowedit['name'] : ''; ?>">
                </div>
                <div class="mb-3 ms-3">
                    <label for="" class="form-label"> harga</label>
                    <input type="number" class="form-control" name="harga" required value="<?= ($edit) ? $rowedit['harga'] : ''; ?>">
                </div>
                <div class="mb-3 ms-3">
                    <label for="" class="form-label"> katagori</label>
                    <input type="text" class="form-control" name="kategori" required value="<?= ($edit) ? $rowedit['kategori'] : ''; ?>">
                </div>
                <div class="mb-3 ms-3">
                    <label for="" class="form-label"> Gambar</label>
                    <input type="file" class="form-control" name="gambar">
                    <?php if ($edit && !empty($rowedit['gambar'])): ?>
                        <img src="uploads/<?= ($rowedit['gambar']) ?>" alt="gambar" width="100" class="mt-3">
                    <?php endif; ?>
                </div>
                <button type="submit" name="simpan" class="btn btn-primary mt-3 ms-3 mb-3"> Simpan</button>
                <a href="produk.php" class="btn btn-danger m-3"> kembali</a>
            </form>
        </div>
    </div>
</body>

</html>