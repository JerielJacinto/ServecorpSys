<?php
include_once("./sqlfunction.php");
include_once("./timezone.php");
session_start();

$brandid = $_POST["brandid"];
$noteby = $_POST["noteby"];
$notes = $_POST["notes"];

$notes = mysql_real_escape_string($notes);

    ////////////////////////////////////
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
    // $strSQL = "SELECT brandid, clientid FROM brand_notes WHERE wocode = '".$wocode."'";
    $strSQL = "INSERT INTO brand_notes (brandid, note, noteby, createdby, status) VALUES
                ('".$brandid."','".$notes."','".$noteby."','".$_SESSION["empid"]."','1')";

    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    
echo "success";

?>