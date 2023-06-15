<?php

include "../../../config/database.php";
include "../../../config/utils.php";
$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (!empty($_POST)) {
            if (isset($_POST['accountId'])) {
                $accountId = $_POST['accountId'];

                $queryCheck = "SELECT * FROM account WHERE accountId = '$accountId'";
                $execute = mysqli_query($database->connection, $queryCheck);
                $accountIsExist = mysqli_num_rows($execute) > 0 ? true : false;

                if ($accountIsExist) {
                    $query = "SELECT * FROM album_artist WHERE accountId = '$accountId'";
                    $execute = mysqli_query($database->connection, $query);
                    $accountIsArtist = mysqli_num_rows($execute) > 0 ? true : false;

                    if ($accountIsArtist) {
                        $response["status"] = $constant->RESPONSE_STATUS["success"];
                        $response["message"] = $constant->RESPONSE_MESSAGES["account_artist"];
                    } else {
                        $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                        $response["message"] = $constant->RESPONSE_MESSAGES["account_not_artist"];
                    }
                } else {
                    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
                    $response['message'] = $constant->RESPONSE_MESSAGES["unavailable_data"];
                }
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
                $response['message'] = $constant->RESPONSE_MESSAGES["account_id_needed"];
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
