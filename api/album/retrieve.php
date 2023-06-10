<?php

include '../../config/database.php';
$database = new Database();
$constant = new Constants();

function getArtistList($database, $albumId)
{
    $artistList = array();
    $queryArtist = "SELECT * FROM album_artist LEFT JOIN account ON account.accountId = album_artist.accountId WHERE albumId = '$albumId'";
    $executeArtist = mysqli_query($database->connection, $queryArtist);
    while ($rowArtist = mysqli_fetch_object($executeArtist)) {
        $artistData["accountId"] = $rowArtist->accountId;
        $artistData["accountName"] = $rowArtist->accountName;
        array_push($artistList, $artistData);
    }
    return $artistList;
}

function getAlbumList($database, $execute)
{
    $albumList = array();
    while ($row = mysqli_fetch_object($execute)) {
        $albumData["albumId"] = $row->albumId;
        $albumData["albumName"] = $row->albumName;
        $albumData["albumArtist"] = getArtistList($database, $row->albumId);
        $albumData["albumPhoto"] = $row->albumPhoto;
        $albumData["music"] = array();
        array_push($albumList, $albumData);
    }
    return $albumList;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['albumId'])) {
            $albumId = $_GET['albumId'];
            $query = "SELECT * FROM album WHERE albumId = '$albumId'";
        } else {
            $query = "SELECT * FROM album";
        }

        $execute = mysqli_query($database->connection, $query);
        $check = mysqli_affected_rows($database->connection);

        if ($check > 0) {
            $data["playlist"] = array();
            $data["album"] = getAlbumList($database, $execute);
            $data["user"] = array();
            $response['data'] = $data;
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
