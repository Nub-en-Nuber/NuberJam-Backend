<?php

include '../../config/database.php';
$database = new Database();
$constant = new Constants();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $query = "SELECT * FROM album WHERE id = '$id'";

            $execute = mysqli_query($database->connection, $query);
            $cek = mysqli_affected_rows($database->connection);

            if ($cek > 0) {
                $response["data"] = array();
                while ($row = mysqli_fetch_object($execute)) {
                    $data["id"] = $row->id;
                    $data["name"] = $row->name;
                    $data["artist"] = $row->artist;
                    $data["photo"] = $row->photo;
                    array_push($response["data"], $data);
                }
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["not_found"];
                $response["message"] = $constant->RESPONSE_MESSAGES["unavailable_data"];
            }
        } else {
            $query = "SELECT * FROM album";

            $execute = mysqli_query($database->connection, $query);
            $response["data"] = array();
            while ($row = mysqli_fetch_object($execute)) {
                $data["id"] = $row->id;
                $data["name"] = $row->name;
                $data["artist"] = $row->artist;
                $data["photo"] = $row->photo;
                array_push($response["data"], $data);
            }
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
