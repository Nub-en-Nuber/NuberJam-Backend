<?php
class Constants
{
    var $host = "localhost";
    var $user = "root";
    var $pass = "";
    var $database = "nuber_jam";

    var $BASE_URL = "http://localhost/nuberjam";
    var $BASE_API_URL = "http://localhost/nuberjam/api";
    var $BASE_ASSET_URL = "http://localhost/nuberjam/asset";
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
        "account_id_needed" => "User ID is required to process data",
        "email_or_password_needed" => "Email or Password is required to process data",
        "albumid_needed" => "Album ID is required to process data",
        "playlistid_needed" => "Playlist ID is required to process data",
        "playlist_detail_id_needed" => "Playlist Detail ID is required to process data",
        "playlist_account_id_needed" => "Playlist and Account ID is required to process data",
        "edit_success" => "Successfully edited data",
        "edit_failed" => "Failed to edit data",
        "login_success" => "Successfully login",
        "login_failed" => "Failed to login",
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
