<?php
include_once("./dbconfiggood.php");
include_once("./timezone.php");
include_once("./sqlfunction.php");


function Notification($result)
{
    if($result=="added")//uploaded
    {    
        $echo.='<div class="alert alert-success">';
        $echo.='<button class="close" data-dismiss="success" type="button">&times;</button>';
        $echo.='New task added.';
        $echo.='</div>';
    }
    if($result=="done")//finish product
    {    
        $echo.='<div class="alert alert-success">';
        $echo.='<button class="close" data-dismiss="success" type="button">&times;</button>';
        $echo.='Done with product.';
        $echo.='</div>';
    }
    if($result=="nofile")//finish product
    {    
        $echo.='<div class="alert alert-danger">';
        $echo.='<button class="close" data-dismiss="success" type="button">&times;</button>';
        $echo.='You forgot to upload the work report. Please save again.';
        $echo.='</div>';
    }
    if($result=="proceed")//finish product
    {    
        $echo.='<div class="alert alert-success">';
        $echo.='<button class="close" data-dismiss="success" type="button">&times;</button>';
        $echo.='You started a task.';
        $echo.='</div>';
    }
    return $echo;
}


function showPendingWorkOrder()
{
    $echo="";

    $echo.='<div class="page-title">';
    $echo.='<h3>Pending List</h3>';
    $echo.='</div>';

    $echo.='<div class="row">';
    $echo.='<div id="basic-modal-content">';
    $echo.='<img src="./images/loading.gif" /> <a>Loading...</a>';
    $echo.='</div>';//row

    $echo.='<div class="row">';
    $echo.='<div class="col-lg-12">';
    $echo.='<div class="" id="divnotif" style="display:none;" >';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';

        $echo.='<div class="row">';
        $echo.='<div class="col-lg-12">';

        $echo.='<div class="widget-container fluid-height">';
        $echo.='<div class="widget-content padded clearfix">';

        $echo.='<a href="./addunlistedwork.php"><button class="btn btn-primary pull-right" onclick="" type="button">Add Unlisted Work</button></a>';

        $echo.='<table class="table table-bordered table-striped" id="dataTable">'; //class="table table-hover"
        
        $echo.='<thead>';
        $echo.='<th class="hidden">urgent</th>';
        $echo.='<th width="40">Code</th>';
        $echo.='<th width="300">Brand Name</th>';
        $echo.='<th width="100">Last update</th>';
        $echo.='<th>Currency</th>';
        $echo.='<th>Old/New</th>';
        $echo.='<th>Action</th>';
        $echo.='<th>Priority</th>';
        $echo.='<th>Status</th>';
        $echo.='<th width="30"></th>';
        $echo.='</thead>';
        
        $echo.='<tbody>';  
        ///// hold      
        $resultlist = workorder_sql("brandid, priority, requeststatus, editor, actiontype, currency, brandentry, wocode, notes, filename", "and requeststatus=5");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {   
            $brandname = brand_row("brandname, id", "and id=".$rowrecord["brandid"]);
            $bname = str_replace("'","\'",$brandname["brandname"]);
            $editor = editor_row($rowrecord["editor"]);

            $lastupdate = lastupdatePricelist_row($rowrecord['brandid']);

            $echo.='<tr>';
            $echo.='<td class="hidden">'.$rowrecord["requeststatus"].'</td>';
            $echo.='<td>'.$rowrecord["wocode"].'</td>';
            $echo.='<td>'.$brandname["brandname"].'</td>';
            $echo.='<td>'.$lastupdate["dateended"].'</td>';
            $echo.='<td>'.ucwords($rowrecord["currency"]).'</td>';
            $echo.='<td>'.strtoupper($rowrecord["brandentry"]).'</td>';
            switch ($rowrecord["actiontype"])
                {
                    case 1: $echo.='<td>ADD</td>'; $actiontype="ADD"; break; //home
                    case 2: $echo.='<td>UPDATE</td>'; $actiontype="UPDATE"; break; //home
                    default: $echo.='<td></td>'; break; //home
                }
            switch ($rowrecord["priority"])
                {
                    case 1: $echo.='<td><span class="label label-default">Low</span></td>'; break; //home
                    case 2: $echo.='<td><span class="label label-primary">Normal</span></td>'; break; //home
                    case 3: $echo.='<td><span class="label label-warning">High</span></td>'; break; //home
                    case 4: $echo.='<td><span class="label label-danger">Urgent</span></td>'; break; //home
                }

            switch ($rowrecord["requeststatus"])
                {
                    case 1: $echo.='<td><span class="label label-warning">Pending</span></td>'; break; //home
                    case 2: $echo.='<td><span class="label label-success">On-going</span>'.$editor.'</td>'; break; //home
                    case 0: $echo.='<td><span class="label label-danger">For Approval</span>'.$editor.'</td>'; break; //home
                    case 5: $echo.='<td><span class="label label-danger">Hold</span></td>'; break; //home
                    default: $echo.='<td></td>'; break; //home
                }
            $echo.='<td class="actions">';
            $echo.='<div class="action-buttons">';
            if($rowrecord["requeststatus"]==1){

                $echo.='<a class="table-actions" data-placement="top" title="proceed" href="javascript:void(0);" id=""
            onclick="showTaskDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="glyphicon glyphicon-log-in"></i></a>';
            }
            else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] == $_SESSION["empid"])
            {
            $echo.='<a class="table-actions" href="javascript:void(0);" data-placement="top" title="view" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
                
                $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodal" id=""
            onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
            }
            else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] != $_SESSION["empid"])
            {
                $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="proceed" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
            }
            else if($rowrecord["requeststatus"]==0 && $rowrecord["editor"] == $_SESSION["empid"])
            {
                $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
                onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
                    $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodal" id=""
                onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
            }
            else if($rowrecord["requeststatus"]==5)
            {
                $echo.='<a class="table-actions" data-placement="top" title="proceed" href="javascript:void(0);" id=""
            onclick="showTaskDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="glyphicon glyphicon-log-in"></i></a>';
            }
            else
            {
               $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
                onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
            }

            $echo.='</div>'; 
            $echo.='</td>';
            $echo.='</tr>';
        }

        ///// for approval      
        $resultlist = workorder_sql("brandid, priority, requeststatus, editor, actiontype, currency, brandentry, wocode, notes, filename", "and requeststatus=0");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {   
            $brandname = brand_row("brandname, id", "and id=".$rowrecord["brandid"]);
            $bname = str_replace("'","\'",$brandname["brandname"]);
            $editor = editor_row($rowrecord["editor"]);
            $lastupdate = lastupdatePricelist_row($rowrecord['brandid']);

            $echo.='<tr>';
            $echo.='<td class="hidden">'.$rowrecord["requeststatus"].'</td>';
            $echo.='<td>'.$rowrecord["wocode"].'</td>';
            $echo.='<td>'.$brandname["brandname"].'</td>';
            $echo.='<td>'.$lastupdate["dateended"].'</td>';
            $echo.='<td>'.ucwords($rowrecord["currency"]).'</td>';
            $echo.='<td>'.strtoupper($rowrecord["brandentry"]).'</td>';
            switch ($rowrecord["actiontype"])
                {
                    case 1: $echo.='<td>ADD</td>'; $actiontype="ADD"; break; //home
                    case 2: $echo.='<td>UPDATE</td>'; $actiontype="UPDATE"; break; //home
                    default: $echo.='<td></td>'; break; //home
                }
            switch ($rowrecord["priority"])
                {
                    case 1: $echo.='<td><span class="label label-default">Low</span></td>'; break; //home
                    case 2: $echo.='<td><span class="label label-primary">Normal</span></td>'; break; //home
                    case 3: $echo.='<td><span class="label label-warning">High</span></td>'; break; //home
                    case 4: $echo.='<td><span class="label label-danger">Urgent</span></td>'; break; //home
                }

            switch ($rowrecord["requeststatus"])
                {
                    case 1: $echo.='<td><span class="label label-warning">Pending</span></td>'; break; //home
                    case 2: $echo.='<td><span class="label label-success">On-going</span>'.$editor.'</td>'; break; //home
                    case 0: $echo.='<td><span class="label label-danger">For Approval</span>'.$editor.'</td>'; break; //home
                    case 5: $echo.='<td><span class="label label-danger">Hold</span></td>'; break; //home
                    default: $echo.='<td></td>'; break; //home
                }
            $echo.='<td class="actions">';
            $echo.='<div class="action-buttons">';
            if($rowrecord["requeststatus"]==1){

                $echo.='<a class="table-actions" data-placement="top" title="proceed" href="javascript:void(0);" id=""
            onclick="showTaskDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="glyphicon glyphicon-log-in"></i></a>';
            }
            else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] == $_SESSION["empid"])
            {
            $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
                
                $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodal" id=""
            onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
            }
            else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] != $_SESSION["empid"])
            {
                $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="proceed" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
            }
            else if($rowrecord["requeststatus"]==0 && $rowrecord["editor"] == $_SESSION["empid"])
            {
                $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
                onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
                    $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodal" id=""
                onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
            }
            else if($rowrecord["requeststatus"]==5)
            {
                $echo.='<a class="table-actions" data-placement="top" title="proceed" href="javascript:void(0);" id=""
            onclick="showTaskDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="glyphicon glyphicon-log-in"></i></a>';
            }
            else
            {
               $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
                onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
            }

            $echo.='</div>'; 
            $echo.='</td>';
            $echo.='</tr>';
        }

        ///// ongoing     
        $resultlist = workorder_sql("brandid, priority, requeststatus, editor, actiontype, currency, brandentry, wocode, notes, filename", "and requeststatus=2");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {   
            $brandname = brand_row("brandname, id", "and id=".$rowrecord["brandid"]);
            $bname = str_replace("'","\'",$brandname["brandname"]);
            $editor = editor_row($rowrecord["editor"]);
            $lastupdate = lastupdatePricelist_row($rowrecord['brandid']);

            $echo.='<tr>';
            $echo.='<td class="hidden">'.$rowrecord["requeststatus"].'</td>';
            $echo.='<td>'.$rowrecord["wocode"].'</td>';
            $echo.='<td>'.$brandname["brandname"].'</td>';
            $echo.='<td>'.$lastupdate["dateended"].'</td>';
            $echo.='<td>'.ucwords($rowrecord["currency"]).'</td>';
            $echo.='<td>'.strtoupper($rowrecord["brandentry"]).'</td>';
            switch ($rowrecord["actiontype"])
                {
                    case 1: $echo.='<td>ADD</td>'; $actiontype="ADD"; break; //home
                    case 2: $echo.='<td>UPDATE</td>'; $actiontype="UPDATE"; break; //home
                    default: $echo.='<td></td>'; break; //home
                }
            switch ($rowrecord["priority"])
                {
                    case 1: $echo.='<td><span class="label label-default">Low</span></td>'; break; //home
                    case 2: $echo.='<td><span class="label label-primary">Normal</span></td>'; break; //home
                    case 3: $echo.='<td><span class="label label-warning">High</span></td>'; break; //home
                    case 4: $echo.='<td><span class="label label-danger">Urgent</span></td>'; break; //home
                }

            switch ($rowrecord["requeststatus"])
                {
                    case 1: $echo.='<td><span class="label label-warning">Pending</span></td>'; break; //home
                    case 2: $echo.='<td><span class="label label-success">On-going</span> by '.$editor.'</td>'; break; //home
                    case 0: $echo.='<td><span class="label label-danger">For Approval</span> by '.$editor.'</td>'; break; //home
                    case 5: $echo.='<td><span class="label label-danger">Hold</span></td>'; break; //home
                    default: $echo.='<td></td>'; break; //home
                }
            $echo.='<td class="actions">';
            $echo.='<div class="action-buttons">';
            if($rowrecord["requeststatus"]==1){

                $echo.='<a class="table-actions" data-placement="top" title="proceed" href="javascript:void(0);" id=""
            onclick="showTaskDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="glyphicon glyphicon-log-in"></i></a>';
            }
            else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] == $_SESSION["empid"])
            {
            $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
                
                $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodal" id=""
            onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
            }
            else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] != $_SESSION["empid"])
            {
                $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="proceed" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
            }
            else if($rowrecord["requeststatus"]==0 && $rowrecord["editor"] == $_SESSION["empid"])
            {
                $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
                onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
                    $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodal" id=""
                onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
            }
            else if($rowrecord["requeststatus"]==5)
            {
                $echo.='<a class="table-actions" data-placement="top" title="proceed" href="javascript:void(0);" id=""
            onclick="showTaskDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="glyphicon glyphicon-log-in"></i></a>';
            }
            else
            {
               $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
                onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
            }

            $echo.='</div>'; 
            $echo.='</td>';
            $echo.='</tr>';
        }
        
        // priority
        $resultlist = workorder_sql("brandid, priority, requeststatus, editor, actiontype, currency, brandentry, wocode, notes, filename", "and requeststatus=1 and (priority = 4 or priority = 3) ORDER BY priority ASC");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {   
            $brandname = brand_row("brandname, id", "and id=".$rowrecord["brandid"]);
            $bname = str_replace("'","\'",$brandname["brandname"]);
            $editor = editor_row($rowrecord["editor"]);
            $lastupdate = lastupdatePricelist_row($rowrecord['brandid']);

            $echo.='<tr>';
            $echo.='<td class="hidden">'.$rowrecord["priority"].'</td>';
            $echo.='<td>'.$rowrecord["wocode"].'</td>';
            $echo.='<td>'.$brandname["brandname"].'</td>';
            $echo.='<td>'.$lastupdate["dateended"].'</td>';
            $echo.='<td>'.ucwords($rowrecord["currency"]).'</td>';
            $echo.='<td>'.strtoupper($rowrecord["brandentry"]).'</td>';
            switch ($rowrecord["actiontype"])
                {
                    case 1: $echo.='<td>ADD</td>'; $actiontype="ADD"; break; //home
                    case 2: $echo.='<td>UPDATE</td>'; $actiontype="UPDATE"; break; //home
                    default: $echo.='<td></td>'; break; //home
                }
            switch ($rowrecord["priority"])
                {
                    case 1: $echo.='<td><span class="label label-default">Low</span></td>'; break; //home
                    case 2: $echo.='<td><span class="label label-primary">Normal</span></td>'; break; //home
                    case 3: $echo.='<td><span class="label label-warning">High</span></td>'; break; //home
                    case 4: $echo.='<td><span class="label label-danger">Urgent</span></td>'; break; //home
                }

            switch ($rowrecord["requeststatus"])
                {
                    case 1: $echo.='<td><span class="label label-warning">Pending</span></td>'; break; //home
                    case 2: $echo.='<td><span class="label label-success">On-going</span> by '.$editor.'</td>'; break; //home
                    case 0: $echo.='<td><span class="label label-danger">For Approval</span> by '.$editor.'</td>'; break; //home
                    case 5: $echo.='<td><span class="label label-danger">Hold</span></td>'; break; //home
                    default: $echo.='<td></td>'; break; //home
                }
            $echo.='<td class="actions">';
            $echo.='<div class="action-buttons">';
            if($rowrecord["requeststatus"]==1){

                $echo.='<a class="table-actions" data-placement="top" title="proceed" href="javascript:void(0);" id=""
            onclick="showTaskDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="glyphicon glyphicon-log-in"></i></a>';
            }
            else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] == $_SESSION["empid"])
            {
            $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
                
                $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodal" id=""
            onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
            }
            else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] != $_SESSION["empid"])
            {
                $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="proceed" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
            }
            else if($rowrecord["requeststatus"]==0 && $rowrecord["editor"] == $_SESSION["empid"])
            {
                $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
                onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
                    $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodal" id=""
                onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
            }
            else if($rowrecord["requeststatus"]==5)
            {
                $echo.='<a class="table-actions" data-placement="top" title="proceed" href="javascript:void(0);" id=""
            onclick="showTaskDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="glyphicon glyphicon-log-in"></i></a>';
            }
            else
            {
               $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
                onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
            }

            $echo.='</div>'; 
            $echo.='</td>';
            $echo.='</tr>';
        }

        // pending
        $resultlist = workorder_sql("brandid, priority, requeststatus, editor, actiontype, currency, brandentry, wocode, notes, filename", "and requeststatus=1 and priority = 2 ORDER BY id ASC");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {   
            $brandname = brand_row("brandname, id", "and id=".$rowrecord["brandid"]);
            $bname = str_replace("'","\'",$brandname["brandname"]);
            $editor = editor_row($rowrecord["editor"]);
            $lastupdate = lastupdatePricelist_row($rowrecord["brandid"]);

            $echo.='<tr>';
            $echo.='<td class="hidden">'.$rowrecord["requeststatus"].'</td>';
            $echo.='<td>'.$rowrecord["wocode"].'</td>';
            $echo.='<td>'.$brandname["brandname"].'</td>';
            $echo.='<td>'.$lastupdate["dateended"].'</td>';
            $echo.='<td>'.ucwords($rowrecord["currency"]).'</td>';
            $echo.='<td>'.strtoupper($rowrecord["brandentry"]).'</td>';
            switch ($rowrecord["actiontype"])
                {
                    case 1: $echo.='<td>ADD</td>'; $actiontype="ADD"; break; //home
                    case 2: $echo.='<td>UPDATE</td>'; $actiontype="UPDATE"; break; //home
                    default: $echo.='<td></td>'; break; //home
                }
            switch ($rowrecord["priority"])
                {
                    case 1: $echo.='<td><span class="label label-default">Low</span></td>'; break; //home
                    case 2: $echo.='<td><span class="label label-primary">Normal</span></td>'; break; //home
                    case 3: $echo.='<td><span class="label label-warning">High</span></td>'; break; //home
                    case 4: $echo.='<td><span class="label label-danger">Urgent</span></td>'; break; //home
                }

            switch ($rowrecord["requeststatus"])
                {
                    case 1: $echo.='<td><span class="label label-warning">Pending</span></td>'; break; //home
                    case 2: $echo.='<td><span class="label label-success">On-going</span> by '.$editor.'</td>'; break; //home
                    case 0: $echo.='<td><span class="label label-danger">For Approval</span> by '.$editor.'</td>'; break; //home
                    case 5: $echo.='<td><span class="label label-danger">Hold</span></td>'; break; //home
                    default: $echo.='<td></td>'; break; //home
                }
            $echo.='<td class="actions">';
            $echo.='<div class="action-buttons">';
            if($rowrecord["requeststatus"]==1){

                $echo.='<a class="table-actions" data-placement="top" title="proceed" href="javascript:void(0);" id=""
            onclick="showTaskDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="glyphicon glyphicon-log-in"></i></a>';
            }
            else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] == $_SESSION["empid"])
            {
            $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
                
                $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodal" id=""
            onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
            }
            else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] != $_SESSION["empid"])
            {
                $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="proceed" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
            }
            else if($rowrecord["requeststatus"]==0 && $rowrecord["editor"] == $_SESSION["empid"])
            {
                $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
                onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
                    $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodal" id=""
                onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
            }
            else if($rowrecord["requeststatus"]==5)
            {
                $echo.='<a class="table-actions" data-placement="top" title="proceed" href="javascript:void(0);" id=""
            onclick="showTaskDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="glyphicon glyphicon-log-in"></i></a>';
            }
            else
            {
               $echo.='<a href="javascript:void(0);" class="table-actions" data-placement="top" title="view" id=""
                onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
            }

            $echo.='</div>'; 
            $echo.='</td>';
            $echo.='</tr>';
        }
        $echo.='</tbody>';
        
        $echo.='</table>';
        
        $echo.='</div>'; //padded clearfix
        $echo.='</div>'; //widget-container
        $echo.='</div>'; //col-lg-12
        $echo.='</div>'; //row
    

    
    $echo.='<div id="basic-modal-content">';
    $echo.='<img src="./images/loading.gif" /> <a>Loading...</a>';
    $echo.='</div>';

    ////modal proceed

    $echo.='<div class="modal fade" id="proceedmodal">';
    $echo.='<div class="modal-dialog">';
    $echo.='<div class="modal-content">';
    $echo.='<div class="modal-header">';
    $echo.='<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    $echo.='<h4 class="modal-title">Request details</h4></div>';

    $echo.='<div class="modal-body">';
    $echo.='<form action="#" class="form-horizontal">';


    $echo.='<input type="hidden" id="idhidden" value=""></input>';

    $echo.='<div  class="col-md-3">Brand</div>';
    $echo.='<div class="col-md-7">';
    $echo.='<div id="brandname"></div>'; 
    $echo.='</div>'; //col-md-7

    $echo.='<div class="col-md-3">Currency</div>';
    $echo.='<div class="col-md-7">';
    $echo.='<div id="currency"></div>'; 
    $echo.='</div>'; //col-md-7

    $echo.='<div class="col-md-3">Action</div>';
    $echo.='<div class="col-md-7">';
    $echo.='<div id="actiontype"></div>'; 
    $echo.='</div>'; //col-md-7

    $echo.='<div class="col-md-3">Old/New</div>';
    $echo.='<div class="col-md-7">';
    $echo.='<div id="brandentry"></div>'; 
    $echo.='</div>'; //col-md-7

    $echo.='<div class="col-md-3">Price list</div>';
    $echo.='<div class="col-md-7">';
    $echo.='<div id="pricelist"></div>'; 
    $echo.='</div>'; //col-md-7

    $echo.='<div class="col-md-3">Notes</div>';
    $echo.='<div class="col-md-7">';
    $echo.='<div id="notes"></div>'; 
    $echo.='</div>'; //col-md-7

    $echo.='</br>'; //form-group

    $echo.='<div class="form-group pull-right">';
    $echo.='<button class="btn btn-primary"  id="successbtn" onclick="pendingproceed()"><i class="fa fa-arrow-down"></i>Proceed</button>';
    $echo.='<button class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-ban-circle"></i>Back</button>';
    $echo.='</div>'; //form-group

    $echo.='</form></p>';       
    $echo.='</div>';
    $echo.='<div class="modal-footer">';
    $echo.='</div></div></div></div>';

    ////modal finish

    $echo.='<div class="modal fade" id="donedmodal">';
    $echo.='<div class="modal-dialog">';
    $echo.='<div class="modal-content">';
    $echo.='<div class="modal-header">';
    $echo.='<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    $echo.='<h4 class="modal-title">Done</h4></div>';

    $echo.='<div class="modal-body">';

    $echo.='<form class="form-horizontal" action="pendingdoneajax.php" method="POST" enctype="multipart/form-data" id="doneTaskForm">';

    $echo.='<input type="hidden" id="idhiddendone" name="wocode" value=""></input>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-4">No of Product(s) added</label>';
    $echo.='<div class="col-md-6">';
    $echo.='<input class="form-control"  type="text" id="productadd" name="productadd" value="" placeholder="Enter number only" tabindex ="1" autofocus>';
    $echo.='</div>'; //form-group
    $echo.='</div>'; //form-group

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-4">No of Product(s) updated</label>';
    $echo.='<div class="col-md-6">';
    $echo.='<input class="form-control"  type="text" id="productupdate" name="productupdate" value="" placeholder="Enter number only" tabindex ="2">';
    $echo.='</div>'; //form-group
    $echo.='</div>'; //form-group

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-4">Task status</label>';
    $echo.='<div class="col-md-6">';
    $echo.='<select class="form-control" id="status" name="status" value="0" tabindex="3" autofocus>';
    $echo.='<option value="all">-- Select Status --</option>';
        $echo.='<option value="0">For Approval</option>';
        $echo.='<option value="3">Done</option>';
    $echo.='</select>';
    $echo.='</div>'; //form-group
    $echo.='</div>'; //form-group


    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-4">Work Report<span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-6">';
    $echo.='<div class="fileupload fileupload-new" data-provides="fileupload">';
    $echo.='<div class="input-group">';
    $echo.='<div class="form-control">';
    $echo.='<i class="fa fa-file fileupload-exists" ></i><span class="fileupload-preview"></span>';
    $echo.='</div>';//form-control
    $echo.='<div class="input-group-btn">';
    $echo.='<a class="btn btn-default fileupload-exists" data-dismiss="fileupload" href="#">Remove</a><span class="btn btn-default btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" id="workreport" name="workreport"></span>';
    $echo.='</div>';//input-group-btn
    $echo.='</div>';//input-group
    $echo.='</div>';//fileupload
    $echo.='</div>';//col-md-9
    $echo.='</div>';//form-group

    $echo.='<div class="form-group pull-right">';
    $echo.='<button class="btn btn-success" onclick="pendingdone()" id="submitBtn" type="button" /><i class="fa fa-check-square-o"></i>OK</button>';
    $echo.='<button class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-ban-circle"></i>Back</button>';
    $echo.='</div>'; //form-group

    $echo.='</form>';       
    $echo.='</div>';
    $echo.='<div class="modal-footer">';
    $echo.='</div></div></div></div>';

    return $echo;
}

function showWorkOrderHistory()
{
    $today = date("m/d/Y");
    $todaydb = date("Y-m-d");
    $filterdate = $today." to ".$today;
    $echo="";
    
    $echo.='<div class="page-title">';
    $echo.='<h3>Work Order History</h3>';
    $echo.='</div>';

        //////////// TABLE
        // $echo.='<div id="samplehtml">';
        // $echo.='</div>';

        $echo.='<div class="row">';
        $echo.='<div class="col-lg-12">';
        $echo.='<div class="widget-container fluid-height">';
        $echo.='<div class="widget-content padded clearfix">';
        $echo.='<form action="#" class="form-horizontal">';
        $echo.='<div class="form-group">';

        $echo.='<div class="col-md-2" style="z-index:0;">';
        $echo.='<input type="text" class="form-control date-range" readonly="readonly" id="daterange" value="'.$filterdate.'"></input>';
        $echo.='</div>';

        $echo.='<div class="col-md-3">';
        $echo.='<div class="input-group col-md-12 col-sm-12 col-xs-12">';
        $echo.='<select class="select2able" id="brand" value="0" tabindex="1" autofocus>';
        $echo.='<option value="all">All Brand</option>';
        $resultlist = brand_sql("id, brandname", "");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {
            $echo.='<option value="'.$rowrecord["id"].'">'.$rowrecord["brandname"].'</option>';
        }
        $echo.='</select>';
        $echo.='</div>';//input-group
        $echo.='</div>';//col-md-3

        //actiontype
        $echo.='<div class="col-md-2 col-sm-12 col-xs-12"">';
        $echo.='<div class="input-group col-md-12 col-sm-12 col-xs-12">';
        $echo.='<select class="select2able" id="actiontype" tabindex="1" autofocus>';
        $echo.='<option value="all">Task Type</option>';
        $echo.='<option value="1">Add</option>';
        $echo.='<option value="2">Update</option>';
        $echo.='</select>';
        $echo.='</div>';//input-group
        $echo.='</div>';//col-md-3

        $echo.='<div class="col-md-2">';
        $echo.='<div class="input-group col-md-12 col-sm-12 col-xs-12">';
        $echo.='<select class="select2able" id="editor" value="0" tabindex="1" autofocus>';
        $echo.='<option value="all">All Editor</option>';
        $editors = editors_sql("name, id", "and (restriction >= 1 or restriction >= 3)");
        while($rowrecord = mysqli_fetch_assoc($editors))
        {
            $echo.='<option value="'.$rowrecord["id"].'">'.$rowrecord["name"].'</option>';
        }
        $echo.='</select>';
        $echo.='</div>';//input-group
        $echo.='</div>';//col-md-3

        $echo.='<div class="col-md-3 col-sm-12 col-xs-12">';
        $echo.='<div class="input-group">';
        $echo.='<button class="btn btn-primary" onclick="workordersearch();" type="button" >Search</button>';
        $echo.='</div>';//input-group
        $echo.='</div>';//col-md-3

        $echo.='</div>';//form group
        $echo.='</form>'; //form
        $echo.='</div>'; //padded clearfix
        $echo.='</div>'; //widget-container
        $echo.='</div>'; //col-lg-12
        $echo.='</div>'; //row

    
        //////////// TABLE
        
    $echo.='<div class="row">';
    $echo.='<div id="basic-modal-content">';
    $echo.='<img src="./images/loading.gif" /> <a>Loading...</a>';
    $echo.='</div>';//row

    $echo.='<div class="col-lg-12">';
    $echo.='<div class="widget-container fluid-height">';
    $echo.='<div class="widget-content padded clearfix">';
    $echo.='<div id="ajaxdata">';
    $echo.='<table class="table table-bordered table-striped dataTable" id="dataTable1">'; //class="table table-hover"
    
    $echo.='<thead>';
    // $echo.='<th width="40">id</th>';
    $echo.='<th width="40">Code</th>';
    $echo.='<th width="550">Brand Name</th>';
    $echo.='<th>Date</th>';
    $echo.='<th>Editor</th>';
    $echo.='<th width="50"></th>';
    $echo.='</thead>';
    
    $echo.='<tbody>';
    
    $echo.='</tbody>';
    
    $echo.='</table>';
    $echo.='</div>'; //ajaxdata
    
    $echo.='</div>'; //padded clearfix
    $echo.='</div>'; //widget-container
    $echo.='</div>'; //col-lg-12
    $echo.='</div>'; //row
    
    return $echo; 
}


function showWorkOrderHistoryBrand()
{
    $today = date("m/d/Y");
    $todaydb = date("Y-m-d");
    $filterdate = $today." to ".$today;
    $echo="";
    
    $echo.='<div class="page-title">';
    $echo.='<h3>Work Order History</h3>';
    $echo.='</div>';

        //////////// TABLE
        // $echo.='<div id="samplehtml">';
        // $echo.='</div>';

        $echo.='<div class="row">';
        $echo.='<div class="col-lg-12">';
        $echo.='<div class="" id="divnotif" style="display:none;" >';
        $echo.='</div>';
        $echo.='</div>';
        $echo.='</div>';

        $echo.='<div class="row">';
        $echo.='<div class="col-lg-12">';
        $echo.='<div class="widget-container fluid-height">';
        $echo.='<div class="widget-content padded clearfix">';
        $echo.='<form action="#" class="form-horizontal">';
        $echo.='<div class="form-group">';

        $echo.='<div class="col-md-3">';
        $echo.='<div class="input-group col-md-12">';
        $echo.='<select class="select2able" id="brand" value="0" tabindex="1" autofocus>';
        $echo.='<option value="all">All Brand</option>';
        $resultlist = brand_sql("id, brandname", "");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {
            $echo.='<option value="'.$rowrecord["id"].'">'.$rowrecord["brandname"].'</option>';
        }
        $echo.='</select>';
        $echo.='</div>';//input-group
        $echo.='</div>';//col-md-3

        //actiontype
        $echo.='<div class="col-md-2 col-sm-12 col-xs-12"">';
        $echo.='<div class="input-group col-md-12 col-sm-12 col-xs-12">';
        $echo.='<select class="select2able" id="actiontype" tabindex="1" autofocus>';
        $echo.='<option value="all">Task Type</option>';
        $echo.='<option value="1">Add</option>';
        $echo.='<option value="2">Update</option>';
        $echo.='</select>';
        $echo.='</div>';//input-group
        $echo.='</div>';//col-md-3

        $echo.='<div class="col-md-3">';
        $echo.='<div class="input-group">';
        $echo.='<button class="btn btn-primary" onclick="workordersearchbrand();" type="button" >Search</button>';
        $echo.='</div>';//input-group
        $echo.='</div>';//col-md-3

        $echo.='</div>';//form group
        $echo.='</form>'; //form
        $echo.='</div>'; //padded clearfix
        $echo.='</div>'; //widget-container
        $echo.='</div>'; //col-lg-12
        $echo.='</div>'; //row

    
        //////////// TABLE
        
    $echo.='<div class="row">';
    $echo.='<div id="basic-modal-content">';
    $echo.='<img src="./images/loading.gif" /> <a>Loading...</a>';
    $echo.='</div>';//row

    $echo.='<div class="col-lg-12">';
    $echo.='<div class="widget-container fluid-height">';
    $echo.='<div class="widget-content padded clearfix">';
    $echo.='<div id="ajaxdata">';
    $echo.='<table class="table table-bordered table-striped dataTable" id="dataTable1">'; //class="table table-hover"
    
    $echo.='<thead>';
    // $echo.='<th width="40">id</th>';
    $echo.='<th width="40">Code</th>';
    $echo.='<th width="550">Brand Name</th>';
    $echo.='<th>Date</th>';
    $echo.='<th>Editor</th>';
    $echo.='<th width="50"></th>';
    $echo.='</thead>';
    
    $echo.='<tbody>';
    
    $echo.='</tbody>';
    
    $echo.='</table>';
    $echo.='</div>'; //ajaxdata
    
    $echo.='</div>'; //padded clearfix
    $echo.='</div>'; //widget-container
    $echo.='</div>'; //col-lg-12
    $echo.='</div>'; //row
    
    return $echo; 
}

function editResult($wocode)
{
    $echo="";
    
    $history = workorderhistory_row("wocode,brandid,priority,createdby,datestarted,timestarted,dateended,timeended,productadd,productupdate,requeststatus,workreport","and wocode like '".$wocode."'");

        $reserve = reserveworkorder_row("createdat,notes", "and wocode='".$wocode."'");
        $assign = 'Jeriel';
        $taskreceive=$reserve["createdat"];
        $currency="N/A";
        $Priority='LOW';
        $brandentry="OLD";
        $action="UPDATE";
        $pricelist="N/A";
        $notes=$reserve["notes"];
    
    $editor=editor_row($history["createdby"]);

    $datestarted=date("m/d/Y",strtotime($history["datestarted"]));
    $timestarted=$history["timestarted"];
    $dateended=date("m/d/Y",strtotime($history["dateended"]));
    $timeended=$history["timeended"];
    $brandname = brand_row("brandname, id", "and id=".$history["brandid"]);
    $status = $history["requeststatus"];
    $workreport=$history["workreport"];
    if($status==2 || $status==1)
    {
        $dateended="N/A"; $timeended="N/A";
        $Productadd="N/A"; $productupdate="N/A";
    }
    else
    {
        $dateended=date("F j, Y",strtotime($history["dateended"]));
        $timeended=date("g:i a",strtotime($history["timeended"]));
        $productadd=$history["productadd"];
        $productupdate=$history["productupdate"];
        $total=$history["productadd"]+$history["productupdate"];
    }
    switch ($status) {
        case 0: $status="For approval"; break;
        case 1: $status="Pending"; break;
        case 2: $status="On going"; break;
        case 3: $status="Done"; break;
        case 4: $status="Canceled"; break;
        case 5: $status="Duplicate"; break;
        // default: break;
    }

    $echo.='<div class="page-title">';//</h3><h1>
    // $echo.='<h3>'.$wocode.''.$brandname["brandname"].'</h3>';
    $echo.='<h2>Complete Details</h2>';
    $echo.='</div>';

    $echo.='<div class="row">';
    $echo.='<div class="col-lg-12">';
    $echo.='<div class="widget-container fluid-height">';
    $echo.='<div class="widget-content padded clearfix">';

    $echo.='<h3>Update Result Details';
 
    $echo.='<button class="btn btn-primary pull-right" onclick="window.location=\'taskedit.php?wocode='.$wocode.'\'" type="button" >Save</button>';
    
    $echo.='</h3>';

    $echo.='<input type="hidden" id="wocode" name="wocode" value="'.$wocode.'"></input>';
    $echo.='<input type="hidden" id="function" name="function" value="updatesave"></input>';

    $echo.='<table class="table table-bordered table-striped">';
    $echo.='<tbody>';
    $echo.='<tr>';
    $echo.='<td width="30%"><b>Product editor</b></td>';
    $echo.='<td id="started">'.$editor.'</td>';
    $echo.='</tr>';

    $echo.='<td width="30%"><b>Date started</b></td>';
    $echo.='<td id="started">';
    $echo.='<div class="col-xs-5"><input class="form-control datepicker" type="text" id="datestarted" name="datestarted" value="" placeholder="'.$datestarted.' "></div>';
    // $echo.='<div class="col-xs-5"><div class="input-group bootstrap-timepicker"><input class="form-control" type="text" id="timepicker-default" name="timestarted" value="" placeholder="'.$timestarted.' "><span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div></div>';
    $echo.='</td>';
    $echo.='</tr>';


    $echo.='<tr>';
    $echo.='<td width="30%"><b>Date finished</b></td>';
    $echo.='<td id="done">';
    $echo.='<div class="col-xs-5"><input class="form-control datepicker" type="text" id="dateended" name="dateended" value="" placeholder="'.$timeended.' "></div>';
    $echo.='</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>No. Products</b></td>';
    $echo.='<td id="products"><label class="control-label col-md-1">Updated: </label><div class="col-xs-1"><input class="form-control" type="text" id="productupdate" name="productupdate" value="" placeholder="'.$productupdate.' "></div>';
    $echo.=',   Added: '.$productadd.', Total: '.$total;
    $echo.='</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Work Report</b></td>';
    $echo.='<td id="pricelist"><a href=workreport/'.$workreport.' target="_Blank">download</a></td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Task status</b></td>';
    $echo.='<td id="status">'.$status.'</td>';
    $echo.='</tr>';

    $echo.='</tbody>';
    $echo.='</table">';

    $echo.='</div>'; //widget-content
    $echo.='</div>'; //widget-container
    $echo.='</div>'; //col-lg-12
    $echo.='</div>'; //row
    return $echo;
}

function taskCompleteDetails($wocode)
{
    $echo="";

    $history = workorderhistory_row("wocode,brandid,priority,createdby,datestarted,timestarted,dateended,timeended,productadd,productupdate,requeststatus,workreport","and wocode like '".$wocode."'");
    if($history["priority"]==1)
    {
        $reserve = reserveworkorder_row("createdat,notes", "and wocode='".$wocode."'");
        $assign = 'Jeriel';
        $taskreceive=$reserve["createdat"];
        $currency="N/A";
        $Priority='LOW';
        $brandentry="OLD";
        $action="UPDATE";
        $pricelist="N/A";
        $notes=$reserve["notes"];
    }
    else
    {
        $workorder = workorder_row("orderfrom,receivedate,currency,priority,brandentry,clientid,actiontype,filename,notes,createdby", "and wocode='".$wocode."'");
        $assign = $workorder["orderfrom"];
        $taskreceive=date("F j, Y",strtotime($workorder["receivedate"]));
        $currency=$workorder["currency"];
        $brandentry=$workorder["brandentry"];
        $pricelist=$workorder["filename"];
        $notes=$workorder["notes"];
        switch($workorder["actiontype"])
        {
            case 1: $action="ADD"; break;
            case 2: $action="UPDATE"; break;
            // default: $priority="ERROR";
        }
        switch($workorder["priority"])
        {
            case 1: $Priority="LOW"; break;
            case 2: $Priority="NORMAL"; break;
            case 3: $Priority="HIGH"; break;
            case 4: $Priority="URGENT"; break;
            // default: $priority="ERROR";
        } //
    }
    $editor=editor_row($history["createdby"]);

    $clientname = getClientName($workorder["clientid"]);

    $datestarted=date("F j, Y",strtotime($history["datestarted"]));
    $timestarted=date("g:i a",strtotime($history["timestarted"]));
    $dateended=date("F j, Y",strtotime($history["dateended"]));
    $timeended=date("g:i a",strtotime($history["timeended"]));
    $brandname = brand_row("brandname, id", "and id=".$history["brandid"]);
    $status = $history["requeststatus"];
    $workreport=$history["workreport"];
    if($status==2 || $status==1)
    {
        $dateended="N/A"; $timeended="N/A";
        $Productadd="N/A"; $productupdate="N/A";
    }
    else
    {
        $dateended=date("F j, Y",strtotime($history["dateended"]));
        $timeended=date("g:i a",strtotime($history["timeended"]));
        $productadd=$history["productadd"];
        $productupdate=$history["productupdate"];
        $total=$history["productadd"]+$history["productupdate"];
    }
    switch ($status) {
        case 0: $status="For approval"; break;
        case 1: $status="Pending"; break;
        case 2: $status="On going"; break;
        case 3: $status="Done"; break;
        case 4: $status="Canceled"; break;
        case 5: $status="Duplicate"; break;
        // default: break;
    }

    $echo.='<div class="page-title">';//</h3><h1>
    // $echo.='<h3>'.$wocode.''.$brandname["brandname"].'</h3>';
    $echo.='<h2>Complete Details</h2>';
    $echo.='</div>';

    $echo.='<div class="row">';
    $echo.='<div class="col-lg-12">';
    $echo.='<div class="widget-container fluid-height">';
    $echo.='<div class="widget-content padded clearfix">';
    // $echo.='<div class="row">';
    $echo.='<h3>Work order details';
    // if($_SESSION["access"]==1 || $_SESSION["access"]==0)
    // {
    //     $echo.='<button class="btn btn-primary pull-right" onclick="window.location=\'taskedit.php?wocode='.$wocode.'\'" type="button" >Edit</button>';
    // }
    // else
    // {
    //     $echo.='<button class="btn btn-primary pull-right disabled" onclick="workordersearch();" type="button" >Edit</button>';
    // }
    $echo.='<button class="btn btn-primary pull-right" onclick="window.location=\'taskedit.php?wocode='.$wocode.'\'" type="button" >Edit</button>';
    
    $echo.='</h3>';
    $echo.='<table class="table table-bordered table-striped">';
    $echo.='<tbody>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Code</b></td>';
    $echo.='<td id="wocode">'.$wocode.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Client Name</b></td>';
    $echo.='<td id="clientname"><strong>'.$clientname.'</strong></td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Brand Name</b></td>';
    $echo.='<td id="brandname"><strong>'.$brandname["brandname"].'</strong> <a href="brands.php?show='.$history["brandid"].'" target="_blank"> <i class="fa fa-chevron-circle-right"></i></a></td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Assigned by</b></td>';
    $echo.='<td id="assign">'.$assign.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Task receive date</b></td>';
    $echo.='<td id="datereceive">'.$taskreceive.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Currency</b></td>';
    $echo.='<td id="currency">'.$currency.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Priority</b></td>';
    $echo.='<td id="priority">'.$Priority.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Brand entry</b></td>';
    $echo.='<td id="brandentry">'.$brandentry.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Action Type</b></td>';
    $echo.='<td id="action">'.$action.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Price list</b></td>';
    $echo.='<td id="pricelist"><a href="pricelist/'.$pricelist.'" target="_Blank">download</a></td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td><b>Instruction<b></td>';
    $echo.='<td>'.$notes.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td><b>Brand Instruction(s)<b></td>';
    $echo.='<td><ul>';
    $brandnotes = brand_notes_sql('note, noteby', ' and brandid = '.$history["brandid"]);
    while($rowrecord = mysqli_fetch_assoc($brandnotes))
    { 
        $echo.='<li>';
        $echo.='<p>';
        $echo.=$rowrecord['note'];
        $echo.='</p>';
        $echo.='</li>';
    }
    $echo.='</ul></td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td><b>Brand Report<b></td>';
    $echo.='<td><a href="workorder-report.php?wocode='.$wocode.'">Click here to view</a></td>';

    $echo.='</tr>';

    $echo.='</tbody>';
    $echo.='</table>';


    $echo.='<h3>Result Details';
    if($history["createdby"]==$_SESSION["empid"] || $_SESSION["access"]==0 || $_SESSION["access"]==1)
    {
        $echo.='<button class="btn btn-primary pull-right" onclick="window.location=\'resultedit.php?wocode='.$wocode.'\'" type="button" >Edit</button>';
    }
    else
    {
        $echo.='<button class="btn btn-primary pull-right disabled" onclick="window.location=\'resultedit.php?wocode='.$wocode.'\'" type="button" >Edit</button>';
    }

    $echo.='</h3>';

    $echo.='<table class="table table-bordered table-striped">';
    $echo.='<tbody>';
    $echo.='<tr>';
    $echo.='<td width="30%"><b>Product editor</b></td>';
    $echo.='<td id="started">'.$editor.'</td>';
    $echo.='</tr>';

    $echo.='<td width="30%"><b>Date started</b></td>';
    $echo.='<td id="started">'.$datestarted.' ,  '.$timestarted.'</td>';
    $echo.='</tr>';


    $echo.='<tr>';
    $echo.='<td width="30%"><b>Date finished</b></td>';
    $echo.='<td id="done">'.$dateended.' ,  '.$timeended.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>No. Products</b></td>';
    $echo.='<td id="products">Updated: '.$productupdate.',   Added: '.$productadd.', Total: '.$total.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Work Report</b></td>';
    $echo.='<td id="pricelist"><a href="workreport/'.$workreport.'" target="_Blank">download</a></td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Task status</b></td>';
    $echo.='<td id="status">'.$status.'</td>';
    $echo.='</tr>';

    $echo.='</tbody>';
    $echo.='</table">';

    $echo.='</div>'; //widget-content
    $echo.='</div>'; //widget-container
    $echo.='</div>'; //col-lg-12
    $echo.='</div>'; //row

    return $echo; 
}


function taskDetails($wocode)
{
    $echo="";
    
    $firstwocode = $wocode[0];
    if($firstwocode=="R")
    {
        $reserve = reserveworkorder_row("createdat,clientid,notes, brandid", "and wocode='".$wocode."'");
        $assign = 'Jeriel';
        $taskreceive=$reserve["createdat"];
        $currency="N/A";
        $Priority='LOW';
        $brandentry="OLD";
        $action="UPDATE";
        $pricelist="N/A";
        $notes=$reserve["notes"];
        $brandname = brand_row("brandname, id", "and id=".$reserve["brandid"]);
    }
    else
    {
        $workorder = workorder_row("orderfrom,clientid,receivedate,currency,priority,brandentry,actiontype,filename,notes,createdby,brandid", "and wocode='".$wocode."'");
        $assign = $workorder["orderfrom"];
        $taskreceive=date("F j, Y",strtotime($workorder["receivedate"]));
        $currency=$workorder["currency"];
        $brandentry=$workorder["brandentry"];
        $pricelist=$workorder["filename"];
        $notes=$workorder["notes"];
        switch($workorder["actiontype"])
        {
            case 1: $action="ADD"; break;
            case 2: $action="UPDATE"; break;
            // default: $priority="ERROR";
        }
        switch($workorder["priority"])
        {
            case 1: $Priority="LOW"; break;
            case 2: $Priority="NORMAL"; break;
            case 3: $Priority="HIGH"; break;
            case 4: $Priority="URGENT"; break;
            // default: $priority="ERROR";
        } 
        $brandname = brand_row("brandname, id", "and id=".$workorder["brandid"]);
    }

    // $editor=editor_row($history["createdby"]);
    $clientname = getClientName($workorder["clientid"]);

    $echo.='<div class="page-title">';//</h3><h1>
    // $echo.='<h3>'.$wocode.''.$brandname["brandname"].'</h3>';
    $echo.='<h2>Complete Details</h2>';
    $echo.='</div>';

    $echo.='<div class="row">';
    $echo.='<div class="col-lg-12">';
    $echo.='<div class="widget-container fluid-height">';
    $echo.='<div class="widget-content padded clearfix">';
    // $echo.='<div class="row">';
    $echo.='<h3>Work order details';
    if($_SESSION["access"]==1 || $_SESSION["access"]==0)
    {
        $echo.='<button class="btn btn-primary pull-right" onclick="window.location=\'taskedit.php?wocode='.$wocode.'\'" type="button" >Edit</button>';
    }
    else
    {
        $echo.='<button class="btn btn-primary pull-right disabled" onclick="workordersearch();" type="button" >Edit</button>';
    }
    $echo.='</h3>';
    $echo.='<table class="table table-bordered table-striped">';
    $echo.='<tbody>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Code</b></td>';
    $echo.='<td id="wocode">'.$wocode;
    $echo.='<input type="hidden" id="idhidden" value="'.$wocode.'"></input>';
    $echo.='</td></tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Client Name</b></td>';
    $echo.='<td id="clientname"><strong>'.$clientname.'</strong></td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Brand Name</b></td>';
    $echo.='<td id="brandname"><strong>'.$brandname["brandname"].'</strong><a href="brands.php?show='.$workorder["brandid"].'" target="_blank"> <i class="fa fa-chevron-circle-right"></i></a></td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Assigned by</b></td>';
    $echo.='<td id="assign">'.$assign.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Task receive date</b></td>';
    $echo.='<td id="datereceive">'.$taskreceive.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Currency</b></td>';
    $echo.='<td id="currency">'.$currency.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Priority</b></td>';
    $echo.='<td id="priority">'.$Priority.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Brand entry</b></td>';
    $echo.='<td id="brandentry">'.$brandentry.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Action Type</b></td>';
    $echo.='<td id="action">'.$action.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Price list</b></td>';
    $echo.='<td id="pricelist"><a href="pricelist/'.$pricelist.'" target="_Blank">download</a></td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td><b>Instruction<b></td>';
    $echo.='<td>'.$notes.'</td>';
    $echo.='</tr>';


    $echo.='<tr>';
    $echo.='<td><b>Brand Instruction(s)<b></td>';
    $echo.='<td><ul>';
    $brandnotes = brand_notes_sql('note, noteby', ' and brandid = '.$workorder["brandid"]);
    while($rowrecord = mysqli_fetch_assoc($brandnotes))
    { 
        $echo.='<li>';
        $echo.=$rowrecord['note'];
        $echo.='</li>';
    }
    $echo.='</ul></td>';
    $echo.='</tr>';


    $echo.='</tbody>';
    $echo.='</table>';

    $echo.='<button class="btn btn-lg btn-block btn-primary" id="successbtn" onclick="pendingproceed()"><i class="fa fa-arrow-down"></i>Proceed</button>';


    $echo.='</div>'; //widget-content
    $echo.='</div>'; //widget-container
    $echo.='</div>'; //col-lg-12
    $echo.='</div>'; //row

    return $echo; 
}

function showReservePendingWorkOrder()
{
    $echo="";
    
    $echo.='<div class="page-title">';
    $echo.='<h3>Reserve Pending List</h3>';
    $echo.='</div>';

    $echo.='<div class="row">';
    $echo.='<div class="col-lg-12">';
    $echo.='<div class="" id="divnotif" style="display:none;" >';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    
        //////////// TABLE
        
        $echo.='<div class="row">';
        $echo.='<div class="col-lg-12">';
        $echo.='<div class="widget-container fluid-height">';
        $echo.='<div class="widget-content padded clearfix">'; 
        $echo.='<table class="table table-bordered table-striped" id="dataTable1">'; //class="table table-hover"
        
        $echo.='<thead>';
        $echo.='<th width="40">Code</th>';
        $echo.='<th width="700">Brand Name</th>';
        $echo.='<th>Priority</th>';
        $echo.='<th with="50">Status</th>';
        $echo.='<th with="50"></th>';
        $echo.='</thead>';
        
        $echo.='<tbody>';
        $resultlist = reserveworkorder_sql("brandid, requeststatus, wocode, notes, editor", "and requeststatus<3 ORDER BY id DESC");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {   
            $brandname = brand_row("brandname, id", "and id=".$rowrecord["brandid"]);
            $editor = editor_row($rowrecord["editor"]);

            $echo.='<tr>';
            $echo.='<td>'.$rowrecord["wocode"].'</td>';
            $echo.='<td>'.$brandname["brandname"].'</td>';
            $echo.='<td>Low</td>';
            switch ($rowrecord["requeststatus"])
                {
                    case 1: $echo.='<td><span class="label label-warning">Pending</span></td>'; break; //home
                    case 2: $echo.='<td><span class="label label-success">On-going</span> by '.$editor.'</td>'; break; //home
                    case 0: $echo.='<td><span class="label label-danger">For Approval</span> by '.$editor.'</td>'; break; //home
                    default: $echo.='<td></td>'; break; //home
                }
            $echo.='<td class="actions">';
            $echo.='<div class="action-buttons">';
            $echo.='';
            if($rowrecord["requeststatus"]==1){

                $echo.='<a class="table-actions" data-placement="top" title="proceed" href="javascript:void(0);" id=""
            onclick="showTaskDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="glyphicon glyphicon-log-in"></i></a>';
            }
            else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] == $_SESSION["empid"])
            {
            $echo.='<a class="table-actions" data-placement="top" title="view" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
                
                $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodalreserve" id=""
            onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
            }
            else if($rowrecord["requeststatus"]==2 && $rowrecord["editor"] != $_SESSION["empid"])
            {
                $echo.='<a class="table-actions" data-placement="top" title="proceed" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
            }
            else if($rowrecord["requeststatus"]==0 && $rowrecord["editor"] == $_SESSION["empid"])
            {
                $echo.='<a class="table-actions" data-placement="top" title="view" id=""
                onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
                    $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodalreserve" id=""
                onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
            }
            else
            {
               $echo.='<a class="table-actions" data-placement="top" title="view" id=""
                onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 
            }
            $echo.='</div>'; 
            $echo.='</td>';
            $echo.='</tr>';
        }
        $echo.='</tbody>';
        
        $echo.='</table>';
        
        $echo.='</div>'; //padded clearfix
        $echo.='</div>'; //widget-container
        $echo.='</div>'; //col-lg-12
        $echo.='</div>'; //row
    

    
    $echo.='<div id="basic-modal-content">';
    $echo.='<img src="./images/loading.gif" /> <a>Loading...</a>';
    $echo.='</div>';
    ////modalproceed

    $echo.='<div class="modal fade" id="proceedmodal">';
    $echo.='<div class="modal-dialog">';
    $echo.='<div class="modal-content">';
    $echo.='<div class="modal-header">';
    $echo.='<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    $echo.='<h4 class="modal-title">Request details</h4></div>';

    $echo.='<div class="modal-body">';
    $echo.='<form action="#" class="form-horizontal">';

    $echo.='<input type="hidden" id="idhidden" value=""></input>';

    $echo.='<div  class="col-md-3">Brand</div>';
    $echo.='<div class="col-md-7">';
    $echo.='<div id="brandname"></div>'; 
    $echo.='</div>'; //col-md-7

    $echo.='<div class="col-md-3">Action</div>';
    $echo.='<div class="col-md-7">';
    $echo.='<div id="">Update Details</div>'; 
    $echo.='</div>'; //col-md-7

    $echo.='<div class="col-md-3">Notes</div>';
    $echo.='<div class="col-md-7">';
    $echo.='<div id="notes"></div>'; 
    $echo.='</div>'; //col-md-7


    $echo.='<div class="form-group pull-right">';
    $echo.='<button class="btn btn-primary" id="successbtn" onclick="pendingreserveproceed()"><i class="fa fa-arrow-down"></i>Proceed</button>';
    $echo.='<button class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-ban-circle"></i>Back</button>';
    $echo.='</div>'; //form-group

    $echo.='</form></p>';       
    $echo.='</div>';
    $echo.='<div class="modal-footer">';
    $echo.='</div></div></div></div>';

    ////modal finish

    $echo.='<div class="modal fade" id="donedmodalreserve">';
    $echo.='<div class="modal-dialog">';
    $echo.='<div class="modal-content">';
    $echo.='<div class="modal-header">';
    $echo.='<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    $echo.='<h4 class="modal-title">Done</h4></div>';

    $echo.='<div class="modal-body">';
    $echo.='<form action="pendingreservedoneajax.php" class="form-horizontal" id="doneReserveTaskForm" method="POST" enctype="multipart/form-data">';

    $echo.='<input type="hidden" id="idhiddendone" id="wocode" name="wocode" value=""></input>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-4">No of Product(s) added</label>';
    $echo.='<div class="col-md-6">';
    $echo.='<input class="form-control" type="text" id="productadd" name="productadd" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="" placeholder="Enter number only" tabindex ="1" autofocus></input>';
    $echo.='</div>'; //col-md-6
    $echo.='</div>'; //form-group

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-4">No of Product(s) updated</label>';
    $echo.='<div class="col-md-6">';
    $echo.='<input class="form-control" type="text"onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="productupdate" name="productupdate" value="" placeholder="Enter number only" tabindex ="2"></input>';
    $echo.='</div>'; //col-md-6
    $echo.='</div>'; //form-group

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-4">Task status</label>';
    $echo.='<div class="col-md-6">';
    $echo.='<select class="form-control" id="status" name="status" value="0" tabindex="3" autofocus>';
    $echo.='<option value="all">-- Select Status --</option>';
        $echo.='<option value="0">For Approval</option>';
        $echo.='<option value="3">Done</option>';
    $echo.='</select>';
    $echo.='</div>'; //form-group
    $echo.='</div>'; //form-group


    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-4">Work Report<span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-6">';
    $echo.='<div class="fileupload fileupload-new" data-provides="fileupload">';
    $echo.='<div class="input-group">';
    $echo.='<div class="form-control">';
    $echo.='<i class="fa fa-file fileupload-exists" ></i><span class="fileupload-preview"></span>';
    $echo.='</div>';//form-control
    $echo.='<div class="input-group-btn">';
    $echo.='<a class="btn btn-default fileupload-exists" data-dismiss="fileupload" href="#">Remove</a><span class="btn btn-default btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" id="workreport" name="workreport"></span>';
    $echo.='</div>';//input-group-btn
    $echo.='</div>';//input-group
    $echo.='</div>';//fileupload
    $echo.='</div>';//col-md-9
    $echo.='</div>';//form-group

    $echo.='<div class="form-group pull-right">';
    $echo.='<button class="btn btn-success" onclick="pendingreservedone()" id="submitBtn" type="button" /><i class="fa fa-check-square-o"></i>Done</button>';
    $echo.='<button class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-ban-circle"></i>Back</button>';
    $echo.='</div>'; //form-group

    $echo.='</form>';       
    $echo.='</div>';
    $echo.='<div class="modal-footer">';
    $echo.='</div></div></div></div>';




    return $echo;
}

function showNewReservePendingWorkOrder()
{
    $echo="";
    $newcode = getReserveCode();
    $echo.='<div class="page-title">';
    $echo.='<h3>Add Reserve Brand</h3>';
    $echo.='</div>';

    $echo.='<div class="row">';
    $echo.='<div class="col-lg-12">';
    $echo.='<div class="" id="divnotif" style="display:none;" >';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    
    $echo.='<div id="basic-modal-content">';
    $echo.='<img src="./images/loading.gif" /> <a>Loading...</a>';
    $echo.='</div>';
    

    $echo.='<div class="row">';
    $echo.='<div class="col-lg-7">';
    $echo.='<div class="widget-container fluid-height clearfix">';
    $echo.='<div class="heading">';
    $echo.='<i class="fa fa-th-list"></i>Task details';
    $echo.='</div>';
    $echo.='<div class="widget-content padded">';
    $echo.='<form action="#" class="form-horizontal">';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Code</label>';
    $echo.='<div class="col-md-9">';
    $echo.='<input class="form-control disabled" readonly="readonly" type="text" id="wocode" value="'.$newcode.'"></input>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Brand name <span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<select class="select2able" id="newbrand" value="0" tabindex="1" autofocus>';
    $echo.='<option value="NULL">Select Brand</option>';
    $brandlist=brand_sql("id,brandname", "");
    while($rowrecord = mysqli_fetch_assoc($brandlist)){
        $echo.='<option value="'.$rowrecord['id'].'">'.$rowrecord['brandname'].'</option>';
    }
    $echo.='</select>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Task/Action <span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<input class="form-control" type="text" id="action" value="" placeholder="Enter task here" tabindex ="2" ></input>';
    $echo.='</div>';
    $echo.='</div>';

    $notesarr = explode("==",$notes);
    $newnote = $notesarr[0];
    $notefrom = $notesarr[1];
    $bub = $notesarr[2];
    $datenote = $notesarr[3];

    if($bub==$notesarr[2])
    {
        $bubclass="";
    }
    else
    {$bubclass="current-user";}

    $echo.='<div class=" scrollable chat" style="height: auto;">';
     $echo.='<div class="widget-content">';
       $echo.='<ul>';
           $echo.='<li class="'.$bubclass.'">';
             $echo.='<div class="bubble">';
               $echo.='<a class="user-name" href="">'.$notefrom.'</a>';
               $echo.='<p class="message">';
                $echo.= $newnote;
               $echo.='</p>';
               $echo.='<p class="time">';
                 $echo.='<strong>'.$datenote.' </strong>';
               $echo.='</p>';
             $echo.='</div>';
           $echo.='</li>';
        $echo.='</ul>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<button class="btn btn-primary" id="successbtn" onclick="reservenew()"><i class="fa fa-save"></i>Save</button>';
    $echo.='</form>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    return $echo;
}

function showAddWorkorder()
{
    $echo="";
    $newcode = getWorkorderCode();
    $echo.='<div class="page-title">';
    $echo.='<h3>Add Brand</h3>';
    $echo.='</div>';

    $echo.='<div class="row">';
    $echo.='<div class="col-lg-12">';
    $echo.='<div class="" id="divnotif" style="display:none;" >';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    
    $echo.='<div id="basic-modal-content">';
    $echo.='<img src="./images/loading.gif" /> <a>Loading...</a>';
    $echo.='</div>';
    

    $echo.='<div class="row">';
    $echo.='<div class="col-lg-7">';
    $echo.='<div class="widget-container fluid-height clearfix">';
    $echo.='<div class="heading">';
    $echo.='<i class="fa fa-th-list"></i>Task details';
    $echo.='</div>';
    $echo.='<div class="widget-content padded">';

    $echo.='<form action="addworkorderajax.php" method="POST" id="submitForm" class="form-horizontal" enctype="multipart/form-data">';


    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Code</label>';
    $echo.='<div class="col-md-9">';
    $echo.='<input class="form-control disabled" readonly="readonly" type="text" id="wocode" name="wocode" value="'.$newcode.'"></input>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Client <span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<select class="select2able" readonly="readonly" id="client" name="client" value="1" tabindex="1" autofocus>';
    $echo.='<option value="1">Eternal Skincare</option>';
    $echo.='</select>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Brand name <span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<select class="select2able" id="newbrand" name="newbrand" onclick="newBrandchecker()" value="0" tabindex="1" autofocus>';
    $echo.='<option value="NULL">Select Brand</option>';
    $brandlist=brand_sql("id,brandname", "");
    while($rowrecord = mysqli_fetch_assoc($brandlist)){
        $echo.='<option value="'.$rowrecord['id'].'">'.$rowrecord['brandname'].'</option>';
    }
    $echo.='</select>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Task/Action <span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';    
    $echo.='<select class="select2able" id="action" name="action" value="0" tabindex="2" autofocus>';
    $echo.='<option value="">--Select Task--</option>';
    $echo.='<option value="1">Add</option>';
    $echo.='<option value="2">update</option>';
    $echo.='</select>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Assign by<span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<input class="form-control" type="text" id="assign" name="assign" value="" placeholder="Asigned by" tabindex ="3" autofocus></input>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Brand Entry <span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<select class="select2able" id="brandentry" name="brandentry" value="0" tabindex="4" autofocus>';
    $echo.='<option value="NULL">--Select--</option>';
    $echo.='<option value="new">New Brand</option>';
    $echo.='<option value="old">Old Brand</option>';
    $echo.='</select>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Currency</label>';
    $echo.='<div class="col-md-9">';
    $echo.='<select class="select2able" id="currency" name="currency" value="0" tabindex="5" autofocus>';
    $echo.='<option value="NULL">Select Currency</option>';
    $echo.='<option value="CAD">CAD</option>';
    $echo.='<option value="USD">USD</option>';
    // $echo.='<option value="IND">IND</option>';
    $echo.='</select>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Priority <span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<select class="select2able" id="priority" name="priority" value="0" tabindex="6" autofocus>';
    $echo.='<option value="2">Select Brand</option>';
    $echo.='<option value="2">Normal</option>';
    $echo.='<option value="3">High</option>';
    $echo.='<option value="4">Urgent</option>';
    $echo.='</select>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Price list <span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<div class="fileupload fileupload-new" data-provides="fileupload">';
    $echo.='<div class="input-group">';
    $echo.='<div class="form-control">';
    $echo.='<i class="fa fa-file fileupload-exists" ></i><span class="fileupload-preview"></span>';
    $echo.='</div>';//form-control
    $echo.='<div class="input-group-btn">';
    $echo.='<a class="btn btn-default fileupload-exists" data-dismiss="fileupload" href="#">Remove</a><span class="btn btn-default btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" id="pricelist" name="pricelist"></span>';
    $echo.='</div>';//input-group-btn
    $echo.='</div>';//input-group
    $echo.='</div>';//fileupload
    $echo.='</div>';//col-md-9
    $echo.='</div>';//form-group


    // $echo.='<div class="form-group">';
    // $echo.='<label class="control-label col-md-2">Notes</label>';
    // $echo.='<div class="col-md-9">';
    // $echo.='<textarea class="form-control" id="notes" name="notes" rows="3"></textarea>';
    // $echo.='</div>';
    // $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Notes</label>';
    $echo.='<div class="col-md-9">';
    $echo.='<div id="texteditor" >';
    $echo.='<textarea class="ckeditor" cols="80" id="notes" name="notes" rows="10">';
    $echo.='</textarea>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';



    $echo.='<button class="btn btn-primary" id="successbtn" name="successbtn" onclick="workordernew()"><i class="fa fa-save"></i>Save</button>';
    $echo.='</form>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    return $echo;
}



function taskEditDetails($wocode)
{
    $echo="";
    
    $firstwocode = $wocode[0];
    if($firstwocode=="R")
    {
        $reserve = reserveworkorder_row("createdat,clientid,notes, brandid", "and wocode='".$wocode."'");
        $assign = 'Jeriel';
        $taskreceive=$reserve["createdat"];
        $currency="N/A";
        $Priority='LOW';
        $brandentry="OLD";
        $action="UPDATE";
        $pricelist="N/A";
        $notes=$reserve["notes"];
        // $brandname = brand_row("brandname, id", "and id=".$reserve["brandid"]);
        $brandname = getBrand($reserve["brandid"]);
    }
    else
    {
        $workorder = workorder_row("orderfrom,clientid,receivedate,currency,priority,brandentry,actiontype,filename,notes,createdby,brandid", "and wocode='".$wocode."'");
        $assign = $workorder["orderfrom"];
        $taskreceive=date("F j, Y",strtotime($workorder["receivedate"]));
        $currency=$workorder["currency"];
        $brandentry=$workorder["brandentry"];
        $pricelist=$workorder["filename"];
        $notes=$workorder["notes"];
        switch($workorder["actiontype"])
        {
            case 1: $action="ADD"; break;
            case 2: $action="UPDATE"; break;
            // default: $priority="ERROR";
        }
        switch($workorder["priority"])
        {
            case 1: $Priority="LOW"; break;
            case 2: $Priority="NORMAL"; break;
            case 3: $Priority="HIGH"; break;
            case 4: $Priority="URGENT"; break;
            // default: $priority="ERROR";
        } 
        // $brandname = brand_row("brandname, id", "and id=".$workorder["brandid"]);
        $brandname = getBrand($workorder["brandid"]);

        $clientname = getClientName($workorder["clientid"]);
    }

    // $editor=editor_row($history["createdby"]);

    $echo.='<div class="page-title">';//</h3><h1>
    // $echo.='<h3>'.$wocode.''.$brandname["brandname"].'</h3>';
    $echo.='<h2>Complete Details</h2>';
    $echo.='</div>';

    $echo.='<div class="row">';
    $echo.='<div class="col-lg-12">';
    $echo.='<div class="widget-container fluid-height">';
    $echo.='<div class="widget-content padded clearfix">';
    // $echo.='<div class="row">';
    $echo.='<h3>Update Work order';
        $echo.='<button class="btn btn-primary pull-right" onclick="workorderupdatejs()" type="button" >Save</button>';
    $echo.='</h3>';

    $echo.='<form action="workorderupdateajax.php" class="form-horizontal" id="workorderupdateajax" method="POST" enctype="multipart/form-data">';

    $echo.='<table class="table table-bordered table-striped">';
    $echo.='<tbody>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Code</b></td>';
    $echo.='<td id="">'.$wocode;
    $echo.='<input type="hidden" id="wocode" name="wocode" value="'.$wocode.'"></input>';
    $echo.='<input type="hidden" id="function" name="function" value="updatesave"></input>';
    $echo.='</td></tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Client Name</b></td>';
    $echo.='<td id="clientname"><input class="form-control" disabled type="text" id="clientname" name="clientname" value="" placeholder="'.$clientname.'" tabindex ="1" autofocus></td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Brand Name</b></td>';
    $echo.='<td>';

        $echo.='<select class="select2able" id="brandname" name="brandname" tabindex="1" autofocus>';
        $echo.='<option value="">'.$brandname.'</option>';
        $resultlist = brand_sql("id, brandname", "");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {
            $echo.='<option value="'.$rowrecord["id"].'">'.$rowrecord["brandname"].'</option>';
        }
        $echo.='</select>';

    $echo.='</td>';
    $echo.='</tr>';
    $echo.='<tr>';
    $echo.='<td width="30%"><b>Assigned by</b></td>';
    // $echo.='<td id="assign">'.$assign.'</td>';
    $echo.='<td><input class="form-control"  type="text" id="assign" name="assign" value="" placeholder="'.$assign.'" tabindex ="1" autofocus></td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Task receive date</b></td>';
    $echo.='<td id="datereceive">'.$taskreceive.'</td>';
    $echo.='</tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Currency</b></td>';
    $echo.='<td id="">';
        $echo.='<select class="select2able" id="currency" name="currency" tabindex="1" autofocus>';
        $echo.='<option value="">'.$currency.'</option>';
        $echo.='<option value="CAD">CAD</option>';
        $echo.='<option value="USD">USD</option>';
        $echo.='<option value="IND">IND</option>';
        $echo.='</select>';
    $echo.='</td></tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Priority</b></td>';
    $echo.='<td>';
        $echo.='<select class="select2able" id="priority" name="priority" tabindex="6" autofocus>';
        $echo.='<option value="">'.$Priority.'</option>';
        $echo.='<option value="2">Normal</option>';
        $echo.='<option value="3">High</option>';
        $echo.='<option value="4">Urgent</option>';
        $echo.='</select>';
    $echo.='</td></tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Brand entry</b></td>';
    $echo.='<td>';
        $echo.='<select class="select2able" id="brandentry" name="brandentry" tabindex="6" autofocus>';
        $echo.='<option value="">'.$brandentry.'</option>';
        $echo.='<option value="New">New</option>';
        $echo.='<option value="Old">Old</option>';
        $echo.='</select>';
    $echo.='</td></tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Action Type</b></td>';
    $echo.='<td>';
        $echo.='<select class="select2able" id="action" name="action" tabindex="6" autofocus>';
        $echo.='<option value="">'.$action.'</option>';
        $echo.='<option value="1">ADD</option>';
        $echo.='<option value="2">Update</option>';
        $echo.='</select>';
    $echo.='</td></tr>';

    $echo.='<tr>';
    $echo.='<td width="30%"><b>Price list</b></td>';
    $echo.='<td id="pricelist"><a href=pricelist/'.$pricelist.' target="_Blank">download</a></td>';
    $echo.='</tr>';

    $echo.='<tr>';

    $echo.='<td id="notes" colspan=2>';

    $noteex = explode("||", $notes);
    $numnote = (sizeof($noteex) - 1);
    // $notep=0;
    for($notep=0; $numnote >= $notep; $notep++)
    {
        $notesarr = explode("==",$noteex[$notep]);
        $newnote = $notesarr[0];
        $notefrom = $notesarr[1];
        $bub = $notesarr[2];
        $datenote = $notesarr[3];

        if($bub=="client")
        {
            $bubclass="";
        }
        else
        {$bubclass="current-user";}

        
        $echo.='<div class=" scrollable chat" style="height: auto;">';
         $echo.='<div class="widget-content">';
           $echo.='<ul>';
               $echo.='<li class="'.$bubclass.'">';
                 $echo.='<div class="bubble">';
                   $echo.='<a class="user-name" href="">'.$notefrom.'</a>';
                   $echo.='<p class="message">';
                    $echo.= $newnote;
                   $echo.='</p>';
                   $echo.='<p class="time">';
                     $echo.='<strong>'.$datenote.' </strong>';
                   $echo.='</p>';
                 $echo.='</div>';
               $echo.='</li>';
            $echo.='</ul>';
    }

    $echo.='</div>';
    // $echo.='<input type="text" id="wocode" name="wocode" value="'.$wocode.'"></input>';
    $echo.='<div class="row">';
    $echo.='<div class="col-xs-5" style="margin-bottom:2px;">';
        $echo.='<select class="form-control" id="bubside" name="bubside" value="0" tabindex="6" autofocus>';
        $echo.='<option value="none">--Select--</option>';
        $echo.='<option value="client">Client</option>';
        $echo.='<option value="editor">Editor</option>';
        $echo.='</select>';
    $echo.='</div>';
    $echo.='<div class="col-xs-5" style="margin-bottom:2px;">';
    $echo.='<input class="form-control" type="text" id="sender" name="sender" value="" placeholder="Name of sender" tabindex ="1" autofocus>';
    $echo.='</div>'; //row
    $echo.='<div class="col-xs-2" style="margin-bottom:2px;">';
    $echo.='<button class="btn btn-primary pull-right" onclick="noteReply();" type="button" ><i class="glyphicon glyphicon-send"></i> Send</button>';
    $echo.='</div>'; //row
    $echo.='</div>'; //row
    $echo.='<textarea class="form-control" id="newnotereply" placeholder="Reply" name="notes" rows="3"></textarea>';

    $echo.='</div>';
    $echo.='</td>';
    $echo.='</tr>';


    $echo.='</tbody>';
    $echo.='</table>';
    $echo.='</form>';



    $echo.='</div>'; //widget-content
    $echo.='</div>'; //widget-container
    $echo.='</div>'; //col-lg-12
    $echo.='</div>'; //row

    return $echo; 
}

function getAddUnlistedWork()
{
    $echo="";
    $newcode = getWorkorderCode();
    $echo.='<div class="page-title">';
    $echo.='<h3>Add Unlisted Work (No Price list)</h3>';
    $echo.='</div>';

    $echo.='<div class="row">';
    $echo.='<div id="basic-modal-content">';
    $echo.='<img src="./images/loading.gif" /> <a>Loading...</a>';
    $echo.='</div>';//row

    $echo.='<div class="row">';
    $echo.='<div class="col-lg-12">';
    $echo.='<div class="" id="divnotif" style="display:none;" >';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';


    $echo.='<div class="row">';
    $echo.='<div class="col-lg-7">';
    $echo.='<div class="widget-container fluid-height clearfix">';
    $echo.='<div class="heading">';
    $echo.='<i class="fa fa-th-list"></i>Task details';
    $echo.='</div>';
    $echo.='<div class="widget-content padded">';

    $echo.='<form action="addunlistedworkajax.php" method="POST" id="unlistedSubmitForm" class="form-horizontal" enctype="multipart/form-data">';


    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Code</label>';
    $echo.='<div class="col-md-9">';
    $echo.='<input class="form-control disabled" readonly="readonly" type="text" id="wocode" name="wocode" value="'.$newcode.'"></input>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Client <span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<select class="select2able" readonly="readonly" id="client" name="client" value="1" tabindex="1" autofocus>';
    $echo.='<option value="1">Eternal Skincare</option>';
    $echo.='</select>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Brand name <span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<select class="select2able" id="newbrand" name="newbrand" onclick="newBrandchecker()" value="0" tabindex="1" autofocus>';
    $echo.='<option value="NULL">Select Brand</option>';
    $brandlist=brand_sql("id,brandname", "");
    while($rowrecord = mysqli_fetch_assoc($brandlist)){
        $echo.='<option value="'.$rowrecord['id'].'">'.$rowrecord['brandname'].'</option>';
    }
    $echo.='</select>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Task/Action <span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';    
    $echo.='<select class="select2able" id="action" name="action" value="0" tabindex="2" autofocus>';
    $echo.='<option value="">--Select Task--</option>';
    $echo.='<option value="1">Add</option>';
    $echo.='<option value="2">update</option>';
    $echo.='</select>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Assign by<span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<input class="form-control" type="text" id="assign" name="assign" value="" placeholder="Asigned by" tabindex ="3" autofocus></input>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Task status</label>';
    $echo.='<div class="col-md-9">';
    $echo.='<select class="form-control" id="status" name="status" value="0" tabindex="3" autofocus>';
    $echo.='<option value="all">-- Select Status --</option>';
        $echo.='<option value="0">For Approval</option>';
        $echo.='<option value="3">Done</option>';
    $echo.='</select>';
    $echo.='</div>'; //form-group
    $echo.='</div>'; //form-group


    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">No of Product(s)</label>';
    $echo.='<div class="col-md-4">'; 
    $echo.='<input class="form-control" type="text" id="productupdate" name="productupdate" value="" placeholder="Updated" tabindex ="2" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="" placeholder="Enter number only">';
    $echo.='</div>'; //form-group
    $echo.='<div class="col-md-4">'; 
    $echo.='<input class="form-control" type="text" id="productadd" name="productadd" value=""  placeholder="Added" tabindex ="2" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="" placeholder="Enter number only">';
    $echo.='</div>'; //form-group
    $echo.='</div>'; //form-group

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Work Report <span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<div class="fileupload fileupload-new" data-provides="fileupload">';
    $echo.='<div class="input-group">';
    $echo.='<div class="form-control">';
    $echo.='<i class="fa fa-file fileupload-exists" ></i><span class="fileupload-preview"></span>';
    $echo.='</div>';//form-control
    $echo.='<div class="input-group-btn">';
    $echo.='<a class="btn btn-default fileupload-exists" data-dismiss="fileupload" href="#">Remove</a><span class="btn btn-default btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" id="workreport" name="workreport"></span>';
    $echo.='</div>';//input-group-btn
    $echo.='</div>';//input-group
    $echo.='</div>';//fileupload
    $echo.='</div>';//col-md-9
    $echo.='</div>';//form-group



    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Notes / Report</label>';
    $echo.='<div class="col-md-9">';
    $echo.='<div id="texteditor" >';
    $echo.='<textarea class="ckeditor" cols="80" id="notes" name="notes" rows="10">';
    $echo.='</textarea>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';



    $echo.='<button class="btn btn-primary" id="unlistedsuccessbtn" name="unlistedsuccessbtn" onclick="unlistednew()"><i class="fa fa-save"></i>Save</button>';
    $echo.='</form>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';

    return $echo;
}

?>