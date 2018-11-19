<?php
include_once("./sqlfunction.php");
include_once("./timezone.php");
session_start();

$wocode = $_POST["wocode"];

    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "UPDATE reserveworkorder SET requeststatus=2,  editor='".$_SESSION["empid"]."' WHERE status = 1 and requeststatus=1 and wocode = '".$wocode."'";

    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);

    ////////////////////////////////////
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
    $strSQL = "SELECT brandid, clientid FROM reserveworkorder WHERE wocode = '".$wocode."'";

    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    $row = mysqli_fetch_assoc($rs);
    
    /////// save sa history

    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "insert into workorderhistory (wocode,clientid,datestarted, timestarted, createdby, status, requeststatus, brandid, priority) VALUES";
    $strSQL.= "('".$wocode."','".$row["clientid"]."','".date("Y-m-d")."', 	'".date("H:i:s")."', '".$_SESSION["empid"]."', '1', '2', '".$row["brandid"]."', '1')";

    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    
echo "success";
// echo "";

// pendingreserveproceedajax

?>