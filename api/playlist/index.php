<?php

include "../../config/database.php";
$database = new Database();
$constant = new Constants();

$info = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        $info['read_user_by_id'] = "http://localhost/nuberJam/user/retrieve.php?username={username}";
        $info['add_user'] = "http://localhost/nuberJam/user/add.php";
        $info['edit_user_data'] = "http://localhost/nuberJam/user/edit.php?username={username}";
        $info['delete_user'] = "http://localhost/nuberJam/user/delete.php?username={username}";
    } else {
        $response["status"] = $constant->RESPONSE_STATUS["unauthorized"];
        $response["message"] = $constant->RESPONSE_MESSAGES['invalid_token'];
    }
} else {
    $response['status'] = $constant->RESPONSE_STATUS["forbidden"];
    $response['message'] = $constant->RESPONSE_MESSAGES['needed_token'];
}

if ($response["status"] == $constant->RESPONSE_STATUS["success"]) echo json_encode($info);
else echo json_encode($response);
mysqli_close($database->connection);
