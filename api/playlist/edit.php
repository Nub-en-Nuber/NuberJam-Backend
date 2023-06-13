<?php

include "../../config/database.php";
include '../../config/utils.php';

$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['playlistId'])) {
            $playlistId = $_GET['playlistId'];

            $checkName = false;
            $checkPhoto = false;

            if (isset($_POST['playlistName'])) {
                $playlistName = $_POST['playlistName'];
                $query = "UPDATE playlist SET playlistName = '$playlistName' WHERE playlistId = '$playlistId'";
                if (mysqli_query($database->connection, $query)) $checkName = true;
            }

            if (isset($_FILES['playlistPhoto'])) {
                $querySelect = "SELECT * FROM playlist WHERE playlistId = '$playlistId'";
                $executeSelect = mysqli_query($database->connection, $querySelect);
                $check = mysqli_affected_rows($database->connection);
                if ($check > 0) {
                    $playlistPhotoName = null;
                    $targetDir = "../../asset/images/playlist/";

                    while ($row = mysqli_fetch_object($executeSelect)) {
                        $playlistPhotoPath = $row->playlistPhoto;
                        $playlistPhoto = explode("/", $playlistPhotoPath);
                        $playlistPhotoName = end($playlistPhoto);
                        if ($playlistPhotoName != "default-playlist.png") {
                            Utils::deleteFile($targetDir . $playlistPhotoName);
                        }
                        $playlistName = $row->playlistName;
                        $timestamp = Utils::getCurrentDate();
                        $playlistPhotoName = "playlist-" . md5(Utils::convertCamelString("$playlistName-$timestamp")) . ".png";
                    }

                    $targetFile = $targetDir . $playlistPhotoName;
                    if (move_uploaded_file($_FILES["playlistPhoto"]["tmp_name"], $targetFile)) {
                        $playlistPhoto = $constant->BASE_ASSET_URL . "/images/playlist/" . $playlistPhotoName;
                        $query = "UPDATE playlist SET playlistPhoto = '$playlistPhoto' WHERE playlistId = '$playlistId'";
                        if (mysqli_query($database->connection, $query)) $checkPhoto = true;
                    }
                }
            }

            if ($checkName || $checkPhoto) {
                $response["status"] = $constant->RESPONSE_STATUS["success"];
                $response["message"] = $constant->RESPONSE_MESSAGES["edit_success"];
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                $response["message"] = $constant->RESPONSE_MESSAGES["edit_failed"];
            }
        } else {
            $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
            $response['message'] = $constant->RESPONSE_MESSAGES["playlist_id_needed"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
