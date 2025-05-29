<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard SILAILA</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome/css/all.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    
    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.js"></script>


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-text mx-3">Dashboard Monitoring SILAILA </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

           

            <!-- Heading -->
            <div class="sidebar-heading">
                Tabel
            </div>

            <!-- Nav Item - Tables Pengunjung -->
            <li class="nav-item">
                <a class="nav-link" href="datapengunjung.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Pengunjung</span></a>
            </li>

            <!-- Nav Item - Tables Kunjungan -->
            <li class="nav-item">
                <a class="nav-link" href="datakunjungan.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Kunjungan</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 large">Admin SILAILA</span>
                                <img class="img-profile rounded-circle"
                                    src="img/silaila2.png">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>
                   
                    <?php
                        //panggil koneksi database
                        include "koneksi.php";
                    
                            $query = "SELECT  (SELECT COUNT(*) FROM penilaian WHERE kepuasan = 'Puas') as jmlh_puas, (SELECT COUNT(*) FROM penilaian WHERE kepuasan = 'Kurang Puas') as jmlh_kurang_puas, (SELECT COUNT(*) FROM penilaian WHERE kepuasan = 'Tidak Puas') as jmlh_tidak_puas FROM penilaian";
                            //tampilkan data dari tabel penilaian
                            $penilaian = mysqli_fetch_array(mysqli_query($koneksi, $query));
                            $pengunjung = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(user_id) AS Jumlah_Pengunjung FROM penilaian"));

                    ?>
                   

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Jumlah Pengunjung -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-lg font-weight-bold text-primary text-uppercase mb-1">
                                                Pengunjung</div>
                                            <div class="h3 mb-0 font-weight-bold text-gray-800"><?=$pengunjung['Jumlah_Pengunjung']?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-3x text-gray-500"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Puas -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-lg font-weight-bold text-success text-uppercase mb-1">
                                                Puas</div>
                                            <div class="h3 mb-0 font-weight-bold text-gray-800"><?=$penilaian['jmlh_puas']?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-face-smile fa-3x text-gray-500"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kurang Puas -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-lg font-weight-bold text-warning text-uppercase mb-1">Kurang Puas
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h3 mb-0 mr-3 font-weight-bold text-gray-800"><?=$penilaian['jmlh_kurang_puas']?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-face-meh fa-3x text-gray-500"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tidak Puas -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-lg font-weight-bold text-danger text-uppercase mb-1">
                                               Tidak Puas</div>
                                            <div class="h3 mb-0 font-weight-bold text-gray-800"><?=$penilaian['jmlh_tidak_puas']?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-face-frown fa-3x text-gray-500"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <!-- Content Row -->
                    <?php
                        //panggil koneksi database
                        include "koneksi.php";
                        $query = "SELECT DATE(time) AS tanggal, COUNT(*) as jmlh_kunjungan FROM penilaian GROUP BY tanggal";
                        $kunjungan = mysqli_query($koneksi, $query);
                        $dummy = [];
                        while($row = $kunjungan->fetch_row()) {
                            $dummy[] = $row;
                        }
                        $label = [];
                        $jumlah_kunjungan = [];
                        
                        foreach($dummy as $index => $item) {
                            $label[$index] = $item[0];
                            $jumlah_kunjungan[$index] = $item[1];
                        }


                    ?>
                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Jumlah Kunjungan</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                    <script>
                                    var ctx = document.getElementById("myAreaChart");
                                    var myLineChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: <?= json_encode($label) ?>,
                                        datasets: [{
                                            label: "Pengunjung",
                                            lineTension: 0.3,
                                            backgroundColor: "rgba(78, 115, 223, 0.05)",
                                            borderColor: "rgba(78, 115, 223, 1)",
                                            pointRadius: 3,
                                            pointBackgroundColor: "rgba(78, 115, 223, 1)",
                                            pointBorderColor: "rgba(78, 115, 223, 1)",
                                            pointHoverRadius: 3,
                                            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                                            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                                            pointHitRadius: 10,
                                            pointBorderWidth: 2,
                                            data: 
                                            <?= json_encode($jumlah_kunjungan)?>, 
                                    }]
                                    },
                                    options: {
                                        maintainAspectRatio: false,
                                        layout: {
                                            padding: {
                                                left: 10,
                                                right: 25,
                                                top: 25,
                                                bottom: 0
                                            }
                                        },
                                        scales: {
                                            xAxes: [{
                                                time: {
                                                    unit: 'date'
                                                },
                                                gridLines: {
                                                    display: false,
                                                    drawBorder: false
                                                },
                                                ticks: {
                                                    maxTicksLimit: 7
                                                }
                                        }],
                                        yAxes: [{
                                            ticks: {
                                                maxTicksLimit: 5,
                                                padding: 10
                                            },
                                            gridLines: {
                                                color: "rgb(234, 236, 244)",
                                                zeroLineColor: "rgb(234, 236, 244)",
                                                drawBorder: false,
                                                borderDash: [2],
                                                zeroLineBorderDash: [2]
                                            }
                                        }],
                                        },
                                        legend: {
                                            display: false
                                        },
                                        tooltips: {
                                            backgroundColor: "rgb(255,255,255)",
                                            bodyFontColor: "#858796",
                                            titleMarginBottom: 10,
                                            titleFontColor: '#6e707e',
                                            titleFontSize: 14,
                                            borderColor: '#dddfeb',
                                            borderWidth: 1,
                                            xPadding: 15,
                                            yPadding: 15,
                                            displayColors: false,
                                            intersect: false,
                                            mode: 'index',
                                            caretPadding: 10
                                        }
                                    }
                                    });
                                    </script>    
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Pengunjung Berdasarkan Jenis Kelamin</h6>
                                </div>
                                <!-- Card Body -->
                                


                                <div class="card-body">
                                    <div class="chart-pie pt-5 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <script>
                                        var ctx = document.getElementById("myPieChart").getContext('2d');
                                        var myPieChart = new Chart(ctx, {
                                            type: 'pie',
                                            data: {
                                                labels: ["Laki-laki", "Perempuan"],
                                                datasets: [{
                                                    label: '',
                                                    data: [
                                                    <?php 
                                                    $jumlah_laki = mysqli_query($koneksi,"SELECT * FROM pengunjung WHERE jenis_kelamin='Laki-laki'");
                                                    echo mysqli_num_rows($jumlah_laki);
                                                    ?>, 
                                                    <?php 
                                                    $jumlah_perempuan = mysqli_query($koneksi,"SELECT * FROM pengunjung WHERE jenis_kelamin='Perempuan'");
                                                    echo mysqli_num_rows($jumlah_perempuan);
                                                    ?>, 
                                                    ],
                                                    backgroundColor: [
                                                    'rgba(54, 162, 235, 1)',
                                                    'rgba(255,99,132,1)'
                                                    ],
                                                    borderColor: [
                                                    'rgba(54, 162, 235, 1)',
                                                    'rgba(255,99,132,1)'
                                                    ],
                                                    borderWidth: 1
                                                }]
                                            },
                                        });
                                    </script>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Created by &copy; Dina Salsabila - PST BPS Kabupaten Pulang Pisau</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="index.php">Logout</a>
                </div>
            </div>
        </div>
    </div>




</body>

</html>