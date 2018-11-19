<?php
include_once("./sqlfunction.php");
session_start();

$currency = $_POST["currency"];
$brandname = $_POST["brandname"];
	
	// check duplicate
	$conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
    
    $strSQL = "SELECT * from brand where brandname like '".$brandname."'";
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);

    $row = mysqli_fetch_assoc($rs);

    if($row["brandname"]!="")
    {
    	$response ="duplicate";
    }
    else
    {

		/////////add brand
		$conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

		$strSQL = "insert into brand (brandname, currency, createdby, status) values
		('".$brandname."', '".$currency."', '".$_SESSION["empid"]."', '1')";

		$rs = mysqli_query($conn, $strSQL);
		mysqli_close($conn);

		
		$response ="success";
	}

	echo $response
?>