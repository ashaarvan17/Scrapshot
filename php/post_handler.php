<?php
// ./php/post_handler.php
ob_start();               // buffer output to prevent stray whitespace
error_reporting(0);       // hide warnings/notices
header("Content-Type: application/json");
session_start();

include "config.php";

// Helper function to always return JSON and exit
function send_json($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    send_json(["error" => "Unauthorized"], 401);
}

$uid = $_SESSION['uid'];

try {

    // ========================
    // DELETE POST
    // ========================
    if (isset($_GET['delete'])) {
        $pid = intval($_GET['delete']);
        $stmt = $conn->prepare("DELETE FROM posts WHERE pid = ? AND uid = ?");
        $stmt->bind_param("ii", $pid, $uid);
        if ($stmt->execute()) send_json(["status" => "deleted"]);
        else send_json(["error" => "Failed to delete post"]);
    }

    // ========================
    // ADD POST
    // ========================
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $desc  = trim($_POST['desc'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $path = null;

        if (empty($title) || empty($desc)) {
            send_json(["error" => "Title and description are required"], 400);
        }

        // Handle image upload
        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowed)) {
                send_json(["error" => "Invalid image type"], 400);
            }

            if (!is_dir("uploads")) mkdir("uploads", 0755, true);
            $path = "uploads/" . uniqid() . "." . $ext;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $path)) {
                send_json(["error" => "Failed to upload image"], 500);
            }
        }

        // Insert post into DB
        $stmt = $conn->prepare(
            "INSERT INTO posts (uid, title, description, img, address) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("issss", $uid, $title, $desc, $path, $address);

        if ($stmt->execute()) {
            send_json(["status" => "success"]);
        } else {
            send_json(["error" => "Failed to add post"], 500);
        }
    }

    // ========================
    // FETCH POSTS (GET)
    // ========================
    $stmt = $conn->prepare("SELECT * FROM posts 
WHERE uid = ? AND (request = 'unsent' OR request IS NULL)
ORDER BY pid DESC");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = [
            'pid' => $row['pid'],
            'title' => $row['title'],
            'description' => $row['description'],
            'address' => $row['address'],
            'img' => $row['img'] ?? ''
        ];
    }

    send_json($posts);

} catch (Exception $e) {
    // Catch any unexpected errors
    send_json(["error" => "Server error: " . $e->getMessage()], 500);
}
