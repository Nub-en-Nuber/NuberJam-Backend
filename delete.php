<?php

include 'database.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$id = $_POST['id'];

	$query = "DELETE FROM tbl_laundry WHERE id = '$id'";
	$execute = mysqli_query($connect, $query);
	$cek = mysqli_affected_rows($connect);

	if ($cek > 0) {
		$response["kode"] = 200;
		$response["pesan"] = "Data Berhasil Dihapus";
	} else {
		$response["kode"] = 500;
		$response["pesan"] = "Gagal Menghapus Data";
	}
} else {
	$response['kode'] = 400;
	$response['pesan'] = "Tidak ada post data";
}

echo json_encode($response);
mysqli_close($connect);
