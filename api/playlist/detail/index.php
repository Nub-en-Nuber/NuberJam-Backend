<?php

include "../../../config/database.php";
$database = new Database();
$constant = new Constants();

$data = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        $data['add_song_to_playlist'] = $constant->BASE_API_URL . "/playlist/detail/add.php";
        $data['check_already_added_playlist_song'] = $constant->BASE_API_URL . "/playlist/detail/check.php";
        $data['read_all_playlist_song'] = $constant->BASE_API_URL . "/playlist/detail/retrieve.php?playlistId={id}&accountId={id}";
        $data['search_playlist_song'] = $constant->BASE_API_URL . "/playlist/detail/retrieve.php?playlistId={id}&accountId={id}&q={q}";
        $data['delete_song_in_playlist'] = $constant->BASE_API_URL . "/playlist/detail/delete.php?playlistDetailId={id}";
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
