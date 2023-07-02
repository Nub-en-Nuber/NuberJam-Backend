<?php

include '../../config/database.php';
include '../../config/utils.php';

$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (!empty($_POST['musicName']) && !empty($_POST['musicDuration']) && !empty($_POST['albumId']) && !empty($_FILES["musicFile"]["tmp_name"]) && !in_array("", $_POST['accountIds'])) {
            $musicName = $_POST['musicName'];
            $musicDuration = $_POST['musicDuration'];
            $albumId = $_POST['albumId'];
            $accountIds = $_POST['accountIds'];

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
                $queryCheck = "SELECT * FROM album WHERE albumId = '$albumId'";
                $execute = mysqli_query($database->connection, $queryCheck);
                $albumExist = mysqli_num_rows($execute) > 0 ? true : false;

                if ($albumExist) {
                    $targetDir = "../../asset/music/";
                    $timestamp = Utils::getCurrentDate();
                    $musicFileName = "music-" . md5(Utils::convertCamelString("$musicName-$timestamp")) . ".mp3";
                    $targetFile = $targetDir . $musicFileName;
                    if (move_uploaded_file($_FILES["musicFile"]["tmp_name"], $targetFile)) {
                        $musicFile = $constant->BASE_ASSET_URL . "/music/" . $musicFileName;

                        $query = "INSERT INTO music (musicName, musicDuration, musicFile, albumId) VALUES ('$musicName', $musicDuration, '$musicFile', $albumId)";
                        $execute = mysqli_query($database->connection, $query);
                        $check = mysqli_affected_rows($database->connection);

                        $query = "SELECT musicId from music WHERE musicName = '$musicName' AND musicFile = '$musicFile' AND musicDuration = '$musicDuration' AND albumId = '$albumId'";
                        $execute = mysqli_query($database->connection, $query);
                        $check = mysqli_affected_rows($database->connection);

                        $row = mysqli_fetch_object($execute);
                        $musicId = $row->musicId;

                        foreach ($accountIds as $index => $accountId) {
                            $query = "INSERT INTO music_artist (musicId, accountId) VALUES ('$musicId', '$accountId')";
                            $execute = mysqli_query($database->connection, $query);
                            $check = mysqli_affected_rows($database->connection);
                            if (!($check > 0)) {
                                break;
                            }
                        }

                        if ($check > 0) {
                            $response["status"] = $constant->RESPONSE_STATUS["success"];
                            $response["message"] = $constant->RESPONSE_MESSAGES["add_success"];
                        } else {
                            $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                            $response["message"] = $constant->RESPONSE_MESSAGES["add_failed"];
                        }
                    } else {
                        $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
                        $response['message'] = $constant->RESPONSE_MESSAGES["no_request_data"];
                    }
                } else {
                    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
                    $response['message'] = $constant->RESPONSE_MESSAGES["album_not_exist"];
                }
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
                $response['message'] = $constant->RESPONSE_MESSAGES["account_not_exist"];
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
