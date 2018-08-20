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
    <a href="index.php"><img src="<?php echo $_SESSION["themepath"]; ?>images/openroom.gif" border="0" alt="OpenRoom"/></a>
    <?php
    if (!isset($_SESSION["systemid"])) {
      $_SESSION["systemid"] = "";
    }
    if ($_SESSION["systemid"] == $settings["systemid"]) {
        if (isset($_SESSION["username"])  ) {
            if($_SESSION["username"] != "") {
                echo "<a id=\"navigation_button\" class=\"logout\" href=\"modules/logout.php\">Logout</a>";
                echo "<a id=\"navigation_button\" class=\"viewreservations\" href=\"?op=viewreservations\">My Reservations</a>";
                echo "<a id=\"navigation_button\" class=\"makereservation\" href=\"?op=makereservation\">Reserve</a>";
            }
        }
        else {
          echo "<a id=\"header_title\">Olivet Nazarene University<br/>Benner Library</a>";
        }
    }
    else {
      echo "<a id=\"header_title\">Olivet Nazarene University<br/>Benner Library</a>";
    }
    ?>
</div>
<?php include("modules/reminder.php"); ?>
<div id="container">
