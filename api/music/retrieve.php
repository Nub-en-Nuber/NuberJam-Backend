<?php

include '../../config/database.php';
$database = new Database();
$constant = new Constants();

function read_detail_music()
{
    $id = $_GET['id'];
    $query = "SELECT m.id as id, m.name as name, m.duration as duration, m.musicFile as musicFile, " .
        "a.id as albumId, a.name as albumName, a.artist as albumArtist, a.photo as albumPhoto " .
        "FROM music m LEFT JOIN album a ON m.albumId = a.id WHERE m.id = '$id'";
    return $query;
}

function read_all_music()
{
    if (isset($_GET['album_id'])) {
        $album_id = $_GET['album_id'];
        $query = "SELECT m.id as id, m.name as name, m.duration as duration, m.musicFile as musicFile, " .
            "a.id as albumId, a.name as albumName, a.artist as albumArtist, a.photo as albumPhoto " .
            "FROM music m LEFT JOIN album a ON m.albumId = a.id WHERE a.id = '$album_id'";
    } else {
        $query = "SELECT m.id as id, m.name as name, m.duration as duration, m.musicFile as musicFile, " .
            "a.id as albumId, a.name as albumName, a.artist as albumArtist, a.photo as albumPhoto " .
            "FROM music m LEFT JOIN album a ON m.albumId = a.id";
    }
    return $query;
}

function search_music()
{
    $searchQuery = $_GET['q'];
    $query = "SELECT m.id as id, m.name as name, m.duration as duration, m.musicFile as musicFile, " .
        "a.id as albumId, a.name as albumName, a.artist as albumArtist, a.photo as albumPhoto " .
        "FROM music m LEFT JOIN album a ON m.albumId = a.id WHERE m.name LIKE '%$searchQuery%' OR a.name LIKE '%$searchQuery%'";
    return $query;
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['id'])) {
            $query = read_detail_music();
        } else if (isset($_GET['q'])) {
            $query = search_music();
        } else {
            $query = read_all_music();
        }

        $execute = mysqli_query($database->connection, $query);
        $success = mysqli_affected_rows($database->connection);

        if ($success > 0) {
            $response["data"] = array();
            while ($row = mysqli_fetch_object($execute)) {
                $data["id"] = $row->id;
                $data["name"] = $row->name;
                $data["duration"] = $row->duration;
                $data["musicFile"] = $row->musicFile;
                $data["albumId"] = $row->albumId;
                $data["albumName"] = $row->albumName;
                $data["albumArtist"] = $row->albumArtist;
                $data["albumPhoto"] = $row->albumPhoto;
                if (isset($_GET['user_id'])) {
                    $user_id = $_GET['user_id'];
                    $favoriteQuery = "SELECT * FROM favorite WHERE musicId = '$row->id' AND userId = '$user_id'";
                    $favoriteResult = mysqli_query($database->connection, $favoriteQuery);
                    $isFavorite = mysqli_num_rows($favoriteResult) > 0 ? true : false;
                    $data["isFavorite"] = $isFavorite;
                }
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
