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

            $cekName = false;
            $cekPhoto = false;
            $cekPassword = false;

            if (isset($_POST['name'])) {
                $name = $_POST['name'];
                $query = "UPDATE album SET name = '$name' WHERE id = '$id'";
                if (mysqli_query($database->connection, $query)) $cekName = true;
            }

            if (isset($_POST['artist'])) {
                $artist = $_POST['artist'];
                $query = "UPDATE album SET artist = '$artist' WHERE id = '$id'";
                if (mysqli_query($database->connection, $query)) $cekPhoto = true;
            }

            if (isset($_FILES['photo'])) {
                $querySelect = "SELECT * FROM album WHERE id = '$id'";
                $executeSelect = mysqli_query($database->connection, $querySelect);
                $cek = mysqli_affected_rows($database->connection);
                if ($cek > 0) {
                    $photoName = null;
                    while ($row = mysqli_fetch_object($executeSelect)) {
                        $photoPath = $row->photo;
                        $photo = explode("/", $photoPath);
                        $photoName = end($photo);
                        if ($photoName == "default-album.png") {
                            $name = $row->name;
                            $artist = $row->artist;
                            $photoName = Utils::convertCamelString("$artist-$name-") . md5(Utils::convertCamelString("$artist-$name")) . ".png";
                        }
                    }
                    $target_dir = "../../asset/images/album/";
                    $target_file = $target_dir . $photoName;
                    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                        $photo = $constant->BASE_ASSET_URL . "/images/album/" . $photoName;
                    } else {
                        $photo = $constant->BASE_ASSET_URL . "/images/album/default-album.png";
                    }
                    $query = "UPDATE album SET photo = '$photo' WHERE id = '$id'";
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
            $response['message'] = $constant->RESPONSE_MESSAGES["albumid_needed"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
