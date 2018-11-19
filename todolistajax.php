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


$function = $_POST["function"];
$task = $_POST["task"];
$empid = $_POST["empid"];
$taskid = $_POST["taskid"];

switch ($function)
{
	case 'newtask': echo newtask($task, $empid); break;
    case 'edittask': echo edittask($task, $taskid, $empid); break;
    case 'deletetask': echo deletetask($taskid, $empid); break;
	
	default:
		# code...
		break;
}

function newtask($task, $empid)
{
	$conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "INSERT INTO todolist (userid,taskstatus,task,status,priority) VALUES ('".$empid."', '1', '".$task."', '1', '2')";
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);

    $echo = "";
    $echo.=updatedlist($empid);

    return $echo;
}

function edittask($task, $taskid, $empid)
{   
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "UPDATE todolist SET task='".$task."' where status = 1 and id = ".$taskid;
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);

    $echo = "";
    $echo.=updatedlist($empid);

    return $echo;
}

function deletetask($taskid, $empid)
{   
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "UPDATE todolist SET status='0' where status = 1 and id = ".$taskid;
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);

    $echo = "";
    $echo.=updatedlist($empid);

    return $echo;
}

function updatedlist($empid)
{
	$echo = "";
    $echo.='<ul>';
	$result = todolist_sql("task, priority,id", " and userid=".$empid." and taskstatus=1");    
    while($rowrecord = mysqli_fetch_assoc($result))
    {
        $echo.='<li>';
        $echo.='<label><input class="task-input" type="checkbox"><span></span>';
        $echo.='<div class="pull-right">';
        $echo.='<a href="#"><i class="fa fa-trash-o"></i>&nbsp;&nbsp</a>';
        $echo.='</div>';
        $echo.='<div class="pull-right">';
        $echo.='<a href="#"><i class="fa fa-edit"></i>&nbsp;&nbsp</a>';
        $echo.='</div>';
        $echo.= $rowrecord["task"].'</label>';
        $echo.='</li>';
    }
    $echo.='</ul>';
    return $echo;
}

?>