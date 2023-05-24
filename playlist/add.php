<?php

include '../database.php';
$database = new Database();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["kode"] == 200) {
        if (!empty($_POST)) {
            $name = $_POST['name'];
            $photo = $_POST['photo'];
            $userId = $_POST['userId'];

            $query = "INSERT INTO playlist (name, photo, userId) VALUES ('$name','$photo','$userId')";
            $execute = mysqli_query($database->connection, $query);
            $cek = mysqli_affected_rows($database->connection);

            if ($cek > 0) {
                $response["kode"] = 200;
                $response["pesan"] = "Simpan Data Berhasil";
            } else {
                $response["kode"] = 500;
                $response["pesan"] = "Gagal Simpan Data";
            }
        } else {
            $response['kode'] = 400;
            $response['pesan'] = "Tidak ada data yang diterima";
        }
    }
} else {
    $response['kode'] = 400;
    $response['pesan'] = "Request method salah";
}

echo json_encode($response);
mysqli_close($database->connection);
