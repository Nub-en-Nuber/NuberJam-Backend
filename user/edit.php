<?php

include '../database.php';
$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['userId'])) {
            $userId = $_GET['userId'];

            $cekName = false;
            $cekPhoto = false;
            $cekPassword = false;

            if (isset($_POST['name'])) {
                echo "masukName";
                $name = $_POST['name'];
                $query = "UPDATE user SET name = '$name' WHERE id = '$userId'";
                if (mysqli_query($database->connection, $query)) $cekName = true;
            }

            if (isset($_FILES['photo'])) {
                $target_dir = "../asset/images/user/";
                $target_file = $target_dir . md5($userId) . ".png";
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                    $photo = $constant->BASE_URL . "/asset/images/user/" . md5($userId) . ".png";
                } else {
                    $photo = $constant->BASE_URL . "/asset/images/user/default_user.png";
                }
                $query = "UPDATE user SET photo = '$photo' WHERE id = '$userId'";
                if (mysqli_query($database->connection, $query)) $cekPhoto = true;
            }

            if (isset($_POST['password'])) {
                $password = $_POST['password'];
                $query = "UPDATE user SET password = '$password' WHERE id = '$userId'";
                if (mysqli_query($database->connection, $query)) $cekPassword = true;
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
            $response['message'] = $constant->RESPONSE_MESSAGES["userid_needed"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
