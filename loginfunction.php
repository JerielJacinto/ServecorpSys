<?php
include_once("./dbconfiggood.php");

//verify login credentials
function verifylogin($username, $password)
{
    
    if ($username != "" && $password != "")
    {
	$conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
	
        $strSQL = "SELECT * FROM users WHERE username LIKE BINARY '" . $username . "' AND password LIKE BINARY '" . $password . "' AND status=1" ;
	
        $rs = mysqli_query($conn, $strSQL);
	mysqli_close($conn);
	
        $row = mysqli_fetch_assoc($rs);
        $empname = strtoupper($row['name']);
        $empid = $row['id'];
        $access = $row['restriction'];
	
    }
    else
    {
        $empname="";
    }
    
    //should create session here for the user
    if ($empname != "")
    {
        session_start();
        $_SESSION["empname"] = $empname;
        $_SESSION["empid"] = $empid;
    	$_SESSION["access"] = $access;
    }
    else
    {
        $empname="failed";
    }
    
    return $empname; //returns na if no match and empname if there is a match
}



function loginNotification($result)
{
    if($result=="failed")//failed login
    {      
        $echo.='<div class="alert alert-danger">';
        $echo.='<button class="close" data-dismiss="alert" type="button">&times;</button>';
        $echo.='ERROR: Invalid username and/or password.';
        //$echo.='<div class="badge pull-right">';
        //$echo.='1';
        //$echo.='</div>';
        $echo.='</div>';
    }
    else if($result=="expired")//expired
    {      
        $echo.='<div class="alert alert-danger">';
        $echo.='<button class="close" data-dismiss="alert" type="button">&times;</button>';
        $echo.='Login expired. Please re-login again.';
        //$echo.='<div class="badge pull-right">';
        //$echo.='1';
        //$echo.='</div>';
        $echo.='</div>';
    }
    return $echo;
}



?>