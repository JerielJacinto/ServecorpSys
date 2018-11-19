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

echo $function=$_POST["function"];

echo $wocode=$_POST["wocode"];
echo $clientname=$_POST["clientname"];
echo $brandname=$_POST["brandname"];
echo $assign=$_POST["assign"];
echo $currency=$_POST["currency"];
echo $priority=$_POST["priority"];
echo $brandentry=$_POST["brandentry"];
echo $action=$_POST["action"];


if($function=="updatesave")
{
	echo updatesave($wocode, $clientname, $brandname, $assign, $currency, $priority, $brandentry, $action);
}


function updatesave($wocode, $clientname, $brandname, $assign, $currency, $priority, $brandentry, $action)
{
	$echo="";
	$echo.='';

	if($clientname!="")
	{
		$clientname = ", clientid = '".$clientname."'";
	}
	else {}

	if($brandname!="")
	{
		$brandname = ", brandid = '".$brandname."'";
	}
	else {}

	if($assign!="")
	{
		$assign = ", orderfrom = '".$assign."'";
	}
	else {}

	if($currency!="")
	{
		$currency = ", currency = '".$currency."'";
	}
	else {}

	if($priority!="")
	{
		$priority = ", priority = '".$priority."'";
	}
	else {}

	if($brandentry!="")
	{
		$brandentry = ", brandentry = '".$brandentry."'";
	}
	else {}

	if($action!="")
	{
		$action = ", actiontype = '".$action."'";
	}
	else {}

	$conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "UPDATE workorder SET status='1' ".$clientname.$brandname.$assign.$currency.$priority.$brandentry.$action. " WHERE status = 1 and wocode like '".$wocode."'";
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);

	header("location: taskview.php?wocode=".$wocode."&res=updated");
	echo $strSQL;

	return $echo;

}

?>