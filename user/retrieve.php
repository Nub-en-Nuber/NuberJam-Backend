<?php

include "../database.php";
$database = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = $database->checkToken();

    if ($response["kode"] == 200) {
        if (isset($_GET['username'])) {
            $username = $_GET['username'];
            $query = "SELECT * FROM user WHERE username = '$username'";

            $execute = mysqli_query($database->connection, $query);
            $cek = mysqli_affected_rows($database->connection);

            if ($cek > 0) {
                $response["data"] = array();

                while ($row = mysqli_fetch_object($execute)) {
                    $data["id"] = $row->id;
                    $data["name"] = $row->name;
                    $data["username"] = $row->username;
                    $data["email"] = $row->email;
                    $data["password"] = $row->password;
                    $data["photo"] = $row->photo;
                    array_push($response["data"], $data);
                }
            } else {
                $response["kode"] = 500;
                $response["pesan"] = "Data Tidak Tersedia";
            }
        } else {
            $response['kode'] = 400;
            $response['pesan'] = "Username diperlukan untuk menghapus";
        }
    }
} else {
    $response['kode'] = 400;
    $response['pesan'] = "Request method salah";
}

echo json_encode($response);
mysqli_close($database->connection);
