<?php
include_once("./sqlfunction.php");
include_once("./timezone.php");
session_start();

$wocode = $_POST["wocode"];
$newbrand = $_POST["newbrand"];
$action = $_POST["action"];
$notes = $_POST["notes"];

// wocode='+wocode+"&newbrand="+newbrand+"&action="+action+"&notes="+notes;'

    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "INSERT INTO reserveworkorder (wocode,clientid,brandid,requeststatus,status,createdby,editor,notes) VALUES
    ('".$wocode."', '".$newbrand."', '1', '1', '".$_SESSION["empid"]."', '0', '".$notes."')";

    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);

    ////last record

    $wocodesave = ltrim($wocode, 'R');

    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "UPDATE lastcode SET reservecode='".$wocodesave."'";

    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);


echo "success";
// echo "";

?>