<?php
class Constants
{
    var $host = "localhost";
    var $user = "root";
    var $pass = "";
    var $database = "nuber_jam";

    var $BASE_URL = "http://localhost/nuberjam/api";
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
        "userid_needed" => "User ID is required to process data",
        "edit_success" => "Successfully edited data",
        "edit_failed" => "Failed to edit data",
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
