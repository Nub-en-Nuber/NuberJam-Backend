<?php

include '../../config/database.php';
$database = new Database();
$constant = new Constants();

function getAlbumListDistinct($dataTempList)
{
    $albumListDistinct = array();
    $albumListIdDistinct = array();
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
            $musicData["musicArtist"] = $dataTemp['musicArtist'];
            $musicData["musicIsFavorite"] = $dataTemp['musicIsFavorite'];
            array_push($musicList, $musicData);
        }
    }
    return $musicList;
}

function getAlbumList($dataTempList)
{
    $albumList = array();
    $albumListDistinct = getAlbumListDistinct($dataTempList);
    foreach ($albumListDistinct as $albumDistinct) {
        $albumData["albumId"] = $albumDistinct["albumId"];
        $albumData["albumName"] = $albumDistinct["albumName"];
        $albumData["albumPhoto"] = $albumDistinct["albumPhoto"];
        $albumData["music"] = getAlbumMusic($dataTempList, $albumDistinct["albumId"]);
        array_push($albumList, $albumData);
    }
    return $albumList;
}

function getMusicArtist($database, $musicId)
{
    $artistList = array();
    $queryArtist = "SELECT * FROM music_artist LEFT JOIN account ON account.accountId = music_artist.accountId WHERE musicId = '$musicId'";
    $executeArtist = mysqli_query($database->connection, $queryArtist);
    while ($rowArtist = mysqli_fetch_object($executeArtist)) {
        $artistData["accountId"] = $rowArtist->accountId;
        $artistData["accountName"] = $rowArtist->accountName;
        array_push($artistList, $artistData);
    }
    return $artistList;
}

function getFavoriteMusic($database, $musicId)
{
    $accountId = $_GET['accountId'];
    $favoriteQuery = "SELECT * FROM favorite WHERE musicId = '$musicId' AND accountId = '$accountId'";
    $favoriteResult = mysqli_query($database->connection, $favoriteQuery);
    $musicIsFavorite = mysqli_num_rows($favoriteResult) > 0 ? true : false;

    return $musicIsFavorite;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = $database->checkToken();
    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['accountId'])) {
            if (isset($_GET['musicId'])) {
                $musicId = $_GET['musicId'];
                $query = "SELECT * FROM music LEFT JOIN album ON music.albumId = album.albumId WHERE musicId = '$musicId'";
            } else if (isset($_GET['q'])) {
                $searchQuery = $_GET['q'];
                $query = "SELECT * FROM music LEFT JOIN album ON music.albumId = album.albumId WHERE musicName LIKE '%$searchQuery%' OR albumName LIKE '%$searchQuery%'";
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
                    $temp["musicIsFavorite"] = getFavoriteMusic($database, $row->musicId);
                    $temp["musicArtist"] = getMusicArtist($database, $row->musicId);
                    array_push($tempList, $temp);
                }
                $data["playlist"] = array();
                $data["album"] = getAlbumList($tempList);
                $data["account"] = array();
                $response['data'] = $data;
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
