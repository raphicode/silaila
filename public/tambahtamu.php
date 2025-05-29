<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Penilaian Layanan</title>
    <script>
        function showToast() {
            const toast = document.getElementById("toast");
            toast.style.display = "block";
            setTimeout(() => {
                toast.style.display = "none";
            }, 1000);
        }
    </script>
</head>
<body class="bg-backsilaila bg-cover">
    <div id="toast" class="hidden fixed top-5 right-5 bg-white text-blue-900 font-semibold px-4 py-2 rounded-lg shadow-lg z-50 transition duration-300 ease-in-out">
        Data berhasil ditambahkan!
    </div>
    <?php
        include "functions.php";
        if( isset($_POST["submit"]) ) {
            if( tambah($_POST) > 0 ) {
                echo "
                    <script>
                        showToast();
                        setTimeout(function(){
                            window.location.href = 'form.php';
                        }, 1000);
                    </script>
                ";
            } else {
                echo "Data gagal ditambahkan";
            }
        }
    ?>
    <div class="w-full">
        <!-- Bagian Form -->
        <div class="w-full flex item-center justify-center">
            <div class="w-full flex item-center justify-center">
                <div class="w-[50%] bg-blue-200 rounded-lg my-2 px-4 py-2 shadow-lg">
                    <form action="" method="post">
                        <!-- <div class="flex justify-center items-center p-2">
                            <img src="../img/silaila2.png" width="100" alt="">
                        </div> -->
                        <div class="text-center text-blue-900">
                            <h1 class="font-bold text-4xl">SILAILA</h1>
                            <h2 class="font-semibold text-lg pb-2">Silahkan isi data diri anda</h2>
                        </div>
                        <div class="text-blue-900 w-full">
                            <div for="nama" class="font-semibold pb-[4px]">
                                <label for="nama">
                                    Nama Lengkap
                                </label>    
                            </div>
                            <div class="w-full text-center rounded-lg border-blue-900 border mb-2">
                                <input type="text" name="nama" id="nama" class="rounded-lg w-full px-2 p-[4px]">
                            </div>
                        </div>
                        <div class="text-blue-900 w-full">
                            <div class="font-semibold pb-[4px]">
                                <label for="jenis_kelamin">
                                    Jenis Kelamin
                                </label>
                            </div>
                            <div class="w-full text-center rounded-lg border-blue-900 border mb-2">
                                <select id="jenis_kelamin" name="jenis_kelamin" id="jenis_kelamin" class="block w-full p-2 rounded-lg bg-white">
                                    <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                <!-- <input type="text" name="Jenis Kelamin" class="rounded-lg w-full px-2 p-[4px]"> -->
                            </div>
                        </div>
                        <div class="text-blue-900 w-full">
                            <div class="font-semibold pb-[4px]">
                                <label for="instansi">
                                    Instansi
                                </label>
                            </div>
                            <div class="w-full text-center rounded-lg border-blue-900 border mb-2">
                                <input type="text" name="instansi" id="instansi" class="rounded-lg w-full px-2 p-[4px]">
                            </div>
                        </div>
                        <div class="text-blue-900 w-full mb-2">
                            <div class="font-semibold pb-[4px]">
                                <label for="email">
                                    Email
                                </label>
                            </div>
                            <div class="w-full text-center rounded-lg border-blue-900 border mb-4">
                                <input type="text" name="email" id="email" class="rounded-lg w-full px-2 p-[4px]">
                            </div>
                        </div>
                        <div class="text-blue-900 w-full mb-2">
                            <div class="font-semibold pb-[4px]">
                                <label for="no_hp">
                                    Nomor HP
                                </label>
                            </div>
                            <div class="w-full text-center rounded-lg border-blue-900 border mb-4">
                                <input type="text" name="no_hp" id="no_hp" class="rounded-lg w-full px-2 p-[4px]">
                            </div>
                        </div>
                        <div class="text-blue-900 w-full mb-2">
                            <div class="font-semibold pb-[4px]">
                                <label for="media_layanan">
                                    Media Layanan
                                </label>
                            </div>
                            <div class="w-full text-center rounded-lg border-blue-900 border mb-4">
                                <select id="media_layanan" name="media_layanan" id="media_layanan" class="block w-full p-2 rounded-lg bg-white">
                                    <option value="" selected disabled>Pilih Media Layanan</option>
                                    <option value="Kunjungan Langsung">Kunjungan Langsung</option>
                                    <option value="Kunjungan Melalui Whatsapp">Kunjungan Melalui Whatsapp</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-blue-900 w-full mb-2">
                            <div class="font-semibold pb-[4px]">
                                <label for="keperluan">
                                    Keperluan
                                </label>
                            </div>
                            <div class="w-full text-center rounded-lg border-blue-900 border mb-4">
                                <input type="text" name="keperluan" id="keperluan" class="rounded-lg w-full px-2 p-[4px]">
                            </div>
                        </div>
                        <div class="w-full text-center mb-2 pt-[16px]">
                            <button type="submit" name="submit" class="bg-blue-900  w-full rounded-lg font-semibold text-lg px-2 py-[2px] hover:contrast-150 active:scale-[98%] text-slate-200">
                                Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>