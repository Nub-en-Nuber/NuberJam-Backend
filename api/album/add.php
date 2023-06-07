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
            $albumName = $_POST['albumName'];
            $albumArtist = $_POST['albumArtist'];

            $targetDir = "../../asset/images/album/";
            // $timestamp = 
            $albumPhotoName = "album-" . Utils::convertCamelString("$albumArtist-$name-") . md5(Utils::convertCamelString("$albumArtist-$name")) . ".png";
            $targetFile = $targetDir . $albumPhotoName;
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                $photo = $constant->BASE_ASSET_URL . "/images/album/" . $albumPhotoName;
            } else {
                $photo = $constant->BASE_ASSET_URL . "/images/album/default-album.png";
            }

            $query = "INSERT INTO album (name, albumArtist, photo) VALUES ('$name','$albumArtist','$photo')";
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
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
