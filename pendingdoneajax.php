<?php
include_once("./sqlfunction.php");
include_once("./timezone.php");
session_start();

$wocode = $_POST["wocode"];
$productadd = $_POST["productadd"];
$productupdate = $_POST["productupdate"];
$requeststatus = $_POST["status"];

if($productadd==""){ $productadd=0; }
if($productupdate==""){ $productupdate=0; }

$target_dir = "workreport/";
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$filename = basename($_FILES["workreport"]["name"]);
$filenameExp=explode(".", $filename);
$extension=$filenameExp[1];
$file=$wocode."-workreport.".$extension;
// $target_file = $target_dir . basename($_FILES["workreport"]["name"]);
$target_file = $target_dir . $file;

    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    echo $strSQL = "SELECT productadd, productupdate, requeststatus FROM workorderhistory WHERE status = 1 and wocode = '".$wocode."'";
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);

    if($row["requeststatus"]=="0")
    {
        $productadd = $row["productadd"] + $productadd;
        $productupdate = $row["productupdate"] + $productupdate;
    }

    echo $row["requeststatus"];

    if (move_uploaded_file($_FILES["workreport"]["tmp_name"], $target_file)) {

        echo "The file ". basename( $_FILES["workreport"]["name"]). " has been uploaded.";

        $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

        $strSQL = "UPDATE workorder SET requeststatus='".$requeststatus."' WHERE status = 1 and (requeststatus=2 or requeststatus=0) and wocode = '".$wocode."'";

        $rs = mysqli_query($conn, $strSQL);
        mysqli_close($conn);

        ////// save sa history

        $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

        $strSQL = "UPDATE workorderhistory SET requeststatus='".$requeststatus."', dateended='".date("Y-m-d")."', timeended='".date("H:i:s")."', productadd='".$productadd."', productupdate='".$productupdate."', priority=2, workreport='".$file."'
        WHERE status = 1 and (requeststatus=2 or requeststatus=0) and wocode like '".$wocode."'";

        $rs = mysqli_query($conn, $strSQL);
        mysqli_close($conn);      

        // echo "success";
        header("location: pendingbrands.php?res=done");

    } else {
        echo "Sorry, there was an error uploading your file.";
        header("location: pendingbrands.php?res=nofile");
    }
    
    
// echo "success";
// echo "";

?>