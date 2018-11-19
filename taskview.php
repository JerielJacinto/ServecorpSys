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
include_once("./workorderfunction.php");


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
  
  <body class="bg-1">
    <div class="modal-shiftfix">
              
        <div class="container-fluid">
              <?php
                if(isset($_GET["wocode"]))
                {
                  echo taskDetails($_GET["wocode"]);
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