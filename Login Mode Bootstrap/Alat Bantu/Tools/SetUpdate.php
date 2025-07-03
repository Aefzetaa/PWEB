<?php
session_start();

$registryFile = __DIR__ . '/Data Users/HistoryRegistry.dat';
$updateFile = __DIR__ . '/Data Users/HistoryUpdate.dat';

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

$message = '';
$oldUsername = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    if ($oldUsername) {
        $users = [];
        if (file_exists($registryFile)) {
            $users = json_decode(file_get_contents($registryFile), true);
        }
        $users = array_filter($users, function ($user) use ($oldUsername) {
            return $user['username'] !== $oldUsername;
        });
        file_put_contents($registryFile, json_encode(array_values($users), JSON_PRETTY_PRINT));
    }
    session_destroy();
    header("Location: ../../Login.php?deleted=1");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUser = trim($_POST['username']);
    $newPass = $_POST['password'];
    if ($newUser && $newPass) {
        $users = [];
        if (file_exists($registryFile)) {
            $users = json_decode(file_get_contents($registryFile), true);
        }
        $found = false;
        foreach ($users as &$user) {
            if ($user['username'] === $oldUsername) {
                $user['username'] = $newUser;
                $user['password'] = password_hash($newPass, PASSWORD_DEFAULT);
                $found = true;
                break;
            }
        }
        unset($user);
        if (!$found) {
            $users[] = [
                'username' => $newUser,
                'password' => password_hash($newPass, PASSWORD_DEFAULT)
            ];
        }
        file_put_contents($registryFile, json_encode($users, JSON_PRETTY_PRINT));

        saveUserData($updateFile, $newUser, $newPass);
        $_SESSION['username'] = $newUser;

        $message = "Berhasil diubah!";
        $user = [
            'username' => $newUser,
            'password' => ''
        ];
    } else {
        $message = "Username dan password tidak boleh kosong!";
    }
} else {
    $user = [
        'username' => '',
        'password' => ''
    ];
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
    <button
        id="deleteAccountBtn"
        type="button"
        style="
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        background: #f8f9fa;
        color: #dc3545;
        border: none;
        border-radius: 32px;
        box-shadow: 0 2px 8px #0002;
        padding: 10px 18px 10px 12px;
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: box-shadow 0.2s;
    "
        data-bs-toggle="modal"
        data-bs-target="#deleteAccountModal">
        <svg width="24" height="24" fill="none" style="margin-right:8px;" viewBox="0 0 24 24">
            <path d="M3 21V3h13v18" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M16 17h5v-10h-5" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="8.5" cy="12" r="1.5" fill="#dc3545" />
        </svg>
        Delete Account
    </button>
    <form id="deleteAccountForm" method="post" style="display:none;">
        <input type="hidden" name="delete_account" value="1">
    </form>
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background:#23272f; color:#fff; border-radius:18px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="deleteAccountModalLabel">
                        <svg width="28" height="28" fill="none" style="margin-right:8px;vertical-align:-6px;" viewBox="0 0 24 24">
                            <path d="M3 21V3h13v18" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M16 17h5v-10h-5" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="8.5" cy="12" r="1.5" fill="#dc3545" />
                        </svg>
                        Delete Account
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p style="font-size:1.1rem;">Are you sure you want to <span style="color:#dc3545;font-weight:600;">delete</span> your account?</p>
                    <p style="font-size:0.98rem;opacity:0.7;">This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" onclick="document.getElementById('deleteAccountForm').submit();">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../Tools/Bootsrap 5/js/bootstrap.bundle.min.js"></script>
</body>

</html>