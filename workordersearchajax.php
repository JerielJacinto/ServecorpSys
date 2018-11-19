<?php
include_once("./sqlfunction.php");
include_once("./importfiles.php");
session_start();

$daterange = $_POST["daterange"];
$brand = $_POST["brand"];
$brandid = $_POST["brand"];
$editor = $_POST["editor"];
$actiontype = $_POST["actiontype"];



    // change format

    if($brand=="all")
    {
        $brand="";
    }
    else
    {
        $brand=" and brandid = '".$brand."' ";
    }

    if($editor=="all")
    {
        $editor="";
    }
    else
    {
        $editor=" and createdby = '".$editor."' ";
    }

    if($actiontype=="all")
    {
        $actiontype="";
    }
    else
    {
        $actiontype=" and actiontype = '".$actiontype."' ";
    }


    if($daterange=="all")
    {
        $daterangesql="";
    }
    else
    {
        // convert daterange
        $daterangeExp = explode(" to ", $daterange);
        $fromdate = $daterangeExp[0];
        $todate = $daterangeExp[1];

        
        $fromdate=date("Y-m-d",strtotime($fromdate));
        $todate=date("Y-m-d",strtotime($todate));

        $daterangesql="and dateended>='".$fromdate."'and dateended<='".$todate."'";
    }


    $echo="";
    $echo.='<table class="table table-bordered table-striped" id="dataTable1">'; //class="table table-hover"      
    $echo.='<thead>';
    // $echo.='<th width="40">id</th>';
    $echo.='<th width="40">Code</th>';
    $echo.='<th width="550">Brand Name</th>';
    $echo.='<th>Currency</th>';
    $echo.='<th>Task type</th>';
    $echo.='<th>Date</th>';
    $echo.='<th>Editor</th>';
    $echo.='<th with="50"></th>';
    $echo.='</thead>';
    // $resultlist = workorderhistory_sql("wocode, priority, dateended, createdby, id", $daterangesql . $brand . $editor);
    
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
    $strSQL = "SELECT wocode, priority, updatetype, dateended, createdby, id, actiontype
        FROM workorderhistory  WHERE status = 1 and requeststatus <= 3 ".
        $daterangesql .$actiontype. $brand . $editor;
    
    $resultlist = mysqli_query($conn, $strSQL);
    mysqli_close($conn);


    while($rowrecord = mysqli_fetch_assoc($resultlist))
    {   
        $newdate=date("M d, Y", strtotime($rowrecord["dateended"]));
        if($rowrecord["priority"]==1){
            $workordername = workorder_row("brandid", " and wocode like '".$rowrecord['wocode']."'");
            $brandname = brand_row("brandname, id", "and id='".$workordername['brandid']."'");
        }
        else if($rowrecord["priority"]>1){
            $workordername = workorder_row("brandid", " and wocode like '".$rowrecord['wocode']."'");
            $brandname = brand_row("brandname, id", "and id='".$workordername['brandid']."'");
        }
        else{
            $brandname["brandname"] = "";
        }

        $workorder = workorder_row("actiontype, currency", " and wocode like '".$rowrecord['wocode']."'");
        $tasktype ="";
        if($workorder['actiontype']=='1'){
            $tasktype = "Add";
        }
        else{
            $tasktype ="Update";
        }

        if($rowrecord['updatetype']==2){
            $updatetype = "(no price list)";
        }
        else{
            $updatetype = "";
        }

        $editor = editor_row($rowrecord["createdby"]);
        $echo.='<tr>';
        // $echo.='<td>'.$rowrecord["id"].'</td>';
        $echo.='<td>'.$rowrecord["wocode"].'</td>';
        $echo.='<td>'.$brandname["brandname"].'</td>';
        $echo.='<td>'.$workorder["currency"].'</td>';
        $echo.='<td>'.$tasktype.'</td>';
        $echo.='<td>'.$newdate.' '.$updatetype.'</td>';
        $echo.='<td>'.$editor.'</td>';
        $echo.='<td>';
        $echo.='<div class="action-buttons">';
        $echo.='<a class="table-actions" data-placement="top" title="View" href="#" 
                 onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>';
        $echo.='</div">';
        $echo.='</td>';
        $echo.='</tr>';

    }


   $echo.= getdataTable1();

echo $echo;
?>