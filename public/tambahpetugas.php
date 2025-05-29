<?php 
    session_start();
    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }

    include "sidebar.php";

    if(isset($_POST["register"]) ) {
        if(registrasi($_POST) > 0 ) {
            header("Location: tambahpetugas.php?sukses=1");
            exit;
        } else {
            echo "<script>alert('Gagal menambahkan petugas');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Petugas</title>
    <script>
        function showToast() {
            const toast = document.getElementById("toast");
            toast.style.display = "block";
            setTimeout(() => {
                toast.style.display = "none";
            }, 1000);
        }

        document.addEventListener("DOMContentLoaded", function () {
            const params = new URLSearchParams(window.location.search);
            if (params.get("sukses") === "1") {
                showToast();
            }
        });
    </script>
</head>
<body>
    <div class="ml-[300px]">
        <div id="toast" class=" hidden fixed top-5 right-5 bg-blue-900 text-white font-semibold px-4 py-2 rounded-lg shadow-lg z-50 transition duration-300 ease-in-out">
            Petugas berhasil ditambahkan!
        </div>
        <div class="flex justify-center items-center h-screen">
            <div id="modalContent" class="bg-white w-[50%] sm:w-[50%] rounded-lg shadow-lg p-4 relative transform transition-all duration-300 scale-95">
                <h2 class="text-xl font-bold text-blue-900 text-center mb-4">Form Tambah Tamu</h2>
                <form action="" method="post">
                    <label class="block text-blue-900 font-semibold mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_petugas" id="nama_petugas" required class="w-full mb-2 p-2 border rounded">

                    <label class="block text-blue-900 font-semibold mb-1">NIP</label>
                    <input type="text" name="nip" id="nip" required class="w-full mb-2 p-2 border rounded">

                    <label class="block text-blue-900 font-semibold mb-1">Username</label>
                    <input type="text" name="username" id="username" required class="w-full mb-2 p-2 border rounded">

                    <label class="block text-blue-900 font-semibold mb-1">Password</label>
                    <input type="password" name="pass_petugas" id="pass_petugas" class="w-full mb-2 p-2 border rounded">

                    <label class="block text-blue-900 font-semibold mb-1">Konfirmasi Password</label>
                    <input type="password" name="con_pass_petugas" id="con_pass_petugas" required class="w-full mb-2 p-2 border rounded">

                    <div class="text-center">
                        <button type="submit" name="register" class="bg-blue-900 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                            Tambah Petugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>