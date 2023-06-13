<?php

include '../../config/database.php';
$database = new Database();
$constant = new Constants();

$data = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        $data['add_music'] = $constant->BASE_API_URL . "/music/add.php";
        $data['read_all_music_by_accountId'] = $constant->BASE_API_URL . "/music/retrieve.php?accountId={accountId}";
        $data['search_music'] = $constant->BASE_API_URL . "/music/retrieve.php?q={query}";
        $data['read_detail_music_by_accountId_and_musicId'] = $constant->BASE_API_URL . "/music/retrieve.php?musicId={musicId}&accountId={accountId}";
        $data['update_music_by_musicId'] = $constant->BASE_API_URL . "/music/edit.php?musicId={musicId}";
        $data['delete_music_by_musicId'] = $constant->BASE_API_URL . "/music/delete.php?musicId={musicId}";
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
