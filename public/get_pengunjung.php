<?php
    header('Content-Type: application/json');
    require 'functions.php';
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = intval($_POST['id']);
        $result = query("SELECT * FROM pengunjung WHERE user_id = $id LIMIT 1");

        if (!empty($result)) {
            echo json_encode($result[0]);
        } else {
            echo json_encode(["error" => "Data tidak ditemukan"]);
        }
    }
?>