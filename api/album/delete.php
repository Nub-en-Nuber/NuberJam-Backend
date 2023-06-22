<?php

include '../../config/database.php';
include '../../config/utils.php';

$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['albumId'])) {
            $albumId = $_GET['albumId'];
            $querySelect = "SELECT * FROM album WHERE albumId = '$albumId'";
            $executeSelect = mysqli_query($database->connection, $querySelect);
            $check = mysqli_affected_rows($database->connection);

            if ($check > 0) {
                while ($row = mysqli_fetch_object($executeSelect)) {
                    $albumPhotoPath = $row->albumPhoto;
                    $albumPhoto = explode("/", $albumPhotoPath);
                    $albumPhotoName = end($albumPhoto);
                    if ($albumPhotoName != "default-album.png") {
                        Utils::deleteFile("../../asset/images/album/$albumPhotoName");
                    }
                }

                $query = "DELETE FROM album WHERE albumId = '$albumId'";
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
            $response['message'] = $constant->RESPONSE_MESSAGES["album_id_needed"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
