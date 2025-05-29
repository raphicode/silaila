<?php 
    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <title>Dashboard Admin</title>
    </head>
    <body>
    <?php
        include "functions.php";
    ?>
        <div class="w-[300px] bg-blue-800 text-yellow-400 flex flex-col h-screen fixed z-50">
            <div class="flex justify-center items-center p-2">
                <img src="../img/silaila2.png" width="150" alt="">
            </div>
            <div class="font-mono text-center font-bold text-2xl">
                Dashboard Admin
            </div>
            <div class="py-3 w-full">
                <div class="w-full">
                    <button onclick="window.location.href='beranda.php'" class="w-full text-center p-2 hover:bg-yellow-400 hover:text-blue-800 font-semibold">
                        Beranda
                    </button>
                </div>
                <div class="w-full">
                    <button onclick="window.location.href='profilepetugas.php'" class="w-full text-center p-2 hover:bg-yellow-400 hover:text-blue-800 font-semibold">
                        Profil
                    </button>
                </div>
                <div class="w-full">
                    <button onclick="window.location.href='manajementamu.php'" class="w-full text-center p-2 hover:bg-yellow-400 hover:text-blue-800 font-semibold">
                        Manajemen Tamu
                    </button>
                </div>
                <div class="w-full">
                    <button onclick="window.location.href='tambahpetugas.php'" class="w-full text-center p-2 hover:bg-yellow-400 hover:text-blue-800 font-semibold">
                        Tambah Petugas
                    </button>
                </div>
                <div class="w-full">
                    <button onclick="window.location.href='datakunjungan.php'" class="w-full text-center p-2 hover:bg-yellow-400 hover:text-blue-800 font-semibold">
                        Data Pengunjung
                    </button>
                </div>
            </div>
            <footer class="p-2 mt-auto">
                <button onclick="window.location.href='logout.php'" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                    Logout
                </button>
            </footer>
        </div>
    </body>
</html>