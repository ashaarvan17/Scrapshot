<?php
// ./php/handle_request.php
ini_set('display_errors', 0);  // hide errors in output
ini_set('log_errors', 1);      // log them
error_reporting(E_ALL);

session_start();

include "config.php";

// Force JSON response
header("Content-Type: application/json; charset=UTF-8");

// Check session
if (!isset($_SESSION['cid'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$cid = $_SESSION['cid'];

// Read JSON POST
$data = json_decode(file_get_contents("php://input"), true);

// Only update if pid is provided
if (isset($data['pid'])) {
    $pid = (int)$data['pid'];
    $_SESSION['pid'] = $pid;
    $uid = (int)$data['uid'];
    $_SESSION['uid'] = $uid;

    $stmt = $conn->prepare("UPDATE posts SET request = 'pending', cid = ? WHERE pid = ?");
    $stmt->bind_param("ii", $cid, $pid);
    $stmt->execute();
    $stmt->close();
}

// Fetch all pending posts
$posts = [];
if ($result = $conn->query("SELECT * FROM posts WHERE request = 'unsent'")) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    $result->free();
}

// Return as JSON
echo json_encode($posts);
exit;
