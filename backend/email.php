<?php
include 'know.php';
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$email = json_decode(file_get_contents('php://input'));

echo $email;

$main = new know;
$main->email( $email);

?>