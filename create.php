<?php

include 'database.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$nama = $_POST['nama'];
	$alamat = $_POST['alamat'];
	$telepon = $_POST['telepon'];

	$query = "INSERT INTO tbl_laundry (nama, alamat, telepon) VALUES ('$nama','$alamat','$telepon')";
	$execute = mysqli_query($connect, $query);
	$cek = mysqli_affected_rows($connect);

	if ($cek > 0) {
		$response["kode"] = 200;
		$response["pesan"] = "Simpan Data Berhasil";
	} else {
		$response["kode"] = 500;
		$response["pesan"] = "Gagal Simpan Data";
	}
} else {
	$response['kode'] = 400;
	$response['pesan'] = "Tidak ada post data";
}

echo json_encode($response);
mysqli_close($connect);
