<?php
include_once("./sqlfunction.php");
include_once("./timezone.php");
session_start();


$wocode = $_POST["wocode"];
$newbrand = $_POST["newbrand"];
$action = $_POST["action"];
$assign = $_POST["assign"];
$workreport = $_POST["workreport"];
$notes = $_POST["notes"];
$client = $_POST["client"];
$status = $_POST["status"];
$productupdate = $_POST["productupdate"];
$productadd = $_POST["productadd"];


// $notes = htmlspecialchars($notes1,ENT_QUOTES);
$notes = mysql_real_escape_string($notes);

$target_dir = "workreport/";
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$filename = basename($_FILES["workreport"]["name"]);
// $filenameExp=explode(".", $filename);
// $extension=$filenameExp[1];
$extension = pathinfo($filename, PATHINFO_EXTENSION);
$file= $wocode."-".$filename;
// $target_file = $target_dir . basename($_FILES["workreport"]["name"]);
$target_file = $target_dir . $file;



    if (move_uploaded_file($_FILES["workreport"]["tmp_name"], $target_file)) 
    {

        echo "The file ". basename( $_FILES["workreport"]["name"]). " has been uploaded.";
echo "<br><br>";
        ///////save starts here
        $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

        $strSQLwork = "INSERT INTO workorder (wocode,clientid,brandid,createdby,editor,receivedate,orderfrom,actiontype,brandentry,currency,priority,requeststatus,status,notes, filename) VALUES
        ('".$wocode."', '".$client."', '".$newbrand."', '".$_SESSION["empid"]."', '".$_SESSION["empid"]."', '".date("Y-m-d")."', '".$assign."', '".$action."', 'old', 'N/A', '3', '".$status."', '1', 'N/A', 'N/A')";

        $rs = mysqli_query($conn, $strSQLwork);
        mysqli_close($conn);


        //////save to workorder history
        $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

        $strSQLhist = "INSERT INTO workorderhistory (wocode,clientid,brandid,createdby,actiontype,requeststatus,status,note, workreport, datestarted, dateended, timestarted, timeended, updatetype,productadd,productupdate,priority) VALUES
        ('".$wocode."', '".$client."', '".$newbrand."', '".$_SESSION["empid"]."','".$action."', '".$status."', '1', '".$notes."', '".$file."','".date("Y-m-d")."','".date("Y-m-d")."','".date("H:i:s")."','".date("H:i:s")."','2','".$productadd."','".$productupdate."','3')";
        
        $rs = mysqli_query($conn, $strSQLhist);
        mysqli_close($conn);


        //// workorder report (chat)
        $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

        $strSQLReport = "INSERT INTO workorderreport(wocode, message, sender, receiver, datecreated, timecreated, subject, status, user) VALUES ('".$wocode."','".$notes."','".$_SESSION["empname"]."','N/A','".date("Y-m-d")."','".date("H:i:s")."','Brand Report','1','".$_SESSION["empid"]."')";

        $rs = mysqli_query($conn, $strSQLReport);
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
          echo "The file ". basename( $_FILES["workreport"]["name"])."<br>";
          echo $target_file;
        header("location: showAddWorkorder.php?res=nofile");
    }



echo "success";
echo "";

?>