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
            $checkArtist = false;

            if (isset($_POST['musicName'])) {
                $musicName = $_POST['musicName'];
                if (!empty($musicName)) {
                    $query = "UPDATE music SET musicName = '$musicName' WHERE musicId = '$musicId'";
                    if (mysqli_query($database->connection, $query)) $checkName = true;
                }
            }

            if (isset($_POST['musicDuration'])) {
                $musicDuration = $_POST['musicDuration'];
                if (!empty($musicDuration)) {
                    $query = "UPDATE music SET musicDuration = '$musicDuration' WHERE musicId = '$musicId'";
                    if (mysqli_query($database->connection, $query)) $checkDuration = true;
                }
            }

            if (isset($_POST['accountIds'])) {
                $accountIds = $_POST['accountIds'];
                if (!in_array("", $accountIds)) {
                    $accountExist = false;
                    foreach ($accountIds as $index => $accountId) {
                        $queryCheck = "SELECT * FROM account WHERE accountId = '$accountId'";
                        $execute = mysqli_query($database->connection, $queryCheck);
                        $accountExist = mysqli_num_rows($execute) > 0 ? true : false;
                        if (!$accountExist) {
                            break;
                        }
                    }
                    if ($accountExist) {
                        $query = "DELETE FROM music_artist WHERE musicId = '$musicId'";
                        $execute = mysqli_query($database->connection, $query);
                        foreach ($accountIds as $index => $accountId) {
                            $query = "INSERT INTO music_artist (musicId, accountId) VALUES ('$musicId', '$accountId')";
                            if (mysqli_query($database->connection, $query)) $checkArtist = true;
                        }
                    }
                }
            }

            if (isset($_POST['albumId'])) {
                $albumId = $_POST['albumId'];
                if (!empty($albumId)) {
                    $queryCheck = "SELECT * FROM album WHERE albumId = '$albumId'";
                    $execute = mysqli_query($database->connection, $queryCheck);
                    $albumExist = mysqli_num_rows($execute) > 0 ? true : false;
                    if ($albumExist) {
                        $query = "UPDATE music SET albumId = '$albumId' WHERE musicId = '$musicId'";
                        if (mysqli_query($database->connection, $query)) $checkAlbumId = true;
                    }
                }
            }

            if (isset($_FILES['musicFile']["tmp_name"])) {
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

            if ($checkName || $checkMusicFile || $checkDuration || $checkAlbumId || $checkArtist) {
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
