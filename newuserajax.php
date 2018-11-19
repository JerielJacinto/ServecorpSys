<?php
include_once("./sqlfunction.php");
include_once("./timezone.php");
session_start();

$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];



    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "insert into users (username,name, password, createdby, status, email, restriction) VALUES";
    $strSQL.= "('".$username."','".$name."','".$password."', '2', '1', '', '3')";

    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    
echo "success";
// echo "";

// pendingreserveproceedajax

?>