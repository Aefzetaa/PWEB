<?php
session_start(); // Memulai atau melanjutkan sesi pengguna

// Mengecek apakah form login telah dikirim
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username']; // Menangkap input username
    $password = $_POST['password']; // Menangkap input password

    // Validasi sederhana: jika username & password cocok
    if ($username === "Nwraaq" && $password === "1") {
        $_SESSION['username'] = $username; // Menyimpan username ke sesi
        header("Location: dashboard.php"); // Mengarahkan ke dashboard
        exit(); // Menghentikan eksekusi
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>::Login Page::</title>
    <style>
        /* Mengatur tampilan latar dan posisi form login */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-size: cover;
        }

        /* Desain tabel form */
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

        /* Tombol submit */
        button {
            background-color: #FF0000;
            color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            border: none;
            cursor: pointer;
        }

        /* Tombol mute dan pause audio */
        #mute-button, #pause-button {
            position: fixed;
            right: 20px;
            padding: 10px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        #mute-button {
            top: 20px;
            background-color: #4B0000;
        }

        #pause-button {
            top: 70px;
            background-color: #4B0000;
        }
    </style>

    <script>
        // Menghapus data localStorage terkait musik setiap kali halaman dimuat
        localStorage.removeItem('musicTime');
        localStorage.removeItem('musicMuted');
    </script>
</head>
<body>
    <!-- Video latar belakang -->
    <video autoplay loop muted playsinline style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: -1;">
        <source src="https://cdn.pixabay.com/video/2020/03/13/33628-397860881_large.mp4" type="video/mp4">
    </video>

    <!-- Form login -->
    <form action="login.php" method="post">
        <table>
            <tr>
                <td colspan="2" style="text-align: center;"><strong>L O G I N</strong></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username" /></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" /></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="checkbox" /> Ingatkan saya <!-- Tidak berfungsi, hanya visual -->
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <button type="submit">SUBMIT</button>
                </td>
            </tr>
        </table>
    </form>

    <!-- Background music -->
    <audio id="bg-music" src="Sound/Backsound Login.mp3" autoplay loop></audio>

    <!-- Tombol mute/pause musik -->
    <button id="mute-button">🔊</button>
    <button id="pause-button">⏸️</button>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const music = document.getElementById("bg-music");
            const muteButton = document.getElementById("mute-button");
            const pauseButton = document.getElementById("pause-button");

            // Jika musik disetel mute sebelumnya, aktifkan mute saat ini
            if (localStorage.getItem("musicMuted") === "true") {
                music.muted = true;
                muteButton.innerHTML = "🔇";
            }

            // Fungsi untuk menyalakan/mematikan mute
            function toggleMusicMute() {
                music.muted = !music.muted;
                muteButton.innerHTML = music.muted ? "🔇" : "🔊";
                localStorage.setItem("musicMuted", music.muted.toString());
            }

            // Fungsi untuk pause atau play musik
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