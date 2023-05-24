<?php

include '../database.php';
$database = new Database();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["kode"] == 200) {
        if (isset($_GET['playlistId'])) {
            $playlistId = $_GET['playlistId'];
            $cekName = 0;
            $cekPhoto = 0;

            if (isset($_POST['name'])) {
                $name = $_POST['name'];
                $query = "UPDATE playlist SET name = '$name' WHERE id = '$playlistId'";
                $execute = mysqli_query($database->connection, $query);
                $cekName = mysqli_affected_rows($database->connection);
            }

            if (isset($_POST['photo'])) {
                $photo = $_POST['photo'];
                $query = "UPDATE playlist SET photo = '$photo' WHERE id = '$playlistId'";
                $execute = mysqli_query($database->connection, $query);
                $cekPhoto = mysqli_affected_rows($database->connection);
            }

            if ($cekName > 0 || $cekPhoto > 0) {
                $response["kode"] = 200;
                $response["pesan"] = "Edit Data Berhasil";
            } else {
                $response["kode"] = 500;
                $response["pesan"] = "Gagal Edit Data";
            }
        } else {
            $response['kode'] = 400;
            $response['pesan'] = "ID playlist diperlukan untuk menghapus";
        }
    }
} else {
    $response['kode'] = 400;
    $response['pesan'] = "Request method salah";
}

echo json_encode($response);
mysqli_close($database->connection);
