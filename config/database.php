<?php
header("Content-Type:application/json");
include 'constant.php';

class Database
{
	var $connection;

	var $constant;

	function __construct()
	{
		$this->constant = new Constants($this);
		$this->connection = mysqli_connect(
			$this->constant->host,
			$this->constant->user,
			$this->constant->pass,
			$this->constant->database
		) or die($this->constant->DATABASE_ERROR);
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
