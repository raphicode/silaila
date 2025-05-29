<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"/>
    <title>Laporan Pengaduan</title>
</head>
<body class="w-full bg-slate-200 bg-cover">
    <?php
        include "functions.php";
        include "navbar.php";
    ?>
    <div class="w-full">
        <div class="flex justify-center mt-4 mb-4">
            <img src="../img/silaila2.png" alt="" width="200">
        </div>
        <div class="text-center w-full pb-[4px]">
            <h1 class="text-xl font-bold text-blue-950 mb-4">LAPORAN PENGADUAN</h1>
        </div>
        <div class="w-full flex justify-center text-center">
            <div class="lg:w-full lg:flex justify-evenly ">
                <div class="w-full lg:w-[25%] pb-4">
                    <a class="mb-2" href="https://lapor.go.id" target="_blank" rel="noopener noreferrer">
                        <div class="bg-white font-bold p-2 rounded-lg shadow-lg active:scale-[95%] hover:text-slate-200 border-blue-900 hover:bg-blue-900">
                            SP4N LAPOR
                        </div>
                    </a>
                </div>
                <div class="w-full lg:w-[25%] pb-4">
                    <a href="https://webapps.bps.go.id/pengaduan" target="_blank" rel="noopener noreferrer">
                        <div class="bg-white font-bold p-2 rounded-lg shadow-lg active:scale-[95%] hover:text-slate-200 border-blue-900 hover:bg-blue-900">
                            WHISTLE BLOWING BPS
                        </div>
                    </a>
                </div>
                <div class="w-full lg:w-[25%]">
                    <a href="https://s.bps.go.id/wbs6210" target="_blank" rel="noopener noreferrer">
                        <div class="bg-white font-bold p-2 rounded-lg shadow-lg active:scale-[95%] hover:text-slate-200 border-blue-900 hover:bg-blue-900">
                            LAPOR BPS 6210
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>