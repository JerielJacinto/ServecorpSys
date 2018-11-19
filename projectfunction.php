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

function showProjCategory()
{
$echo="";

$echo.='<div class="page-title">';
$echo.='<h3>Project Category</h3>';
$echo.='</div>';

$echo.='<div class="row">';
$echo.='<div class="col-lg-12">';
$echo.='<div class="" id="divnotif" style="display:none;" >';
$echo.='</div>';
$echo.='</div>';
$echo.='</div>';

    $echo.='<div class="row">';
    $echo.='<div id="basic-modal-content">';
    $echo.='<img src="./images/loading.gif" /> <a>Loading...</a>';
    $echo.='</div>';//row


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

        $echo.='<div class="col-md-3">';
        $echo.='<div class="input-group">';
        $echo.='<button class="btn btn-primary" onclick="prjcatsearchbrand();" type="button" >Search</button>';
        $echo.='</div>';//input-group
        $echo.='</div>';//col-md-3

        $echo.='</div>';//form group
        $echo.='</form>'; //form
        $echo.='</div>'; //padded clearfix
        $echo.='</div>'; //widget-container
        $echo.='</div>'; //col-lg-12
        $echo.='</div>'; //row



    $echo.='<div class="row">';
    $echo.='<div class="col-lg-12">';
    $echo.='<div class="widget-container fluid-height">';
    $echo.='<div class="widget-content padded clearfix">';


    $echo.='<table class="table table-bordered table-striped" id="dataTable1">'; //class="table table-hover"
    
    $echo.='<thead>';
    $echo.='<th class="hidden"></th>';
    $echo.='<th>ID</th>';
    $echo.='<th width="550">Brand Name</th>';
    $echo.='<th>Status</th>';
    $echo.='<th ></th>';
    $echo.='</thead>';
    
    $echo.='<tbody id="ajaxdata">';
    $resultlist = prjcat_sql("brandid, requeststatus, editor", "and requeststatus<3 LIMIT 10 ");
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
    $echo.='</tbody>';
    
    $echo.='</table>';
    
    $echo.='</div>'; //padded clearfix
    $echo.='</div>'; //widget-container
    $echo.='</div>'; //col-lg-12
    $echo.='</div>'; //row



$echo.='<div id="basic-modal-content">';
$echo.='<img src="./images/loading.gif" /> <a>Loading...</a>';
$echo.='</div>';

////modal finish

$echo.='<div class="modal fade" id="prjcatdone">';
$echo.='<div class="modal-dialog">';
$echo.='<div class="modal-content">';
$echo.='<div class="modal-header">';
$echo.='<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
$echo.='<h4 class="modal-title">Done</h4></div>';

$echo.='<div class="modal-body">';

$echo.='<form class="form-horizontal" action="projcategorysaveajax.php" method="POST" enctype="multipart/form-data" id="doneprjcategory">';

$echo.='<input type="hidden" id="brandid" name="brandid" value=""></input>';
$echo.='<input type="hidden" id="action" name="action" value="done"></input>';


// $echo.='<div class="form-group">';
// $echo.='<label class="control-label col-md-4">No of Product(s) updated</label>';
// $echo.='<div class="col-md-6">';
$echo.='<input class="form-control"  type="hidden" id="productupdate" name="productupdate" value="-1" placeholder="Enter number only" tabindex ="2">';
// $echo.='</div>'; //form-group
// $echo.='</div>'; //form-group


$echo.='<div class="form-group">';
$echo.='<label class="control-label col-md-4"></label>';
$echo.='<div class="col-md-6">';
$echo.='<select class="select2able" id="actiondrop" name="actiondrop" tabindex="1" onclick="categoryDoneKeyup()" autofocus>';
$echo.='<option value="upload">Upload work report</option>';
$echo.='<option value="workorder">from work order</option>';
$echo.='<option value="nochange">No Changes</option>';
$echo.='</select>';
$echo.='</div>';
$echo.='</div>';

$echo.='<div class="form-group" id="divworkreport">';
$echo.='<label class="control-label col-md-4">Work Report<span style="color:red;">*</span></label>';
$echo.='<div class="col-md-6" id="divworkreport">';
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
$echo.='</div>';//col-md-6
$echo.='</div>';//form-group


$echo.='<div class="form-group" id="divworkorder" style="display:none;">';
$echo.='<label class="control-label col-md-4">Work order<span style="color:red;">*</span></label>';
$echo.='<div class="col-md-6">';
    $echo.='<input class="form-control" type="text" id="wocodetext" name="wocodetext" value=""></input>';
// $echo.='<select class="select2able" id="wocodetext" name="wocodetext" tabindex="1" autofocus>';
// $echo.='<option value="">-- Select Code --</option>';
// $wocodelist=workorderhistory_sql("wocode,brandid", "and requeststatus=2 and brandid=".$brandid);
// while($rowrecord = mysqli_fetch_assoc($wocodelist)){
//     $echo.='<option value="'.$rowrecord['wocode'].'">'.$rowrecord['wocode'].' ['.getBrand($rowrecord['brandid']).']</option>';
// }
// $echo.='</select>';
$echo.='</div>';
$echo.='</div>';



$echo.='<div class="form-group pull-right">';
$echo.='<button class="btn btn-success" onclick="prjcategorydone()" id="submitBtn" type="button" /><i class="fa fa-check-square-o"></i>OK</button>';
$echo.='<button class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-ban-circle"></i>Back</button>';
$echo.='</div>'; //form-group

$echo.='</form>';       
$echo.='</div>';
$echo.='<div class="modal-footer">';
$echo.='</div></div></div></div>';

return $echo;
}


function showPrjCatHistory()
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

        $echo.='<div class="col-md-2">';
        $echo.='<div class="input-group col-md-12">';
        $echo.='<select class="select2able" id="editor" value="0" tabindex="1" autofocus>';
        $echo.='<option value="all">All Editor</option>';
        $echo.='<option value="1">Jeriel</option>';
        $echo.='<option value="2">Anne</option>';
        $echo.='</select>';
        $echo.='</div>';//input-group
        $echo.='</div>';//col-md-3

        $echo.='<div class="col-md-3">';
        $echo.='<div class="input-group">';
        $echo.='<button class="btn btn-primary" onclick="categorysearch();" type="button" >Search</button>';
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


?>