<?php

include '../database.php';
$database = new Database();

$info = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["kode"] == 200) {
        $info['read_user_by_id'] = "http://localhost/nuberJam/user/retrieve.php?username={username}";
        $info['add_user'] = "http://localhost/nuberJam/user/add.php";
        $info['edit_user_data'] = "http://localhost/nuberJam/user/edit.php?username={username}";
        $info['delete_user'] = "http://localhost/nuberJam/user/delete.php?username={username}";
    } else {
        $response["kode"] = 401;
        $response["pesan"] = "Token anda salah";
    }
} else {
    $response['kode'] = 403;
    $response['pesan'] = "Token diperlukan untuk dapat mengakses";
}

if ($response["kode"] == 200) echo json_encode($info); else echo json_encode($response);
mysqli_close($database->connection);
