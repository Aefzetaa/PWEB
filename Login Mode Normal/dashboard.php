<?php
session_start(); // Memulai session untuk menyimpan data pengguna

// Jika belum login, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Menyimpan jumlah kunjungan ke halaman ini dalam session
if (!isset($_SESSION["counter"])) {
    $_SESSION["counter"] = 1;
} else {
    $_SESSION["counter"]++;
}

// Inisialisasi array 'daftar' jika belum ada
if (!isset($_SESSION["daftar"])) {
    $_SESSION["daftar"] = [];
}

// Menangani form POST untuk menambah data baru
if (isset($_POST["nama"]) && isset($_POST["umur"])) {
    $daftar = [
        "nama" => $_POST["nama"],
        "umur" => $_POST["umur"]
    ];
    $_SESSION["daftar"][] = $daftar; // Tambahkan data ke array daftar
}

// Data default untuk form (jika tidak sedang edit)
$edit_daftar = [
    "nama" => "",
    "umur" => "",
];

// Menentukan target aksi form: default dashboard.php, bisa berubah ke Edit.php saat mengedit
$target = "dashboard.php";

// Jika ada parameter GET Key (edit mode), isi form dengan data yang ingin diedit
if (isset($_GET["Key"])) {
    $target = "Alat Bantu/Tools/Edit.php?Key=" . $_GET["Key"];
    if ($_GET["Key"] != null) {
        $index = $_GET["Key"];
        $edit_daftar = $_SESSION["daftar"][$index];
    }
}
