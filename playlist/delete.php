<?php

include '../database.php';
$database = new Database();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $response = $database->checkToken();

    if ($response["kode"] == 200) {
        if (isset($_GET['playlistId'])) {
            $playlistId = $_GET['playlistId'];

            $query = "DELETE FROM playlist WHERE id = '$playlistId'";
            $execute = mysqli_query($database->connection, $query);
            $cek = mysqli_affected_rows($database->connection);

            if ($cek > 0) {
                $response["kode"] = 200;
                $response["pesan"] = "Hapus Data Berhasil";
            } else {
                $response["kode"] = 500;
                $response["pesan"] = "Gagal Hapus Data";
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
