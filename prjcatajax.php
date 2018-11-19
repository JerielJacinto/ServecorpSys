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

$brand = $_POST["brand"];
$function = $_POST["function"];

if($function=="brandsearch")
{
	echo searchbrand($brand);
	// echo $brand;
}

function searchbrand($brand)
{
	$echo="";
	$resultlist = prjcat_sql("brandid, requeststatus, editor", "and brandid = '".$brand."'");
	while($rowrecord = mysqli_fetch_assoc($resultlist))
    {   
        $brandname = brand_row("brandname, id", "and id=".$rowrecord["brandid"]);
        // $bname = str_replace("'","\'",$brandname["brandname"]);
        $editor = editor_row($rowrecord["editor"]);

        $echo.='<tr>';
        $echo.='<td class="hidden">'.$brandname["brandname"].'</td>';
        $echo.='<td>'.$rowrecord["brandid"].'</td>';
        $echo.='<td>'.$brandname["brandname"].'</td>';
        // switch ($rowrecord["priority"])
        //     {
        //         case 1: $echo.='<td><span class="label label-default">Low</span></td>'; break; //home
        //         case 2: $echo.='<td><span class="label label-primary">Normal</span></td>'; break; //home
        //         case 3: $echo.='<td><span class="label label-warning">High</span></td>'; break; //home
        //         case 4: $echo.='<td><span class="label label-danger">Urgent</span></td>'; break; //home
        //     }

        switch ($rowrecord["requeststatus"])
            {
                case 1: $echo.='<td><span class="label label-warning">Pending</span></td>'; break; //home
                case 2: $echo.='<td><span class="label label-success">On-going</span> by '.$editor.'</td>'; break; //home
                case 0: $echo.='<td><span class="label label-danger">For Approval</span> by '.$editor.'</td>'; break; //home
                case 3: $echo.='<td><span class="label label-success">Done</span> by '.$editor.'</td>'; break; //home
                default: $echo.='<td></td>'; break; //home
            }
        $echo.='<td class="actions">';
        $echo.='<div class="action-buttons">';
        if($rowrecord["requeststatus"]==1){
            $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="proceed" href="#" id="" onclick="prjcat_confirm(\''.$rowrecord["brandid"].'\')"><i class="fa fa-pencil-square"></i></a>';
        }
        else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] == $_SESSION["empid"])
        {
			$echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="proceed" href="#prjcatdone" id="" onclick="prjcat_done(\''.$rowrecord["brandid"].'\')"><i class="fa fa-check-square-o""></i></a>';
        }
        else {}

        $echo.='</div>'; 
        $echo.='</td>';
        $echo.='</tr>';
    }

    return $echo;
}

?>