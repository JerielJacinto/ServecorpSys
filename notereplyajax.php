<?php
include_once("./sqlfunction.php");
include_once("./timezone.php");
session_start();

echo $wocode = $_POST["wocode"];
$newnotereply = $_POST["newnotereply"];
$sender = $_POST["sender"];
$bubside = $_POST["bubside"];
$senderside = "";
$todaydb=date("Y-m-d");

    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT notes, wocode,orderfrom, editor FROM workorder WHERE wocode like '".$wocode."' and status = 1";

    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);

    $row = mysqli_fetch_assoc($rs);


    //////// NEW CODE

    echo $newnote = $row["notes"] . "||".$newnotereply."==".$sender."==".$bubside."==".$todaydb;


    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "UPDATE workorder SET notes = '".$newnote."' WHERE wocode like '".$wocode."' and status = 1";

    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);


// echo 

?>