<?php
include_once("./dbconfiggood.php");
include_once("./timezone.php");
include_once("./sqlfunction.php");


function homeNotification($result)
{
    if($result=="uploaded")//uploaded
    {      
        $echo.='<div class="alert alert-success">';
        $echo.='<button class="close" data-dismiss="alert" type="button">&times;</button>';
        $echo.='Upload successful.';
        $echo.='</div>';
    }
    return $echo;
}

function showBrandList()
{
    $echo="";
    
    $echo.='<div class="page-title">';
    $echo.='<h3>Brands master list</h3>';
    $echo.='</div>';

    //////////// TABLE
        
    $echo.='<div class="row">';

    
    $echo.='<div class="col-lg-12">';
    $echo.='<ul class="breadcrumb">';
    $echo.='<li>';
    $echo.='<a href="#"></a><i class="fa fa-home"></i>';
    $echo.='</li>';
    $echo.='<li class="active">';
    $echo.='Brands';
    $echo.='</li>';
    $echo.='<li class="active">';
    $echo.=$brand;
    $echo.='</li>';
    $echo.='</ul>';
    $echo.='</div>';

        $echo.='<div class="col-lg-12">';
        $echo.='<div class="widget-container fluid-height">';
        $echo.='<div class="widget-content padded clearfix">';
        $echo.='<table class="table table-bordered table-striped" class="table table-hover" id="dataTable1">'; //class="table table-hover" id='dataTable1'
        
        $echo.='<thead>';
        $echo.='<th width="40">Code</th>';
        $echo.='<th width="550">Brand Name</th>';
        $echo.='<th width="200">last update </th>';
        $echo.='<th width="200">last update (W/O Price list)</th>';
        $echo.='<th>Currency</th>';
        $echo.='<th with="50"></th>';
        $echo.='</thead>';
        
        $echo.='<tbody>';
        $resultlist = brand_sql("id, brandname, currency", "");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {   

            $lastupdatePricelist = lastupdatePricelist_row($rowrecord['id']);
            $lastupdateWebsite = lastupdateWebsite_row($rowrecord['id']);

            $echo.='<tr>';
            $echo.='<td>'.$rowrecord["id"].'</td>';
            $echo.='<td>'.$rowrecord["brandname"].'</td>';
            $echo.='<td>'.$lastupdatePricelist["dateended"].'</td>';
            $echo.='<td>'.$lastupdateWebsite["dateended"].'</td>';
            $echo.='<td>'.$rowrecord["currency"].'</td>';
            $echo.='<td>';
            $echo.='<div class="action-buttons">';
            $echo.='<a class="table-actions" title="Go!" href="brands.php?show='.$rowrecord["id"].'">
            <i class="fa fa-share"></i></a>';
            $echo.='</div">';
            $echo.='</td>';
            $echo.='</tr>';
        }
        $echo.='</tbody>';
        
        $echo.='</table>';
        
        $echo.='</div>'; //padded clearfix
        $echo.='</div>'; //widget-container
        $echo.='</div>'; //col-lg-12
        $echo.='</div>'; //row

        // $echo.='<script>';
        // $echo.='$(document).ready(function() {
        //     $("#123").DataTable( {
        //         "bPaginate": false
        //     } );
        // } );';
        // $echo.='</script>';
    
    return $echo;
}

function showNewBrand()
{
    $echo="";
    
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
    $echo.='<i class="fa fa-th-list"></i>Brand Details';
    $echo.='</div>';
    $echo.='<div class="widget-content padded">';
    $echo.='<form action="#" class="form-horizontal">';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Brand name<span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<input class="form-control" type="text" id="brandname" value="" placeholder="Brand here" tabindex ="1"autofocus ></input>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Currency<span style="color:red;">*</span></label>';
    $echo.='<div class="col-md-9">';
    $echo.='<select class="select2able" id="currency" value="0" tabindex="2" autofocus>';
    $echo.='<option value="NULL">Select Currency</option>';
    $echo.='<option value="CAD">CAD</option>';
    $echo.='<option value="USD">USD</option>';
    $echo.='<option value="IND">IND</option>';
    $echo.='</select>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='<button class="btn btn-primary" id="successbtn" onclick="newbrand()"><i class="fa fa-save"></i>Save</button>';
    $echo.='</form>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';

    return $echo;
}


function showBrandDetails($brandid)
{
    $echo="";

    $brand = getBrand($brandid);
    $brandnotes = brand_notes_sql('note, noteby,createdat', ' and brandid = '.$brandid);
    $echo.='<div class="page-title">';
    $echo.='<h3>Brand Details</h3>';
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

    $echo.='<div class="col-lg-12">';
    $echo.='<ul class="breadcrumb">';
    $echo.='<li>';
    $echo.='<a href="#"></a><i class="fa fa-home"></i>';
    $echo.='</li>';
    $echo.='<li>';
    $echo.='<a href="brands.php">Brand master list</a>';
    $echo.='</li>';
    $echo.='<li class="active">';
    $echo.=$brand;
    $echo.='</li>';
    $echo.='</ul>';
    $echo.='</div>';

    $echo.='<div class="col-md-6">';
    $echo.='<div class="well">';
    $echo.='<h3>'.$brand.'</h3>';
    $echo.='<p>';
    $echo.='Brand ID: '.$brandid.'<br>';
    // $echo.='Currency: '.$brandid.'<br>';
    $echo.='</p>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';


    $echo.='<div class="row">';
    $echo.='<div class="col-lg-6">';
    $echo.='<div class="widget-container list ">';
    $echo.='<div class="heading">';
    $echo.='<i class="fa fa-gears"></i>Notes / Brand Instruction <a class="table-actions" data-placement="top" data-toggle="modal" title="Done" href="#addnotes" id=""
            onclick="showmodaladdnotes(\''.$brandid.'\')"><i class="fa fa-plus pull-right"></i></a>';
    $echo.='</div>';
    $echo.='<div class="widget-content">';
    $echo.='<ul>';

    $brandnotes = brand_notes_sql('note, noteby', ' and brandid = '.$brandid);
    while($rowrecord = mysqli_fetch_assoc($brandnotes))
    { 
        $echo.='<li>';
        $echo.='<div class="reviewer-info">';
        // $echo.='noted by: '.$rowrecord['noteby'].'<em> '.$rowrecord['createdat'].'</em>';
        $echo.='</div><br>';

        $echo.='<div class="review-text">';
        $echo.='<p>';
        $echo.=$rowrecord['note'];
        $echo.='</p>';
        $echo.='</div>';
        $echo.='</li>';
    }


    $echo.='</ul>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';


    ////modal add notes

    $echo.='<div class="modal fade" id="addnotes">';
    $echo.='<div class="modal-dialog">';
    $echo.='<div class="modal-content">';
    $echo.='<div class="modal-header">';
    $echo.='<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    $echo.='<h4 class="modal-title">Add Notes</h4></div>';

    $echo.='<div class="modal-body">';
    // $echo.='<form action="#" class="form-horizontal">';

    $echo.='<input type="hidden" id="idhidden" value=""></input>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Noted by</label>';
    $echo.='<div class="col-md-10">';
    $echo.='<input class="form-control" type="text" id="noteby" name="noteby" autofocus></input>';
    $echo.='</div>'; //col-md-8
    $echo.='</div> <br>'; //form-group


    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Note</label>';
    $echo.='<div class="col-md-10">';
    $echo.= '<textarea class="" cols="60" id="notes" name="notes" rows="10">';
    $echo.= '</textarea>';
    $echo.='</div>'; //col-md-8
    $echo.='</div> <br>'; //form-group

    $echo.='<div class="form-group pull-right">';
    $echo.='<button class="btn btn-primary" onclick="addnotessave()"><i class="fa fa-save"></i>Save </button>';
    $echo.='<button class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-ban-circle"></i>Back</button>';
    $echo.='</div>'; //form-group

    // $echo.='</form>';       
    $echo.='</div>';
    $echo.='<div class="modal-footer">';
    $echo.='</div></div></div></div>';



    /////history
    $echo.='<div class="col-lg-6">';
    $echo.='<div class="widget-container list ">';
    $echo.='<div class="heading">';
    $echo.='<i class="fa fa-table"></i>Work History';
    $echo.='</div>';
    $echo.='<div class="widget-content padded clearfix">';
    $echo.='<table class="table">';
    $echo.='<thead>';
    $echo.='<th>Wocode</th>';
    $echo.='<th>task type</th>';
    $echo.='<th>Price list</th>';  
    $echo.='<th>Date</th>';
    $echo.='<th>Editor</th>';            
    $echo.='<th width="4%"></th>';
    $echo.='</thead>';
    $echo.='<tbody>';
    $workorderhistory = workorderhistory_sql('wocode,actiontype,updatetype, dateended,createdby', ' and brandid = '.$brandid);
    while($rowrecord = mysqli_fetch_assoc($workorderhistory))
    { 

        if($rowrecord['actiontype']==1){ $tasktype="Add";} else{ $tasktype="Update";}
        if($rowrecord['updatetype']==1){ $pricelist="Yes";} else{ $pricelist="NO";}

        $echo.='<tr>';
        $echo.='<td>'.$rowrecord['wocode'].'</td>';
        $echo.='<td>'.$tasktype.'</td>';
        $echo.='<td>'.$pricelist.'</td>';
        $echo.='<td>'.$rowrecord['dateended'].'</td>';
        $echo.='<td>'.editor_row($rowrecord['createdby']).'</td>';
        $echo.='<td >';

        $echo.='<a class="table-actions" href="javascript:void(0);" data-placement="top" title="view" id=""
            onclick="showHistoryDetailsjs(\''.$rowrecord["wocode"].'\')"><i class="fa fa-eye"></i></a>'; 

        $echo.='</td>';
        $echo.='</tr>';
    }


    $echo.='</tbody>';
    $echo.='</table>';




    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    $echo.='</div>';
    return $echo;
}

function showBrandPageList()
{
    $echo="";
    
    $echo.='<div class="page-title">';
    $echo.='<h3>Band Page</h3>';
    $echo.='</div>';

     //////////// TABLE
        
    $echo.='<div class="row">';

    //breadcrumb
    $echo.='<div class="col-lg-12">';
    $echo.='<ul class="breadcrumb">';
    $echo.='<li>';
    $echo.='<a href="#"></a><i class="fa fa-home"></i>';
    $echo.='</li>';
    $echo.='<li class="active">';
    $echo.='Brand Page';
    $echo.='</li>';
    $echo.='</ul>';
    $echo.='</div>';

    $echo.='</div>';


    $echo.='<div class="row">';
    $echo.='<div class="col-sm-6 col-lg-3">';
    $echo.='<div class="alert alert-danger" >';
    $echo.='Brands with OLD brand page <a href="#" onclick=""><i class="fa fa-arrow-circle-right"></i></a>';
    $echo.='<div class="badge pull-right">106</div>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="col-sm-6 col-lg-3">';;
    $echo.='<div class="alert alert-warning" >';
    $echo.='incomplete requirements <a href="#" onclick="prjstatusjs(\'incomplete\')"><i class="fa fa-arrow-circle-right"></i></a>';
    $echo.='<div class="badge pull-right">106</div>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<div class="col-sm-6 col-lg-3">';;
    $echo.='<div class="alert alert-info" >';
    $echo.='Ready to apply <a href="#" onclick="prjstatusjs(\'ready\')"><i class="fa fa-arrow-circle-right"></i></a>';
    $echo.='<div class="badge pull-right">106</div>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='<a href="javascript:void(0)"><div class="col-sm-6 col-lg-3">';;
    $echo.='<div class="alert alert-success" >';
    $echo.='Done <a href="#" onclick="prjstatusjs(\'done\')" ><i class="fa fa-arrow-circle-right"></i></a>';
    $echo.='<div class="badge pull-right">106</div>';
    $echo.='</div>';
    $echo.='</div></a>';

    $echo.='</div>';
    $echo.='</div>';

     $echo.='<div id="basic-modal-content">';
    $echo.='<img src="./images/loading.gif" /> <a>Loading...</a>';
    $echo.='</div>';


        $echo.='<div class="col-lg-12">';
        $echo.='<div class="widget-container fluid-height">';
        $echo.='<div class="widget-content padded clearfix">';
        $echo.='<table class="table table-bordered table-striped" class="table table-hover" id="dataTable1">'; //class="table table-hover" id='dataTable1'
        
        $echo.='<thead>';
        // $echo.='<th width="40">Code</th>';
        $echo.='<th width="30%">Brand Name</th>';
        $echo.='<th width="25%"">Done</th>';
        $echo.='<th width="25%"">unfinish</th>';
        $echo.='<th width="15%"">status</th>';
        $echo.='<th width="5%"></th>';
        $echo.='</thead>';
        
        $echo.='<tbody id="dataajax">';
        $resultlist = brandpage_sql("id, brandid, prjstatus, requirement", " and prjstatus = 1");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {   
            $done = '';
            $unfinish = '';

            if($rowrecord['prjstatus'] == 2){
                $prjstatus = 'Requirements complete';
            }
            else if($rowrecord['prjstatus'] == 1){
                $prjstatus = 'Incomplete';
            }

            $brand = getBrand($rowrecord['brandid']);
            
            $resultlist1 = brandpagemeta_sql('req_key, req_status', ' and brandpageid = '.$rowrecord['id'].' and brandid = '.$rowrecord['brandid']);
            while($meta = mysqli_fetch_assoc($resultlist1))
            {
                if($meta['req_status'] == 2)
                {
                    $done = $done . '• '.$meta['req_key'].'<br>';
                }
                else
                {
                    $unfinish = $unfinish .'• '.$meta['req_key'] .'<br>';
                }
            }

            $echo.='<tr>';
            $echo.='<td>'.$brand.'</td>';
            $echo.='<td>'.$done.'</td>';
            $echo.='<td>'.$unfinish.'</td>';
            $echo.='<td>'.$prjstatus.'</td>';
            $echo.='<td>';
            $echo.='<div class="action-buttons">';
            $echo.='<a class="table-actions" title="Go!" href="brandpage.php?show='.$rowrecord["id"].'">
            <i class="fa fa-share"></i></a>';
            $echo.='</div">';
            $echo.='</td>';
            $echo.='</tr>';
        }
        $echo.='</tbody>';
        
        $echo.='</table>';
        
        $echo.='</div>'; //padded clearfix
        $echo.='</div>'; //widget-container
        $echo.='</div>'; //col-lg-12
        $echo.='</div>'; //row

        // $echo.='<script>';
        // $echo.='$(document).ready(function() {
        //     $("#123").DataTable( {
        //         "bPaginate": false
        //     } );
        // } );';
        // $echo.='</script>';
    
    return $echo;
}

function showBrandPageDetails($brandpageid)
{
    $echo="";

    $brandid = brandpage_row('brandid, prjstatus', ' and id = '.$brandpageid);
    $brand = getBrand($brandid['brandid']);


    $echo.='<div class="page-title">';
    $echo.='<h3>Brand Page Detail ('.$brand.')</h3>';
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

    $echo.='<div class="col-lg-12">';
    $echo.='<ul class="breadcrumb">';
    $echo.='<li>';
    $echo.='<a href="#"></a><i class="fa fa-home"></i>';
    $echo.='</li>';
    $echo.='<li>';
    $echo.='<a href="brandpage.php">Brand page list</a>';
    $echo.='</li>';
    $echo.='<li class="active">';
    $echo.=$brand;
    $echo.='</li>';
    $echo.='</ul>';
    $echo.='</div>';

    $echo.='<div class="col-md-12">';
    $echo.='<div class="well">';
    $echo.='<h3>'.$brand.'</h3>';
    $echo.='<p>';
    if($brandid['prjstatus'] == '1'){
        $echo.='Done: ';

        $rec = brandpagemeta_sql('req_key, req_status', ' and brandpageid = '.$brandpageid);
        while($bpmeta = mysqli_fetch_assoc($rec))
        {
            if($bpmeta['req_status'] ==2){
                $echo.='<span class="label label-default">'.$bpmeta['req_key'].'</span>';
            }
        }
        $echo.='</p><p>';
        $echo.='Unfinish: ';

        $rec = brandpagemeta_sql('req_key, req_status', ' and brandpageid = '.$brandpageid);
        while($bpmeta2 = mysqli_fetch_assoc($rec))
        {
            if($bpmeta2['req_status'] == 1){
                $echo.='<span class="label label-default">'.$bpmeta2['req_key'].'</span>';
            }
        }
        $echo.='<br>';
    }
    else if($brandid['prjstatus'] == '2'){
        $echo.='<button class="btn btn-lg btn-block btn-primary" ><i class="fa fa-check"></i>Done</button>';
    }

    $echo.='</p>';
    $echo.='</div>';
    $echo.='</div>';

    $echo.='</div>'; //row

    /////
    $echo.='<div class="row">';

    $rec = brandpagemeta_sql('req_key, req_value, req_status,content,id,content', ' and brandpageid = '.$brandpageid);
    while($bpmeta2 = mysqli_fetch_assoc($rec))
    {
        if($bpmeta2['req_status'] == 2){ $status='fa-check'; } else{ $status='fa-times'; }

        if($bpmeta2['content'] == 'text'){ $content=$bpmeta2['req_value']; } else{ $content='<img class="img-responsive" src="brandpage/'.$bpmeta2['req_value'].'">'; }

        if($bpmeta2['req_value'] == ''){  }

        $echo.='<div class="col-md-4 col-lg-4 col-sm-6" style="padding-top:20px !important;">';
        $echo.='<div class="widget-container scrollable">';
        $echo.='<div class="heading">';
        $echo.='<i class="fa fa-list"></i>'.$bpmeta2['req_key'];
        $echo.='<a href="#" onclick="BPReqEdit(\''.$bpmeta2["id"].'\', \''.$bpmeta2["content"].'\', \''.$bpmeta2["req_key"].'\')" data-placement="top" data-toggle="modal" title="Edit"><i class="fa fa-edit pull-right"></i></a>';
        $echo.='<a><i class="fa '.$status.' pull-right"></i></a>';
        $echo.='</div>';//heading
        $echo.='<div class="widget-content padded">';

        $echo.='<p class="content">';
        $echo.= $content;
        $echo.='</p>';

        $echo.='</div>';
        $echo.='</div>';


        $echo.='</div>';
    }


    $echo.='</div>'; //row


    ////Add Text
    $echo.='<div class="modal fade" id="AddTextContent">';
    $echo.='<div class="modal-dialog">';
    $echo.='<div class="modal-content">';
    $echo.='<div class="modal-header">';
    $echo.='<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    $echo.='<h4 class="modal-title" id="modal-title">Add Notes</h4></div>';

    $echo.='<div class="modal-body">';
    // $echo.='<form action="#" class="form-horizontal">';

    $echo.='<input type="hidden" id="idhidden" value=""></input>';

    $echo.='<div class="form-group">';
    $echo.='<label class="control-label col-md-2">Content</label>';
    $echo.='<div class="col-md-10">';
    $echo.= '<textarea class="form-control" cols="60" id="textContent" name="textContent" rows="10">';
    $echo.= '</textarea>';
    $echo.='</div>'; //col-md-8
    $echo.='</div> <br>'; //form-group

    $echo.='<div class="form-group pull-right">';
    $echo.='<button class="btn btn-primary" onclick="BPReqsave()"><i class="fa fa-save"></i>Save </button>';
    $echo.='<button class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-ban-circle"></i>Back</button>';
    $echo.='</div>'; //form-group

    // $echo.='</form>';       
    $echo.='</div>';
    $echo.='<div class="modal-footer">';
    $echo.='</div></div></div></div>';



    ////Add Pic
    $echo.='<div class="modal fade" id="AddPicContent">';
    $echo.='<div class="modal-dialog">';
    $echo.='<div class="modal-content">';
    $echo.='<div class="modal-header">';
    $echo.='<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    $echo.='<h4 class="modal-title" id="modal-titleP">Add Notes</h4></div>';

    $echo.='<div class="modal-body">';
    $echo.='<form action="brandpagereqsaveajax.php" class="form-horizontal" method="POST" enctype="multipart/form-data" id="">';

    $echo.='<input type="text" id="idhiddenP" name="idhiddenP" value=""></input>';

    $echo.='<div class="col-md-12">
      <div class="fileupload fileupload-new" data-provides="fileupload">
        <div class="fileupload-new img-thumbnail" style="width: 200px; height: 150px;">
          <img src="brandpage/no-image.jpg">
        </div>
        <div class="fileupload-preview fileupload-exists img-thumbnail" style="width: 200px; max-height: 150px"></div>
        <div>
          <span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" id="picture" name="picture"></span><a class="btn btn-default fileupload-exists" data-dismiss="fileupload" href="#">Remove</a>
        </div>
      </div>
    </div>';

    $echo.='<div class="form-group pull-right">';
    $echo.='<button class="btn btn-primary" onclick="BPReqsave()"><i class="fa fa-save"></i>Save </button>';
    $echo.='<button class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-ban-circle"></i>Back</button>';
    $echo.='</div>'; //form-group

    $echo.='</form>';       
    $echo.='</div>';
    $echo.='<div class="modal-footer">';
    $echo.='</div></div></div></div>';

    return $echo;
    
}


?>