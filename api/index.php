<?php

include "../config/database.php";
$database = new Database();
$constant = new Constants();

$data = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        $data['account_endpoint'] = $constant->BASE_API_URL . "/account";
        $data['album_endpoint'] = $constant->BASE_API_URL . "/album";
        $data['music_artist_endpoint'] = $constant->BASE_API_URL . "/music/artist";
        $data['music_endpoint'] = $constant->BASE_API_URL . "/music";
        $data['favorite_endpoint'] = $constant->BASE_API_URL . "/favorite";
        $data['playlist_endpoint'] = $constant->BASE_API_URL . "/playlist";
        $data['playlist_detail_endpoint'] = $constant->BASE_API_URL . "/playlist/detail";
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
