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

function userlist(){

    $today = date("m/d/Y");
    $todaydb = date("Y-m-d");
    $filterdate = $today." to ".$today;
    $echo="";
    
    $echo.='<div class="page-title">';
    $echo.='<h3>Users</h3>';
    $echo.='</div>';


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
    $echo.='<th>user id</th>';
    $echo.='<th>Name</th>';
    $echo.='<th>user name</th>';
    // $echo.='<th>password</th>';
    $echo.='<th>restriction</th>';
    $echo.='<th>status</th>';
    $echo.='<th></th>';
    $echo.='</thead>';
    $echo.='<tbody>';

    $resultlist = users_sql("id,username, name, email, restriction, status", "");
    while($rowrecord = mysqli_fetch_assoc($resultlist))
    {
        if($rowrecord["restriction"]=="1"){  $restriction ="Admin Editor"; }
        else{ $restriction ="Editor"; }

        if($rowrecord["status"] == "1"){ $status="Active";}
            elseif($rowrecord["status"] == "2"){$status="Inactive";}

        $echo.='<tr>';
        $echo.='<td>'.$rowrecord["id"].'</td>';
        $echo.='<td>'.$rowrecord["name"].'</td>';
        $echo.='<td>'.$rowrecord["username"].'</td>';
        $echo.='<td>'.$restriction.'</td>';
        $echo.='<td>'.$status.'</td>';
        $echo.='<td>';
        $echo.='<a class="table-actions" data-placement="top" data-toggle="modal" title="Edit user" href="#edituser" id=""
            onclick="showdonemodal(\''.$rowrecord["wocode"].'\', \'pending\')"><i class="fa fa-pencil-square-o"></i></a>';

        $echo.='</td>';
        $echo.='</tr>';
    }
    
    
    $echo.='</tbody>';
    
    $echo.='</table>';
    $echo.='</div>'; //ajaxdata
    
    $echo.='</div>'; //padded clearfix
    $echo.='</div>'; //widget-container
    $echo.='</div>'; //col-lg-12
    $echo.='</div>'; //row


    ////edi modal

    $echo.='<div class="modal fade" id="edituser">';
    $echo.='<div class="modal-dialog">';
    $echo.='<div class="modal-content">';
    $echo.='<div class="modal-header">';
    $echo.='<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    $echo.='<h4 class="modal-title">Edit user</h4></div>';

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

    ////edi modal finish
    
    return $echo; 
}



function showNewuser()
{
    $echo="";
    
    $echo.='<div class="page-title">';
    $echo.='<h3>Add new user</h3>';
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
    $echo.='<i class="fa fa-th-list"></i>Brand Details';
    $echo.='</div>';
    $echo.='<div class="widget-content padded">';
    $echo.='<form action="#" class="form-horizontal">';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Name<span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<input class="form-control" type="text" id="name" value="" placeholder="name" tabindex ="1"autofocus ></input>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Name<span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<input class="form-control" type="text" id="username" value="" placeholder="username" tabindex ="1"autofocus ></input>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">password<span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<input class="form-control" type="password" id="password" value="" placeholder="password" tabindex ="1"autofocus ></input>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<button class="btn btn-primary" id="successbtn" onclick="newuser()"><i class="fa fa-save"></i>Save</button>';
    $echo.='</form>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';

    return $echo;
}

?>