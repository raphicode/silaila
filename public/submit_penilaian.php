<?php
// Include koneksi dan fungsi
require_once "functions.php";

// Cek apakah data dikirim via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Panggil fungsi penilaian()
    if (penilaian($_POST) > 0) {
        echo "sukses";
    } else {
        echo "gagal";
    }
} else {
    // Bukan akses POST
    echo "invalid";
}
?>