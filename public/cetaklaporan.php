<?php
require_once __DIR__ . '/../vendor/autoload.php';
include 'functions.php'; // file koneksi ke database

$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];

// Query statistik
$queryTotal = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pengunjung WHERE MONTH(time) = $bulan AND YEAR(time) = $tahun");
$queryStatus = mysqli_query($koneksi, "
    SELECT status, COUNT(*) as jumlah 
    FROM pengunjung
    WHERE MONTH(time) = $bulan AND YEAR(time) = $tahun 
    GROUP BY status
");
$queryMedia = mysqli_query($koneksi, "
    SELECT media_layanan, COUNT(*) as jumlah 
    FROM pengunjung
    WHERE MONTH(time) = $bulan AND YEAR(time) = $tahun 
    GROUP BY media_layanan
");
$queryLayanan = mysqli_query($koneksi, "
    SELECT nama, instansi, keperluan, time, waktu_selesai 
    FROM pengunjung
    WHERE MONTH(time) = $bulan AND YEAR(time) = $tahun 
    ORDER BY time ASC
");

$bulanNama = date('F', mktime(0, 0, 0, $bulan, 10)); // Nama bulan

// Siapkan HTML
$html = "
<h2 style='text-align:center;'>Laporan Kunjungan Bulanan</h2>
<h4 style='text-align:center;'>Bulan: $bulanNama $tahun</h4>
<hr>
";

// Total pengunjung
$rowTotal = mysqli_fetch_assoc($queryTotal);
$html .= "<p><strong>Total Pengunjung:</strong> " . $rowTotal['total'] . "</p>";


// Statistik media
$html .= "<h4>Statistik Berdasarkan Media Layanan:</h4><ul>";
while ($row = mysqli_fetch_assoc($queryMedia)) {
    $html .= "<li>{$row['media_layanan']}: {$row['jumlah']}</li>";
}
$html .= "</ul>";

// Tabel data layanan
$html .= "<h4>Daftar Layanan:</h4>
<table border='1' cellpadding='5' cellspacing='0' width='100%'>
<thead>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Instansi</th>
    <th>Keperluan</th>
    <th>Waktu Masuk</th>
    <th>Waktu Selesai</th>
</tr>
</thead>
<tbody>";
$no = 1;
while ($row = mysqli_fetch_assoc($queryLayanan)) {
    $html .= "<tr>
        <td>{$no}</td>
        <td>{$row['nama']}</td>
        <td>{$row['instansi']}</td>
        <td>{$row['keperluan']}</td>
        <td>{$row['time']}</td>
        <td>{$row['waktu_selesai']}</td>
    </tr>";
    $no++;
}
$html .= "</tbody></table>";

// Generate PDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);

// Query performa petugas per bulan
$queryPerformaPetugas = mysqli_query($koneksi, "
    SELECT 
        l.nip, 
        l.nama, 
        l.rating_rata_rata,
        COUNT(p.user_id) AS jumlah_layanan,
        SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, p.time, p.waktu_selesai))) AS total_durasi,
        SEC_TO_TIME(ROUND(AVG(TIMESTAMPDIFF(SECOND, p.time, p.waktu_selesai)))) AS rata_rata_durasi
    FROM login l
    JOIN pengunjung p 
        ON l.nip = p.nip_petugas 
        AND MONTH(p.time) = $bulan 
        AND YEAR(p.time) = $tahun
        AND p.time IS NOT NULL 
        AND p.waktu_selesai IS NOT NULL 
    GROUP BY l.nip
");

// Tambah halaman performa petugas
$mpdf->AddPage();

$htmlPerforma = "
<h2 style='text-align:center;'>Laporan Performa Petugas Bulanan</h2>
<h4 style='text-align:center;'>Bulan: $bulanNama $tahun</h4>
<hr>
<table border='1' cellpadding='5' cellspacing='0' width='100%'>
<thead>
<tr>
    <th>No</th>
    <th>NIP</th>
    <th>Nama Petugas</th>
    <th>Total Layanan</th>
    <th>Durasi Pelayanan</th>
    <th>Rata-Rata Durasi Pelayanan</th>
    <th>Rating Petugas</th>
</tr>
</thead>
<tbody>
";

$no = 1;
while ($row = mysqli_fetch_assoc($queryPerformaPetugas)) {
    $htmlPerforma .= "<tr>
        <td>{$no}</td>
        <td>{$row['nip']}</td>
        <td>{$row['nama']}</td>
        <td>{$row['jumlah_layanan']}</td>
        <td>{$row['total_durasi']}</td>
        <td>{$row['rata_rata_durasi']}</td>
        <td>{$row['rating_rata_rata']}</td>
    </tr>";
    $no++;
}

$htmlPerforma .= "</tbody></table>";

$mpdf->WriteHTML($htmlPerforma);

// Query Pengunjung Baru Bulan Ini Berdasarkan Nomor Telepon
$mpdf->AddPage();
$queryPengunjungBaru = mysqli_query($koneksi, "
                        WITH kunjungan_bulan_ini AS (
                        SELECT 
                            nama, instansi, no_hp, time,
                            ROW_NUMBER() OVER (PARTITION BY no_hp ORDER BY time ASC) AS rn
                        FROM pengunjung p1
                        WHERE MONTH(time) = $bulan AND YEAR(time) = $tahun
                        AND NOT EXISTS (
                            SELECT 1 FROM pengunjung p2
                            WHERE p2.no_hp = p1.no_hp
                            AND (
                                YEAR(p2.time) < $tahun OR 
                                (YEAR(p2.time) = $tahun AND MONTH(p2.time) < $bulan)
                            )
                        )
                    )
                    SELECT nama, instansi, no_hp, time
                    FROM kunjungan_bulan_ini
                    WHERE rn = 1
                    ORDER BY time ASC;
");

$htmlUniqueVisitor .= "<h2 style='text-align:center;'>Pengunjung Baru untuk Survei Kepuasan Data (SKD)</h2>
<h4 style='text-align:center;'>Bulan: $bulanNama $tahun</h4>
<hr>
<table border='1' cellpadding='5' cellspacing='0' width='100%'>
<thead>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Instansi</th>
    <th>No HP</th>
    <th>Waktu Kedatangan</th>
</tr>
</thead>
<tbody>";
$no = 1;
while ($row = mysqli_fetch_assoc($queryPengunjungBaru)) {
    $htmlUniqueVisitor .= "<tr>
        <td>{$no}</td>
        <td>{$row['nama']}</td>
        <td>{$row['instansi']}</td>
        <td>{$row['no_hp']}</td>
        <td>{$row['time']}</td>
    </tr>";
    $no++;
}
$htmlUniqueVisitor .= "</tbody></table>";
$mpdf->WriteHTML($htmlUniqueVisitor);
$mpdf->Output("Laporan_Bulanan_{$bulan}_{$tahun}.pdf", "I"); 
?>