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
            $name = $_POST['name'];
            $duration = $_POST['duration'];
            $album_id = $_POST['album_id'];

            $target_dir = "../../asset/music/";
            $music_name = Utils::convertCamelString("$album_id-$name-") . md5(Utils::convertCamelString("$album_id-$name")) . ".mp3";
            $target_file = $target_dir . $music_name;
            if (move_uploaded_file($_FILES["music_file"]["tmp_name"], $target_file)) {
                $music_file = $constant->BASE_ASSET_URL . "/music/" . $music_name;

                $query = "INSERT INTO music (name, duration, musicFile, albumId) VALUES ('$name', $duration, '$music_file', $album_id)";
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
