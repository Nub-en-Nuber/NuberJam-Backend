<?php

include '../../config/database.php';
include '../../config/utils.php';

$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $querySelect = "SELECT * FROM album WHERE id = '$id'";
            $executeSelect = mysqli_query($database->connection, $querySelect);
            $cek = mysqli_affected_rows($database->connection);

            if ($cek > 0) {
                while ($row = mysqli_fetch_object($executeSelect)) {
                    $photoPath = $row->photo;
                    $photo = explode("/", $photoPath);
                    $photoName = end($photo);
                    if ($photoName != "default-album.png") {
                        Utils::deleteFile("../../asset/images/album/$photoName");
                    }
                }

                $query = "DELETE FROM album WHERE id = '$id'";
                $execute = mysqli_query($database->connection, $query);
                $cek = mysqli_affected_rows($database->connection);

                if ($cek > 0) {
                    $response["status"] = $constant->RESPONSE_STATUS["success"];
                    $response["message"] = $constant->RESPONSE_MESSAGES["delete_success"];
                } else {
                    $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                    $response["message"] = $constant->RESPONSE_MESSAGES["delete_failed"];
                }
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                $response["message"] = $constant->RESPONSE_MESSAGES["delete_failed"];
            }
        } else {
            $querySelect = "SELECT * FROM album";
            $executeSelect = mysqli_query($database->connection, $querySelect);
            $cek = mysqli_affected_rows($database->connection);
            if ($cek > 0) {
                while ($row = mysqli_fetch_object($executeSelect)) {
                    $photoPath = $row->photo;
                    $photo = explode("/", $photoPath);
                    $photoName = end($photo);
                    if ($photoName != "default-album.png") {
                        Utils::deleteFile("../../asset/images/album/$photoName");
                    }
                }
                $query = "DELETE FROM album";
                $execute = mysqli_query($database->connection, $query);
                $cek = mysqli_affected_rows($database->connection);

                if ($cek > 0) {
                    $response["status"] = $constant->RESPONSE_STATUS["success"];
                    $response["message"] = $constant->RESPONSE_MESSAGES["delete_success"];
                } else {
                    $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                    $response["message"] = $constant->RESPONSE_MESSAGES["delete_failed"];
                }
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                $response["message"] = $constant->RESPONSE_MESSAGES["delete_failed"];
            }
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
