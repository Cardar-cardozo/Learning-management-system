<?php 
 include 'know.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: *");
$user = json_decode(file_get_contents('php://input'));
//  echo json_encode($user);
function trimit($trimi){
   return trim($trimi);
}
$firstName = trimit($user->obj->first);

$lastName = trimit($user->obj->last);
$Email = trimit($user->obj->email);
$Password = trimit($user->obj->pass);
$passwordharsh = password_hash($Password, PASSWORD_DEFAULT);
$main = new know;
$res = $main->register($firstName, $lastName, $Email, $passwordharsh);

// $arrayName = array('data' => $firstName,'lastname'=>$lastName, 'password'=>$passwordharsh);

// echo json_encode($arrayName);







// $data = array('resp'=>'tuuuuu');



// echo json_encode($data);
 ?>