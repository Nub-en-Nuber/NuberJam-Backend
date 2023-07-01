<?php

include "../../../config/database.php";

$database = new Database();
$constant = new Constants();

function getPlaylistAlbumQuery($database, $playlistId, $accountId)
{
    if (isset($_GET['q'])) {
        $keyword = $_GET['q'];
        $query = "SELECT * FROM `playlist_detail` JOIN `music` ON `playlist_detail`.`musicId` = `music`.`musicId`  JOIN `album` ON `music`.`albumId` = `album`.`albumId` WHERE `playlist_detail`.`playlistId` = '$playlistId' AND `music`.`musicName` LIKE '%$keyword%' GROUP BY `album`.`albumId`";
    } else {
        $query = "SELECT * FROM `playlist_detail` JOIN `music` ON `playlist_detail`.`musicId` = `music`.`musicId`  JOIN `album` ON `music`.`albumId` = `album`.`albumId` WHERE `playlist_detail`.`playlistId` = '$playlistId' GROUP BY `album`.`albumId`";
    }
    $execute = mysqli_query($database->connection, $query);
    $check = mysqli_affected_rows($database->connection);

    $album = array();
    if ($check > 0) {
        while ($row = mysqli_fetch_object($execute)) {

            $albumData["albumId"] = $row->albumId;
            $albumData["albumName"] = $row->albumName;
            $albumData["albumPhoto"] = $row->albumPhoto;
            $albumData["music"] = getAlbumMusic($database, $row->albumId, $playlistId, $accountId);

            array_push($album, $albumData);
        }
    }
    return $album;
}

function getSongFavorite($database, $musicId, $accountId)
{
    $query = "SELECT * FROM `favorite` WHERE `musicId` = '$musicId' AND `accountId` = '$accountId'";
    mysqli_query($database->connection, $query);
    $check = mysqli_affected_rows($database->connection);

    return ($check > 0) ? true : false;
}

function getAlbumMusic($database, $albumId, $playlistId, $userId)
{
    if (isset($_GET['q'])) {
        $keyword = $_GET['q'];
        $query = "SELECT * FROM `playlist_detail` JOIN `music` ON `playlist_detail`.`musicId` = `music`.`musicId`  JOIN `album` ON `music`.`albumId` = `album`.`albumId` WHERE `playlist_detail`.`playlistId` = '$playlistId' AND `album`.`albumId` = '$albumId' AND `music`.`musicName` LIKE '%$keyword%'";
    } else {
        $query = "SELECT * FROM `playlist_detail` JOIN `music` ON `playlist_detail`.`musicId` = `music`.`musicId`  JOIN `album` ON `music`.`albumId` = `album`.`albumId` WHERE `playlist_detail`.`playlistId` = '$playlistId' AND `album`.`albumId` = '$albumId'";
    }

    $execute = mysqli_query($database->connection, $query);
    $check = mysqli_affected_rows($database->connection);

    $song = array();
    if ($check > 0) {
        while ($row = mysqli_fetch_object($execute)) {
            $songData["playlistDetailId"] = $row->playlistDetailId;
            $songData["musicId"] = $row->musicId;
            $songData["musicName"] = $row->musicName;
            $songData["musicDuration"] = $row->musicDuration;
            $songData["musicFile"] = $row->musicFile;
            $songData["musicArtist"] = getMusicArtist($database, $row->musicId);
            $songData["musicIsFavorite"] = getSongFavorite($database, $row->musicId, $userId);
            array_push($song, $songData);
        }
    }
    return $song;
}

function getMusicArtist($database, $musicId)
{
    $query = "SELECT * FROM `music_artist` JOIN `account` ON `music_artist`.`accountId` = `account`.`accountId` WHERE `musicId` = '$musicId'";
    $execute = mysqli_query($database->connection, $query);
    $check = mysqli_affected_rows($database->connection);

    $artist = array();
    if ($check > 0) {
        while ($row = mysqli_fetch_object($execute)) {
            $artistData["artistId"] = $row->artistId;
            $artistData["artistName"] = $row->accountName;
            array_push($artist, $artistData);
        }
    }
    return $artist;
}

function getPlaylistDetail($playlistId)
{
    if (isset($_GET['q'])) {
        $keyword = $_GET['q'];
        return "SELECT * FROM `playlist_detail` JOIN `music` ON `playlist_detail`.`musicId` = `music`.`musicId` JOIN `playlist` ON `playlist_detail`.`playlistId` = `playlist`.`playlistId` WHERE `playlist_detail`.`playlistId` = '$playlistId' AND `music`.`musicName` LIKE '%$keyword%'";
    } else {
        return "SELECT * FROM `playlist_detail` JOIN `music` ON `playlist_detail`.`musicId` = `music`.`musicId` JOIN `playlist` ON `playlist_detail`.`playlistId` = `playlist`.`playlistId` WHERE `playlist_detail`.`playlistId` = '$playlistId'";
    }
    
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = $database->checkToken();

    if ($response["status"] == $constant->RESPONSE_STATUS["success"]) {
        if (isset($_GET['accountId']) && isset($_GET['playlistId'])) {
            $accountId = $_GET['accountId'];
            $playlistId = $_GET['playlistId'];

            $execute = mysqli_query($database->connection, getPlaylistDetail($playlistId));
            $check = mysqli_affected_rows($database->connection);

            if ($check > 0) {
                $response["data"] = array();

                $data["playlist"] = array();
                $data["album"] = getPlaylistAlbumQuery($database, $playlistId, $accountId);

                while ($row = mysqli_fetch_object($execute)) {

                    $playlistData["playlistId"] = $row->playlistId;
                    $playlistData["playlistName"] = $row->playlistName;
                    $playlistData["playlistPhoto"] = $row->playlistPhoto;

                    array_push($data["playlist"], $playlistData);
                }
                $data["account"] = array();
                $response["data"] = $data;
            } else {
                $response["status"] = $constant->RESPONSE_STATUS["not_found"];
                $response["message"] = $constant->RESPONSE_MESSAGES["unavailable_data"];
            }
        } else {
            $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
            $response['message'] = $constant->RESPONSE_MESSAGES["playlist_account_id_needed"];
        }
    }
} else {
    $response["status"] = $constant->RESPONSE_STATUS["bad_request"];
    $response['message'] = $constant->RESPONSE_MESSAGES["wrong_request_method"];
}

echo json_encode($response);
mysqli_close($database->connection);
