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
    $target = "Tools/Edit.php?Key=" . $_GET["Key"];
    if ($_GET["Key"] != null) {
        $index = $_GET["Key"];
        $edit_daftar = $_SESSION["daftar"][$index];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>::Login Page::</title>
    <style>
        /* Style utama halaman */
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-size: cover;
        }

        /* Tampilan tabel input & output */
        table {
            background-color: #4B0000;
            border: 3px solid grey;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            font-family: Arial, Helvetica, sans-serif;
            color: white;
        }

        td {
            padding: 5px;
        }

        /* Tombol submit & logout */
        button {
            background-color: #33B249;
            padding: 10px;
            border-radius: 5px;
        }

        #logout {
            background-color: #FF0000;
            cursor: pointer;
        }

        /* Tombol musik & pause */
        #mute-button,
        #pause-button {
            position: fixed;
            right: 20px;
            padding: 10px;
            background-color: #4B0000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        #mute-button {
            top: 20px;
        }

        #pause-button {
            top: 70px;
        }
    </style>
</head>
<body>
    <!-- Video latar belakang -->
    <video autoplay loop muted playsinline style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: -1;">
        <source src="https://cdn.pixabay.com/video/2020/03/13/33628-397860881_large.mp4" type="video/mp4">
    </video>

    <!-- Judul sambutan -->
    <h1 style="color: white; text-shadow: 2px 2px 4px black;">
        <?php echo "Selamat datang " . $_SESSION['username'] . " Ke-" . $_SESSION["counter"]; ?>
    </h1>

    <!-- Form input (bisa untuk tambah atau edit tergantung $target) -->
    <form action="<?php echo $target; ?>" method="post">
        <table>
            <tr>
                <td colspan="2" style="text-align: center;"><strong>DAFTAR</strong></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td><input type="text" name="nama" value="<?php echo $edit_daftar["nama"]; ?>" /></td>
            </tr>
            <tr>
                <td>Umur</td>
                <td><input type="number" name="umur" value="<?php echo $edit_daftar["umur"]; ?>" /></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <button type="submit">SUBMIT</button>
                    <a href="logout.php"><button id="logout" type="button">LOGOUT</button></a>
                </td>
            </tr>
        </table>
    </form>

    <!-- Tabel menampilkan semua data yang telah ditambahkan -->
    <table border="1" style="background-color: #4B0000; color: white; border-color: #A9A9A9; margin-top: 20px;">
        <tr>
            <td>Name</td>
            <td>Age</td>
            <td>Category</td>
            <td>Edit</td>
            <td>Delete</td>
        </tr>
        <?php foreach ($_SESSION["daftar"] as $index => $daftar): ?>
            <tr>
                <td><?php echo htmlspecialchars($daftar["nama"]); ?></td>
                <td><?php echo htmlspecialchars($daftar["umur"]); ?></td>
                <td>
                    <?php
                        // Menentukan kategori umur berdasarkan nilai numerik
                        $umur = $daftar['umur'];
                        if ($umur <= 10) {
                            echo "Anak-anak";
                        } elseif ($umur <= 20) {
                            echo "Remaja";
                        } elseif ($umur <= 40) {
                            echo "Dewasa";
                        } elseif ($umur < 60) {
                            echo "Tua";
                        } else {
                            echo "Lansia";
                        }
                    ?>
                </td>
                <td style="text-align: center;">
                    <!-- Link edit -->
                    <a href="dashboard.php?Key=<?php echo $index; ?>" style="color: #00BFFF; font-size: 18px; text-decoration: none;">✏</a>
                </td>
                <td style="text-align: center;">
                    <!-- Link hapus -->
                    <a href="Tools/Delete.php?Key=<?php echo $index; ?>" style="color: red; font-size: 20px; text-decoration: none;">X</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Musik latar belakang -->
    <audio id="bg-music" src="Sound/Backsound Dashboard.mp3" autoplay loop></audio>
    <button id="mute-button">🔊</button>
    <button id="pause-button">⏸️</button>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const music = document.getElementById("bg-music");
            const muteButton = document.getElementById("mute-button");
            const pauseButton = document.getElementById("pause-button");

            // Cek apakah sebelumnya musik disetel mute oleh user
            if (localStorage.getItem("musicMuted") === "true") {
                music.muted = true;
                muteButton.innerHTML = "🔇";
            }

            // Fungsi untuk mematikan/menyalakan suara
            function toggleMusicMute() {
                music.muted = !music.muted;
                muteButton.innerHTML = music.muted ? "🔇" : "🔊";
                localStorage.setItem("musicMuted", music.muted.toString());
            }

            // Fungsi untuk pause atau lanjutkan musik
            function toggleMusicPause() {
                if (music.paused) {
                    music.play();
                    pauseButton.innerHTML = "⏸️";
                } else {
                    music.pause();
                    pauseButton.innerHTML = "▶️";
                }
            }

            muteButton.onclick = toggleMusicMute;
            pauseButton.onclick = toggleMusicPause;
        });
    </script>
</body>
</html>