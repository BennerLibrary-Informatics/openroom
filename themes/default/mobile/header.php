<?php
require_once("includes/or-dbinfo.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo $settings["instance_name"]; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION["themepath"]; ?>style.css"/>
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
    <script type="text/javascript" src="includes/jquery.min.js"></script>
</head>

<body>
<div id="heading">
    <a href="https://library.olivet.edu/index.php" style="margin-left: 15px; float: left;"><img src = 'https://library.olivet.edu/img/logo.svg' width="50" height="50"></a>
    <?php
    if (!isset($_SESSION["systemid"])) {
      $_SESSION["systemid"] = "";
    }
    if ($_SESSION["systemid"] == $settings["systemid"]) {
        if (isset($_SESSION["username"])  ) {
            if($_SESSION["username"] != "") {
                echo "<div id='buttonHeading'>";
                echo "<a id=\"navigation_button\" class=\"logout\" href=\"modules/logout.php\">Logout</a>";
                echo "<a id=\"navigation_button\" class=\"viewreservations\" href=\"?op=viewreservations\">My Reservations</a>";
                echo "<a id=\"navigation_button\" class=\"makereservation\" href=\"?op=makereservation\">Reserve</a></div>";
                echo "<div id=\"header_title\">";
                echo "<a style=\"text-decoration:none; color:white; float: left; margin-left: 0px;\">";
                echo "Open Room";
                echo "</div></a>";
                /*echo "<br><a id= \"header_title\" href=\" index.php\">OpenRoom</a>";*/
            }
        }
        else {
          echo "<a id=\"login_title\">Benner Library<br/>Olivet Nazarene University</a>";
        }
    }
    else {
      echo "<a id=\"login_title\">Benner Library<br/>Olivet Nazarene University</a>";
    }
    ?>
</div>
<div id="container">
  <?php include("modules/reminder.php"); ?>
