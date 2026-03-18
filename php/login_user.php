<?php
session_start();
include "config.php";

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT uid FROM users WHERE uemail=? AND upassword=?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

   session_regenerate_id(true);

   $_SESSION['uid'] = $row['uid'];

   header("Location: user.php");
   exit();
}
echo "<script>alert('Wrong email or password'); window.history.back();</script>";

