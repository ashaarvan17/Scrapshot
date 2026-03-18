<?php
//login_collector.php
session_start();
include "config.php";

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT * FROM collectors WHERE cemail='$email' AND cpassword='$password'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Store cid in session
    $_SESSION['cid'] = $row['cid'];

    header("Location: ./collector.php");
exit;

}


