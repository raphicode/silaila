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
</head>
<body>
    <?php
        include "sidebar.php";
        $pengunjung = query("SELECT * FROM pengunjung ORDER BY time DESC");
    ?>
    <div class="p-6 space-y-6 w-full ml-[300px] ">
        <form method="post" action="export.php">
            <button class="bg-slate-400 hover:bg-slate-300 shadow-lg rounded-md p-2" type="submit" name="export">Export Data</button>
        </form>
        <div class="my-2 w-full pt-4 pb-4  sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8 flex justify-center">
            <div class="">
                <table class="w-full table-fixed border shadow-lg" style="table-layout: fixed;" border="1" cellpadding="10" cellspacing="0" id="userTable">
                    <thead>
                        <tr class="text-xs text-center bg-slate-400 leading-4 font-bold text-black uppercase tracking-wider">
                            <th class="px-6 py-3 border-b w-[200px] border-gray-200 text-center">Nama</th>
                            <th class="px-6 py-3 border-b w-[150px] border-gray-200 text-center">Instansi</th>
                            <th class="px-6 py-3 border-b w-[150px] border-gray-200 text-center">Email</th>
                            <th class="px-6 py-3 border-b w-[150px] border-gray-200 text-center">No. HP</th>
                            <th class="px-6 py-3 border-b w-[150px] border-gray-200 text-center">Media Layanan</th>
                            <th class="px-6 py-3 border-b w-[150px] border-gray-200 text-center">Keperluan</th>
                            <th class="px-6 py-3 border-b w-[150px] border-gray-200 text-center">Nama Petugas</th>
                            <th class="px-6 py-3 border-b w-[150px] border-gray-200 text-center">Waktu Berkunjung</th>
                            <th class="px-6 py-3 border-b w-[150px] border-gray-200 text-center">Waktu Selesai</th>
                        </tr>
                    </thead>
                    <tbody class="text-black bg-slate-300/70 truncate">
                        <?php foreach ($pengunjung as $tamu) : ?>
                            <tr>
                                <td title="<?= $tamu['nama']; ?>" class="w-[200px] px-6 py-4 border-b border-gray-200 truncate whitespace-nowrap text-sm leading-5">
                                    <div class="text-sm truncate leading-5 font-medium"><?= $tamu["nama"]; ?></div>
                                </td>
                                <td title="<?= $tamu['instansi']; ?>" class="w-[150px] px-6 py-4 border-b border-gray-200 truncate whitespace-nowrap text-sm leading-5">
                                    <div class="text-sm truncate leading-5"><?= $tamu["instansi"]; ?></div>
                                </td>
                                <td title="<?= $tamu['email']; ?>" class="w-[150px] px-6 py-4 border-b border-gray-200 truncate whitespace-nowrap text-sm leading-5 max-w-[150px] overflow-hidden">
                                    <div class="truncate overflow-hidden whitespace-nowrap w-full"><?= $tamu["email"]; ?></div>
                                </td>
                                <td title="<?= $tamu['no_hp']; ?>" class="w-[100px] px-6 py-4 border-b border-gray-200 truncate whitespace-nowrap text-sm leading-5">
                                    <div class="text-sm leading-5"><?= $tamu["no_hp"]; ?></div>
                                </td>
                                <td title="<?= $tamu['media_layanan']; ?>" class="w-[100px] px-6 py-4 border-b border-gray-200 truncate whitespace-nowrap text-sm leading-5">
                                    <div class="text-sm truncate leading-5"><?= $tamu["media_layanan"]; ?></div>
                                </td>
                                <td title="<?= $tamu['keperluan']; ?>" class="w-[100px] px-6 py-4 border-b border-gray-200 truncate whitespace-nowrap text-sm leading-5">
                                    <div class="text-sm truncate leading-5"><?= $tamu["keperluan"]; ?></div>
                                </td>
                                <td title="<?= $tamu['nama_petugas']; ?>" class="w-[100px] px-6 py-4 border-b border-gray-200 truncate whitespace-nowrap text-sm leading-5">
                                    <div class="text-sm truncate leading-5"><?= $tamu["nama_petugas"]; ?></div>
                                </td>
                                <td title="<?= $tamu['time']; ?>" class="w-[100px] px-6 py-4 border-b border-gray-200 truncate whitespace-nowrap text-sm leading-5">
                                    <div class="text-sm leading-5"><?= $tamu["time"]; ?></div>
                                </td>
                                <td title="<?= $tamu['waktu_selesai']; ?>" class="w-[100px] px-4 py-4 border-b border-gray-200 truncate whitespace-nowrap text-sm leading-5">
                                    <div class="text-sm leading-5"><?= $tamu["waktu_selesai"]; ?></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    


    </body>
</html>
<!-- <div class="container-fluid bg-info text-center mb-0 bt-2 pb-2">
        <img src="img/silaila2.png" class="media-object" style = "width:120px">  
        <h3> <strong> SILAILA </strong> </h3>
        <h4> <strong> Kepuasan Pengunjung <br> Terhadap Pelayanan BPS Kabupaten Pulang Pisau </strong> </h4> 
    </div>
    

    <div class="container-fluid bg-warning text-center mb-0 mt-0 pb-1 pt-1">
        <h5> I. SILAKAN ISI DATA DIRI ANDA </h5>
    </div>  -->

<!-- <div class="container-fluid bg-primary pt-3">
        <form class="form-horizontal" action="simpan.php" method="post">
            <div class="row justify-content-center">
                <div class="col-md-5 pl-0 pr-0">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="nama"><strong>Nama Lengkap:</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama" placeholder="Masukkan nama" name="nama" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3 radio" for="jenis_kelamin"><strong>Jenis Kelamin: </strong></label>
                            <div class= "form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="Laki-laki" value="Laki-laki" required>
                                <label class="form-check-label text-white" for="Laki-laki"> Laki-laki </label>
                            </div>
                            <div class= "form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="Perempuan" value="Perempuan" required>
                                <label class="form-check-label text-white" for="Perempuan"> Perempuan </label>
                            </div>
                        </div>   
                        <div class="form-group">
                            <label class="control-label col-sm-5" for="instansi"><strong>Asal Instansi/Institusi:</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="instansi" placeholder="Masukkan nama asal instansi/institusi" name="instansi" required>
                            </div>
                        </div>             
                </div>
                <div class="col-md-5 pl-0 pr-0">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email"><strong>Email:</strong></label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" placeholder="Masukkan email" name="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="no_hp"><strong>Nomor HP:</strong></label>
                            <div class="col-sm-10">
                                <input type="tel" class="form-control" id="no_hp" placeholder="Masukkan nomor HP" name="no_hp" required>
                            </div>
                        </div> 
                </div>
            </div>

            <div class="container text-center pb-3">
                <input type="submit" class="btn btn-success mb-1" name="simpan" value="Selanjutnya">
            </div>
        </form>
    </div> 

    

    <footer class="bg-info text-center text-white mt-0 bt-0 pt-0 pb-1">
      Created by Dina Salsabila - PST BPS Kabupaten Pulang Pisau 
    </footer> -->
