<?php
class Constants
{
    var $host = "localhost";
    var $user = "528209";
    var $pass = "ywUjzY!qBnJt4TT";
    var $database = "528209";

    var $BASE_URL = "http://nuberjam-dev.orgfree.com/";
    var $BASE_API_URL = $BASE_URL + "api/";
    var $BASE_ASSET_URL = $BASE_URL + "asset/";

    var $DATABASE_ERROR = "MYSQL database is not connected correctly";
    var $RESPONSE_MESSAGES = array(
        "add_success" => "Successfully added data",
        "available_data" => "Data is available",
        "unavailable_data" => "Data not available",
        "invalid_token" => "Invalid Token",
        "needed_token" => "Token required to be able to access",
        "add_failed" => "Failed to add data",
        "no_request_data" => "There is no request data",
        "wrong_request_method" => "Wrong request method",
        "delete_success" => "Successfully deleted data",
        "delete_failed" => "Failed to delete data",
        "account_id_needed" => "Account ID is required to process data",
        "email_or_password_needed" => "Email or Password is required to process data",
        "album_id_needed" => "Album ID is required to process data",
        "music_id_needed" => "Music ID is required to process data",
        "music_and_account_id_needed" => "Music and Account ID is required to process data",
        "music_and_playlist_id_needed" => "Music and Playlist ID is required to process data",
        "playlist_id_needed" => "Playlist ID is required to process data",
        "playlist_detail_id_needed" => "Playlist Detail ID is required to process data",
        "playlist_account_id_needed" => "Playlist and Account ID is required to process data",
        "edit_success" => "Successfully edited data",
        "edit_failed" => "Failed to edit data",
        "login_success" => "Successfully login",
        "login_failed" => "Failed to login",
        "account_artist" => "You're artist",
        "account_not_artist" => "You're not artist",
        "account_not_exist" => "Account doesn't exist",
        "album_not_exist" => "Album doesn't exist",
        "music_not_exist" => "Music doesn't exist",
        "playlist_not_exist" => "Playlist doesn't exist",
        "music_or_account_not_exist" => "Music or Account doesn't exist",
        "email_used" => "The email entered has been used",
        "username_used" => "The username entered has been used",
        "music_exist_in_playlist" => "This music is already in playlist",
        "music_not_exist_in_playlist" => "This music is not in playlist",
    );
    var $RESPONSE_STATUS = array(
        "success" => 200,
        "bad_request" => 400,
        "unauthorized" => 401,
        "forbidden" => 403,
        "not_found" => 404,
        "internal_server_error" => 500,
    );
}
