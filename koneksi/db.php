<?php
$name = "localhost";
$user = "root";
$pass = "1234";
$db = "toko_elektronik";

$koneksi =mysqli_connect($name,$user,$pass,$db);

    
if(!$koneksi){
    die("koneksi gagal". mysqli_connect_error());
} else {
    // echo "koneksi berhasil";
}