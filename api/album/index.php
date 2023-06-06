<?php

include '../../config/database.php';
$database = new Database();
$constant = new Constants();

$data = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        $data['add_album'] = "http://localhost/nuberjam/api/album/add.php";
        $data['read_all_album'] = "http://localhost/nuberjam/api/album/retrieve.php";
        $data['read_album_by_id'] = "http://localhost/nuberjam/api/album/retrieve.php?id={id}";
        $data['edit_album_data'] = "http://localhost/nuberjam/api/album/edit.php?id={id}";
        $data['delete_all_album'] = "http://localhost/nuberjam/api/album/delete.php";
        $data['delete_album_by_id'] = "http://localhost/nuberjam/api/album/delete.php?id={id}";
    } else {
        $response["status"] = $constant->RESPONSE_STATUS["unauthorized"];
        $response["message"] = $constant->RESPONSE_MESSAGES['invalid_token'];
    }
} else {
    $response['status'] = $constant->RESPONSE_STATUS["forbidden"];
    $response['message'] = $constant->RESPONSE_MESSAGES['needed_token'];
}

if ($response["status"] == $constant->RESPONSE_STATUS["success"]) echo json_encode($data);
else echo json_encode($response);
mysqli_close($database->connection);
