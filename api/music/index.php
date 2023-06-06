<?php

include '../../config/database.php';
$database = new Database();
$constant = new Constants();

$data = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        $data['add_music'] = $constant->BASE_API_URL . "/music/add.php";
        $data['read_all_music'] = $constant->BASE_API_URL . "/music/retrieve.php";
        $data['read_all_music_filter_by_user_id'] = $constant->BASE_API_URL . "/music/retrieve.php?user_id={user_id}";
        $data['read_all_music_filter_by_user_id_and_album_id'] = $constant->BASE_API_URL . "/music/retrieve.php?user_id={user_id}&album_id={album_id}";
        $data['search_music'] = $constant->BASE_API_URL . "/music/retrieve.php?q={query}";
        $data['read_detail_music_filter_by_id'] = $constant->BASE_API_URL . "/music/retrieve.php?id={id}";
        $data['read_detail_music_filter_by_user_id_and_id'] = $constant->BASE_API_URL . "/music/retrieve.php?id={id}&user_id={user_id}";
        $data['edit_music_filter_by_id'] = $constant->BASE_API_URL . "/music/edit.php?id={id}";
        $data['delete_all_music'] = $constant->BASE_API_URL . "/music/delete.php";
        $data['delete_music_filter_by_id'] = $constant->BASE_API_URL . "/music/delete.php?id={id}";
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
