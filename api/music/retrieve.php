<?php

include '../../config/database.php';
$database = new Database();
$constant = new Constants();

function getAlbumListDistinct($dataTempList)
{
    $albumListDistinct = [];
    $albumListIdDistinct = [];
    foreach ($dataTempList as $dataTemp) {
        if (!in_array($dataTemp['albumId'], $albumListIdDistinct)) {
            $albumTemp['albumId'] = $dataTemp['albumId'];
            $albumTemp['albumName'] = $dataTemp['albumName'];
            $albumTemp['albumPhoto'] = $dataTemp['albumPhoto'];
            array_push($albumListDistinct, $albumTemp);
            array_push($albumListIdDistinct, $dataTemp['albumId']);
        }
    }
    return $albumListDistinct;
}

function getAlbumArtist($database, $albumId)
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

function getAlbumMusic($dataTempList, $albumId)
{
    $musicList = array();
    foreach ($dataTempList as $dataTemp) {
        if ($dataTemp['albumId'] == $albumId) {
            $musicData["playlistDetailId"] = null;
            $musicData["musicId"] = $dataTemp['musicId'];
            $musicData["musicName"] = $dataTemp['musicName'];
            $musicData["musicDuration"] = $dataTemp['musicDuration'];
            $musicData["musicFile"] = $dataTemp['musicFile'];
            $musicData["musicIsFavorite"] = $dataTemp['musicIsFavorite'];
            array_push($musicList, $musicData);
        }
    }
    return $musicList;
}

function getAlbumList($database, $dataTempList)
{
    $albumList = [];
    $albumListDistinct = getAlbumListDistinct($dataTempList);
    foreach ($albumListDistinct as $albumDistinct) {
        $albumData["albumId"] = $albumDistinct["albumId"];
        $albumData["albumName"] = $albumDistinct["albumName"];
        $albumData["albumArtist"] = getAlbumArtist($database, $albumDistinct["albumId"]);
        $albumData["albumPhoto"] = $albumDistinct["albumPhoto"];
        $albumData["music"] = getAlbumMusic($dataTempList, $albumDistinct["albumId"]);
        array_push($albumList, $albumData);
    }
    return $albumList;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['musicId'])) {
            $musicId = $_GET['musicId'];
            $query = "SELECT m.id as id, m.name as name, m.duration as duration, m.musicFile as musicFile, " .
                "a.id as albumId, a.name as albumName, a.artist as albumArtist, a.photo as albumPhoto " .
                "FROM music m LEFT JOIN album a ON m.albumId = a.id WHERE m.id = '$id'";
        } else if (isset($_GET['q'])) {
            $searchQuery = $_GET['q'];
            $query = "SELECT m.id as id, m.name as name, m.duration as duration, m.musicFile as musicFile, " .
                "a.id as albumId, a.name as albumName, a.artist as albumArtist, a.photo as albumPhoto " .
                "FROM music m LEFT JOIN album a ON m.albumId = a.id WHERE m.name LIKE '%$searchQuery%' OR a.name LIKE '%$searchQuery%'";
        } else {
            $query = "SELECT * FROM music LEFT JOIN album ON music.albumId = album.albumId";
        }

        $execute = mysqli_query($database->connection, $query);
        $success = mysqli_affected_rows($database->connection);

        if ($success > 0) {
            $tempList = array();
            while ($row = mysqli_fetch_object($execute)) {
                $temp["musicId"] = $row->musicId;
                $temp["musicName"] = $row->musicName;
                $temp["musicDuration"] = $row->musicDuration;
                $temp["musicFile"] = $row->musicFile;
                $temp["albumId"] = $row->albumId;
                $temp["albumName"] = $row->albumName;
                $temp["albumPhoto"] = $row->albumPhoto;
                if (isset($_GET['accountId'])) {
                    $accountId = $_GET['accountId'];
                    $favoriteQuery = "SELECT * FROM favorite WHERE musicId = '$row->musicId' AND accountId = '$accountId'";
                    $favoriteResult = mysqli_query($database->connection, $favoriteQuery);
                    $musicIsFavorite = mysqli_num_rows($favoriteResult) > 0 ? true : false;
                    $temp["musicIsFavorite"] = $musicIsFavorite;
                }
                array_push($tempList, $temp);
            }
            $data["playlist"] = array();
            $data["album"] = getAlbumList($database, $tempList);
            $data["account"] = array();
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
