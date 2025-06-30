<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/style.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="icon" href="../img/silaila2.png" type="image/png">
    <title>SILAILA</title>
</head>
<body class="bg-slate-200 bg-cover overflow-x-hidden ">
    <?php
        include "functions.php";
        include "navbar.php";
        $tanggal_hari_ini = date('Y-m-d');
        date_default_timezone_set('Asia/Jakarta');
        $hari_ini = date('Y-m-d');

        // Ambil 1 petugas yang sudah presensi 'Masuk' hari ini
        $query_petugas = mysqli_query($koneksi, "SELECT p.nip, u.nama, p.waktu AS waktu_masuk, ( SELECT MAX(waktu) FROM presensi_petugas WHERE nip = p.nip AND jenis = 'Keluar' AND DATE(waktu) = '$hari_ini') AS waktu_keluar FROM presensi_petugas p 
        JOIN login u ON p.nip = u.nip 
        WHERE DATE(p.waktu) = '$hari_ini' 
            AND p.jenis = 'Masuk' 
        ORDER BY p.waktu DESC 
        LIMIT 1");

        $nama_petugas = '';
        $nip_petugas = '';

        $petugas = mysqli_fetch_assoc($query_petugas);
        if ($petugas) {
            $waktu_masuk = $petugas['waktu_masuk'];
            $waktu_keluar = $petugas['waktu_keluar'];
            if ($waktu_keluar && $waktu_keluar > $waktu_masuk) {
                $nip_petugas = '';
                $nama_petugas = '';
            } else {
                $nip_petugas = $petugas['nip'] ?? '';
                $nama_petugas = $petugas['nama'] ?? '';
            }
        }
        // Konfigurasi paginasi
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Ambil total data
        $total_query = $koneksi->query("SELECT COUNT(*) AS total FROM pengunjung");
        $total_data = $total_query->fetch_assoc()['total'];
        $total_page = ceil($total_data / $limit);

        // Ambil data sesuai halaman
        $pengunjung = $koneksi->query("SELECT * FROM pengunjung ORDER BY user_id DESC LIMIT $limit OFFSET $offset");
    ?>
    <div class="w-full">
        <!-- Bagian Buku Tamu -->
        <!-- <div id="toast" class="hidden fixed top-5 right-5 bg-white text-blue-900 font-semibold px-4 py-2 rounded-lg shadow-lg z-50 transition duration-300 ease-in-out">
            Data berhasil ditambahkan!
        </div> -->
        <div class="block lg:flex text-center md:text-left w-full mx-auto justify-center items-center">
            <div class="lg:w-[25%] w-full flex justify-center  mt-4">
                <img src="../img/silaila2.png" alt="" class="w-[50%]">
            </div>
            <div class=" h-full text-blue-950 px-2">
                <div>
                    <h1 class="text-lg md:text-2xl text-center font-bold">
                        DAFTAR TAMU STATISTIK BPS KABUPATEN PULANG PISAU
                    </h1>
                    <h2 class="text-sm lg:text-left text-center mb-2">
                        Sistem Informasi Pelayanan dan Pelaporan (SILAILA)
                    </h2>
                </div>
            </div>
        </div>
        <div class="flex md:justify-center w-full items-end">
            <div class="w-full text-center lg:text-right lg:mr-[200px]">
                <button onclick="openModal()" class="w-[50%] md:w-[150px] p-2 text-sm md:text-lg font-bold rounded-lg shadow-lg bg-blue-900 hover:bg-blue-600 active:scale-90 text-slate-200 hover:text-yellow-400">
                    Tambah
                </button>
            </div>
        </div>
        <div class="my-2 pt-4 pb-4 px-8 flex justify-center w-full">
            <div class="shadow-lg rounded-b-lg w-full">
                <table class="w-full shadow-lg" border="1" cellpadding="10" cellspacing="0" id="userTable">
                    <thead>
                        <tr class="text-xs text-center bg-white leading-4 font-bold text-black uppercase tracking-wider">
                            <th class="h-[50px] px-2 md:px-6 py-3 border-b rounded-tl-lg border-gray-200 text-center">Nama</th>
                            <th class="h-[50px] px-2 md:px-6 py-3 border-b hidden lg:table-cell border-gray-200 text-center">Jenis Kelamin</th>
                            <th class="h-[50px] px-2 md:px-6 py-3 border-b border-gray-200 text-center">Instansi</th>
                            <th class="h-[50px] px-2 md:px-6 py-3 border-b hidden lg:table-cell border-gray-200  text-center">Media Layanan</th>
                            <th class="h-[50px] px-2 md:px-6 py-3 border-b rounded-tr-lg border-gray-200 text-center">Waktu Berkunjung</th>
                        </tr>
                    </thead>
                    <tbody class="text-black bg-white">
                        <?php foreach ($pengunjung as $tamu) : ?>
                            <tr class="rounded-b-lg">
                                <td class="px-2 md:px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="block">
                                        <div class="text-sm leading-5 font-medium"><?= ucwords(strtolower(htmlspecialchars($tamu["nama"]))); ?></div>
                                    </div>
                                </td>
                                
                                <td class="px-2 md:px-6 py-4 whitespace-no-wrap hidden lg:table-cell border-b border-gray-200">
                                    <div class="text-sm leading-5"><?= $tamu["jenis_kelamin"]; ?></div>
                                </td>
                                
                                <td class="px-2 md:px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5"><?= strtoupper(htmlspecialchars($tamu["instansi"])); ?></div>
                                </td>

                                <td class="px-2 md:px-6 py-4 hidden lg:table-cell whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5"><?= $tamu["media_layanan"]; ?></div>
                                </td>

                                <td class="px-2 md:px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5" >
                                    <div class="text-sm leading-5"><?= $tamu["time"]; ?></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Paginasi -->
        <div class="flex justify-center items-center space-x-1 mt-4 text-sm mb-4 gap-2">
            <?php if ($page > 1): ?>
                <a href="?page=1" class="px-3 py-1 bg-white border rounded shadow-lg hover:bg-gray-100"><<</a>
                <a href="?page=<?= $page - 1 ?>" class="px-3 py-1 bg-white shadow-lg border rounded hover:bg-gray-100"><</a>
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
                <a href="?page=<?= $page + 1 ?>" class="px-3 py-1 bg-white shadow-lg border rounded hover:bg-gray-100">></a>
                <a href="?page=<?= $total_page ?>" class="px-3 py-1 bg-white border shadow-lg rounded hover:bg-gray-100">>></a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="modalForm" class="hidden fixed inset-0 z-50 backdrop-blur-sm bg-black/30">
        <div id="modalContent" class="bg-white w-[75%] lg:w-[50%] max-h-screen overflow-y-auto rounded-lg shadow-lg p-4 relative scale-95">
            <button onclick="closeModal()" class="absolute top-2 right-4 text-xl font-bold text-blue-800">×</button>
            <h2 class="text-xl font-bold text-blue-900 text-center mb-4">Form Tambah Tamu</h2>
            <form method="post" onsubmit="return validatePhoneNumber()">
                <input type="hidden" name="nip_petugas" value="<?= $nip_petugas ?>">
                <input type="hidden" name="nama_petugas" value="<?= $nama_petugas ?>"> 
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
                <input type="text" name="no_hp" id="no_hp" required placeholder="Awali dengan 08" class="w-full mb-2 p-2 border rounded">

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

                <div class="text-center">
                    <button type="submit" class="bg-blue-900 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            const modal = document.getElementById('modalForm');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'items-center', 'justify-center');
        }
        function closeModal() {
            const modal = document.getElementById('modalForm');
            const content = document.getElementById('modalContent');

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function showAntrianModal(nomor) {
            document.getElementById("nomorAntrian").innerText = nomor;
            document.getElementById("antrianModal").classList.remove('hidden');
            document.getElementById("antrianModal").classList.add('flex', 'items-center', 'justify-center');
        }

        function closeAntrianModal() {
            document.getElementById("antrianModal").classList.add('hidden');
            document.getElementById("antrianModal").classList.remove('flex', 'items-center', 'justify-center');
            window.location.href = 'form.php';
        }

        function showBerhasilTambahModal() {
            document.getElementById("berhasilTambahModal").classList.remove('hidden');
            document.getElementById("berhasilTambahModal").classList.add('flex', 'items-center', 'justify-center');
        }

        function closeBerhasilTambahModal() {
            document.getElementById("berhasilTambahModal").classList.add('hidden');
            document.getElementById("berhasilTambahModal").classList.remove('flex', 'items-center', 'justify-center');
            window.location.href = 'form.php';
        }

        function validatePhoneNumber() {
            const input = document.getElementById("no_hp").value;
            if (!/^\d+$/.test(input)) {
                alert("Nomor HP hanya boleh berisi angka.");
                return false;
            }
            return true;
        }
    </script>

    

    <div id="antrianModal" class="hidden fixed inset-0 z-50 bg-black/60 items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center w-[300px]">
            <h2 class="text-xl font-bold text-blue-900 mb-4">Nomor Antrian Anda</h2>
            <div id="nomorAntrian" class="text-6xl font-bold text-red-600"></div>
            <button onclick="closeAntrianModal()" class="mt-6 bg-blue-900 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Tutup</button>
        </div>
    </div>

    <div id="berhasilTambahModal" class="hidden fixed inset-0 z-50 bg-black/60 items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center w-[300px]">
            <h2 class="text-xl font-bold text-blue-900 mb-4">Data Anda Berhasil Ditambahkan!</h2>
            <button onclick="closeBerhasilTambahModal()" class="mt-6 bg-blue-900 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Tutup</button>
        </div>
    </div>

    <?php

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (tambah($_POST) > 0) {
                // Cek media layanan
                $media_layanan = $_POST['media_layanan'];

                if ($media_layanan === "Kunjungan Langsung") {
                    // Ambil nomor antrian terakhir
                    $query = "SELECT nomor_antrian FROM pengunjung WHERE DATE(time) = '$tanggal_hari_ini' ORDER BY time DESC LIMIT 1";
                    $result = mysqli_query($koneksi, $query);
                    $row = mysqli_fetch_assoc($result);
                    $nomor_antrian = $row['nomor_antrian'];

                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showAntrianModal('$nomor_antrian');
                        });
                    </script>";
                } else {
                    // Tidak menampilkan nomor antrian
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showBerhasilTambahModal();
                        });
                    </script>";
                }

            } else {
                echo "<script>alert('Gagal menambahkan data');</script>";
            }
        }
    ?>
    </body>
</html>
