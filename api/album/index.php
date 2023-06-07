<?php

include '../../config/database.php';
$database = new Database();
$constant = new Constants();

$data = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        $data['add_album'] = $constant->BASE_API_URL . "/album/add.php";
        $data['read_all_album'] = $constant->BASE_API_URL . "/album/retrieve.php";
        $data['read_detail_album_by_albumId_and_userId'] = $constant->BASE_API_URL . "/album/retrieve.php?albumId={albumId}&userId={userId}";
        $data['update_album_by_albumId'] = $constant->BASE_API_URL . "/album/edit.php?albumId={albumId}";
        $data['delete_album_by_albumId'] = $constant->BASE_API_URL . "/album/delete.php?albumId={albumId}";
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
