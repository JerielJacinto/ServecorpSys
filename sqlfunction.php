<?php
include_once("dbconfiggood.php");

function calldbcondocs()
{
	$conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    return $conn;
}

function calldbconmain()
{
	mysql_connect(get_dbservermain(), get_dbusermain(), get_dbpasswordmain()) or die (mysql_error());
    mysql_select_db(get_dbnamemain()) or die(mysql_error());
}
////////////////

function drlistunion($datesearch)
{	
	$str="SELECT DISTINCT drno, id, fname, lname, branch, MIN(grp) AS source_group, noofbags, driver, crew, crew2, crew3, drdate from 
	(select 1 as grp, iti.drno as drno, drsum.id as id, mem.fname as fname, mem.lname as lname, mem.branchname as branch, iti.noofbags as noofbags, iti.driver as driver, iti.crew as crew, iti.crew2 as crew2, iti.crew3 as crew3, iti.drdate as drdate 
	from 11_itinerarytable iti join 8_directsalessummary drsum on drsum.drno = iti.drno join 8_membermain mem on mem.idno=iti.idno where iti.status>0 and iti.drdate = '".$datesearch."'    
	union  
	select 2 as grp, drtag.drno as drno, drsum.id as id, mem.fname as fname, mem.lname as lname, mem.branchname as branch, drtag.noofbags as noofbags, emp.name as driver, NULL as crew, NULL as crew2, NULL as crew3, drtag.drdate as drdate 
	from 2_drtagtable drtag join 8_directsalessummary drsum on drsum.drno = drtag.drno join 2_employee emp on emp.id = drtag.driveridno join 8_membermain mem on mem.idno=drsum  .idno where drtag.status>0 and drsum.status>0 and drtag.drdate = '".$datesearch."' 
	union
	select 3 as grp, drsum.drno as drno, drsum.id as id, mem.fname as fname, mem.lname as lname, mem.branchname as branch, NULL as noofbags, null as driver, NULL as crew, NULL as crew2, NULL as crew3, drsum.trandate as drdate 
	from 8_directsalessummary drsum  join 8_membermain mem on mem.idno=drsum.idno where drsum.status>0 and drsum.trandate = '".$datesearch."')
	as table1 
	GROUP BY drno 
	ORDER BY MIN(grp) ASC, drno"; //".$datesearch."
	calldbcondocs();
	$sql=mysqli_query($conn, $strSQL);
	mysqli_close($conn);
	return $sql;
}


function workorder_row($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM workorder WHERE status = 1 ".$cond;

    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);
    
    return $row;
}


function workorder_sql($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
    
    $strSQL = "SELECT ".$tbl." FROM workorder WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    return $rs;
}


function reserveworkorder_sql($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
    
    $strSQL = "SELECT ".$tbl." FROM reserveworkorder WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    return $rs;
}



function reserveworkorder_row($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
    
    $strSQL = "SELECT ".$tbl." FROM reserveworkorder WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);

    $row = mysqli_fetch_assoc($rs);
    
    return $row;
}

function workorderhistory_sql($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
    
    $strSQL = "SELECT ".$tbl." FROM workorderhistory WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    return $rs;
}

function workorderhistory_row($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
    
    $strSQL = "SELECT ".$tbl." FROM workorderhistory WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    $row = mysqli_fetch_assoc($rs);
    
    return $row;
}




function getBrand($id)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT brandname FROM brand WHERE status = 1 and id= ".$id." LIMIT 1";
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);
    
    return $row["brandname"];
}

function brand_sql($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM brand WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    
    return $rs;
}

function brand_row($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM brand WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);
    
    return $row;
}

function brandpage_sql($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM prj_brandpage WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    return $rs;
}

function brandpage_row($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM prj_brandpage WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);
    
    return $row;
}


function brandpagemeta_sql($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM prj_brandpagemeta WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    return $rs;
}


function brandpagemeta_row($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM prj_brandpagemeta WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);
    
    return $row;
}


function brand_notes_row($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM brand_notes WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);
    
    return $row;
}

function brand_notes_sql($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM brand_notes WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);

    
    return $rs;
}


function samplesql()
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT brandnamesample FROM sampletbl";
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);
    
    return $rs;
}

function editor_row($editorid)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT name FROM users WHERE status = 1 and id = ".$editorid;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);
    
    return $row["name"];
}

function editors_sql($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM users WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    
    return $rs;
}

function users_sql($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM users WHERE status > 0 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    
    return $rs;
}


function users_row($editorid)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT name FROM users WHERE status > 0 and id = ".$editorid;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);
    
    return $row;
}

function prjcat_row($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM prj_category WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);
    
    return $row;
}

function prjcat_sql($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM prj_category WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    
    return $rs;
}

function getClientName($clientid)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT clientname FROM client WHERE status = 1 and id = ".$clientid;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    $row = mysqli_fetch_assoc($rs);
    
    return $row["clientname"];
}

function todolist_sql($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM todolist WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    
    // $row = mysqli_fetch_assoc($rs);
    
    return $rs;
}

function workorderreport_sql($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM workorderreport WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
        
    return $rs;
}

function workorderreport_row($tbl, $cond)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT ".$tbl." FROM workorderreport WHERE status = 1 ".$cond;
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    $row = mysqli_fetch_assoc($rs);
        
    return $row;
}

function lastupdatePricelist_row($brandid)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
    
    $strSQL = 'select dateended from workorderhistory where status=1 and brandid = '.$brandid.' and actiontype = 2 and updatetype = 1 and (requeststatus = 3 or requeststatus = 0) ORDER BY dateended DESC LIMIT 1';
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    $row = mysqli_fetch_assoc($rs);
    
    return $row;
}

function lastupdateWebsite_row($brandid)
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
    
    $strSQL = 'select dateended from workorderhistory where status=1 and brandid = '.$brandid.' and actiontype = 2 and updatetype = 2 and (requeststatus = 3 or requeststatus = 0) ORDER BY dateended DESC LIMIT 1';
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);

    $row = mysqli_fetch_assoc($rs);
    
    return $row;
}









function getReserveCode()
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT reservecode FROM lastcode";
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    $row = mysqli_fetch_assoc($rs);
    $reservecode = $row["reservecode"];

    $reservecodeexplode = ltrim($reservecode, 'R');
    $newcode = $reservecodeexplode+1;
    return "R".$newcode;
}

function getWorkorderCode()
{
    $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());

    $strSQL = "SELECT workordercode FROM lastcode";
    
    $rs = mysqli_query($conn, $strSQL);
    mysqli_close($conn);
    $row = mysqli_fetch_assoc($rs);
    $wocode = $row["workordercode"];

    $wocodeexplode = ltrim($wocode, 'P');
    $newcode = $wocodeexplode+1;
    return "P".$newcode;
}

function getdataTable1()
{
    return' <script>
  $(document).ready(function(){
    $("#dataTable1").dataTable();
  });
  </script>';
}

?>