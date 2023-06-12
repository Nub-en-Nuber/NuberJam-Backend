<?php

include "../../config/database.php";
$database = new Database();
$constant = new Constants();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['accountEmail']) || isset($_GET['accountUsername'])) {
            if (isset($_GET['accountEmail'])) {
                $accountLogin = $_GET['accountEmail'];
            } else if (isset($_GET['accountUsername'])) {
                $accountLogin = $_GET['accountUsername'];
            }

            $query = "SELECT * FROM account WHERE accountEmail = '$accountLogin' OR accountUsername = '$accountLogin'";

            $execute = mysqli_query($database->connection, $query);
            $check = mysqli_affected_rows($database->connection);

            if ($check > 0) {
                $response["data"] = array();

                while ($row = mysqli_fetch_object($execute)) {
                    $data["accountId"] = $row->accountId;
                    $data["accountName"] = $row->accountName;
                    $data["accountUsername"] = $row->accountUsername;
                    $data["accountEmail"] = $row->accountEmail;
                    $data["accountPassword"] = $row->accountPassword;
                    $data["accountPhoto"] = $row->accountPhoto;
                    array_push($response["data"], $data);
                }
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["not_found"];
                $response["message"] = $constant->RESPONSE_MESSAGES["unavailable_data"];
            }
        } else {
            $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
            $response['message'] = $constant->RESPONSE_MESSAGES["email_or_password_needed"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
