<?php
session_start();

if (!isset($_SESSION['username'])) {
    // User is already logged in, redirect to welcome page
    header("Location: login.php");

} 

if(!isset($_SESSION["counter"])){
    $_SESSION["counter"] = 1;
} else {
    $_SESSION["counter"]++;
}

if(!isset($_SESSION["daftar"])){
    $_SESSION["daftar"] = []; 
}

if(isset($_POST["nama"]) && isset($_POST["umur"])){
    $daftar = [
        "nama" => $_POST["nama"],
        "umur" => $_POST["umur"]
    ];

    $_SESSION["daftar"][] = $daftar;
} 

?>
<html>
    <head>
        <title>::Login Page::</title>
        <style type="text/css">
            body{
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-size: cover;
            }
            table{
                 background-color: 4B0000;
                 border: 3px solid grey;
                 padding: 20px;
                 border-radius: 10px;
                 box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
                 font-family: Arial, Helvetica, sans-serif;
                 color: white;
            }
            td{
                padding: 5px;
            }
            button{
                background-color: 33B249;
                padding: 10px;
                border-radius: 5px;
            }
            #logout {
                background-color: FF0000;
                cursor: pointer;
            }
        #mute-button {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 10px;
                background-color: 4B0000;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                z-index: 1000;
             }
          #pause-button {
                position: fixed;
                top: 70px;
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
        </style>
    </head>
    <body>
         <video autoplay loop muted playsinline style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: -1;">
              <source src="https://cdn.pixabay.com/video/2020/03/13/33628-397860881_large.mp4" type="video/mp4">
         </video>
        <h1 style="color: White; text-shadow: 2px 2px 4px black;">
            <?php echo "Selamat datang " . $_SESSION['username'] . " Ke-" . $_SESSION["counter"] ?></h1>
        <form action="dashboard.php" method="post">
         <table>
            <tr>
                <td colspan="2" style="text-align: center;"><strong> DAFTAR</strong></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td><input type="text" name="nama" /></td>
            </tr>
            <tr>
                <td>Umur</td>
                <td><input type="number" name="umur" /></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <button type="submit" >SUBMIT</button>
                    <a href="logout.php">
                        <button id="logout" type="button" >LOGOUT</button>
                    </a>
                </td>
            </tr>
        </table>
    </form>
        <table border="1" style="background-color: 4B0000; color: white; border-color: A9A9A9; margin-top: 20px;">
            <tr>
                <td>Nama</td>
                <td>Umur</td>
                <td>Keterangan</td>
            </tr>
            <?php foreach($_SESSION["daftar"] as $daftar_item): ?>
            <tr>
                <td><?php echo $daftar_item["nama"] ?></td>
                <td><?php echo $daftar_item["umur"] ?></td>
                <td>
                    <?php
                    switch (true) {
                    case ($daftar_item["umur"] >= 0 && $daftar_item["umur"] <= 13):
                    echo "Anak";
                    break;
                    case ($daftar_item["umur"] >= 14 && $daftar_item["umur"] <= 25):
                    echo "Remaja";
                    break;
                    case ($daftar_item["umur"] > 25 && $daftar_item["umur"] <= 40):
                    echo "Dewasa";
                    break;
                    case ($daftar_item["umur"] > 40 && $daftar_item["umur"] <= 60):
                    echo "Tua";
                    break;
                    case ($daftar_item["umur"] > 60):
                    echo "Lansia";
                    break;
                    }
                    ?>
            </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </form>
    <audio id="bg-music" src="Sound/Backsound Dashboard.mp3" autoplay loop></audio>
    <button id="mute-button" onclick="toggleMusic()">🔊</button>
    <button id="pause-button" style="position: fixed; top: 70px; right: 20px;">⏸️</button>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
                const music = document.getElementById("bg-music");
                const muteButton = document.getElementById("mute-button");
                const pauseButton = document.getElementById("pause-button");

                if (localStorage.getItem("musicMuted") === "true") {
                    music.muted = true;
                    muteButton.innerHTML = "🔇";
                }

                function toggleMusicMute() {
                    music.muted = !music.muted;
                    muteButton.innerHTML = music.muted ? "🔇" : "🔊";
                    localStorage.setItem("musicMuted", music.muted.toString());
                }

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
    </script>
    </body>
</html> 