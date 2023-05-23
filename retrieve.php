<?php

include "database.php";
$database = new Database();

$response = $database->checkToken();

if ($response["kode"] == 200) {
	$query = "SELECT * FROM user";

	$execute = mysqli_query($database->connection, $query);
	$cek = mysqli_affected_rows($database->connection);

	if ($cek > 0) {
		$response["data"] = array();

		while ($row = mysqli_fetch_object($execute)) {
			$data["id"] = $row->id;
			$data["name"] = $row->name;
			$data["username"] = $row->username;
			array_push($response["data"], $data);
		}
	} else {
		$response["kode"] = 200;
		$response["pesan"] = "Data Tidak Tersedia";
	}
}

echo json_encode($response);
mysqli_close($database->connection);
