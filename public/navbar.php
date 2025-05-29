<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="css/style.css" />
    <script>
        // JavaScript untuk toggle menu mobile
        document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.querySelector('#menu-toggle');
        const menu = document.querySelector('#mobile-menu');

        toggle.addEventListener('change', () => {
            menu.classList.toggle('hidden');
        });
        });
    </script>
    <title>Navbar</title>
</head>
<body>
    <nav class="bg-blue-900 h-[70px] flex items-center justify-between px-5 z-30 w-full relative">
        <div class="flex items-center w-full md:w-[50%] h-14">
        <img src="../img/logo BPS 2 icon.png" alt="" class="w-14 h-14">
        <div class="text-white px-2 ml-2">
            <div class="font-bold text-xl font-sans">Badan Pusat Statistik</div>
            <div class="text-sm font-sans">Kabupaten Pulang Pisau</div>
        </div>
        </div>

        <!-- Hamburger Toggle -->
        <div class="lg:hidden">
            <label for="menu-toggle" class="cursor-pointer flex flex-col justify-center">
                <input type="checkbox" id="menu-toggle" class="hidden">
                <span class="w-[26px] h-[3px] border-[2px] border-kuning block my-1 origin-left transition duration-100"></span>
                <span class="w-[26px] h-[3px] border-[2px] border-kuning block my-1 transition duration-100"></span>
                <span class="w-[26px] h-[3px] border-[2px] border-kuning block my-1 origin-left transition duration-100"></span>       
            </label>
        </div>

        <!-- Desktop Menu -->
        <ul class="hidden lg:flex text-white justify-between items-center px-4 text-lg font-semibold h-full w-[50%]">
        <!-- Ulangi li untuk menu -->
            <li class="hover:text-yellow-400 h-full group active:scale-90">
                <a class="w-full h-full flex items-center" href="form.php">
                    <div class="mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M4.5 3.75a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V6.75a3 3 0 0 0-3-3h-15Zm4.125 3a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Zm-3.873 8.703a4.126 4.126 0 0 1 7.746 0 .75.75 0 0 1-.351.92 7.47 7.47 0 0 1-3.522.877 7.47 7.47 0 0 1-3.522-.877.75.75 0 0 1-.351-.92ZM15 8.25a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 0-1.5H15ZM14.25 12a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H15a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 0-1.5H15Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="text-sm lg:text-lg">
                        Buku Tamu
                        <hr class="scale-0 group-hover:border-yellow-400 group-hover:scale-100 border-[1px] transition ease-out duration-300 translate-y-0.5">
                    </div>
                </a>    
            </li>
            <li class="hover:text-yellow-400 h-full group active:scale-90">
                <a class="w-full h-full flex items-center" href="penilaian.php">
                    <div class="mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -2 28 28" fill="currentColor" class="size-6">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </div>
                    <div class="text-sm lg:text-lg">
                        Form Penilaian Layanan
                        <hr class="scale-0 group-hover:border-yellow-400 group-hover:scale-100 border-[1px] transition ease-out duration-300 translate-y-0.5">
                    </div>
                </a>    
            </li>
            <li class="hover:text-yellow-400 h-full group active:scale-90">
                <a class="w-full h-full flex items-center" href="laporan.php">
                    <div class="mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                            <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                        </svg>
                    </div>
                    <div class="text-sm lg:text-lg">
                        Laporan Pengaduan
                        <hr class="scale-0 group-hover:border-yellow-400 group-hover:scale-100 border-[1px] transition ease-out duration-300 translate-y-0.5">
                    </div>
                </a>    
            </li>
        </ul>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="absolute top-[70px] left-0 w-full bg-blue-900 text-white px-4 text-lg font-semibold hidden lg:hidden">
            <ul class="py-4">
                <li class="hover:text-yellow-400 h-full group active:scale-90 my-2">
                    <a class="w-full h-full flex items-center" href="form.php">
                        <div class="mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path fill-rule="evenodd" d="M4.5 3.75a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V6.75a3 3 0 0 0-3-3h-15Zm4.125 3a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Zm-3.873 8.703a4.126 4.126 0 0 1 7.746 0 .75.75 0 0 1-.351.92 7.47 7.47 0 0 1-3.522.877 7.47 7.47 0 0 1-3.522-.877.75.75 0 0 1-.351-.92ZM15 8.25a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 0-1.5H15ZM14.25 12a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H15a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 0-1.5H15Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="text-sm lg:text-lg">
                            Buku Tamu
                            <hr class="scale-0 group-hover:border-yellow-400 group-hover:scale-100 border-[1px] transition ease-out duration-300 translate-y-0.5">
                        </div>
                    </a>    
                </li>
                <li class="hover:text-yellow-400 h-full group active:scale-90 my-2">
                    <a class="w-full h-full flex items-center" href="penilaian.php">
                        <div class="mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -2 28 28" fill="currentColor" class="size-6">
                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                            </svg>
                        </div>
                        <div class="text-sm lg:text-lg">
                            Form Penilaian Layanan
                            <hr class="scale-0 group-hover:border-yellow-400 group-hover:scale-100 border-[1px] transition ease-out duration-300 translate-y-0.5">
                        </div>
                    </a>    
                </li>
                <li class="hover:text-yellow-400 h-full group active:scale-90 my-2">
                    <a class="w-full h-full flex items-center" href="laporan.php">
                        <div class="mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                                <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                            </svg>
                        </div>
                        <div class="text-sm lg:text-lg">
                            Laporan Pengaduan
                            <hr class="scale-0 group-hover:border-yellow-400 group-hover:scale-100 border-[1px] transition ease-out duration-300 translate-y-0.5">
                        </div>
                    </a>    
                </li>
            </ul>
        </div>
    </nav>
    </body>
</html>
