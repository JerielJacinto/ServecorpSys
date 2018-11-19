<?php
session_start();
$empname = $_SESSION["empname"];
if($empname == "na" || $empname == "")
{
  header("Location: ./login.php?res=expired");
}
$access = $_SESSION["access"];

include_once("./dbconfiggood.php");
include_once("./timezone.php");
include_once("./sqlfunction.php");
$action = $_POST["action"];
// echo $actiondrop = $_POST["actiondrop"];
// $wocode = $_POST["wocodetext"];

if($action=="proceed")
{
	echo proceed($_POST["brandid"]);
}
else if($action=="done")
{

	echo done($_POST["brandid"], $_POST["productupdate"], $_POST["actiondrop"], $_POST["wocodetext"]);
}

function proceed($brandid)
{
    $timedb = date("H:i:s");
    $todaydb = date("Y-m-d");

	$conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "UPDATE prj_category SET requeststatus = '2', editor='".$_SESSION["empid"]."', datestarted='".$todaydb."', timestarted='".$timedb."' WHERE status = 1 and brandid like '".$brandid."'";
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);

    return "success";
}



function done($brandid, $updated, $actiondrop, $wocode)
{
	$echo ="";

    $timedb = date("H:i:s");
    $todaydb = date("Y-m-d");

	$prjcat = brand_row("brandname", "and id='".$brandid."'");
	$prjcatrep = str_replace("'","''",$prjcat["brandname"]);

if($actiondrop=="upload")
{
	$target_dir = "workreport/";
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$filename = basename($_FILES["workreport"]["name"]);
	$filenameExp=explode(".", $filename);
	$extension=$filenameExp[1];
	$file="prj-category-".$prjcat["brandname"].".".$extension;
	// $target_file = $target_dir . basename($_FILES["workreport"]["name"]);
	$target_file = $target_dir . $file;

	    if (move_uploaded_file($_FILES["workreport"]["tmp_name"], $target_file)) {

	        echo "The file ". basename( $_FILES["workreport"]["name"]). " has been uploaded.";

	        $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

	        $strSQL = "UPDATE prj_category SET requeststatus='3', dateended='".$todaydb."', timeended='".$timedb."', workreport='N/A', updated='".$updated."' WHERE status = 1 and brandid = '".$brandid."'";

	        $rs = mysqli_query($conn, $strSQL);
	        mysqli_close($conn);

	        echo "success";
	        header("location: proj-category.php?res=done");

	    } else {
	        echo "Sorry, there was an error uploading your file.";
	        header("location: proj-category.php?res=nofile");
	    }

}
else if($actiondrop=="workorder")
{
		    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
	        $strSQL = "UPDATE prj_category SET requeststatus='3', dateended='".$todaydb."', timeended='".$timedb."', workreport='".$wocode."', updated='".$updated."' WHERE status = 1 and brandid = '".$brandid."'";

	        $rs = mysqli_query($conn, $strSQL);
	        mysqli_close($conn);

	        echo "success";
	        header("location: proj-category.php?res=done");
}
else if($actiondrop=="nochange")
{
		    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
	        $strSQL = "UPDATE prj_category SET requeststatus='4', dateended='".$todaydb."', timeended='".$timedb."', workreport='no changes', updated='".$updated."' WHERE status = 1 and brandid = '".$brandid."'";

	        $rs = mysqli_query($conn, $strSQL);
	        mysqli_close($conn);
	        
	        echo "success";
	        header("location: proj-category.php?res=done");
}

echo "end";

}//function
?>