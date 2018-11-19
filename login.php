<?php
session_start();
session_destroy();

include_once("./mainmenu.php");
include_once("./companyinfo.php");
include_once("./importfiles.php");
include_once("./loginfunction.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>
        <?php echo companyname(); ?>
    </title>
    <?php echo getImports(); ?>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
  </head>
  <body class="login1">
    <!-- Login Screen -->
    <div class="login-wrapper">
      <div class="login-container">
      <a href="./"><img width="200" height="90" src="images/logo2.png" /></a>
      <?php
            $res = $_GET["res"];
            if($res=="failed")
            {
                  echo loginNotification($res);
            }
            else if($res=="expired")
            {
                  echo loginNotification($res);
            }
      ?>
      
            
      <form method="post" action="loginprocess.php">
          <div class="form-group">
            <input class="form-control" placeholder="Username or Email" type="text" name="username" id="username">
          </div>
          <div class="form-group">
            <input class="form-control" placeholder="Password" type="password" name="password" id="username"><input type="submit" value="&#xf054;">
          </div>
      </form>
      
      
      </div>
    </div>
    <!-- End Login Screen -->
  </body>
</html>