<?php
//./php/final_request.php
ini_set('display_errors', 0);  // hide errors from output
ini_set('log_errors', 1);      // log them to server log
error_reporting(E_ALL);
session_start();

include "config.php";


// make sure this outputs nothing

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['pid'])) {
    echo json_encode(["error" => "Missing pid"]);
    exit();
}

$pid = $data['pid'];



// Update the post
$sql = "UPDATE posts SET request = 'pending' WHERE pid = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pid);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => $stmt->error]);
}
exit();