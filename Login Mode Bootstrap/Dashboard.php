<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="Alat Bantu/Tools/Bootsrap 5/css/bootstrap.min.css" />
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            min-height: 100vh;
            background: var(--bs-body-bg);
            transition: background 0.3s;
            overflow: hidden;
            display: block;
        }

        .dashboard-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 48px;
            margin-bottom: 24px;
        }

        .dashboard-card {
            max-width: 900px;
            min-width: 380px;
            width: 90vw;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            background: linear-gradient(90deg, #4f8cff 0%, #6a82fb 100%);
            border: none;
            overflow: hidden;
            transition: background 0.3s, color 0.3s;
        }

        [data-bs-theme="dark"] .dashboard-card,
        body[data-bs-theme="dark"] .dashboard-card {
            background: linear-gradient(90deg, #23272f 0%, #4f8cff 100%);
        }

        .dashboard-header {
            color: #fff;
            padding: 28px 48px 24px 48px;
            text-align: center;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 22px;
            background: transparent;
            border-radius: 0;
        }

        .settings-icon {
            background: #fff;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px #0002;
            border: 3px solid #f4f6fb;
            flex-shrink: 0;
        }

        [data-bs-theme="dark"] .settings-icon,
        body[data-bs-theme="dark"] .settings-icon {
            background: #23272f;
            border-color: #23272f;
        }

        .settings-icon svg {
            width: 22px;
            height: 22px;
            fill: #4f8cff;
        }

        .greeting {
            font-size: 1.35rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-align: left;
            margin-bottom: 0;
        }

        .username {
            font-weight: bold;
            color: #fff;
            text-shadow: 0 2px 8px #0002;
        }

        .dashboard-body {
            background: transparent;
            padding: 28px 48px 28px 48px;
            text-align: center;
        }

        .logout-btn {
            margin-top: 18px;
            padding: 8px 28px;
            border-radius: 8px;
            font-size: 1.05rem;
            font-weight: 500;
            box-shadow: 0 2px 8px #0001;
        }

        .logout-btn:active {
            transform: scale(0.97);
        }

        .btn-bd-primary {
            --bd-blue: #4f8cff;
            --bd-blue-hover: #357ae8;
            color: #fff;
            background-color: var(--bd-blue);
            border-color: var(--bd-blue);
            box-shadow: 0 2px 8px #0001;
            transition: background 0.2s, border 0.2s;
        }

        .btn-bd-primary:hover,
        .btn-bd-primary:focus {
            background-color: var(--bd-blue-hover);
            border-color: var(--bd-blue-hover);
            color: #fff;
        }

        .bd-mode-toggle .btn {
            font-size: 0.95rem;
            padding: 0.25rem 0.8rem;
            border-radius: 0.5rem;
            min-width: 0;
            height: 38px;
            line-height: 1.2;
            display: flex;
            align-items: center;
        }

        .bd-mode-toggle .theme-icon-active {
            width: 1.2em;
            height: 1.2em;
        }

        .bd-mode-toggle .dropdown-toggle::after {
            margin-left: 0.4em;
            vertical-align: middle;
        }

        .bd-mode-toggle .dropdown-menu {
            font-size: 0.95rem;
            min-width: 8rem;
        }

        .bd-mode-toggle .dropdown-item svg {
            width: 1.1em;
            height: 1.1em;
        }

        .bd-mode-toggle .dropdown-item.active,
        .bd-mode-toggle .dropdown-item:active {
            background-color: #4f8cff !important;
            color: #fff !important;
        }

        .bd-mode-toggle .dropdown-item:focus {
            background-color: #eaf1ff !important;
            color: #4f8cff !important;
        }

        @media (max-width: 900px) {
            .dashboard-card {
                max-width: 98vw;
                min-width: 0;
            }

            .dashboard-header,
            .dashboard-body {
                padding-left: 12px;
                padding-right: 12px;
            }
        }

        @media (max-width: 600px) {
            .dashboard-wrapper {
                margin-top: 18px;
            }

            .dashboard-header,
            .dashboard-body {
                padding: 14px 4vw;
            }

            .dashboard-header {
                flex-direction: column;
                gap: 8px;
            }

            .settings-icon {
                width: 32px;
                height: 32px;
            }
        }
    </style>
    <script>
        (() => {
            'use strict';
            const getStoredTheme = () => localStorage.getItem('theme');
            const setStoredTheme = (theme) => localStorage.setItem('theme', theme);
            const getPreferredTheme = () => {
                const storedTheme = getStoredTheme();
                if (storedTheme) {
                    return storedTheme;
                }
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };
            const setTheme = (theme) => {
                if (theme === 'auto') {
                    document.documentElement.setAttribute(
                        'data-bs-theme',
                        window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
                    );
                } else {
                    document.documentElement.setAttribute('data-bs-theme', theme);
                }
            };
            const showActiveTheme = (theme, focus = false) => {
                const themeSwitcher = document.querySelector('#bd-theme');
                if (!themeSwitcher) return;
                const themeSwitcherText = document.querySelector('#bd-theme-text');
                const activeThemeIcon = document.querySelector('.theme-icon-active use');
                const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);
                const svgOfActiveBtn = btnToActive.querySelector('svg use').getAttribute('href');
                document.querySelectorAll('[data-bs-theme-value]').forEach((el) => {
                    el.classList.remove('active');
                    el.setAttribute('aria-pressed', 'false');
                });
                btnToActive.classList.add('active');
                btnToActive.setAttribute('aria-pressed', 'true');
                activeThemeIcon.setAttribute('href', svgOfActiveBtn);
                const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`;
                themeSwitcher.setAttribute('aria-label', themeSwitcherLabel);
                if (focus) themeSwitcher.focus();
            };
            setTheme(getPreferredTheme());
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                const storedTheme = getStoredTheme();
                if (storedTheme !== 'light' && storedTheme !== 'dark') {
                    setTheme(getPreferredTheme());
                }
            });
            window.addEventListener('DOMContentLoaded', () => {
                showActiveTheme(getPreferredTheme());
                document.querySelectorAll('[data-bs-theme-value]').forEach((toggle) => {
                    toggle.addEventListener('click', () => {
                        const theme = toggle.getAttribute('data-bs-theme-value');
                        setStoredTheme(theme);
                        setTheme(theme);
                        showActiveTheme(theme, true);
                    });
                });
            });
        })();
    </script>
</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="check2" viewBox="0 0 16 16">
            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"></path>
        </symbol>
        <symbol id="circle-half" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
        </symbol>
        <symbol id="moon-stars-fill" viewBox="0 0 16 16">
            <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"></path>
            <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"></path>
        </symbol>
        <symbol id="sun-fill" viewBox="0 0 16 16">
            <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"></path>
        </symbol>
    </svg>
    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
        <button
            class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center"
            id="bd-theme"
            type="button"
            aria-expanded="false"
            data-bs-toggle="dropdown"
            aria-label="Toggle theme (auto)">
            <svg class="bi my-1 theme-icon-active" aria-hidden="true">
                <use href="#circle-half"></use>
            </svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
                <button
                    type="button"
                    class="dropdown-item d-flex align-items-center"
                    data-bs-theme-value="light"
                    aria-pressed="false">
                    <svg class="bi me-2 opacity-50" aria-hidden="true">
                        <use href="#sun-fill"></use>
                    </svg>
                    Light
                    <svg class="bi ms-auto d-none" aria-hidden="true">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="dropdown-item d-flex align-items-center"
                    data-bs-theme-value="dark"
                    aria-pressed="false">
                    <svg class="bi me-2 opacity-50" aria-hidden="true">
                        <use href="#moon-stars-fill"></use>
                    </svg>
                    Dark
                    <svg class="bi ms-auto d-none" aria-hidden="true">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button
                    type="button"
                    class="dropdown-item d-flex align-items-center active"
                    data-bs-theme-value="auto"
                    aria-pressed="true">
                    <svg class="bi me-2 opacity-50" aria-hidden="true">
                        <use href="#circle-half"></use>
                    </svg>
                    Auto
                    <svg class="bi ms-auto d-none" aria-hidden="true">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
        </ul>
    </div>
    <main>
        <div class="dashboard-wrapper">
            <div class="dashboard-card shadow">
                <div class="dashboard-header">
                    <div class="settings-icon" title="Pengaturan" onclick="window.location.href='Alat Bantu/Tools/SetUpdate.php'" style="cursor:pointer;">
                        <svg viewBox="0 0 24 24">
                            <path d="M19.14,12.94a7.07,7.07,0,0,0,.05-1,7.07,7.07,0,0,0-.05-1l2.11-1.65a.5.5,0,0,0,.12-.64l-2-3.46a.5.5,0,0,0-.61-.22l-2.49,1a7,7,0,0,0-1.73-1l-.38-2.65A.5.5,0,0,0,13,2h-4a.5.5,0,0,0-.5.42l-.38,2.65a7,7,0,0,0-1.73,1l-2.49-1a.5.5,0,0,0-.61.22l-2,3.46a.5.5,0,0,0,.12.64L4.86,10a7.07,7.07,0,0,0-.05,1,7.07,7.07,0,0,0,.05,1l-2.11,1.65a.5.5,0,0,0-.12.64l2,3.46a.5.5,0,0,0,.61.22l2.49-1a7,7,0,0,0,1.73,1l.38,2.65A.5.5,0,0,0,9,22h4a.5.5,0,0,0,.5-.42l.38-2.65a7,7,0,0,0,1.73-1l2.49,1a.5.5,0,0,0,.61-.22l2-3.46a.5.5,0,0,0-.12-.64ZM12,15.5A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z" />
                        </svg>
                    </div>
                    <div class="greeting">Halo, <span class="username"><?= $username ?></span>!</div>
                </div>
                <div class="dashboard-body">
                    <div>Selamat datang di dashboard Anda.</div>
                    <form method="post" action="Alat Bantu/Tools/Logout.php">
                        <button class="btn btn-primary logout-btn" type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="Alat Bantu/Tools/Bootsrap 5/js/bootstrap.bundle.min.js"></script>
</body>

</html>