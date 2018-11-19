<?php
session_start();
$empname = $_SESSION["empname"];
if($empname == "na" || $empname == "")
{
  header("Location: ./login.php?res=expired");
}
$access = $_SESSION["access"];



include_once("./mainmenu.php");
include_once("./companyinfo.php");
include_once("./importfiles.php");
include_once("./userfunction.php");


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
  
  
  <body class="page-header-fixed sidebar-nav bg-1">
    <div class="modal-shiftfix">
        
        <!-- Navigation -->
        <div class="navbar navbar-fixed-top sidebar-nav scroll-hide">
          <div class="container-fluid top-bar">
            <button class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="logo" href="home.php">ServeCorp</a>
          </div>
          <div class="container-fluid main-nav clearfix">
              <div class="nav-collapse">
                  <ul class="nav">
                      <?php echo showmenu($access,4); ?>
                  </ul>
              </div>
          </div>
        </div>
        <!-- End Navigation -->
        
        <div class="container-fluid main-content">
              <?php
                if(isset($_GET['result'])){
                  echo userlist($_GET['result']);
                }
                else{
                  echo userlist();
                }
              ?>
        </div>
        
    </div>
    
    <div class="style-selector">
        <div class="style-selector-container">
          <!-- side setting here -->
        </div>
    </div>
    
  </body>
</html>