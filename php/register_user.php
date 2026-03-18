<?php
include "config.php";

$name = $_POST['uname'];
$email = $_POST['uemail'];
$password = $_POST['upassword'];

$sql = "INSERT INTO users(uname,uemail,upassword)
        VALUES('$name','$email','$password')";

if(mysqli_query($conn,$sql)){
    echo "Registered successfully";
    header("Location: ./login_user.php"); 
}else{
    echo "Error";
}
?>
 