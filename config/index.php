<?php
include 'constant.php';
$constant = new Constants();
header("location: " . $constant->BASE_API_URL);
