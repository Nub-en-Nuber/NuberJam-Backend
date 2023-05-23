<?php

include 'database.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$id = $_POST['id'];
	$nama = $_POST['nama'];
	$alamat = $_POST['alamat'];
	$telepon = $_POST['telepon'];

	$query = "UPDATE tbl_laundry SET nama = '$nama', alamat = '$alamat', telepon = '$telepon' WHERE id = '$id'";
	$execute = mysqli_query($connect, $query);
	$cek = mysqli_affected_rows($connect);

	if ($cek > 0) {
		$response["kode"] = 200;
		$response["pesan"] = "Edit Data Berhasil";
	} else {
		$response["kode"] = 500;
		$response["pesan"] = "Gagal Edit Data";
	}
} else {
	$response['kode'] = 400;
	$response['pesan'] = "Tidak ada post data";
}

echo json_encode($response);
mysqli_close($connect);
