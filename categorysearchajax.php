<?php
include_once("./sqlfunction.php");
include_once("./importfiles.php");
session_start();

$daterange = $_POST["daterange"];
$brand = $_POST["brand"];
$editor = $_POST["editor"];



    // change format


    if($editor=="all")
    {
        $editor="";
    }
    else
    {
        $editor=" and editor = '".$editor."' ";
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
    $echo.='<th>Date</th>';
    $echo.='<th>Editor</th>';
    $echo.='<th with="50"></th>';
    $echo.='</thead>';
    $resultlist = prjcat_sql("dateended, editor, id, brandid", $daterangesql . $editor);
    while($rowrecord = mysqli_fetch_assoc($resultlist))
    {   
        $newdate=date("M d, Y", strtotime($rowrecord["dateended"]));

        $editor = editor_row($rowrecord["editor"]);
        $brandname = getBrand($rowrecord["brandid"]);

        $echo.='<tr>';
        // $echo.='<td>'.$rowrecord["id"].'</td>';
        $echo.='<td>'.$rowrecord["id"].'</td>';
        $echo.='<td>'.$brandname.'</td>';
        $echo.='<td>'.$newdate.'</td>';
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