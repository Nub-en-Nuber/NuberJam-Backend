<?php

include "../../config/database.php";
include '../../config/utils.php';

$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (!empty($_POST['playlistName']) && !empty($_POST['accountId'])) {
            $playlistName = $_POST['playlistName'];
            $accountId = $_POST['accountId'];
            $playlistPhoto = $constant->BASE_ASSET_URL . "/images/playlist/default-playlist.png";

            $queryCheck = "SELECT * FROM account WHERE accountId = '$accountId'";
            $execute = mysqli_query($database->connection, $queryCheck);
            $accountExist = mysqli_num_rows($execute) > 0 ? true : false;

            if (!$accountExist) {
                $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                $response["message"] = $constant->RESPONSE_MESSAGES["account_not_exist"];
            } else {
                $query = "INSERT INTO playlist (playlistName, playlistPhoto, accountId) VALUES ('$playlistName','$playlistPhoto','$accountId')";
                $execute = mysqli_query($database->connection, $query);
                $check = mysqli_affected_rows($database->connection);

                if ($check > 0) {
                    $response["status"] = $constant->RESPONSE_STATUS["success"];
                    $response["message"] = $constant->RESPONSE_MESSAGES["add_success"];
                } else {
                    $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                    $response["message"] = $constant->RESPONSE_MESSAGES["add_failed"];
                }
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
