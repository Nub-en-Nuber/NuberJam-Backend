<?php

include "../../config/database.php";
$database = new Database();
$constant = new Constants();

$data = array();

$response = $database->checkToken();

if (isset($_GET['token'])) {
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        $data['add_account'] = $constant->BASE_API_URL . "/account/add.php";
        $data['login_account'] = $constant->BASE_API_URL . "/account/login.php";
        $data['read_account'] = $constant->BASE_API_URL . "/account/retrieve.php?accountUsername={accountUsername}|accountEmail={accountEmail}";
        $data['edit_account_data'] = $constant->BASE_API_URL . "/account/edit.php?accountId={id}";
        $data['delete_account'] = $constant->BASE_API_URL . "/account/delete.php?accountId={id}";
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
