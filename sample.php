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
include_once("./sqlfunction.php");
include_once("./functions/report-workorder-function.php");


?>
<!DOCTYPE html>
<html>
  <head>
      <title>
          <?php echo companyname(); ?>
      </title>
      <?php echo getImports(); ?>
      <meta content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
        
  <script src="http://ckeditor.com/apps/ckeditor/4.3.4/ckeditor.js"></script>
  </head>
  
  <body class="bg-1">
    <div class="modal-shiftfix">

        <div class="container-fluid">
          <?php
          $echo ="";

            

          
          echo $echo;
          ?>
        </div>
              
        <div class="container-fluid">
          <?php
            $echo = "";


                $conn = mysqli_connect(get_dbserver(), get_dbuser(), get_dbpassword(), get_dbname()) or die("Connection failed: " . mysqli_connect_error());
                
                $strSQL = "SELECT * FROM workorderhistory WHERE 1 ";
                
                $rs = mysqli_query($conn, $strSQL);
                mysqli_close($conn);

                while($rowrecord = mysqli_fetch_assoc($rs))
                {
                  echo $rowrecord['wocode'] . "<br>";
                }


            echo $echo;
          ?>
        </div>

        <div class="container-fluid">

        </div>
        
    </div>
    <script>CKEDITOR.replace('#ckeditor');</script>
    <div class="style-selector">
        <div class="style-selector-container">
          <!-- side setting here -->
        </div>
    </div>
    
  </body>
</html>