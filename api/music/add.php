<?php

include '../../config/database.php';
include '../../config/utils.php';

$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (!empty($_POST)) {
            $musicName = $_POST['musicName'];
            $musicDuration = $_POST['musicDuration'];
            $albumId = $_POST['albumId'];

            $targetDir = "../../asset/music/";
            $timestamp = Utils::getCurrentDate();
            $musicName = "music-" . md5(Utils::convertCamelString("$name-$timestamp")) . ".mp3";
            $targetFile = $targetDir . $musicName;
            if (move_uploaded_file($_FILES["musicFile"]["tmp_name"], $targetFile)) {
                $musicFile = $constant->BASE_ASSET_URL . "/music/" . $musicName;

                $query = "INSERT INTO music (musicName, musicDuration, musicFile, albumId) VALUES ('$musicName', $musicDuration, '$musicFile', $albumId)";
                $execute = mysqli_query($database->connection, $query);
                $cek = mysqli_affected_rows($database->connection);

                if ($cek > 0) {
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
            $response['message'] = $constant->RESPONSE_MESSAGES["no_request_data"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
