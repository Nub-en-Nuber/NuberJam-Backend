<?php
include '../../config/constant.php';
$constant = new Constants();
header("location: " . $constant->BASE_API_URL);
