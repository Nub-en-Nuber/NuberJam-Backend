<?php

include "../../config/database.php";
$database = new Database();
$constant = new Constants();

$data = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        $data['add_playlist'] = $constant->BASE_API_URL . "/playlist/add.php";
        $data['read_playlist_by_id'] = $constant->BASE_API_URL . "/playlist/retrieve.php?accountId={id}";
        $data['search_playlist'] = $constant->BASE_API_URL . "/playlist/retrieve.php?accountId={id}&q={q}";
        $data['edit_playlist_data'] = $constant->BASE_API_URL . "/playlist/edit.php?playlistId={id}";
        $data['delete_playlist_by_id'] = $constant->BASE_API_URL . "/playlist/delete.php?playlistId={id}";
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
