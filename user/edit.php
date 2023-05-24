<?php

include '../database.php';
$database = new Database();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["kode"] == 200) {
        if (isset($_GET['username'])) {
            $username = $_GET['username'];
            $email = $_POST['email'];

            if (isset($_POST['name'])) {
                $name = $_POST['name'];
                $query = "UPDATE user SET name = '$name' WHERE username = '$username' AND email = '$email'";
                $execute = mysqli_query($database->connection, $query);
                $cekName = mysqli_affected_rows($database->connection);
            }

            if (isset($_POST['photo'])) {
                $photo = $_POST['photo'];
                $query = "UPDATE user SET photo = '$photo' WHERE username = '$username' AND email = '$email'";
                $execute = mysqli_query($database->connection, $query);
                $cekPhoto = mysqli_affected_rows($database->connection);
            }

            if (isset($_POST['password'])) {
                $password = $_POST['password'];
                $query = "UPDATE user SET password = '$password' WHERE username = '$username' AND email = '$email'";
                $execute = mysqli_query($database->connection, $query);
                $cekPassword = mysqli_affected_rows($database->connection);
            }

            if ($cekName > 0 || $cekPhoto > 0 || $cekPassword > 0) {
                $response["kode"] = 200;
                $response["pesan"] = "Edit Data Berhasil";
            } else {
                $response["kode"] = 500;
                $response["pesan"] = "Gagal Edit Data";
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
