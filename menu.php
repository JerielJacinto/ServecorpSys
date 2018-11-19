<?php
session_start();
$empname = $_SESSION["empname"];
if($empname == "na" || $empname == "")
{
  header("Location: ./login.php?res=expired");
}
$access = $_SESSION["access"];


include_once("./mainmenu.php");
include_once("./mainmenufunction.php");
include_once("./companyinfo.php");
include_once("./importfiles.php");
include_once("./homefunction.php");
//include_once("./m_userinfo.php");


$res = $_GET["res"];
$num=0;

switch($res)
{
  case "workorder": $num=1; break;
  case "brands": $num=2; break;
  case "reports": $num=3; break;
  case "users": $num=4; break;
}


?>
<!DOCTYPE html>
<html>
  <head>
      <title>
          <?php echo companyname(); ?>
      </title>
      <?php echo getImports(); ?>
      <meta content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
        
  </head>
  
  
  <body class="page-header-fixed bg-2">
    <div class="modal-shiftfix">
        
        <!-- Navigation -->
        <div class="navbar navbar-fixed-top scroll-hide">
          <div class="container-fluid top-bar">
            <button class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="logo" href="home.php">JCPremiere</a>
          </div>
          <div class="container-fluid main-nav clearfix">
              <div class="nav-collapse">
                  <ul class="nav">
                      <?php echo showmenu($access,$num); ?>
                  </ul>
              </div>
          </div>
        </div>
        <!-- End Navigation -->
        
        <div class="container-fluid main-content">
          <?php
                
                switch($num)
                {
                    case 1: echo mainmenu_workorder($access); break;
                    case 2: echo mainmenu_settings($access); break;
                }
          ?>
        </div>
        
        
        
    </div>
    
  </body>
</html>