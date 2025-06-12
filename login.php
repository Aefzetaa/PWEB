<?php
session_start();
echo "<script>
    localStorage.removeItem('musicTime');
    localStorage.removeItem('musicMuted');
</script>";
if (isset($_POST['username']) && isset($_POST['password'])) {
    // User is already logged in, redirect to welcome page
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username == "Nwraaq" && $password == "1") {
        // Set session variable
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
         exit();
 
    }
}
?>
<html>
    <head>
        <title>::Login Page::</title>
        <style type="text/css">
            body{
                display: flex;
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
                background-color: FF0000;
                    color: white;
                    padding: 10px;
                    border-radius: 5px;
                    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
                    border: none;
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
                <td colspan="2"><input type="checkbox" /> Ingatkan saya</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;"><button type="submit" >SUBMIT</button></td>
            </tr>
        </table>
        </form>
     <audio id="bg-music" src="Sound/Backsound Login.mp3" autoplay loop></audio>
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
    </body>
</html>
