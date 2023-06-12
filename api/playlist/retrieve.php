<?php

include "../../config/database.php";
$database = new Database();
$constant = new Constants();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['accountId'])) {
            $accountId = $_GET['accountId'];
            if ((isset($_GET['q']))) {
                $keyword = $_GET['q'];
                $query = "SELECT * FROM playlist WHERE accountId = '$accountId' AND playlistName LIKE '%$keyword%'";
            } else {
                $query = "SELECT * FROM playlist WHERE accountId = '$accountId'";
            }

            $execute = mysqli_query($database->connection, $query);
            $check = mysqli_affected_rows($database->connection);

            if ($check > 0) {
                $response["data"] = array();
                $data["playlist"] = array();
                $data["album"] = array();
                $data["account"] = array();

                while ($row = mysqli_fetch_object($execute)) {
                    $playlistData["playlistId"] = $row->playlistId;
                    $playlistData["playlistName"] = $row->playlistName;
                    $playlistData["playlistPhoto"] = $row->playlistPhoto;
                    array_push($data["playlist"], $playlistData);
                }
                $response["data"] = $data;
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["not_found"];
                $response["message"] = $constant->RESPONSE_MESSAGES["unavailable_data"];
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
