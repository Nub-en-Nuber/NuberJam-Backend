<?php

include '../../config/database.php';
$database = new Database();
$constant = new Constants();

$data = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        $data['add_favorite_song'] = $constant->BASE_API_URL . "/favorite/add.php";
        $data['read_all_favorite_song_by_accountId'] = $constant->BASE_API_URL . "/favorite/retrieve.php?accountId={accountId}";
        $data['search_favorite_music'] = $constant->BASE_API_URL . "/favorite/retrieve.php?accountId={accountId}&q={query}";
        $data['delete_favorite_song_by_musicId_and_accountId'] = $constant->BASE_API_URL . "/favorite/delete.php?musicId={musicId}&accountId={accountId}";
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
