<?php

include '../../config/database.php';
include '../../config/utils.php';
$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['musicId'])) {
            $musicId = $_GET['musicId'];

            $checkName = false;
            $checkMusicFile = false;
            $checkDuration = false;
            $checkAlbumId = false;

            if (isset($_POST['musicName'])) {
                $musicName = $_POST['musicName'];
                $query = "UPDATE music SET musicName = '$musicName' WHERE musicId = '$musicId'";
                if (mysqli_query($database->connection, $query)) $checkName = true;
            }

            if (isset($_POST['musicDuration'])) {
                $musicDuration = $_POST['musicDuration'];
                $query = "UPDATE music SET musicDuration = '$musicDuration' WHERE musicId = '$musicId'";
                if (mysqli_query($database->connection, $query)) $checkDuration = true;
            }

            if (isset($_POST['albumId'])) {
                $albumId = $_POST['albumId'];
                $query = "UPDATE music SET albumId = '$albumId' WHERE musicId = '$musicId'";
                if (mysqli_query($database->connection, $query)) $checkAlbumId = true;
            }

            if (isset($_FILES['musicFile'])) {
                $querySelect = "SELECT * FROM music WHERE musicId = '$musicId'";
                $executeSelect = mysqli_query($database->connection, $querySelect);
                $check = mysqli_affected_rows($database->connection);

                if ($check > 0) {
                    $musicFileName = null;
                    while ($row = mysqli_fetch_object($executeSelect)) {
                        $musicFilePath = $row->musicFile;
                        $musicFile = explode("/", $musicFilePath);
                        $musicFileName = end($musicFile);

                        Utils::deleteFile("../../asset/music/$musicFileName");

                        $musicName = $row->musicName;
                        $timestamp = Utils::getCurrentDate();
                        $musicFileName = "music-" . md5(Utils::convertCamelString("$musicName-$timestamp")) . ".mp3";
                    }

                    $targetDir = "../../asset/music/";
                    $targetFile = $targetDir . $musicFileName;
                    if (move_uploaded_file($_FILES["musicFile"]["tmp_name"], $targetFile)) {
                        $musicFile = $constant->BASE_ASSET_URL . "/music/" . $musicFileName;
                        $query = "UPDATE music SET musicFile = '$musicFile' WHERE musicId = '$musicId'";
                        if (mysqli_query($database->connection, $query)) $checkMusicFile = true;
                    }
                } else {
                    $checkMusicFile = false;
                }
            }

            if ($checkName || $checkMusicFile || $checkDuration || $checkAlbumId) {
                $response["status"] = $constant->RESPONSE_STATUS["success"];
                $response["message"] = $constant->RESPONSE_MESSAGES["edit_success"];
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                $response["message"] = $constant->RESPONSE_MESSAGES["edit_failed"];
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
