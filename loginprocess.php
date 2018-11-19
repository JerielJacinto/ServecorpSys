<?php
include_once("./timezone.php");
include_once("./loginfunction.php");

$username = $_POST["username"];
$password = $_POST["password"];

$result = verifylogin($username, $password);

if ($result != "failed")
{
    header("Location: ./home.php");
}
else
{
    header("Location: ./login.php?res=failed");
}

?>