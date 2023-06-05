<?php

include "../../config/database.php";
include '../../config/utils.php';

$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (!empty($_POST)) {
            $name = $_POST['name'];
            $userId = $_POST['userId'];

            $target_dir = "../../asset/images/playlist/";
            $photoName = Utils::convertCamelString("$userId-$name-") . md5(Utils::convertCamelString("$userId-$name")) . ".png";
            $target_file = $target_dir . $photoName;

            if (isset($_FILES['photo'])) {
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                    $photo = $constant->BASE_ASSET_URL . "/images/playlist/" . $photoName;
                } else {
                    $photo = $constant->BASE_ASSET_URL . "/images/playlist/default-playlist.png";
                }
            } else {
                $photo = $constant->BASE_ASSET_URL . "/images/playlist/default-playlist.png";
            }

            $query = "INSERT INTO playlist (name, photo, userId) VALUES ('$name','$photo','$userId')";
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
