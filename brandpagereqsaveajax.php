<?php
include_once("./sqlfunction.php");
include_once("./timezone.php");
session_start();

$id = $_POST['idhiddenP'];
$textContent = $_POST['textContent'];

$textContent = mysql_real_escape_string($textContent);

$meta = brandpagemeta_row('req_value, req_status, content, brandid, brandpageid', ' and id = '.$id);

if($meta['content']=='text'){

	    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

        $strSQL = "UPDATE prj_brandpagemeta SET req_value='".$textContent."',createby='".$_SESSION["empid"]."', req_status='2' WHERE status = 1 and id = '".$id."'";

        $rs = mysqli_query($conn, $strSQL);
        mysqli_close($conn);
}

else if($meta['content']=='picture'){

	$target_dir = "brandpage/";
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$filename = basename($_FILES["picture"]["name"]);

	$extension = pathinfo($filename, PATHINFO_EXTENSION);

	$file= $meta['brandid']."-".$filename;

	$target_file = $target_dir . $file;

	if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) 
    {
		$conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

		$strSQL = "UPDATE prj_brandpagemeta SET req_value='".$file."',createby='".$_SESSION["empid"]."', req_status='2' WHERE status = 1 and id = '".$id."'";

		$rs = mysqli_query($conn, $strSQL);
		mysqli_close($conn);
	}

}

$meta2 = brandpagemeta_sql('req_status', ' and brandpageid = '.$meta['brandpageid']);

$check=0;

while($rowrecord = mysqli_fetch_assoc($meta2))
{
	if($rowrecord['req_status']=="1"){ 
		echo $check=1; 
	}

}

if($check==0){
	$conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

	$strSQL = "UPDATE prj_brandpage SET prjstatus='2' WHERE status = 1 and id = '".$meta['brandpageid']."'";

	$rs = mysqli_query($conn, $strSQL);
	mysqli_close($conn);
}

echo $check;

header("location: brandpage.php?show=".$meta['brandpageid']);

?>