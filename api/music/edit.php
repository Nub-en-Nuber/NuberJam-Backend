<?php

include '../../config/database.php';
include '../../config/utils.php';
$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $checkName = false;
            $checkMusicFile = false;
            $checkDuration = false;
            $checkAlbumId = false;

            if (isset($_POST['name'])) {
                $name = $_POST['name'];
                $query = "UPDATE music SET name = '$name' WHERE id = '$id'";
                if (mysqli_query($database->connection, $query)) $checkName = true;
            }

            if (isset($_POST['duration'])) {
                $duration = $_POST['duration'];
                $query = "UPDATE music SET duration = '$duration' WHERE id = '$id'";
                if (mysqli_query($database->connection, $query)) $checkDuration = true;
            }

            if (isset($_POST['album_id'])) {
                $album_id = $_POST['album_id'];
                $query = "UPDATE music SET albumId = '$album_id' WHERE id = '$id'";
                if (mysqli_query($database->connection, $query)) $checkAlbumId = true;
            }

            if (isset($_FILES['music_file'])) {
                $querySelect = "SELECT * FROM music WHERE id = '$id'";
                $executeSelect = mysqli_query($database->connection, $querySelect);
                $check = mysqli_affected_rows($database->connection);


                if ($check > 0) {
                    $musicFileName = null;
                    while ($row = mysqli_fetch_object($executeSelect)) {
                        $musicFilePath = $row->musicFile;
                        $musicFile = explode("/", $musicFilePath);
                        $musicFileName = end($musicFile);

                        Utils::deleteFile("../../asset/music/$musicFileName");

                        $name = $row->name;
                        $album_id = $row->albumId;
                        $musicFileName = Utils::convertCamelString("$album_id-$name-") . md5(Utils::convertCamelString("$album_id-$name")) . ".mp3";
                    }

                    $target_dir = "../../asset/music/";
                    $target_file = $target_dir . $musicFileName;
                    if (move_uploaded_file($_FILES["music_file"]["tmp_name"], $target_file)) {
                        $musicFile = $constant->BASE_ASSET_URL . "/music/" . $musicFileName;
                        $query = "UPDATE music SET musicFile = '$musicFile' WHERE id = '$id'";
                        if (mysqli_query($database->connection, $query)) $checkMusicFile = true;
                    }
                } else {
                    $checkMusicFile = false;
                }
            }

            if ($cekName || $checkMusicFile || $cekDuration || $cekAlbumId) {
                $response["status"] = $constant->RESPONSE_STATUS["success"];
                $response["message"] = $constant->RESPONSE_MESSAGES["edit_success"];
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                $response["message"] = $constant->RESPONSE_MESSAGES["edit_failed"];
            }
        } else {
            $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
            $response['message'] = $constant->RESPONSE_MESSAGES["musicid_needed"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
