<?php
session_start(); // Memulai atau melanjutkan sesi

$index = $_GET["Key"]; // Mengambil index data yang akan diubah dari URL (GET)

// Memperbarui data 'nama' dan 'umur' pada array 'daftar' berdasarkan index
$_SESSION["daftar"]["$index"] = [
    "nama" => $_POST["nama"],   // Mengambil data nama dari input POST
    "umur" => $_POST["umur"]    // Mengambil data umur dari input POST
];

header("Location: ../../dashboard.php"); // Mengarahkan kembali ke halaman dashboard setelah update
