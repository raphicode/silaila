<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="../img/silaila2.png" type="image/png">
    <title>Penilaian Layanan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-200 bg-cover">
    <?php
        include "functions.php";
        include "navbar.php";
        date_default_timezone_set('Asia/Jakarta');
        $hari_ini = date('Y-m-d');
        $nama_petugas = '';
        $nip_petugas = '';

        // Ambil 1 petugas yang sudah presensi 'Masuk' hari ini
        $query_petugas = mysqli_query($koneksi, "SELECT p.nip, u.nama, p.waktu AS waktu_masuk, ( SELECT MAX(waktu) FROM presensi_petugas WHERE nip = p.nip AND jenis = 'Keluar' AND DATE(waktu) = '$hari_ini') AS waktu_keluar FROM presensi_petugas p 
        JOIN login u ON p.nip = u.nip 
        WHERE DATE(p.waktu) = '$hari_ini' 
            AND p.jenis = 'Masuk' 
        ORDER BY p.waktu DESC 
        LIMIT 1");

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
    ?>
    <div class="w-full p-4">
        <div id="toast" class="hidden fixed top-5 right-5 bg-white text-blue-900 font-semibold px-4 py-2 rounded-lg shadow-lg z-50 transition duration-300 ease-in-out">
            Terima Kasih Sudah Memberikan Kami Penilaian!
        </div>
        <!-- Bagian Form -->
        <form id="form-penilaian" method="post">
            <div class="w-full flex item-center justify-center">
                <div class="w-full flex item-center justify-center">
                    <div class="w-full lg:w-[50%] bg-white rounded-lg my-2 px-4 py-2 shadow-lg">
                        <div class="flex justify-center items-center p-2">
                            <img src="../img/silaila2.png" width="150" alt="">
                        </div>
                        <div class="text-center text-blue-900">
                            <h1 class="font-bold text-4xl">SILAILA</h1>
                            <h2 class="font-semibold text-lg pb-2">Mohon beri penilaian terhadap pelayanan yang telah kami berikan</h2>
                        </div>
                        <div class="text-blue-900 w-full">
                            <div class="text-blue-900 w-full">
                                <div class="font-semibold pb-[4px]">
                                    Petugas yang melayani Anda
                                </div>
                                <div class="w-full text-center rounded-lg border-blue-900 border mb-2 bg-white py-1">
                                    <input type="text" name="nama_petugas" disabled value="<?php 
                                    if($nama_petugas == '') {
                                        echo 'Tidak ada petugas yang bertugas, silahkan lewati penilaian';
                                    } else echo $nama_petugas ?>" class="w-full text-center font-semibold text-blue-900 bg-transparent">
                                    <input type="hidden" name="nama_petugas" value="<?= $nama_petugas ?>" class="w-full text-center font-semibold text-blue-900 bg-transparent">
                                    <input type="hidden" name="nip_petugas" value="<?= $nip_petugas ?>">
                                </div>
                            </div>
                            <div class="font-semibold pb-[4px]">
                                Nama Lengkap
                            </div>
                            <div class="w-full text-center rounded-lg border-blue-900 border mb-2">
                                <input type="text" name="nama" id="nama" class="rounded-lg w-full px-2 p-[4px]" required>
                            </div>
                        </div>
                        <div class="text-blue-900 w-full">
                            <div class="font-semibold pb-[4px]">
                                Data yang dibutuhkan/koordinasi yang dilakukan
                            </div>
                            <div class="w-full text-center rounded-lg border-blue-900 border mb-2">
                                <input type="text" name="kebutuhan" id="kebutuhan" class="rounded-lg w-full px-2 p-[4px]" required>
                            </div>
                        </div>
                        <div class="text-blue-900 w-full">
                            <div class="font-semibold pb-[4px]">
                                Berikan penilaian terhadap pelayanan kami
                            </div>
                            <div class="rounded-lg px-4 w-full bg-white border border-blue-900 mb-2 flex justify-between items-center">
                                <input type="hidden" name="kepuasan" id="kepuasan">
                                <p id="rating-value" class="text-gray-600">Rating: 0</p>
                                <div id="rating-container" class="flex gap-1 text-3xl text-gray-300 pb-[4px]">
                                    <span class="star cursor-pointer" data-value="1">★</span>
                                    <span class="star cursor-pointer" data-value="2">★</span>
                                    <span class="star cursor-pointer" data-value="3">★</span>
                                    <span class="star cursor-pointer" data-value="4">★</span>
                                    <span class="star cursor-pointer" data-value="5">★</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-blue-900 w-full mb-2">
                            <div class="font-semibold pb-[4px]">
                                Kesan/Pesan/Kritik
                            </div>
                            <div class="w-full text-center rounded-lg border-blue-900 border mb-4">
                                <input type="text" name="pesan" id="pesan" class="rounded-lg w-full px-2 p-[4px]" required>
                            </div>
                        </div>
                        <div class="w-full text-center mb-2 pt-[16px]">
                            <button type="submit" id="kirimButton" class="bg-blue-900 w-full rounded-lg font-semibold text-lg px-2 py-[2px] hover:contrast-150 active:scale-[98%] text-slate-200">
                                Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            document.getElementById("form-penilaian").addEventListener("submit", function(e) {
                e.preventDefault(); // Cegah submit default
            
                const form = e.target;
                const formData = new FormData(form);
                const kepuasan = document.getElementById("kepuasan").value;
            
                // Validasi bintang
                if (kepuasan === "" || kepuasan === "0") {
                    Swal.fire('Oops!', 'Mohon berikan penilaian dengan memilih minimal 1 bintang.', 'warning');
                    return;
                }
            
                // Kirim form ke server
                fetch("submit_penilaian.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire({
                            title: 'Terima kasih!',
                            text: 'Penilaian Anda berhasil dikirim.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            timer: 3000,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.href = "../index.php";
                        });
                    } else {
                        throw new Error('Gagal mengirim data');
                    }
                })
                .catch(error => {
                    Swal.fire('Gagal', 'Terjadi kesalahan saat mengirim data.', 'error');
                    console.error(error);
                });
            });
        </script>

    </div>
    
</body>
<!-- Script untuk rating -->
<script>
    const stars = document.querySelectorAll(".star");
    const ratingValue = document.getElementById("rating-value");
    let savedRating = 0; // Menyimpan nilai rating yang sudah dipilih

    stars.forEach(star => {
        star.addEventListener("click", function() {
        savedRating = Number(this.getAttribute("data-value"));
        updateStars(savedRating);
        ratingValue.textContent = `Rating: ${savedRating}`;
        document.getElementById("kepuasan").value = savedRating;
        });

        star.addEventListener("mouseover", function() {
        const currentValue = Number(this.getAttribute("data-value"));
        updateStars(currentValue);
        });

        star.addEventListener("mouseout", function() {
        updateStars(savedRating);
        });
    });

    function updateStars(rating) {
        stars.forEach(star => {
            const starValue = Number(star.getAttribute("data-value"));
                if (starValue <= rating) {
                    star.classList.add("text-yellow-400", "scale-125");
                    star.classList.remove("text-gray-300");
                    } 
                    else {
                    star.classList.remove("text-yellow-400", "scale-125");
                    star.classList.add("text-gray-300");
            }
        });
    }
</script>
</html>