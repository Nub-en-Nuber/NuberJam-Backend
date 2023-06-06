<?php

include "../../config/database.php";
include '../../config/utils.php';

$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['playlistId'])) {
            $playlistId = $_GET['playlistId'];

            $cekName = false;
            $cekPhoto = false;

            if (isset($_POST['name'])) {
                $name = $_POST['name'];
                $query = "UPDATE playlist SET name = '$name' WHERE id = '$playlistId'";
                if (mysqli_query($database->connection, $query)) $cekName = true;
            }

            if (isset($_FILES['photo'])) {
                $querySelect = "SELECT * FROM playlist WHERE id = '$playlistId'";
                $executeSelect = mysqli_query($database->connection, $querySelect);
                $cek = mysqli_affected_rows($database->connection);
                if ($cek > 0) {
                    $photoName = null;
                    while ($row = mysqli_fetch_object($executeSelect)) {
                        $photoPath = $row->photo;
                        $photo = explode("/", $photoPath);
                        $photoName = end($photo);
                        if ($photoName == "default-playlist.png") {
                            $userId = $row->userId;
                            $name = $row->name;
                            $photoName = Utils::convertCamelString("$userId-$name-") . md5(Utils::convertCamelString("$userId-$name")) . ".png";
                        }
                    }
                    $target_dir = "../../asset/images/playlist/";
                    $target_file = $target_dir . $photoName;
                    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                        $photo = $constant->BASE_ASSET_URL . "/images/playlist/" . $photoName;
                    } else {
                        $photo = $constant->BASE_ASSET_URL . "/images/playlist/default-playlist.png";
                    }
                    $query = "UPDATE playlist SET photo = '$photo' WHERE id = '$playlistId'";
                    if (mysqli_query($database->connection, $query)) $cekPhoto = true;
                } else {
                    $cekPhoto = false;
                }
            }

            if ($cekName || $cekPhoto || $cekPassword) {
                $response["status"] = $constant->RESPONSE_STATUS["success"];
                $response["message"] = $constant->RESPONSE_MESSAGES["edit_success"];
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                $response["message"] = $constant->RESPONSE_MESSAGES["edit_failed"];
            }
        } else {
            $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
            $response['message'] = $constant->RESPONSE_MESSAGES["playlistid_needed"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
