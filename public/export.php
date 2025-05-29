<?php
    include "functions.php";
    header('Content-Type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=Data_Pengunjung.xls');
?>

<center>
    <h2>Data Pengunjung</h2>
</center>

<table border = "1">
    <tr>
        <th>Nama</th>
        <th>Jenis Kelamin</th>
        <th>Instansi</th>
        <th>Email</th>
        <th>Nomor HP</th>
        <th>Media Layanan</th>
        <th>Keperluan</th>
        <th>Rincian Keperluan</th>
        <th>Waktu Datang</th>
        <th>Nomor Antrian</th>
        <th>Nama Petugas</th>
        <th>Waktu Selesai</th>
    </tr>

    <?php   
    $pengunjung = mysqli_query($koneksi, "SELECT * FROM pengunjung" );
    while($data = mysqli_fetch_assoc($pengunjung)) :    
    ?>
    <tr>
        <td> <?= $data["nama"] ?> </td>
        <td> <?= $data["jenis_kelamin"] ?> </td>
        <td> <?= $data["instansi"] ?> </td>
        <td> <?= $data["email"] ?> </td>
        <td> <?= $data["no_hp"] ?> </td>
        <td> <?= $data["media_layanan"] ?> </td>
        <td> <?= $data["keperluan"] ?> </td>
        <td> <?= $data["rincian_keperluan"] ?> </td>
        <td> <?= $data["time"] ?> </td>
        <td> <?= $data["nomor_antrian"] ?> </td>
        <td> <?= $data["nama_petugas"] ?> </td>
        <td> <?= $data["waktu_selesai"] ?> </td>
    </tr>
    <?php endwhile ?>
</table>


