<?php

include "../../config/database.php";
include "../../config/utils.php";
$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (!empty($_POST)) {
            if (isset($_POST['accountEmail'])) {
                $accountLogin = $_POST['accountEmail'];
            } else if (isset($_POST['accountUsername'])) {
                $accountLogin = $_POST['accountUsername'];
            }
            $accountPassword = $_POST['accountPassword'];
            $encryptPassword = Utils::getEncryptPassword($accountPassword);


            $query = "SELECT * FROM account WHERE (accountEmail = '$accountLogin' OR accountUsername = '$accountLogin') AND accountPassword = '$encryptPassword'";
            $execute = mysqli_query($database->connection, $query);
            $check = mysqli_affected_rows($database->connection);

            if ($check > 0) {
                $response["status"] = $constant->RESPONSE_STATUS["success"];
                $response["message"] = $constant->RESPONSE_MESSAGES["login_success"];
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                $response["message"] = $constant->RESPONSE_MESSAGES["login_failed"];
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
