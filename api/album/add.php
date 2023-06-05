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
            $artist = $_POST['artist'];

            $target_dir = "../../asset/images/album/";
            $photoName = Utils::convertCamelString("$artist-$name-") . md5(Utils::convertCamelString("$artist-$name")) . ".png";
            $target_file = $target_dir . $photoName;
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = $constant->BASE_ASSET_URL . "/images/album/" . $photoName;
            } else {
                $photo = $constant->BASE_ASSET_URL . "/images/album/default-album.png";
            }

            $query = "INSERT INTO album (name, artist, photo) VALUES ('$name','$artist','$photo')";
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
