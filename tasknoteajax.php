<?php
include_once("./dbconfiggood.php");
include_once("./timezone.php");
include_once("./sqlfunction.php");

$wocode =$_POST["wocode"];


    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT notes FROM workorder WHERE status = 1 and wocode like '".$wocode."'";

    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);
    
    echo $row["notes"];

?>