<?php

include "../../config/database.php";
$database = new Database();
$constant = new Constants();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['userId'])) {
            $userId = $_GET['userId'];
            $query = "SELECT * FROM playlist WHERE userId = '$userId'";
        } else {
            $query = "SELECT * FROM playlist";
        }
        $execute = mysqli_query($database->connection, $query);
        $cek = mysqli_affected_rows($database->connection);

        if ($cek > 0) {
            $response["data"] = array();

            while ($row = mysqli_fetch_object($execute)) {
                $data["id"] = $row->id;
                $data["name"] = $row->name;
                $data["photo"] = $row->photo;
                $data["userId"] = $row->userId;
                array_push($response["data"], $data);
            }
        } else {
            $response["status"] = $constant->RESPONSE_STATUS["not_found"];
            $response["message"] = $constant->RESPONSE_MESSAGES["unavailable_data"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
