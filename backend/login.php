<?php
include 'know.php';
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$login = json_decode(file_get_contents('php://input'));
function trimit($trimi){
    return trim($trimi);
}

$Email = trimit($login->obj->email);
$Password = trimit($login->obj->pass);

// echo json_encode($Email);


$main = new know;
$res = $main->login( $Email, $Password );

// echo json_encode($res);



?>