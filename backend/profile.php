<?php
include 'know.php';
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$token = json_decode(file_get_contents('php://input'));

// echo json_encode( $token);

$main = new know;
$main->decode( $token);

?>