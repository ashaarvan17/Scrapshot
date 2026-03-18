<?php
include "config.php";

$name = $_POST['cname'];
$email = $_POST['cemail'];
$password = $_POST['cpassword'];

$sql = "INSERT INTO collectors(cname,cemail,cpassword)
        VALUES('$name','$email','$password')";

try {
    mysqli_query($conn, $sql);
    echo "<script>alert('Registered Succesfully');</script>";
} 
catch (mysqli_sql_exception $e) {
    
    if ($e->getCode() == 1062) {
        echo "<script>alert('Email already exists. Try another email.')</script>";
    } else {
        echo "<script>alert('Database Error: ');</script>" . $e->getMessage();
    }
}
?>
