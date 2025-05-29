<?php 
    session_start();
    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/style.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Data Kunjungan</title>
    <script>
        function showToast() {
            const toast = document.getElementById("toast");
            toast.style.display = "block";
            setTimeout(() => {
                toast.style.display = "none";
            }, 1000);
        }
        function confirmDelete(id) {
            const confirmed = confirm("Apakah Anda yakin ingin menghapus data ini?");
            if (confirmed) {
                // Kirim ID melalui POST request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = ''; // Aksi halaman ini (beranda.php atau file terkait)

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_id';
                input.value = id;
                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</head>
<body class="overflow-x-hidden">
    <?php
        include "sidebar.php";
        // Konfigurasi paginasi
        $limit = 6;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Ambil total data
        $total_query = $koneksi->query("SELECT COUNT(*) AS total FROM pengunjung");
        $total_data = $total_query->fetch_assoc()['total'];
        $total_page = ceil($total_data / $limit);

        // Ambil data sesuai halaman
        $pengunjung = $koneksi->query("SELECT * FROM pengunjung ORDER BY user_id DESC LIMIT $limit OFFSET $offset");
    ?>
    
    <div class="p-6 space-y-6 w-full ml-[300px]">
        <div id="toast" class="hidden fixed top-5 right-5 bg-white text-blue-900 font-semibold px-4 py-2 rounded-lg shadow-lg z-50 transition duration-300 ease-in-out">
            Data berhasil diubah!
        </div>
        <form method="post" class="flex">
            <input type="text" name="keyword" size="50" placeholder="Silahkan cari disini" autofocus class="border border-slate-300 shadow-md p-2">
            <button type="submit" name="cari" class="bg-slate-300 p-2 shadow-md hover:bg-slate-500 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>
        </form>
        <?php
            if(isset($_POST['cari'])){
                $pengunjung = cari($_POST["keyword"]);
            }
        ?>
        <div class="w-full bg-white rounded p-4  sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8 flex justify-center">
            <div class="">
                <table class="w-[75%] border shadow-lg" style="table-layout: fixed;" border="1" cellpadding="10" cellspacing="0" id="userTable">
                    <thead>
                        <tr class="text-xs text-center overflow-hidden leading-4 font-bold text-black uppercase tracking-wider">
                            <th class="px-6 py-3 border-b border-gray-200 text-center">Nama</th>
                            <th class="px-6 py-3 border-b border-gray-200 text-center">Jenis Kelamin</th>
                            <th class="px-6 py-3 border-b border-gray-200 text-center">Instansi</th>
                            <th class="px-6 py-3 border-b border-gray-200 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-black truncate">
                        <?php foreach ($pengunjung as $tamu) : ?>
                            <tr>
                                <td class="px-6 py-4 border-b border-gray-200 truncate whitespace-nowrap text-sm leading-5">
                                    <div class="text-sm leading-5"><?= ucwords(strtolower($tamu["nama"])); ?></div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200 truncate whitespace-nowrap text-sm leading-5">
                                    <div class="text-sm leading-5"><?= $tamu["jenis_kelamin"]; ?></div>
                                </td>
                                
                                <td class="px-6 py-4 border-b border-gray-200 truncate whitespace-nowrap text-sm leading-5">
                                    <div class="text-sm leading-5"><?= strtoupper($tamu["instansi"]); ?></div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200 leading-5 text-center flex justify-evenly">
                                    <button onclick='openModal(<?= $tamu["user_id"]; ?>)' name="edit" class="hover:scale-75 hover:stroke-yellow-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>

                                    </button>
                                    <a href="hapus.php?id=<?= $tamu['user_id']; ?>" name="hapus" onclick="return confirm('Yakin ingin menghapus data ini?');" class="bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Paginasi -->
    <div class="flex justify-center items-center space-x-1 mt-4 text-sm mb-4 gap-2 ml-[300px]">
        <?php if ($page > 1): ?>
            <a href="?page=1" class="px-3 py-1 bg-white border shadow-lg rounded hover:bg-gray-100"><<</a>
            <a href="?page=<?= $page - 1 ?>" class="px-3 py-1 bg-white border shadow-lg rounded hover:bg-gray-100"><</a>
        <?php endif; ?>

        <?php
            $range = 2;
            $start = max(1, $page - $range);
            $end = min($total_page, $page + $range);

            if ($start > 1) echo '<span class="px-2">...</span>';

            for ($i = $start; $i <= $end; $i++):
        ?>
        <a href="?page=<?= $i ?>" class="px-3 py-1 shadow-lg border rounded <?= $i == $page ? 'bg-blue-500 text-white font-bold' : 'bg-white hover:bg-gray-100' ?>">
            <?= $i ?>
        </a>
        <?php endfor;

            if ($end < $total_page) echo '<span class="px-2">...</span>';
        ?>

        <?php if ($page < $total_page): ?>
            <a href="?page=<?= $page + 1 ?>" class="px-3 py-1 shadow-lg bg-white border rounded hover:bg-gray-100">></a>
            <a href="?page=<?= $total_page ?>" class="px-3 py-1 shadow-lg bg-white border rounded hover:bg-gray-100">>></a>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div id="modalForm" class="hidden fixed inset-0 z-50 backdrop-blur-sm bg-black/30">
        <div id="modalContent" class="bg-white w-[50%] sm:w-[50%] rounded-lg shadow-lg p-4 relative transform transition-all duration-300 scale-95">
            <button onclick="closeModal()" class="absolute top-2 right-4 text-xl font-bold text-blue-800">×</button>
            <h2 class="text-xl font-bold text-blue-900 text-center mb-4">Form Edit Tamu</h2>
            <form method="post">
                <label class="block text-blue-900 font-semibold mb-1">Nama Lengkap</label>
                <input type="text" name="nama" required class="w-full mb-2 p-2 border rounded">

                <label class="block text-blue-900 font-semibold mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" required class="w-full mb-2 p-2 border rounded">
                    <option value="" disabled selected>Pilih</option>
                    <option value="Laki-Laki">Laki-Laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>

                <label class="block text-blue-900 font-semibold mb-1">Instansi</label>
                <input type="text" name="instansi" required class="w-full mb-2 p-2 border rounded">

                <label class="block text-blue-900 font-semibold mb-1">Email</label>
                <input type="email" name="email" class="w-full mb-2 p-2 border rounded">

                <label class="block text-blue-900 font-semibold mb-1">Nomor HP</label>
                <input type="text" name="no_hp" required class="w-full mb-2 p-2 border rounded">

                <label class="block text-blue-900 font-semibold mb-1">Media Layanan</label>
                <select name="media_layanan" required class="w-full mb-2 p-2 border rounded">
                    <option value="" disabled selected>Pilih</option>
                    <option value="Kunjungan Langsung">Kunjungan Langsung</option>
                    <option value="Kunjungan Melalui Whatsapp">Kunjungan Melalui Whatsapp</option>
                </select>

                <label class="block text-blue-900 font-semibold mb-1">Keperluan</label>
                <select name="keperluan" required class="w-full mb-2 p-2 border rounded">
                    <option value="" disabled selected>Pilih</option>
                    <option value="Konsultasi Statistik">Konsultasi Statistik/Permintaan Data</option>
                    <option value="Rekomendasi Statistik">Rekomendasi Statistik</option>
                    <option value="Pustaka Tercetak/Digital">Pustaka Tercetak/Digital</option>
                    <option value="Koordinasi">Koordinasi</option>
                    <option value="Layanan PPID">Layanan PPID</option>
                </select>

                <label class="block text-blue-900 font-semibold mb-1">Rincian Keperluan</label>
                <input type="text" name="rincian_keperluan" required placeholder="Tuliskan rincian keperluan anda" class="w-full mb-2 p-2 border rounded">

                <div class="text-center flex justify-evenly">
                    <button type="submit" name="edit" class="bg-blue-900 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                        Ubah Data!
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById('modalForm');
            const content = document.getElementById('modalContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex', 'items-center', 'justify-center');

            $.ajax({
                url: 'get_pengunjung.php',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    $('[name="nama"]').val(response.nama);
                    if (response.jenis_kelamin.toLowerCase() === 'laki-laki') {
                            $('[name="jenis_kelamin"]').val('Laki-Laki');
                        } else if (response.jenis_kelamin.toLowerCase() === 'perempuan') {
                            $('[name="jenis_kelamin"]').val('Perempuan');
                        }
                    $('[name="instansi"]').val(response.instansi);
                    $('[name="email"]').val(response.email);
                    $('[name="no_hp"]').val(response.no_hp);
                    $('[name="media_layanan"]').val(response.media_layanan);
                    $('[name="keperluan"]').val(response.keperluan);
                    $('[name="rincian_keperluan"]').val(response.rincian_keperluan);

                    if (!$('[name="id"]').length) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'id',
                            value: id
                        }).appendTo('form');
                    } else {
                        $('[name="id"]').val(id);
                    }
                },
                error: function() {
                    alert("Gagal mengambil data.");
                }
            });
        }

        function closeModal() {
            const modal = document.getElementById('modalForm');
            const content = document.getElementById('modalContent');

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>

    <?php

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['edit'])){
            if (edit($_POST) > 0) {
                echo "<script>
                        showToast();
                        setTimeout(function(){
                            window.location.href = 'manajementamu.php';
                        }, 1000);
                        </script>";
            } else {
                echo "<script>alert('Gagal mengubah data');</script>";
            }
        }
    }

    $pengunjung = query("SELECT * FROM pengunjung ORDER BY time DESC LIMIT 10");
    ?>
    </body>
</html>
