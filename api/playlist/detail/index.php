<?php

include "../../../config/database.php";
$database = new Database();
$constant = new Constants();

$data = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        $data['add_song_to_playlist'] = "http://localhost/nuberjam/api/playlist/detail/add.php";
        $data['read_all_playlist_song'] = "http://localhost/nuberjam/api/playlist/detail/retrieve.php?playlistId={id}&accountId={id}";
        $data['delete_song_in_playlist'] = "http://localhost/nuberjam/api/playlist/detail/delete.php?playlistDetailId={id}";
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
