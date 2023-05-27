<?php
header("Content-Type:application/json");
include '../constant.php';

class Database
{
	var $host = "localhost";
	var $user = "root";
	var $pass = "";
	var $database = "nuber_jam";

	var $connection;

	var $constant;

	function __construct()
	{
		$this->connection = mysqli_connect($this->host, $this->user, $this->pass, $this->database) or die("Database MYSQL tidak terhubung");
		$this->constant = new Constants($this);
	}

	function checkToken()
	{
		if (isset($_GET['token'])) {
			$token = $_GET['token'];
			$query = "SELECT * FROM token WHERE token = '$token'";
			$execute = mysqli_query($this->connection, $query);
			$cek = mysqli_affected_rows($this->connection);

			if ($cek > 0) {
				$response["status"] = $this->constant->RESPONSE_STATUS["success"];
				$response["message"] = $this->constant->RESPONSE_MESSAGES["available_data"];
			} else {
				$response["status"] = $this->constant->RESPONSE_STATUS["unauthorized"];
				$response["message"] = $this->constant->RESPONSE_MESSAGES['invalid_token'];
			}
		} else {
			$response['status'] = $this->constant->RESPONSE_STATUS["forbidden"];
			$response['message'] = $this->constant->RESPONSE_MESSAGES['needed_token'];
		}
		return $response;
	}
}
