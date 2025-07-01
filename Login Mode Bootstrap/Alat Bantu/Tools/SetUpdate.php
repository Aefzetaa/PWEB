<?php
session_start();

$dataFile = __DIR__ . '/HistoryUpdate.dat';

function getUserData($file)
{
    if (!file_exists($file)) {
        return ['username' => 'Nwraaq', 'password' => password_hash('1', PASSWORD_DEFAULT)];
    }
    $data = file_get_contents($file);
    return unserialize($data);
}

function saveUserData($file, $username, $password)
{
    $data = [
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ];
    file_put_contents($file, serialize($data));
}

$user = getUserData($dataFile);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUser = trim($_POST['username']);
    $newPass = $_POST['password'];
    if ($newUser && $newPass) {
        saveUserData($dataFile, $newUser, $newPass);
        $message = "Berhasil diubah!";
        $_SESSION['username'] = $newUser;
    } else {
        $message = "Username dan password tidak boleh kosong!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Pengaturan Akun</title>
    <link rel="stylesheet" href="../Tools/Bootsrap 5/css/bootstrap.min.css" />
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            min-height: 100vh;
            background: #23272f;
            transition: background 0.3s;
            overflow: hidden;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .dashboard-card {
            max-width: 600px;
            min-width: 340px;
            width: 90vw;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            background: linear-gradient(90deg, #23272f 0%, #4f8cff 100%);
            border: none;
            overflow: hidden;
            margin-top: 48px;
            color: #f4f6fb;
        }

        .dashboard-header {
            background: transparent;
            color: #fff;
            padding: 28px 48px 18px 48px;
            text-align: center;
            font-size: 1.35rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .dashboard-body {
            background: transparent;
            padding: 28px 48px 28px 48px;
        }

        .form-label {
            font-weight: 500;
            color: #f4f6fb;
        }

        .form-control {
            background: #23272f;
            color: #f4f6fb;
            border: 1px solid #4f8cff;
        }

        .form-control:focus {
            background: #23272f;
            color: #fff;
            border-color: #6a82fb;
        }

        .btn-primary {
            border-radius: 8px;
            font-size: 1.05rem;
            font-weight: 500;
            box-shadow: 0 2px 8px #0001;
        }

        .btn-link.dashboard-back {
            color: #fff !important;
            text-decoration: underline;
        }

        .btn-link.dashboard-back:hover,
        .btn-link.dashboard-back:focus {
            color: #cce0ff !important;
            text-decoration: underline;
        }

        .alert {
            font-size: 1rem;
            padding: 8px 12px;
            margin-bottom: 18px;
        }

        @media (max-width: 600px) {
            .dashboard-card {
                max-width: 98vw;
                min-width: 0;
                margin-top: 18px;
            }

            .dashboard-header,
            .dashboard-body {
                padding: 14px 4vw;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-card shadow">
        <div class="dashboard-header">
            <span>Pengaturan Akun</span>
        </div>
        <div class="dashboard-body">
            <?php if ($message): ?>
                <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <form method="post" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label">Username Baru</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="text" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Simpan</button>
                <a href="../../Dashboard.php" class="btn btn-link dashboard-back w-100 mt-2">Kembali ke Dashboard</a>
            </form>
        </div>
    </div>
    <script src="../Tools/Bootsrap 5/js/bootstrap.bundle.min.js"></script>
</body>

</html>