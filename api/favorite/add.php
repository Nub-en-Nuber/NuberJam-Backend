<?php

include '../../config/database.php';
include '../../config/utils.php';

$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (!empty($_POST['musicId']) && !empty($_POST['accountId'])) {
            $musicId = $_POST['musicId'];
            $accountId = $_POST['accountId'];

            $queryCheck = "SELECT * FROM music WHERE musicId = '$musicId'";
            $execute = mysqli_query($database->connection, $queryCheck);
            $musicExist = mysqli_num_rows($execute) > 0 ? true : false;

            $queryCheck = "SELECT * FROM account WHERE accountId = '$accountId'";
            $execute = mysqli_query($database->connection, $queryCheck);
            $accountExist = mysqli_num_rows($execute) > 0 ? true : false;

            if ($musicExist && $accountExist) {
                $query = "INSERT INTO favorite (musicId, accountId) VALUES ('$musicId', '$accountId')";
                $execute = mysqli_query($database->connection, $query);
                $check = mysqli_affected_rows($database->connection);

                if ($check > 0) {
                    $response["status"] = $constant->RESPONSE_STATUS["success"];
                    $response["message"] = $constant->RESPONSE_MESSAGES["add_success"];
                } else {
                    $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                    $response["message"] = $constant->RESPONSE_MESSAGES["add_failed"];
                }
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
                $response["message"] = $constant->RESPONSE_MESSAGES["music_or_account_not_exist"];
            }
        } else {
            $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
            $response['message'] = $constant->RESPONSE_MESSAGES["no_request_data"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
