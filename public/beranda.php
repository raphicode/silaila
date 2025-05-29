<?php 
    session_start();
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
    <title>Beranda Admin</title>
</head>
<body class="flex">
    <?php
        include "sidebar.php";
        $jumlah_pengunjung = query("SELECT COUNT(*) AS total FROM pengunjung")[0]['total'];
        $tanggal_hari_ini = date("Y-m-d");
        $pengunjung_hari_ini = query("SELECT COUNT(*) AS total FROM pengunjung WHERE DATE(time) = '$tanggal_hari_ini'")[0]['total'];
        $jumlah_kunjungan_langsung = query("SELECT COUNT(*) AS total FROM pengunjung WHERE media_layanan = 'Kunjungan Langsung'")[0]['total'];
        $jumlah_kunjungan_wa = query("SELECT COUNT(*) AS total FROM pengunjung WHERE media_layanan = 'Kunjungan Melalui Whatsapp'")[0]['total'];

        $linechart = query("SELECT DATE(time) AS tanggal,
                        SUM(CASE WHEN media_layanan = 'Kunjungan Langsung' THEN 1 ELSE 0 END) AS offline,
                        SUM(CASE WHEN media_layanan = 'Kunjungan Melalui Whatsapp' THEN 1 ELSE 0 END) AS online,COUNT(*) AS jumlah
                    FROM pengunjung
                    WHERE time IS NOT NULL
                    GROUP BY tanggal
                    ORDER BY tanggal ASC
        ");

        $tanggal = [];
        $jumlah = [];
        foreach ($linechart as $row) {
            if ($row['tanggal'] !== NULL) {
                $tanggal[] = $row['tanggal'];
                $jumlah[] = (int) $row['jumlah'];
                $jumlah_offline[] = (int) $row['offline'];
                $jumlah_online[] = (int) $row['online'];
            }
        }

        // Konfigurasi paginasi
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Ambil total data
        $total_query = $koneksi->query("SELECT COUNT(*) AS total FROM penilaian");
        $total_data = $total_query->fetch_assoc()['total'];
        $total_page = ceil($total_data / $limit);

        // Ambil data sesuai halaman
        $penilaian = $koneksi->query("SELECT * FROM penilaian ORDER BY id_penilaian DESC LIMIT $limit OFFSET $offset");

        // Ambil Petugas
        $petugas = $koneksi->query("SELECT 
                            l.nip, 
                            l.nama, 
                            l.rating_rata_rata,
                            COUNT(p.user_id) AS jumlah_layanan,
                            SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, p.time, p.waktu_selesai))) AS total_durasi,
                            SEC_TO_TIME(ROUND(AVG(TIMESTAMPDIFF(SECOND, p.time, p.waktu_selesai)))) AS rata_rata_durasi
                        FROM login l
                        JOIN pengunjung p 
                            ON l.nip = p.nip_petugas 
                            AND p.time IS NOT NULL 
                            AND p.waktu_selesai IS NOT NULL 
                        GROUP BY l.nip")
    ?>
    <div class="p-6 space-y-6 w-full ml-[300px]">
    <!-- Judul -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Admin Buku Tamu</h1>
            <div class="relative">
                <!-- Tombol utama -->
                <button onclick="document.getElementById('modal').classList.remove('hidden');document.getElementById('modal').classList.add('flex');" class="bg-blue-600 shadow-lg text-white rounded-lg text-lg px-4 py-2 hover:bg-blue-700">
                    Cetak Laporan Bulanan
                </button>

                <!-- Modal -->
                <div id="modal" class="fixed inset-0 bg-black bg-opacity-50  items-center justify-center hidden z-50">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
                        <h2 class="text-xl font-bold mb-4 text-center">Pilih Bulan dan Tahun</h2>
                        <form action="cetaklaporan.php" method="GET" class="space-y-4">
                        <!-- Pilihan Tahun -->
                        <div>
                            <label for="tahun" class="block text-gray-700 mb-1">Tahun</label>
                            <select name="tahun" id="tahun" class="w-full p-2 border rounded">
                            <!-- Tambahkan tahun secara manual atau lewat JavaScript -->
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            </select>
                        </div>
                        <!-- Pilihan Bulan -->
                        <div>
                            <label for="bulan" class="block text-gray-700 mb-1">Bulan</label>
                            <select name="bulan" id="bulan" class="w-full p-2 border rounded">
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                            </select>
                        </div>
                        <!-- Tombol Kirim -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="document.getElementById('modal').classList.remove('flex');document.getElementById('modal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Cetak</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    
    <!-- Kartu Statistik -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="bg-white shadow rounded p-4 text-center">
                <div class="text-gray-500 text-2xl">
                    <div class="mb-4">
                        Jumlah Pengunjung Total
                    </div>
                    <div class="text-6xl font-bold h-full flex justify-center items-center">
                        <?= $jumlah_pengunjung ?>
                    </div>
                    
                </div>
            </div>
            <div class="bg-white shadow rounded p-4 text-center">
                <div class="text-gray-500 text-2xl">
                    <div class="mb-4">
                        Pengunjung Hari Ini
                    </div>
                    <div class="text-6xl font-bold h-full flex justify-center items-center">
                        <?= $pengunjung_hari_ini ?>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-500 text-sm mb-2">Pengunjung Berdasarkan Kategori</div>
                <!-- Placeholder untuk chart -->
                <div class="w-full bg-gray-100 rounded flex items-center justify-center text-sm text-gray-400">
                    <div>
                        <canvas width="200px" height="150px" id="piechart" class=""></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan Kunjungan -->
        <div class="bg-white shadow rounded p-4">
            <div class="text-gray-500 text-sm mb-2">Laporan Kunjungan</div>
            <div class="w-full bg-gray-100 rounded flex items-center justify-center text-sm text-gray-400">
                <div>
                    <canvas width="500px" height="400px" id="line" class=""></canvas>
                </div>
            </div>
        </div>

        <!-- Tabel Performa Petugas -->
        <div class="bg-white shadow rounded p-4">
            <div class="text-lg font-semibold mb-4">Performa Petugas Pelayanan</div>
            <div class="overflow-auto">
                <table class="min-w-full table-auto text-sm text-left">
                    <thead>
                    <tr class="text-gray-600 border-b">
                        <th class="px-4 py-2 text-center">Nama Petugas</th>
                        <th class="px-4 py-2 text-center">Rating</th>
                        <th class="px-4 py-2 text-center">Jumlah Layanan</th>
                        <th class="px-4 py-2 text-center">Durasi Pelayanan</th>
                        <th class="px-4 py-2 text-center">Rata-Rata Durasi Pelayanan</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($petugas as $pelayan) : ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?= $pelayan["nama"]; ?></td>
                                <td class="px-4 py-2 text-center"><?= $pelayan["rating_rata_rata"]; ?></td>
                                <td class="px-4 py-2 text-center"><?= $pelayan["jumlah_layanan"]; ?></td>
                                <td class="px-4 py-2 text-center"><?= $pelayan["total_durasi"]; ?></td>
                                <td class="px-4 py-2 text-center"><?= $pelayan["rata_rata_durasi"]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Feedback -->
        <div class="bg-white shadow rounded p-4">
            <div class="text-lg font-semibold mb-4">Pencapaian & Feedback</div>
            <div class="overflow-auto">
                <table class="min-w-full table-auto text-sm text-left">
                    <thead>
                    <tr class="text-gray-600 border-b">
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Kebutuhan</th>
                        <th class="px-4 py-2">Tingkat Kepuasan</th>
                        <th class="px-4 py-2">Pesan/Kesan</th>
                        <th class="px-4 py-2">Waktu Penilaian</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($penilaian as $tamu) : ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?= $tamu["nama"]; ?></td>
                                <td class="px-4 py-2"><?= $tamu["kebutuhan"]; ?></td>
                                <td class="px-4 py-2"><?= $tamu["kepuasan"]; ?></td>
                                <td class="px-4 py-2"><?= $tamu["pesan"]; ?></td>
                                <td class="px-4 py-2"><?= $tamu["time"]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx1 = document.getElementById('piechart');
        new Chart(ctx1, {
            type: 'pie',
            data: {
            labels: ['Langsung', 'Online'],
            datasets: [{
                label: 'Jumlah',
                data: [<?= $jumlah_kunjungan_langsung ?>, <?= $jumlah_kunjungan_wa ?> ],
                borderWidth: 1
            }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                    position: 'right',
                    labels: {
                        font: {
                            size: 12
                            },
                    boxWidth: 20
                        } 
                    }
                }    
            },
        });
    </script>
    <script>
        const labels = <?php echo json_encode($tanggal); ?>;
        const data = <?php echo json_encode($jumlah); ?>;
        const dataOnline = <?php echo json_encode($jumlah_online); ?>;
        const dataOffline = <?php echo json_encode($jumlah_offline); ?>;

        const ctx2 = document.getElementById('line').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Jumlah Pengunjung',
                        data: data,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 0, 255, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Online',
                        data: dataOnline,
                        borderColor: 'green',
                        backgroundColor: 'rgba(0, 255, 0, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Offline',
                        data: dataOffline,
                        borderColor: 'red',
                        backgroundColor: 'rgba(255, 0, 0, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
            ]
            },
            options: {
            responsive: true,
            scales: {
                x: {
                    title: { display: true, text: 'Tanggal' },
                    ticks: {
                        maxRotation: 90,
                        minRotation: 45
                    },
                    grid: {
                        display: false
                    }
                },
                y: {
                    title: { display: true, text: 'Jumlah Pengunjung' },
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                }
            }
            }
        });
    </script>
</body>
</html>