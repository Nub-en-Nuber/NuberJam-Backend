<?php

include '../../config/database.php';
include '../../config/utils.php';

$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['musicId'])) {
            $musicId = $_GET['musicId'];
            $querySelect = "SELECT * FROM music WHERE musicId = '$musicId'";
            $executeSelect = mysqli_query($database->connection, $querySelect);
            $check = mysqli_affected_rows($database->connection);

            if ($check > 0) {
                while ($row = mysqli_fetch_object($executeSelect)) {
                    $musicFilePath = $row->musicFile;
                    $musicFile = explode("/", $musicFilePath);
                    $musicFileName = end($musicFile);

                    Utils::deleteFile("../../asset/music/$musicFileName");
                }

                $query = "DELETE FROM music WHERE musicId = '$musicId'";
                $execute = mysqli_query($database->connection, $query);
                $check = mysqli_affected_rows($database->connection);

                if ($check > 0) {
                    $response["status"] = $constant->RESPONSE_STATUS["success"];
                    $response["message"] = $constant->RESPONSE_MESSAGES["delete_success"];
                } else {
                    $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                    $response["message"] = $constant->RESPONSE_MESSAGES["delete_failed"];
                }
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                $response["message"] = $constant->RESPONSE_MESSAGES["delete_failed"];
            }
        } else {
            $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
            $response['message'] = $constant->RESPONSE_MESSAGES["music_id_needed"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
