<?php
    session_start();
    if( isset ($_SESSION["login"]) ) {
        header("Location: beranda.php");
        exit;
    }
    include "functions.php";

    if(isset($_POST['proseslogin'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = mysqli_query($koneksi, "SELECT * FROM login WHERE username = '$username' AND password = '$password'");

        $cek = mysqli_num_rows($sql);

        if($cek > 0) {
            $data = mysqli_fetch_assoc($sql);
            $_SESSION['login'] = true;
            $_SESSION['username'] = $data['username'];
            $_SESSION['nip'] = $data['nip'];
            $_SESSION['nama'] = $data['nama'];

            header("Location: beranda.php");
            exit;
        } else {
            echo "<div class='text-center text-red-500 py-2'><b> Username dan Password Salah! </b></div>";
        }
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="./css/style.css"/>
        <title>LOGIN SILAILA</title>
    </head>
    <body>
        <div class="w-full min-h-screen bg-slate-200 flex justify-center items-center">
            <div class="w-[25%] flex flex-col justify-center items-center">
                <div class="">
                    <img src="../img/silaila2.png" class="w-32 h-32 object-center" alt="">
                </div>
                <div class="w-full shadow-lg bg-blue-600 rounded-lg my-2">
                    <h1 class="font-bold text-center text-3xl pt-4 text-slate-200">LOGIN SILAILA</h1>
                    <h3 class="text-center font-semibold mb-4 text-slate-200">Sistem Informasi Pelayanan dan Pelaporan</h3>
                    <form action="" method="POST">
                        <div class="relative flex items-center px-4 mb-4" id="secemail;">
                            <input type="text" id="username" name="username"
                                class="border w-full text-sm px-2 py-2 focus:outline-none focus:ring-0 focus:border-hijau01 rounded-md"
                                placeholder="username">
                            <div class="absolute right-8">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </div>
                        </div>
                        <div class="relative flex items-center px-4 mb-4" id="secpass;">
                            <input type="password" id="password" name="password"
                                class="border w-full text-sm px-2 py-2 focus:outline-none focus:ring-0 focus:border-hijau01 rounded-md"
                                placeholder="password">
                            <div class="absolute right-8">
                                <div class="w-full bg-transparent rounded-lg hover:scale-105 active:scale-90" id="lockicon" onclick="toggle()">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>    
                                </div>
                            </div>
                        </div>
                        <div class="mt-3  w-full text-center">
                            <button name="proseslogin" type="submit" class=" bg-yellow-400 w-[50%] py-1 rounded-md font-semibold hover:scale-105 active:scale-90 mb-4 hover:text-blue-400">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> 
    </body>
    <script>
        let state= false;
        function toggle(){
            if(state){
                document.getElementById("password").setAttribute("type","password");
                document.getElementById("lockicon").setAttribute("class","bg-transparent hover:scale-105 active:scale-90");
                state = false;
            } else{
                document.getElementById("password").setAttribute("type","text");
                document.getElementById("lockicon").setAttribute("class","bg-blue-400 rounded-lg hover:scale-105 active:scale-90");
                state = true;
            }
        }
    </script>
</html>

<!-- <div class="container-fluid bg-info text-center mb-0 bt-3 pb-3">
            <img src="img/silaila2.png" class="media-object" style = "width:150px">  
            <h3> <strong> SILAILA </strong> </h3>
            <h4> <strong> Kepuasan Pengunjung <br> Terhadap Pelayanan BPS Kabupaten Pulang Pisau </strong> </h4> 
        </div>

        <div class="container-fluid bg-warning text-center mb-0 mt-0 pb-1 pt-1">
            <h5> LOGIN ADMIN </h5>
        </div> 


        <div class="container-fluid bg-primary pb-4 pt-5">
            <div class="row justify-content-center">    
                <form action="" method="POST">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="username">Username:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="username"  name="username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="password">Password:</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password"  name="password">
                        </div>
                    </div>
                    <div class="container text-center">
                        <input type="submit" class="btn btn-success mb-1" value="Login" name="proseslogin">
                    </div>
                </form>
            </div>
        </div>

        
        <footer class="bg-info text-center text-white mt-0 bt-0 pt-1 pb-1">
            Created by Dina Salsabila - PST BPS Kabupaten Pulang Pisau 
        </footer> -->