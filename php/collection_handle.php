<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();

include "config.php";

header("Content-Type: application/json; charset=UTF-8");


if(!isset($_SESSION['cid'])){
http_response_code(401);
echo json_encode(["error"=>"Unauthorized"]);
exit();
}


$cid = $_SESSION['cid'];


$data = json_decode(file_get_contents("php://input"),true);


if($data && isset($data['pid'])){

$pid = (int)$data['pid'];

$stmt = $conn->prepare("UPDATE posts SET request='collected' WHERE pid=?");

if(!$stmt){
echo json_encode(["error"=>$conn->error]);
exit();
}

$stmt->bind_param("i",$pid);
$stmt->execute();
$stmt->close();

}


$posts = [];

$result = $conn->query("SELECT * FROM posts WHERE request='sent'");

if($result){

while($row = $result->fetch_assoc()){
$posts[] = $row;
}

$result->free();

}


echo json_encode($posts);
exit;

?>