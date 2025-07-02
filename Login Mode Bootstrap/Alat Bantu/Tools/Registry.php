<?php
session_start();
$dataFile = __DIR__ . '/HistoryRegistry.dat';

function getUsers($file)
{
    if (!file_exists($file)) return [];
    $json = file_get_contents($file);
    $users = json_decode($json, true);
    return is_array($users) ? $users : [];
}
function saveUsers($file, $users)
{
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

$message = '';
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if ($username && $password) {
        $users = getUsers($dataFile);
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $message = "Username sudah terdaftar!";
                break;
            }
        }
        if (!$message) {
            $users[] = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ];
            saveUsers($dataFile, $users);
            $message = "Akun berhasil dibuat! Silakan login.";
            $success = true;
            $updateFile = __DIR__ . '/HistoryUpdate.dat';
            file_put_contents($updateFile, serialize([
                'username' => $username,
                'password' => end($users)['password']
            ]));
        }
    } else {
        $message = "Username dan password wajib diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrasi Akun</title>
    <link rel="stylesheet" href="../../Tools/Bootsrap 5/css/bootstrap.min.css" />
    <style>
        html,
        body {
            height: 100%;
            min-height: 100vh;
            overflow: hidden;
        }

        body {
            min-height: 100vh;
            background: #23272f;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .reg-card {
            background: linear-gradient(90deg, #23272f 0%, #4f8cff 100%);
            color: #f4f6fb;
            border-radius: 18px;
            box-shadow:
                0 8px 32px 0 rgba(31, 38, 135, 0.18),
                0 1.5px 8px 0 rgba(0, 0, 0, 0.18);
            border: 1.5px solid rgba(80, 120, 255, 0.18);
            padding: 32px 36px 28px 36px;
            min-width: 340px;
            width: 100%;
            max-width: 600px;
            margin: 0;
            margin-top: 45px;
            border: none;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .reg-icon {
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .reg-icon-circle {
            background: #e0e6ed;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            margin-bottom: 10px;
        }

        .reg-icon-circle svg {
            width: 32px;
            height: 32px;
            color: #4f8cff;
        }

        .header-title {
            font-size: 1.1rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 12px;
            color: #f4f6fb;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 8px #4f8cff22, 0 1px 0 #222;
        }

        .form-label {
            font-size: 0.98rem;
            font-weight: 600;
            color: #e0e6ed;
            letter-spacing: 0.2px;
            margin-bottom: 2px;
        }

        .form-control {
            background: #23272f;
            color: #f4f6fb;
            border: 1.2px solid #4f8cff;
            border-radius: 7px;
            font-size: 0.98rem;
            padding: 8px 10px;
            margin-bottom: 10px;
            box-shadow: 0 1px 4px #4f8cff11;
            transition: border 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .form-control:focus {
            background: #23272f;
            color: #fff;
            border-color: #4f8cff;
            box-shadow: 0 0 0 2px #4f8cff33;
        }

        .btn-primary {
            border-radius: 7px;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            padding: 9px 0;
            background: linear-gradient(90deg, #4f8cff 0%, #357ae8 100%);
            border: none;
            box-shadow: 0 2px 8px #4f8cff22;
            transition: background 0.2s, transform 0.1s;
            color: #e0e6ed;
            min-width: 90px;
            width: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 12px;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(90deg, #357ae8 0%, #4f8cff 100%);
            transform: scale(1.03);
        }

        .btn-link.center-back {
            color: #e0e6ed !important;
            text-decoration: underline;
            font-size: 0.98rem;
            font-weight: 500;
            letter-spacing: 0.2px;
            margin-top: 14px;
            display: block;
            text-align: center;
        }

        .btn-link.center-back:hover,
        .btn-link.center-back:focus {
            color: #fff !important;
            text-decoration: underline;
        }

        .alert {
            font-size: 0.98rem;
            padding: 8px 12px;
            margin-bottom: 12px;
            border-radius: 7px;
            text-align: center;
        }

        @media (max-width: 400px) {
            .reg-card {
                padding: 12px 2vw 12px 2vw;
                min-width: 0;
                max-width: 98vw;
            }

            .header-title {
                font-size: 0.98rem;
            }

            .form-label,
            .btn-primary,
            .alert {
                font-size: 0.92rem;
            }
        }

        input:-webkit-autofill,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #23272f inset !important;
            box-shadow: 0 0 0 1000px #23272f inset !important;
            -webkit-text-fill-color: #f4f6fb !important;
            color: #f4f6fb !important;
            caret-color: #f4f6fb !important;
            border-color: #4f8cff !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>

<body>
    <div class="reg-card shadow">
        <div class="reg-icon-circle" style="display: flex; justify-content: center; align-items: center;">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.967 0 8 1.484 8 4.444V20H4v-3.556C4 13.484 9.033 12 12 12zm0-2c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z" />
            </svg>
        </div>
        <div class="header-title">Buat Akun Baru</div>
        <?php if ($message): ?>
            <div class="alert alert-<?= $success ? 'success' : 'danger' ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off" style="width:100%">
            <div class="mb-2">
                <label class="form-label" for="username">Nama Pengguna</label>
                <input type="text" id="username" name="username" class="form-control" required autocomplete="off" style="width:100%">
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="off" style="width:100%">
            </div>
            <button class="btn btn-primary w-100" type="submit">Daftar</button>
            <a href="../../Login.php" class="btn btn-link center-back w-100 mt-3">Sudah punya akun? Login</a>
        </form>
    </div>
    <script src="../../Tools/Bootsrap 5/js/bootstrap.bundle.min.js"></script>
</body>

</html>