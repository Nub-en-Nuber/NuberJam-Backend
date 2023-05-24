<?php
class Database
{
	var $host = "localhost";
	var $user = "root";
	var $pass = "";
	var $database = "nuber_jam";

	var $connection;

	function __construct()
	{
		$this->connection = mysqli_connect($this->host, $this->user, $this->pass, $this->database) or die("Database MYSQL tidak terhubung");
	}

	function checkToken()
	{
		if (isset($_GET['token'])) {
			$token = $_GET['token'];
			$query = "SELECT * FROM token WHERE token = '$token'";
			$execute = mysqli_query($this->connection, $query);
			$cek = mysqli_affected_rows($this->connection);

			if ($cek > 0) {
				$response["kode"] = 200;
				$response["pesan"] = "Data Tersedia";
			} else {
				$response["kode"] = 401;
				$response["pesan"] = "Token anda salah";
			}
		} else {
			$response['kode'] = 403;
			$response['pesan'] = "Token diperlukan untuk dapat mengakses";
		}
		return $response;
	}
}
