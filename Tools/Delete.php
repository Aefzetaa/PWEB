<?php
session_start(); // Memulai session agar bisa mengakses data $_SESSION

$index = $_GET["Key"]; // Mengambil indeks data yang ingin dihapus dari parameter URL 'Key'

unset($_SESSION["daftar"][$index]); // Menghapus elemen pada array 'daftar' berdasarkan indeks tersebut

header("Location: ../dashboard.php"); // Mengarahkan kembali ke halaman dashboard setelah proses hapus
?>