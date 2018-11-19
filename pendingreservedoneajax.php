<?php
include_once("./sqlfunction.php");
include_once("./timezone.php");
session_start();

echo $wocode = $_POST["wocode"];
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
$file="workreport-".$wocode.".".$extension;
// $target_file = $target_dir . basename($_FILES["workreport"]["name"]);
$target_file = $target_dir . $file;

    if (move_uploaded_file($_FILES["workreport"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["workreport"]["name"]). " has been uploaded.";

        ////save to db
        $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

        $strSQL = "UPDATE reserveworkorder SET requeststatus='".$requeststatus."' WHERE status = 1 and (requeststatus=2 or requeststatus=0) and wocode = '".$wocode."'";

        $rs = mysqli_query($conn, $strSQL);
        mysqli_close($conn);

        ////// save sa history

        $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

        $strSQL = "UPDATE workorderhistory SET requeststatus='".$requeststatus."', dateended='".date("Y-m-d")."', timeended='".date("H:i:s")."', productadd=".$productadd.", productupdate=".$productupdate.", priority=1, workreport='".$file."'
        WHERE status = 1 and (requeststatus=2 or requeststatus=0) and wocode like '".$wocode."'";

        $rs = mysqli_query($conn, $strSQL);
        mysqli_close($conn);

        // echo "success";
        header("location: reservependingbrands.php?res=done");

    } else {
        echo "Sorry, there was an error uploading your file.";
        header("location: reservependingbrands.php?res=nofile");
    }

// echo $extension;

// echo "";

?>