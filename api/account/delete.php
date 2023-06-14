<?php

include "../../config/database.php";
include "../../config/utils.php";
$database = new Database();
$constant = new Constants();

$response = array();

function getPlaylistByAccount($database, $accountId)
{
    $query = "SELECT * FROM playlist WHERE accountId = '$accountId'";
    $execute = mysqli_query($database->connection, $query);
    $check = mysqli_affected_rows($database->connection);

    if ($check > 0) {
        while ($row = mysqli_fetch_object($execute)) {
            $querySelect = "SELECT * FROM playlist WHERE playlistId = '$row->playlistId'";
            $executeSelect = mysqli_query($database->connection, $querySelect);
            $check = mysqli_affected_rows($database->connection);

            if ($check > 0) {
                while ($row = mysqli_fetch_object($executeSelect)) {
                    $photoPath = $row->playlistPhoto;
                    $photo = explode("/", $photoPath);
                    $photoName = end($photo);
                    if ($photoName != "default-playlist.png") {
                        Utils::deleteFile("../../asset/images/playlist/$photoName");
                    }
                }
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['accountId'])) {
            $accountId = $_GET['accountId'];

            $querySelect = "SELECT * FROM account WHERE accountId = '$accountId'";
            $executeSelect = mysqli_query($database->connection, $querySelect);
            $check = mysqli_affected_rows($database->connection);

            if ($check > 0) {
                while ($row = mysqli_fetch_object($executeSelect)) {
                    $photoPath = $row->accountPhoto;
                    $photo = explode("/", $photoPath);
                    $photoName = end($photo);
                    if ($photoName != "default-account.png") {
                        Utils::deleteFile("../../asset/images/account/$photoName");
                    }
                }

                getPlaylistByAccount($database, $accountId);

                $query = "DELETE FROM account WHERE accountId = '$accountId'";
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
            $response['message'] = $constant->RESPONSE_MESSAGES["account_id_needed"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
