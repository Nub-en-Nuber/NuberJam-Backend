<?php

include "../../config/database.php";
include '../../config/utils.php';
$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (!empty($_POST['accountName']) && !empty($_POST['accountUsername']) && !empty($_POST['accountEmail']) && !empty($_POST['accountPassword'])) {
            $accountName = $_POST['accountName'];
            $accountUsername = $_POST['accountUsername'];
            $accountEmail = $_POST['accountEmail'];
            $accountPassword = $_POST['accountPassword'];
            $accountPhoto = $constant->BASE_ASSET_URL . "/images/account/default-account.png";

            $encryptPassword = Utils::getEncryptPassword($accountPassword);

            $queryCheck = "SELECT * FROM account WHERE accountUsername = '$accountUsername'";
            $execute = mysqli_query($database->connection, $queryCheck);
            $usernameExist = mysqli_num_rows($execute) > 0 ? true : false;

            $queryCheck = "SELECT * FROM account WHERE accountEmail = '$accountEmail'";
            $execute = mysqli_query($database->connection, $queryCheck);
            $emailExist = mysqli_num_rows($execute) > 0 ? true : false;

            if ($usernameExist) {
                $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                $response["message"] = $constant->RESPONSE_MESSAGES["username_used"];
            } else if ($emailExist) {
                $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                $response["message"] = $constant->RESPONSE_MESSAGES["email_used"];
            } else {
                $query = "INSERT INTO account (accountName, accountUsername, accountEmail, accountPassword, accountPhoto) VALUES ('$accountName','$accountUsername','$accountEmail','$encryptPassword','$accountPhoto')";
                $execute = mysqli_query($database->connection, $query);
                $check = mysqli_affected_rows($database->connection);

                if ($check > 0) {
                    $response["status"] = $constant->RESPONSE_STATUS["success"];
                    $response["message"] = $constant->RESPONSE_MESSAGES["add_success"];
                } else {
                    $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                    $response["message"] = $constant->RESPONSE_MESSAGES["add_failed"];
                }
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
