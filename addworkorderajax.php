<?php
include_once("./sqlfunction.php");
include_once("./timezone.php");
session_start();

$wocode = $_POST["wocode"];
$newbrand = $_POST["newbrand"];
$action = $_POST["action"];
$assign = $_POST["assign"];
$brandentry = $_POST["brandentry"];
$currency = $_POST["currency"];
$priority = $_POST["priority"];
$pricelist = $_POST["pricelist"];
$notes = $_POST["notes"];
$client = $_POST["client"];

// $notes = htmlspecialchars($notes1,ENT_QUOTES);

$target_dir = "pricelist/";
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$filename = basename($_FILES["pricelist"]["name"]);
$filename = mysql_real_escape_string($filename);

// $filenameExp=explode(".", $filename);
// $extension=$filenameExp[1];
$extension = pathinfo($filename, PATHINFO_EXTENSION);
$file= $wocode."-".$filename;
// $target_file = $target_dir . basename($_FILES["pricelist"]["name"]);
$target_file = $target_dir . $file;

    if (move_uploaded_file($_FILES["pricelist"]["tmp_name"], $target_file)) 
    {

        echo "The file ". basename( $_FILES["pricelist"]["name"]). " has been uploaded.";

        ///////save starts here
        $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

        $strSQL = "INSERT INTO workorder (wocode,clientid,brandid,createdby,editor,receivedate,orderfrom,actiontype,brandentry,currency,priority,requeststatus,status,notes, filename) VALUES
        ('".$wocode."', '".$client."', '".$newbrand."', '".$_SESSION["empid"]."', '0', '".date("Y-m-d")."', '".$assign."', '".$action."', '".$brandentry."', '".$currency."', '".$priority."', '1', '1', '".$notes."', '".$file."')";

        $rs = mysqli_query($conn, $strSQL);
        mysqli_close($conn);

        ////last record

        $wocodesave = ltrim($wocode, 'P');

        $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

        $strSQL = "UPDATE lastcode SET workordercode='".$wocodesave."'";

        $rs = mysqli_query($conn, $strSQL);
        mysqli_close($conn);        


        echo "success";
        // echo $client;
        header("location: pendingbrands.php?res=added");

    } else {
        echo "Sorry, there was an error uploading your file.";
          echo "The file ". basename( $_FILES["pricelist"]["name"])."<br>";
          echo $target_file;
        // header("location: showAddWorkorder.php?res=nofile");
    }



// echo "success";
// echo "";

?>