<?php
include_once("./sqlfunction.php");
/*
Access code
0   -   Main
1   -   General Manager
2   -   d
3   -   product editor
*/

function mainmenu_workorder($access)
{
    $echo="";

    if($access==0) //main
    {
        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="changepassword.php">Change Password</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3
        $echo.='</div>';//close pricing table
    } 
   
    else if($access==1) //editor manager
    {
        $reservenum = reserveworkorder_row("count(id)", " and requeststatus=1");
        $workordernum = workorder_row("count(id)", " and requeststatus=1");
        $prjcatnum = prjcat_row("count(id)", " and requeststatus=1");

        $echo.='<div class="row">';
        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="pendingbrands.php">Pending Brands <span class="badge">'.$workordernum["count(id)"].'</span> </a>';
        $echo.='<a class="btn btn-block btn-success" href="showAddWorkorder.php">Add task</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3

        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="reservependingbrands.php">Reserve Brands  <span class="badge">'.$reservenum["count(id)"].'</span></a>';
        $echo.='<a class="btn btn-block btn-success" href="reservependingbrandsnew.php">Add Reserve Brands</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3v

        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="workhistorybrand.php">Work History (by brand)</a>';
        $echo.='<a class="btn btn-block btn-info" href="workhistory.php">Work History (by date)</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3v

        $echo.='</div>';//row

        $echo.='<div class="row">';
        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="proj-category.php">Project Category<span class="badge">'.$prjcatnum["count(id)"].'</span> </a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3

        $echo.='<div class="col-sm-3 col-sm-offset-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="proj-category-history.php">Category History</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3

        $echo.='</div>';//row
    }
    
    else if($access==2) //supervisor
    {
        $reservenum = reserveworkorder_row("count(id)", " and requeststatus=1");
        $workordernum = workorder_row("count(id)", " and requeststatus=1");


        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="pendingbrands.php">Pending Brands <span class="badge">'.$workordernum["count(id)"].'</span> </a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3

        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="reservependingbrands.php">Reserve Brands  <span class="badge">'.$reservenum["count(id)"].'</span></a>';
        $echo.='<a class="btn btn-block btn-success" href="reservependingbrandsnew.php">Add Reserve Brands</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3v

        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="workhistorybrand.php">Work History (by brand)</a>';
        $echo.='<a class="btn btn-block btn-info" href="workhistory.php">Work History (by date)</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3v

    }
    else if($access==3) // asst supervisor
    {
        $reservenum = reserveworkorder_row("count(id)", " and requeststatus=1");
        $workordernum = workorder_row("count(id)", " and requeststatus=1");


        $echo.='<div class="row">';
        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="pendingbrands.php">Pending Brands <span class="badge">'.$workordernum["count(id)"].'</span> </a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3

        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="reservependingbrands.php">Reserve Brands  <span class="badge">'.$reservenum["count(id)"].'</span></a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3v

        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="workhistorybrand.php">Work History (by brand)</a>';
        $echo.='<a class="btn btn-block btn-info" href="workhistory.php">Work History (by date)</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3v
        $echo.='</div>';//row
        


        $echo.='<div class="row">';
        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="proj-category.php">Project Category<span class="badge">'.$prjcatnum["count(id)"].'</span> </a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3


        $echo.='<div class="col-sm-3 col-sm-offset-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="proj-category-history.php">Category History</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3


        $echo.='</div>';//row
    }
    return $echo;
}
    
function mainmenu_settings($access)
{
    $echo="";

    if($access==0)
    {
        
    
        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="pendingbrands.php">Pending Brands</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3

        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="workhistory.php">Work History</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3v
        
        
    } 
    else if($access==1) //genereal manager
    {
        
    
        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="brands.php">Brand List</a>';
        $echo.='<a class="btn btn-block btn-success" href="newbrand.php">Add Brand</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3
    }
    
    else if($access==2) //supervisor
    {
       
    
        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="pendingbrands.php">Pending Brands</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3

        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="workhistory.php">Work History</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3v
    }
    
    else if($access==3) //assistant supervisor
    {
    
        $echo.='<div class="col-sm-3">';
        $echo.='<div class="widget-container fluid-height list">';
        $echo.='<div class="widget-content padded text-left">';
        $echo.='<a class="btn btn-block btn-info" href="brands.php">Brands</a>';
        $echo.='</div>';//widget-content
        $echo.='</div>';//widget-container
        $echo.='</div>';//col-sm-3
        
        
    }
    return $echo;
}
?>