<?php
include_once("./dbconfiggood.php");
include_once("./timezone.php");
include_once("./sqlfunction.php");


function homeNotification($result)
{
    if($result=="added")//uploaded
    {      
        $echo.='<div class="alert alert-success">';
        $echo.='<button class="close" data-dismiss="alert" type="button">&times;</button>';
        $echo.='Upload successful.';
        $echo.='</div>';
    }
    return $echo;
}
function showHomeMenu($empname)
{
    $echo="";
    $echo.='<div class="page-title">';
    $echo.='<h4>Welcome, '.strtoupper($empname).'</h4>';
    $echo.='</div>';

    $echo.='<div class="row">';
    $echo.='<div class="col-lg-12">';
    $echo.='<div class="" id="divnotif" style="display:none;" >';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';

    return $echo;
}        

function showCurrentBrand($empname,$access)
{
    $echo="";
    //$echo.='<div id="txtResult5">';
    // $echo.='<div class="row">';
    $echo.='<div class="col-md-4 col-lg-4 col-sm-6">';
    $echo.='<div class="widget-container scrollable fluid-height">';

    $echo.='<div class="heading">';
    $echo.='<i class="fa fa-tasks"></i>Brand you currently working';
    $echo.='</div>';

    $echo.='<div class="widget-content padded clearfix">';
    $echo.='<table class="table table-striped" id="">'; //class="table table-hover"
        
    $echo.='<thead>';
    $echo.='<th>Code</th>';
    $echo.='<th>Brand</th>';
    $echo.='<th width="20"></th>';
    $echo.='</thead>';

    $result= workorderhistory_sql("wocode,priority,brandid,requeststatus", "and createdby='".$_SESSION["empid"]."' and (requeststatus='2' or requeststatus='0') ");
    while($rowrecord = mysqli_fetch_assoc($result))
    {
        if($rowrecord["requeststatus"]==0) { $status=" (For Approval)"; } 
        else { 
            $status='<a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#donedmodal" id="" onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-check-square-o"></i></a>';
        }
        $brandname = brand_row("brandname, id", "and id=".$rowrecord["brandid"]);
        $echo.='<tr>';
        $echo.='<td>'.$rowrecord["wocode"].'</td>';
        $echo.='<td>'.$brandname["brandname"].$status.'</td>';
        $echo.='<td><a href="#" onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><span class="label label-success">Details</span></a>';
        
        $echo.='</td>';
        $echo.='</tr>';
    }
    $result = prjcat_sql("brandid, requeststatus", "and editor='".$_SESSION["empid"]."' and (requeststatus='2' or requeststatus='0') ");
    while($rowrecord = mysqli_fetch_assoc($result))
    {
        if($rowrecord["requeststatus"]==0) { $status=" (For Approval)"; } else { $status=""; }
        $brandname = brand_row("brandname, id", "and id=".$rowrecord["brandid"]);
        $echo.='<tr>';
        $echo.='<td>Category</td>';
        $echo.='<td>'.$brandname["brandname"].$status.'</td>';
        $echo.='<td>';
        $echo.='</td>';
        $echo.='</tr>';
    }

    $echo.='</tbody>';
    
    $echo.='</table>';

    $echo.='</div>';//widget-content
    $echo.='</div>';//widget-container
    $echo.='</div>';//col-sm-3
    // $echo.='</div>';//row

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

function showToDoList($empid)
{
    $echo="";
    $echo.='<div class="col-lg-4 col-md-4 col-sm-6">';
    $echo.='<div class="widget-container scrollable list task-widget">';
    $echo.='<div class="heading">';
    $echo.='<i class="fa fa-list"></i>Personal Todo list';
    $echo.='</div>';
    $echo.='<div class="widget-content" style="padding-bottom:50px !important;">';
    $echo.='<div id="dataajax">';
    $echo.='<ul>';
    $result = todolist_sql("task, priority, id", " and userid=".$empid." and taskstatus=1");    
    while($rowrecord = mysqli_fetch_assoc($result))
    {
        $echo.='<li>';
        $echo.='<label id="task'.$rowrecord["id"].'"><input class="task-input" id="taskcheck'.$rowrecord["id"].'" onclick="todolistcheck(\''.$rowrecord["id"].'\')" type="checkbox"><span></span>';
        $echo.='<div class="pull-right">';
        $echo.='<a href="#" data-placement="top" data-toggle="modal" title="edit" onclick="todolistdelete(\''.$rowrecord["id"].'\', \''.$empid.'\')"><i class="fa fa-trash-o"></i>&nbsp;&nbsp</a>';
        $echo.='</div>';
        $echo.='<div class="pull-right">';
        $echo.='<a href="#" data-placement="top" data-toggle="modal" title="edit" onclick="todolistedit(\''.$rowrecord["id"].'\', \''.$rowrecord["task"].'\')"><i class="fa fa-edit"></i>&nbsp;&nbsp</a>';
        $echo.='</div>';
        $echo.=$rowrecord["task"].'</label>';
        $echo.='</li>';
    }
    $echo.='</ul>';
    $echo.='</div>';//dataajax
    $echo.='</div>';//widget-content
    $echo.='<div class="chat">';
    $echo.='<div class="post-message">';
    $echo.='<input class="form-control" id="tasktext" placeholder="Write your task" type="text"><input type="submit" onclick="newtodolist(\''.$empid.'\')" value="Send">';
    $echo.='</div>';//post-message
    $echo.='</div>';//chat
    $echo.='</div>';//task-widget
    $echo.='</div>';//col-lg-6

    /////     edit modal

    $echo.='<div class="modal fade" id="todolistedit">';
    $echo.='<div class="modal-dialog">';
    $echo.='<div class="modal-content">';
    $echo.='<div class="modal-header">';
    $echo.='<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    $echo.='<h4 class="modal-title">Edit personal task</h4></div>';

    $echo.='<div class="modal-body">';
    $echo.='<form action="#" class="form-horizontal">';


    $echo.='<input type="hidden" id="idhidden" value=""></input>';


    $echo.='<div class="form-group">';
    // $echo.='<label class="control-label col-md-4">No of Product(s) added</label>';
    $echo.='<div class="col-md-12">';
    $echo.='<input class="form-control" type="text" id="edittask" name="edittask" value="" tabindex ="1" autofocus>';
    $echo.='</div>'; //form-group
    $echo.='</div>'; //form-group


    $echo.='<div class="form-group pull-right">';
    $echo.='<button class="btn btn-primary"  onclick="todolisteditsave(\''.$empid.'\')"><i class="fa fa-save"></i>Update</button>';
    $echo.='<button class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-ban-circle"></i>Cance</button>';
    $echo.='</div>'; //form-group

    $echo.='</form></p>';       
    $echo.='</div>';
    $echo.='<div class="modal-footer">';
    $echo.='</div></div></div></div>';
    return $echo;
}

function showweeklyproducts($empname,$access)
{
    $echo = "";
    $monday = date('Y-m-d',strtotime('monday this week'));
    $friday = date('Y-m-d',strtotime('friday this week'));
    $saturday = date('Y-m-d',strtotime('saturday this week'));
// $echo.='<div class="row">';
       
    $echo.='<div class="col-lg-4 col-md-4 col-sm-6">';
  $echo.='<div class="widget-container fluid-height">';
    $echo.='<div class="heading">';
    $echo.='<i class="fa fa-tasks"></i>Number of products this week';
    $echo.='</div>';
    //<!-- Table -->
    $echo.='<table class="table table-filters">';
        $echo.='<tr>';
          $echo.='<th class="filter-category blue">';
            $echo.='<div class="arrow-left"></div>';
            $echo.='Code';
          $echo.='</th>';
          $echo.='<th width="250">Brand</th>';
          $echo.='<th>Total Product</th>';
          $echo.='<th with=></th>';
        $echo.='</tr>';
      $echo.='<tbody>';
    $result= workorderhistory_sql("wocode,priority,brandid,productadd,productupdate,requeststatus", "and createdby='".$_SESSION["empid"]."' and (requeststatus='3' or requeststatus='0') and dateended>='".$monday."' and dateended<='".$saturday."' ");
    while ($rowrecord = mysqli_fetch_assoc($result))
    {
        if($rowrecord["requeststatus"]==0) { $status=" (For Approval)"; } else { $status=""; }
        $total = $rowrecord["productupdate"]+$rowrecord["productadd"];
        $brandname = brand_row("brandname, id", "and id=".$rowrecord["brandid"]);
        $echo.='<tr>';
          $echo.='<td class="filter-category blue">';
            $echo.='<div class="arrow-left"></div>';
            $echo.=$rowrecord["wocode"];
          $echo.='</td>';
          $echo.='<td>'.$brandname["brandname"].$status.'</td>';
          $echo.='<td>'.$total.'</td>';
        $echo.='<td><a href="#" onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><span class="label label-success">Details</span></a></td>';
        $echo.='</tr>';
    }   

    $result= prjcat_sql("brandid,requeststatus, updated", "and editor='".$_SESSION["empid"]."' and (requeststatus='3' or requeststatus='0') and dateended>='".$monday."' and dateended<='".$friday."' ");
    while ($rowrecord = mysqli_fetch_assoc($result))
    {
        if($rowrecord["requeststatus"]==0) { $status=" (For Approval)"; } else { $status=""; }
        $total = $rowrecord["updated"];
        $brandname = brand_row("brandname, id", "and id=".$rowrecord["brandid"]);
        $echo.='<tr>';
          $echo.='<td class="filter-category green">';
            $echo.='<div class="arrow-left"></div>';
            $echo.="Category";
          $echo.='</td>';
          $echo.='<td>'.$brandname["brandname"].$status.'</td>';
          $echo.='<td>'.$total.'</td>';
        $echo.='<td></td>';
        $echo.='</tr>';
    } 

      $echo.='</tbody>';
    $echo.='</table>';
  $echo.='</div>';
$echo.='</div>';
// $echo.='</div>';//row
return $echo;
}

function changepassword($empname)
{
    $echo="";
    
    $echo.='<div class="page-title">';
    $echo.='<h4>Welcome, '.strtoupper($empname).'</h4>';
    $echo.='</div>';
        
    $echo.='<div class="row pricing-table">';
    $echo.='<input id="jc_uname"  value="'.$empname.'" type="hidden">';
    $echo.='<div class="col-sm-12">';
    $echo.='<div id="txtResult5">';
    $echo.='<div class="widget-container fluid-height list">';
    $echo.='<div class="widget-content padded">';
    $echo.='<form class="form-horizontal" >';
    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-3">Current Password</label>';
    $echo.='<div class="col-md-8"   >';
    $echo.='<input class="form-control" id="oldpass"  type="password">';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-3">New Password</label>';
    $echo.='<div class="col-md-8"   >';
    $echo.='<input class="form-control" id="newpass"  type="password">';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-3">Confirm New Password</label>';
    $echo.='<div class="col-md-8"   >';
    $echo.='<input class="form-control" id="newpass2"  type="password">';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</form>';
    
    $echo.='<button class="btn btn-lg btn-block btn-warning" id="savePassword" onclick="saveNewPassword();" >Save New Password</button>';
    
    
    $echo.='</div>';//widget
    $echo.='</div>';//widget
    
    $echo.='</div>';//txtResult5 close
    
    $echo.='</div>';//col 9
    
    
    $echo.='</div>';//close pricing table
    
    
    
    $echo.='<div id="basic-modal-content">';
    $echo.='<img src="./images/loading.gif" /> <a>Loading...</a>';
    $echo.='</div>';
   
    return $echo;
}

function showImageRename($empname,$access)
{
    $echo="";
    //$echo.='<div id="txtResult5">';
    // $echo.='<div class="row">';
    $echo.='<div class="col-md-4 col-lg-4 col-sm-6">';
    $echo.='<div class="widget-container fluid-height">';

    $echo.='<div class="heading">';
    $echo.='<i class="fa fa-tasks"></i>SEO rename';
    $echo.='</div>';

    $echo.='<div class="widget-content padded clearfix">';
    

    $echo.='<div class="col-md-12">';
    $echo.='<input class="form-control" type="text" id="prodname" name="prodname" value="" placeholder="Enter product name" tabindex ="1" autofocus>';
    $echo.='<input class="form-control" type="text" id="newprodname" name="newprodname" value="" placeholder="New SEO product name" tabindex ="1" autofocus>';
    $echo.='<button class="btn btn-lg btn-block btn-sm btn-primary" onclick="seorenamejs()" >convert</button>';
    $echo.='</div>'; //form-group


    $echo.='</div>';//widget-content
    $echo.='</div>';//widget-container
    $echo.='</div>';//col-sm-3
    // $echo.='</div>';//row
  
      
    return $echo;
}

?>