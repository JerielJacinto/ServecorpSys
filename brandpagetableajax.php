<?php
include_once("./sqlfunction.php");
include_once("./importfiles.php");
session_start();


switch($_POST['prjstatus']){
    case 'incomplete': echo incomplete(); break;
    case 'ready': echo ready(); break;
    case 'done': echo done(); break;
}


function incomplete(){
    $echo="";
    // $echo.='<tbody>';
        $resultlist = brandpage_sql("id, brandid, prjstatus, requirement", " and prjstatus = 1");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {   

            $prjstatus = 'Incomplete';
        
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
        // $echo.='</tbody>';

    return $echo;
}

function old(){
    $echo="";
    // $echo.='<tbody>';
        $resultlist = brandpage_sql("id, brandid, prjstatus, requirement", " and prjstatus = 0");
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
        // $echo.='</tbody>';

    return $echo;
}
function ready(){
    $echo="";
    // $echo.='<tbody>';
        $resultlist = brandpage_sql("id, brandid, prjstatus, requirement", " and prjstatus = 2");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {   
            $done = '';
            $unfinish = '';
                
            $prjstatus = 'Not yet applied';

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
        // $echo.='</tbody>';

    return $echo;
}

function done(){
    $echo="";
    // $echo.='<tbody>';
        $resultlist = brandpage_sql("id, brandid, prjstatus, requirement", " and prjstatus = 3");
        while($rowrecord = mysqli_fetch_assoc($resultlist))
        {   
            $done = '';
            $unfinish = '';

            $prjstatus = 'Done';
            
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
        // $echo.='</tbody>';

    return $echo;
}
?>