<?php
require_once("includes/or-dbinfo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title><?php echo $settings["instance_name"]; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION["themepath"]; ?>style.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


    <meta http-equiv="Content-Script-Type" content="text/javascript"/>
</head>

<body>
<div id="heading">
	<span class="username">
	<?php
    if ($_SESSION["systemid"] == $settings["systemid"]){
<<<<<<< HEAD
    echo isset($_SESSION["username"]) ? "<strong>Hello, </strong>" . $_SESSION["username"] : "&nbsp;"; ?></span>&nbsp;<?php echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(<a href=\"admin/index.php\"style=\"color:white;\">Admin</a>)</span>&nbsp;" : "";
=======
    echo isset($_SESSION["displayname"]) ? "<strong>Logged in as</strong>: " . $_SESSION["displayname"] : "&nbsp;"; ?></span>&nbsp;<?php echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(<a href=\"admin/index.php\">Admin</a>)</span>&nbsp;" : "";
>>>>>>> parent of ab8949c... moving things from laptop to desktop, no big changes
    echo ($_SESSION["isreporter"] == "TRUE") ? "<span class=\"isreporter\">(<a href=\"admin/index.php\">Reporter</a>)</span>&nbsp;" : "";
    if ($settings["login_method"] == "normal" && $_SESSION["username"] != "") {
        echo "|&nbsp;<a href=\"editaccount.php\"style=\"color:white;\">Edit Account</a>&nbsp;|";
    }
    if ($_SESSION["username"] != "") {
        echo "&nbsp;<a href=\"modules/logout.php\"style=\"color:white;\">Logout</a>";
    }
    }
    ?>
</div>
<?php include("modules/reminder.php"); ?>
<div id="container">
    <div id="leftside">
        <!-- <img src="<?php echo $_SESSION["themepath"]; ?>images/openroom09.png" alt="OpenRoom"/> -->
        <br/>

        <?php include("modules/calendar.php"); ?>
        <br/>

<<<<<<< HEAD

=======
        <?php include("modules/legend.php"); ?>
        <br/>
>>>>>>> parent of ab8949c... moving things from laptop to desktop, no big changes


        <!-- <?php include("modules/roomdetails.php"); ?> -->

    </div>
    <div id="rightside">
