<?php

include '../../config/database.php';
$database = new Database();
$constant = new Constants();

$data = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        $data['add_playlist'] = "http://localhost/nuberjam/api/playlist/add.php";
        $data['read_all_playlist'] = "http://localhost/nuberjam/api/playlist/retrieve.php";
        $data['read_playlist_by_id'] = "http://localhost/nuberjam/api/playlist/retrieve.php?playlistId={id}";
        $data['edit_playlist_data'] = "http://localhost/nuberjam/api/playlist/edit.php?playlistId={id}";
        $data['delete_playlist_by_id'] = "http://localhost/nuberjam/api/playlist/delete.php?playlistId={id}";
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
