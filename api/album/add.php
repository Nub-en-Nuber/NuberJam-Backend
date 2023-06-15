<?php

include '../../config/database.php';
include '../../config/utils.php';

$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (!empty($_POST['albumName']) && !in_array("", $_POST['accountIds'])) {
            $albumName = $_POST['albumName'];
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

                $query = "SELECT albumId from album WHERE albumName = '$albumName' AND albumPhoto = '$albumPhoto'";
                $execute = mysqli_query($database->connection, $query);
                $check = mysqli_affected_rows($database->connection);

                $row = mysqli_fetch_object($execute);
                $albumId = $row->albumId;

                foreach ($accountIds as $index => $accountId) {
                    $query = "INSERT INTO album_artist (albumId, accountId) VALUES ('$albumId', '$accountId')";
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
