<?php
session_start();              // Memulai session untuk memastikan session sebelumnya aktif
session_destroy();            // Menghapus seluruh data session (logout total)

header("Location: ../../login.php"); // Mengarahkan pengguna kembali ke halaman login
exit();                        // Menghentikan eksekusi script agar redirect berjalan
