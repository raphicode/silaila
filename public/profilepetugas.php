<?php
    session_start();
    include "sidebar.php";
    date_default_timezone_set("Asia/Jakarta");
    if (isset($_POST['tandai_selesai'])) {
        $user_id = $_POST['user_id'];
        $waktu_selesai = date('Y-m-d H:i:s');
        $query = "UPDATE pengunjung SET status = 'Selesai', waktu_selesai = '$waktu_selesai' WHERE user_id = '$user_id'";
        mysqli_query($koneksi, $query);
        
        // Setelah update, refresh halaman
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    if (!isset($_SESSION["login"])) {
        header("Location: login.php");
        exit;
    }

    // Cek apakah nip dan nama tersedia
    $nip = isset($_SESSION["nip"]) ? $_SESSION["nip"] : '';
    $nama = isset($_SESSION["nama"]) ? $_SESSION["nama"] : '';

    // algoritma rating petugas
    $hari_ini = date('Y-m-d');

    // Ambil petugas yang sedang bertugas hari ini 
    $query_petugas = mysqli_query($koneksi, "SELECT p.nip, u.nama 
        FROM presensi_petugas p 
        JOIN login u ON p.nip = u.nip 
        WHERE DATE(p.waktu) = '$hari_ini' AND p.jenis = 'Masuk' 
        ORDER BY p.waktu ASC LIMIT 1");

    $petugas = mysqli_fetch_assoc($query_petugas);
    $nip_petugas = $petugas['nip'] ?? '';
    $nama_petugas = $petugas['nama'] ?? '';

    // Jika petugas aktif ditemukan
    if ($nip_petugas) {
        // Cek presensi keluar
        $cek_keluar = mysqli_query($koneksi, "SELECT * FROM presensi_petugas 
            WHERE nip = '$nip_petugas' AND DATE(waktu) = '$hari_ini' AND jenis = 'Keluar'");
        
        if (mysqli_num_rows($cek_keluar) > 0) {
            $nip_petugas = '';
            $nama_petugas = '';
        } else {
            // Hitung rata-rata kepuasan petugas
            $query_rating = mysqli_query($koneksi, "SELECT AVG(kepuasan) as rata_rata 
                FROM penilaian 
                WHERE nip_petugas = '$nip_petugas'");
            
            $data_rating = mysqli_fetch_assoc($query_rating);
            $rata_rata = round($data_rating['rata_rata'], 2) ?? 0;

            $update_rating = mysqli_query($koneksi, "UPDATE login SET rating_rata_rata = '$rata_rata' 
            WHERE nip = '$nip_petugas'");
        }
    };
    $rating = mysqli_query($koneksi, "
        SELECT l.nip, l.rating_rata_rata
        FROM login l
        WHERE nip = '$nip'");
    $rating = mysqli_fetch_assoc($rating);

    // Ambil rata-rata dari tabel penilaian berdasarkan nip petugas
    $query_rating = mysqli_query($koneksi, "
        SELECT AVG(kepuasan) as rata_rata 
        FROM penilaian 
        WHERE nip_petugas = '$nip'");

    $data_rating = mysqli_fetch_assoc($query_rating);
    $rata_rata = isset($data_rating['rata_rata']) ? round($data_rating['rata_rata'], 2) : 0;

    // Update rating ke tabel login
    $update_rating = mysqli_query($koneksi, "
        UPDATE login 
        SET rating_rata_rata = '$rata_rata' 
        WHERE nip = '$nip'");

    // Menghitung Waktu pelayanan
    $durasi = hitungDurasiPelayanan($nip);

    // Total kunjungan hari ini
    $total_kunjungan = hitungKunjunganPetugas($nip);

    // Menampikan feedback
    $feedback_tamu = getFeedbackPetugas($nip);

     // Algoritma untuk presensi

    $tanggal_hari_ini = date("Y-m-d");

    $query_waktu_terakhir = mysqli_query($koneksi, "SELECT jenis, waktu FROM presensi_petugas WHERE nip='$nip' AND DATE(waktu) = '$tanggal_hari_ini' ORDER BY waktu DESC LIMIT 1");

    $waktu_terakhir = null;
    $jenis_terakhir_presensi = null;

    if( $row = mysqli_fetch_assoc($query_waktu_terakhir) ) {
        $waktu_terakhir = $row['waktu'];
        $jenis_terakhir_presensi = $row['jenis'];
    }

    // info status terakhir
    $status = $jenis_terakhir_presensi
        ? "Sudah Presensi $jenis_terakhir_presensi"
        : "Belum Presensi Hari Ini!";
    
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['presensi'])) {
        $jenis = $_POST['presensi'];
        $waktu = date("Y-m-d H:i:s");
        if ($jenis !== $jenis_terakhir_presensi) {
            mysqli_query($koneksi, "
                INSERT INTO presensi_petugas (nip, nama, waktu, jenis) 
                VALUES ('$nip', '$nama', '$waktu', '$jenis')
            ");
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $status = "Presensi '$jenis' sudah dilakukan. Lakukan presensi berbeda terlebih dahulu.";
        }
    }

    // Untuk Detail Tamu
    $user_id = $_GET['user_id'] ?? null;
    $detail_tamu = null;

    if ($user_id) {
        $query = "SELECT * FROM pengunjung WHERE user_id = '$user_id'";
        $result = mysqli_query($koneksi, $query);
        if ($result) {
            $detail_tamu = mysqli_fetch_assoc($result);
        }
    }

    $pengunjung = query("SELECT * FROM pengunjung ORDER BY time DESC");

    // Konfigurasi paginasi
    $limit = 6;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Ambil total data
    $total_query = $koneksi->query("SELECT COUNT(*) AS total FROM pengunjung WHERE nip_petugas='$nip'");
    $total_data = $total_query->fetch_assoc()['total'];
    $total_page = ceil($total_data / $limit);

    // Ambil data sesuai halaman
    $pengunjung_page = $koneksi->query("SELECT * FROM pengunjung WHERE nip_petugas='$nip' ORDER BY user_id DESC LIMIT $limit OFFSET $offset");
            
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Profile Petugas</title>
</head>

<body class="bg-gray-100 overflow-x-hidden">

    <!-- Header -->
    <header class="bg-white shadow p-4 flex justify-between items-center ml-[300px]">
        <h1 class="text-2xl font-bold text-blue-600">Profile Petugas</h1>
        <div class="flex items-center space-x-4">
            <span class="font-medium text-gray-700 uppercase"> <?php echo $nama ?> </span>
        </div>
    </header>

    <main class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6 ml-[300px]">
        <!-- Profile Card -->
        <div class="bg-white p-6 rounded-xl shadow col-span-1 text-center">
            <h2 class="text-lg font-semibold uppercase"><?php echo $nama ?></h2>
            <p class="text-gray-500">NIP: <?php echo $nip ?></p>
            <button onclick='openModal(<?= $nip; ?>)' name="editProfile" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Edit Profil</button>
        </div>

        <!-- Statistik Layanan -->
        <div class="bg-white p-4 rounded-xl shadow text-center">
            <h1 class="text-gray-500 text-sm mb-4">Rating Rata-rata</h1>
            <h1 class="text-6xl font-bold text-yellow-500">
                    <?= $rating['rating_rata_rata']?>
            </h1>
        </div>

        <!-- Presensi -->
        <div class="bg-white p-6 rounded-xl shadow col-span-1">
            <h2 class="text-lg font-semibold mb-4">Presensi Hari Ini</h2>
            <p class="text-gray-700"><?= $status; ?></p>
            <?php if ($waktu_terakhir): ?>
                <div class="text-gray-600 text-sm mb-2">Waktu presensi terakhir (<?= $jenis_terakhir_presensi ?>): <?= $waktu_terakhir ?></div>
            <?php else: ?>
                <div class="text-gray-600 text-sm mb-2">Belum ada presensi hari ini.</div>
            <?php endif; ?>
            <form method="post" id="formPresensi">
                <input type="hidden" name="presensi" id="jenisPresensi">
                <div class="flex justify-evenly gap-4">
                    <button type="button" onclick="konfirmasiPresensi('Masuk')" 
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600" 
                        <?= $jenis_terakhir_presensi === 'Masuk' ? "disabled" : "" ?>>
                        Presensi Masuk
                    </button>

                    <button type="button" onclick="konfirmasiPresensi('Keluar')" 
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600" 
                        <?= $jenis_terakhir_presensi !== 'Masuk' ? "disabled" : "" ?>>
                        Presensi Keluar
                    </button>
                </div>
            </form>
        </div>

        <!-- Modal Konfirmasi -->
        <div id="modalPresensi" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
            <div class="bg-white p-6 rounded-xl w-80 text-center">
                <h2 class="text-lg font-semibold mb-4">Konfirmasi Presensi</h2>
                <p id="teksKonfirmasi" class="mb-6"></p>
                <div class="flex justify-center gap-4">
                    <button onclick="submitPresensi()" class="px-4 py-2 bg-blue-600 text-white rounded">Ya</button>
                    <button onclick="tutupModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                </div>
            </div>
        </div>

        <!-- Statistik Tambahan -->
        <div class="w-full flex justify-center gap-2 col-span-3">
            <div class="bg-white w-full p-4 rounded-xl shadow text-center">
                <h3 class="text-gray-500 text-sm">Durasi Pelayanan Petugas</h3>
                <p class="text-2xl font-bold text-blue-600"><?= $durasi['total_jam'] ?> Jam <?= $durasi['total_menit'] ?> Menit <?= $durasi['total_detik'] ?> Detik</p>
            </div>
            <div class="bg-white w-full p-4 rounded-xl shadow text-center">
                <h3 class="text-gray-500 text-sm">Rata-Rata Durasi Pelayanan per Pengunjung</h3>
                <p class="text-2xl font-bold text-blue-600"><?= $durasi['rata_jam'] ?> Jam <?= $durasi['rata_menit'] ?> Menit <?= $durasi['rata_detik'] ?> Detik</p>
            </div>
            <div class="bg-white w-full p-4 rounded-xl shadow text-center flex justify-evenly">
                <div>
                    <h3 class="text-gray-500 text-sm">Hari Ini</h3>
                    <p class="text-2xl font-bold text-blue-600"><?= $total_kunjungan['harian']?></p>
                </div>
                <div class="border h-full"></div>
                <div>
                    <h3 class="text-gray-500 text-sm">Bulan Ini</h3>
                    <p class="text-2xl font-bold text-blue-600"><?= $total_kunjungan['bulanan']?></p>
                </div>
                <div class="border h-full"></div>
                <div>
                    <h3 class="text-gray-500 text-sm">Total</h3>
                    <p class="text-2xl font-bold text-blue-600"><?= $total_kunjungan['total']?></p>
                </div>
            </div>
        </div>

        
        <!-- Riwayat Layanan -->
        <div class="bg-white p-6 rounded-xl shadow col-span-2">
            <h3 class="text-lg font-semibold mb-4">Riwayat Layanan</h3>
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-gray-500 text-center">
                        <th class="py-2">Masuk</th>
                        <th>Keluar</th>
                        <th>Pengunjung</th>
                        <th>Instansi</th>
                        <th>Nomor</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($pengunjung_page)): ?>
                    <tr class="border-t">
                        <td class="py-2 px-2"><?= ucwords(strtolower($row['time'])); ?></td>
                        <td class="py-2 px-2"><?= ucwords(strtolower($row['waktu_selesai'])); ?></td>
                        <td class="py-2 px-2"><?= ucwords(strtolower($row['nama'])); ?></td>
                        <td class="py-2 px-2"><?= $row['instansi']; ?></td>
                        <td class="py-2 px-2 text-center font-semibold"><?= $row['nomor_antrian']; ?></td>
                        <td class="py-2 px-2 flex justify-evenly gap-2">
                            <button onclick ='openModalTamu(<?= $row["user_id"]; ?>)' name="detail_tamu" class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-600">Detail</button>
                            <?php if ($row['status'] !== 'Selesai'): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $row['user_id']; ?>">
                                    <button type="submit" name="tandai_selesai" class="bg-green-500 text-white px-5 py-1 rounded hover:bg-green-600">Aktif</button>
                                </form>
                            <?php else: ?>
                                <span class="text-green-600 font-semibold py-1 px-3">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Paginasi -->
            <div class="flex justify-center items-center space-x-1 mt-4 text-sm mb-4 gap-2">
                <?php if ($page > 1): ?>
                    <a href="?page=1" class="px-3 py-1 bg-white border shadow-sm rounded hover:bg-gray-100"><<</a>
                    <a href="?page=<?= $page - 1 ?>" class="px-3 py-1 shadow-sm bg-white border rounded hover:bg-gray-100"><</a>
                <?php endif; ?>

                <?php
                    $range = 2;
                    $start = max(1, $page - $range);
                    $end = min($total_page, $page + $range);

                    if ($start > 1) echo '<span class="px-2">...</span>';

                    for ($i = $start; $i <= $end; $i++):
                ?>
                <a href="?page=<?= $i ?>" class="px-3 py-1 shadow-sm border rounded <?= $i == $page ? 'bg-blue-500 text-white font-bold' : 'bg-white hover:bg-gray-100' ?>">
                    <?= $i ?>
                </a>
                <?php endfor;

                    if ($end < $total_page) echo '<span class="px-2">...</span>';
                ?>

                <?php if ($page < $total_page): ?>
                    <a href="?page=<?= $page + 1 ?>" class="px-3 shadow-sm py-1 bg-white border rounded hover:bg-gray-100">></a>
                    <a href="?page=<?= $total_page ?>" class="px-3 py-1 shadow-sm bg-white border rounded hover:bg-gray-100">>></a>
                <?php endif; ?>
            </div>
        </div>

    <!-- Feedback Pengunjung -->
        <div class="bg-white p-6 rounded-xl shadow col-span-1">
            <h3 class="text-lg font-semibold mb-4">Feedback Terbaru</h3>
            <ul class="space-y-2">
                <?php foreach ($feedback_tamu as $row): ?>
                    <li class="border p-2 rounded">
                        <p class="text-gray-700"><?= htmlspecialchars($row['pesan']) ?></p>
                        <p class="text-yellow-500">⭐<?= $row['kepuasan'] ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Form Edit Profile -->
        <div id="modalEditProfile" class="hidden justify-center items-center fixed inset-0 z-50 backdrop-blur-sm bg-black/30"/>
            <div id="modalCloseEdit" class="bg-white w-[50%] sm:w-[50%] rounded-lg shadow-lg p-4 relative transform transition-all duration-300 scale-95">
                <button onclick="closeModal()" class="absolute top-2 right-4 text-xl font-bold text-blue-800">×</button>
                <h2 class="text-xl font-bold text-blue-900 text-center mb-4">Form Edit Profile</h2>
                <form method="post">
                    <label class="block text-blue-900 font-semibold mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full mb-2 p-2 border rounded">
    
                    <label class="block text-blue-900 font-semibold mb-1">NIP</label>
                    <input type="text" name="instansi" disabled class="w-full mb-2 p-2 border rounded">
    
                    <div class="text-center flex justify-evenly">
                        <button type="submit" name="editProfile" class="bg-blue-900 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                            Ubah Profile!
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <div id="modalDetailTamu" class="hidden fixed inset-0 z-50 backdrop-blur-sm bg-black/30">
        <div id="modalContentDetailTamu" class="bg-white w-[50%] sm:w-[50%] rounded-lg shadow-lg p-4 relative transform transition-all duration-300 scale-95">
            <button onclick="closeModalTamu()" class="absolute top-2 right-4 text-xl font-bold text-blue-800">×</button>
            <h2 class="text-xl font-bold text-blue-900 text-center mb-4">Detail Tamu</h2>
                <label class="block text-blue-900 font-semibold mb-1">Nama Lengkap</label>
                <input type="text" name="nama" disabled class="w-full mb-2 p-2 border rounded">

                <label class="block text-blue-900 font-semibold mb-1">Jenis Kelamin</label>
                <input type="text" name="jenis_kelamin" disabled class="w-full mb-2 p-2 border rounded">

                <label class="block text-blue-900 font-semibold mb-1">Instansi</label>
                <input type="text" name="instansi" disabled class="w-full mb-2 p-2 border rounded">

                <label class="block text-blue-900 font-semibold mb-1">Email</label>
                <input type="email" name="email" disabled class="w-full mb-2 p-2 border rounded">

                <label class="block text-blue-900 font-semibold mb-1">Nomor HP</label>
                <input type="text" name="no_hp" disabled class="w-full mb-2 p-2 border rounded">

                <label class="block text-blue-900 font-semibold mb-1">Media Layanan</label>
                <input type="text" name="media_layanan" disabled class="w-full mb-2 p-2 border rounded">

                <label class="block text-blue-900 font-semibold mb-1">Keperluan</label>
                <input type="text" name="keperluan" disabled class="w-full mb-2 p-2 border rounded">

                <label class="block text-blue-900 font-semibold mb-1">Rincian Keperluan</label>
                <input type="text" name="rincian_keperluan" disabled class="w-full mb-2 p-2 border rounded">
            </div>
    </div>

    <script>
        let jenisDipilih = '';

        function konfirmasiPresensi(jenis) {
            jenisDipilih = jenis;
            document.getElementById('teksKonfirmasi').textContent = `Anda yakin ingin melakukan presensi ${jenis}?`;
            document.getElementById('modalPresensi').classList.remove('hidden');
            document.getElementById('modalPresensi').classList.add('flex');
        }

        function tutupModal() {
            document.getElementById('modalPresensi').classList.remove('flex');
            document.getElementById('modalPresensi').classList.add('hidden');
        }

        function submitPresensi() {
            document.getElementById('jenisPresensi').value = jenisDipilih;
            document.getElementById('formPresensi').submit();
        }

        function openModal(id) {
            const modal = document.getElementById('modalEditProfile');
            const content = document.getElementById('modalCloseEdit');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('modalEditProfile');
            const content = document.getElementById('modalCloseEdit');

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function openModalTamu(id) {
            const modal = document.getElementById('modalDetailTamu');
            const content = document.getElementById('modalContentDetailTamu');

            modal.classList.remove('hidden');
            modal.classList.add('flex', 'items-center', 'justify-center');

            // Ambil data detail dari server
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

        function closeModalTamu() {
            document.getElementById('modalDetailTamu').classList.remove('flex');
            document.getElementById('modalDetailTamu').classList.add('hidden');
        }
    </script>

    </body>
</html>
