<?php

include "../../config/database.php";
include "../../config/utils.php";
$database = new Database();
$constant = new Constants();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['accountId'])) {
            $accountId = $_GET['accountId'];

            $checkName = false;
            $checkPhoto = false;
            $checkPassword = false;

            if (!empty($_POST['accountName'])) {
                $accountName = $_POST['accountName'];
                $query = "UPDATE account SET accountName = '$accountName' WHERE accountId = '$accountId'";
                if (mysqli_query($database->connection, $query)) $checkName = true;
            }

            if (!empty($_FILES['accountPhoto'])) {
                $querySelect = "SELECT * FROM account WHERE accountId = '$accountId'";
                $executeSelect = mysqli_query($database->connection, $querySelect);
                $check = mysqli_affected_rows($database->connection);
                if ($check > 0) {
                    $accountPhotoName = null;
                    $targetDir = "../../asset/images/account/";

                    while ($row = mysqli_fetch_object($executeSelect)) {
                        $accountPhotoPath = $row->accountPhoto;
                        $accountPhoto = explode("/", $accountPhotoPath);
                        $accountPhotoName = end($accountPhoto);
                        if ($accountPhotoName != "default-account.png") {
                            Utils::deleteFile($targetDir . $accountPhotoName);
                        }
                        $accountName = $row->accountName;
                        $timestamp = Utils::getCurrentDate();
                        $accountPhotoName = "account-" . md5(Utils::convertCamelString("$accountName-$timestamp")) . ".png";
                    }

                    $targetFile = $targetDir . $accountPhotoName;
                    if (move_uploaded_file($_FILES["accountPhoto"]["tmp_name"], $targetFile)) {
                        $accountPhoto = $constant->BASE_ASSET_URL . "/images/account/" . $accountPhotoName;
                        $query = "UPDATE account SET accountPhoto = '$accountPhoto' WHERE accountId = '$accountId'";
                        if (mysqli_query($database->connection, $query)) $checkPhoto = true;
                    }
                }
            }

            if (!empty($_POST['accountPassword'])) {
                $accountPassword = $_POST['accountPassword'];
                $encryptPassword = Utils::getEncryptPassword($accountPassword);
                $query = "UPDATE account SET accountPassword = '$encryptPassword' WHERE accountId = '$accountId'";
                if (mysqli_query($database->connection, $query)) $checkPassword = true;
            }

            if ($checkName || $checkPhoto || $checkPassword) {
                $response["status"] = $constant->RESPONSE_STATUS["success"];
                $response["message"] = $constant->RESPONSE_MESSAGES["edit_success"];
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["internal_server_error"];
                $response["message"] = $constant->RESPONSE_MESSAGES["edit_failed"];
            }
        } else {
            $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
            $response['message'] = $constant->RESPONSE_MESSAGES["account_id_needed"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
