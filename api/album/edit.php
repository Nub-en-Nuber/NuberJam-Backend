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

            $checkName = false;
            $checkPhoto = false;
            $checkArtist = false;

            if (isset($_POST['albumName'])) {
                $albumName = $_POST['albumName'];
                if (!empty($albumName)) {
                    $query = "UPDATE album SET albumName = '$albumName' WHERE albumId = '$albumId'";
                    if (mysqli_query($database->connection, $query)) $checkName = true;
                }
            }

            if (isset($_FILES['albumPhoto']["tmp_name"])) {
                $querySelect = "SELECT * FROM album WHERE albumId = '$albumId'";
                $executeSelect = mysqli_query($database->connection, $querySelect);
                $check = mysqli_affected_rows($database->connection);
                if ($check > 0) {
                    $albumPhotoName = null;
                    $targetDir = "../../asset/images/album/";

                    while ($row = mysqli_fetch_object($executeSelect)) {
                        $albumPhotoPath = $row->albumPhoto;
                        $albumPhoto = explode("/", $albumPhotoPath);
                        $albumPhotoName = end($albumPhoto);
                        if ($albumPhotoName != "default-album.png") {
                            Utils::deleteFile($targetDir . $albumPhotoName);
                        }
                        $albumName = $row->albumName;
                        $timestamp = Utils::getCurrentDate();
                        $albumPhotoName = "album-" . md5(Utils::convertCamelString("$albumName-$timestamp")) . ".png";
                    }

                    $targetFile = $targetDir . $albumPhotoName;
                    if (move_uploaded_file($_FILES["albumPhoto"]["tmp_name"], $targetFile)) {
                        $albumPhoto = $constant->BASE_ASSET_URL . "/images/album/" . $albumPhotoName;
                        $query = "UPDATE album SET albumPhoto = '$albumPhoto' WHERE albumId = '$albumId'";
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
            $response['message'] = $constant->RESPONSE_MESSAGES["album_id_needed"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
