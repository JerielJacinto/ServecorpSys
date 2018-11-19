<?php
function showmenu($access,$num)
{
    $part0 ="";
    $part1 ="";
    $part2 ="";
    $part3 ="";
    $part4 ="";
    $partl ="";
    switch ($num)
    {
        case 0: $a="current"; $part0="current"; break; //home
        case 1: $b="current"; $part1="current"; break; //work oders
        case 2: $c="current"; $part2="current"; break; //settings
        case 3: $d="current"; $part3="current"; break; //users
        case 4: $e="current"; $part4="current"; break; //report
    }
    
    
    
    //MAIN ACCESS
    if($access==0)
    {
        $echo="";
        $echo.='<li>';
        $echo.='<a class="'.$part0.'" href="home.php"><span aria-hidden="true" class="glyphicon glyphicon-home"></span>Home</a>';
        $echo.='</li>';
        
        $echo.='<li class="dropdown"><a class="'.$part1.'" href="menu.php?res=workorder" data-toggle="dropdown" >';
        $echo.='<span aria-hidden="true" class="glyphicon glyphicon-list"></span>Work Order</a>';
        $echo.='<ul class="dropdown-menu">';
            $echo.='<li><a class="'.$part2.'" href="menu.php?res=brands">';
            $echo.='<span aria-hidden="true" class="fa fa-gear"></span>Brands</a>';
            $echo.='</li>';
        $echo.='</ul>';
        $echo.='</li>';
        
        $echo.='<li class="dropdown"><a class="'.$part2.'" href="menu.php?res=brands">';
        $echo.='<span aria-hidden="true" class="fa fa-gear"></span>Brands</a>';
        $echo.='</li>';
        
        $echo.='<li class="dropdown"><a class="'.$part3.'" href="menu.php?res=reports">';
        $echo.='<span aria-hidden="true" class="fa fa-bar-chart-o"></span>Reports</a>';
        $echo.='</li>';
        
        
        $echo.='<li class="dropdown"><a class="'.$part4.'" href="menu.php?res=users">';
        $echo.='<span aria-hidden="true" class="glyphicon glyphicon-user"></span>Users</a>';
        $echo.='</li>';
        $echo.='<li>';
        $echo.='<a class="'.$partl.'" href="processlogout.php"><span aria-hidden="true" class="glyphicon glyphicon-off"></span>Logout</a>';
        $echo.='</li>';
        
        return $echo;
    }
    else if($access==1) //general manager
    {
        $reservenum = reserveworkorder_row("count(id)", " and requeststatus=1");
        $workordernum = workorder_row("count(id)", " and requeststatus=1");
        $prjcatnum = prjcat_row("count(id)", " and requeststatus=1");

        $echo="";
        $echo.='<li>';
        $echo.='<a class="'.$part0.'" href="home.php"><span aria-hidden="true" class="glyphicon glyphicon-home"></span>Home</a>';
        $echo.='</li>';
        
        $echo.='<li class="dropdown"><a class="'.$part1.'" href="#" data-toggle="dropdown" >';
        $echo.='<span aria-hidden="true" class="glyphicon glyphicon-list"></span>Work Order <b class="caret"></b></a>';
        $echo.='<ul class="dropdown-menu">';

            $echo.='<li><a class="'.$part1.'" href="pendingbrands.php">Pending Brands <span class="badge notifications">'.$workordernum["count(id)"].'</span></a>';
            $echo.='</li>';
        
            $echo.='<li><a class="'.$part1.'" href="showAddWorkorder.php">Add task</a>';
            $echo.='</li>';
        
            $echo.='<li><a class="'.$part1.'" href="workhistorybrand.php">Work History (by brand)</a>';
            $echo.='</li>';
        
            $echo.='<li><a class="'.$part1.'" href="workhistory.php">Work History (by date)</a>';
            $echo.='</li>';
        
            $echo.='<li><a class="'.$part1.'" href="proj-category.php">Project Category <span class="badge notifications">'.$prjcatnum["count(id)"].'</span></a>';
            $echo.='</li>';
        
            $echo.='<li><a class="'.$part1.'" href="proj-category-history.php">Category History</a>';
            $echo.='</li>';

        $echo.='</ul>';
        $echo.='</li>';
        
        
        $echo.='<li class="dropdown"><a class="'.$part2.'" href="#" data-toggle="dropdown">';
        $echo.='<span aria-hidden="true" class="fa fa-tag"></span>Brands<b class="caret"></b></a>';
        $echo.='<ul class="dropdown-menu">';

            $echo.='<li><a class="'.$part2.'" href="brands.php">Master List</a>';
            $echo.='</li>';

            $echo.='<li><a class="'.$part2.'" href="brandpage.php">Brand Page</a>';
            $echo.='</li>';

            $echo.='<li><a class="'.$part2.'" href="newbrand.php">Add Brand</a>';
            $echo.='</li>';
        
        $echo.='</ul>';
        $echo.='</li>';
        
        $echo.='<li class="dropdown"><a class="'.$part3.'" href="menu.php?res=reports">';
        $echo.='<span aria-hidden="true" class="fa fa-bar-chart-o"></span>Reports</a>';
        $echo.='</li>';
        
        
        $echo.='<li class="dropdown"><a class="'.$part4.'" href="#" data-toggle="dropdown">';
        $echo.='<span aria-hidden="true" class="glyphicon glyphicon-user"></span>Users<b class="caret"></b></a>';
        $echo.='<ul class="dropdown-menu">';

            $echo.='<li><a class="'.$part4.'" href="users.php">User list</a>';
            $echo.='</li>';

            $echo.='<li><a class="'.$part4.'" href="addnewuser.php">add user</a>';
            $echo.='</li>';
        
        $echo.='</ul>';
        $echo.='</li>';


        $echo.='<li>';
        $echo.='<a class="'.$partl.'" href="processlogout.php"><span aria-hidden="true" class="glyphicon glyphicon-off"></span>Logout</a>';
        $echo.='</li>';
        
        
        return $echo;
    }
    else if($access==2) //Super Visor
    {
        $echo="";
     
        return $echo;
    }

    else if($access==3) //accounting staff
    {
        $reservenum = reserveworkorder_row("count(id)", " and requeststatus=1");
        $workordernum = workorder_row("count(id)", " and requeststatus=1");
        $prjcatnum = prjcat_row("count(id)", " and requeststatus=1");

        $echo="";
        $echo.='<li>';
        $echo.='<a class="'.$part0.'" href="home.php"><span aria-hidden="true" class="glyphicon glyphicon-home"></span>Home</a>';
        $echo.='</li>';
        
        $echo.='<li class="dropdown"><a class="'.$part1.'" href="menu.php?res=workorder" data-toggle="dropdown" >';
        $echo.='<span aria-hidden="true" class="glyphicon glyphicon-list"></span>Work Order <b class="caret"></b></a>';
        $echo.='<ul class="dropdown-menu">';

            $echo.='<li><a class="'.$part2.'" href="pendingbrands.php">Pending Brands <span class="badge notifications">'.$workordernum["count(id)"].'</span></a>';
            $echo.='</li>';
        
            $echo.='<li><a class="'.$part2.'" href="workhistorybrand.php">Work History (by brand)</a>';
            $echo.='</li>';
        
            $echo.='<li><a class="'.$part2.'" href="workhistory.php">Work History (by date)</a>';
            $echo.='</li>';
        
            $echo.='<li><a class="'.$part2.'" href="proj-category.php">Project Category <span class="badge notifications">'.$prjcatnum["count(id)"].'</span></a>';
            $echo.='</li>';
        
            $echo.='<li><a class="'.$part2.'" href="proj-category-history.php">Category History</a>';
            $echo.='</li>';

        $echo.='</ul>';
        $echo.='</li>';
        
        
        $echo.='<li class="dropdown"><a class="'.$part2.'" href="menu.php?res=brands" data-toggle="dropdown">';
        $echo.='<span aria-hidden="true" class="fa fa-tag"></span>Brands<b class="caret"></b></a>';
        $echo.='<ul class="dropdown-menu">';

            $echo.='<li><a class="'.$part2.'" href="brands.php">Master List</a>';
            $echo.='</li>';

            $echo.='<li><a class="'.$part2.'" href="brandpage.php">Brand Page</a>';
            $echo.='</li>';
        
        $echo.='</ul>';
        $echo.='</li>';
        
        $echo.='<li class="dropdown"><a class="'.$part3.'" href="menu.php?res=reports">';
        $echo.='<span aria-hidden="true" class="fa fa-bar-chart-o"></span>Reports</a>';
        $echo.='</li>';
        
        
        $echo.='<li class="dropdown"><a class="'.$part4.'" href="menu.php?res=users">';
        $echo.='<span aria-hidden="true" class="glyphicon glyphicon-user"></span>Users</a>';
        $echo.='</li>';
        $echo.='<li>';
        $echo.='<a class="'.$partl.'" href="processlogout.php"><span aria-hidden="true" class="glyphicon glyphicon-off"></span>Logout</a>';
        $echo.='</li>';
        
        return $echo;
    }

}


?>
