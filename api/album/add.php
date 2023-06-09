<?php

include '../../config/database.php';
include '../../config/utils.php';

$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (!empty($_POST['albumName'])) {
            $albumName = $_POST['albumName'];

            $targetDir = "../../asset/images/album/";
            $timestamp = Utils::getCurrentDate();
            $albumPhotoName = "album-" . md5(Utils::convertCamelString("$albumName-$timestamp")) . ".png";
            $targetFile = $targetDir . $albumPhotoName;
            $albumPhoto = $constant->BASE_ASSET_URL . "/images/album/default-album.png";
            if (isset($_FILES["albumPhoto"]["tmp_name"])) {
                if (move_uploaded_file($_FILES["albumPhoto"]["tmp_name"], $targetFile)) {
                    $albumPhoto = $constant->BASE_ASSET_URL . "/images/album/" . $albumPhotoName;
                }
            }

            $query = "INSERT INTO album (albumName, albumPhoto) VALUES ('$albumName', '$albumPhoto')";
            $execute = mysqli_query($database->connection, $query);
            $check = mysqli_affected_rows($database->connection);

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
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
