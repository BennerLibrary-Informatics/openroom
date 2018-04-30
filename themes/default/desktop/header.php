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
<div id = "container">
 <div id="heading" >
   <div class = "row" >
   <div class = "col-1 margin-top-20 margin-left-10 margin-bottom-15">
     <a href="index.php" style="text-decoration:none; color:white;"><img src = 'http://library.olivet.edu/img/logo.svg'>
     </div>
     <div id = "headerText" class = "col-11 float-right padding-left-30">
        <div class = "row openroomtitle">
            <div class = "col">Benner Library</div>
           </a>
            <div class = "col text-right">Open Room</div>
        </div>
        <a href="index.php" style="text-decoration:none; color:white;">
            <div class = "ONUtitle">
                Olivet Nazarene University
            </div>
     </a>
    </div>
   </div>
	<span class="username">
	<?php
    if ($_SESSION["systemid"] == $settings["systemid"]){

    echo isset($_SESSION["username"]) ? "<strong>Logged in as</strong>: " . $_SESSION["username"] : "&nbsp;"; ?></span>&nbsp;<?php echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(<a href=\"admin/index.php\">Admin</a>)</span>&nbsp;" : "";
    echo ($_SESSION["isreporter"] == "TRUE") ? "<span class=\"isreporter\">(<a href=\"admin/index.php\">Reporter</a>)</span>&nbsp;" : "";

    if(isset($_SESSION["isreporter"])) {
        echo ($_SESSION["isreporter"] == "TRUE") ? "<span class=\"isreporter\">(<a href=\"admin/index.php\">Reporter</a>)</span>&nbsp;" : "";
    }
    if ($settings["login_method"] == "normal" && isset($_SESSION["username"])) {
         if($_SESSION["username"] != "") {
            echo "|&nbsp;<a href=\"editaccount.php\">Edit Account</a>&nbsp;|";
         }
    }
    if(isset($_SESSION["username"])) {
        if ($_SESSION["username"] != "") {
            echo "&nbsp;<a href=\"modules/logout.php\">Logout</a>";
        }
    }
    }
    ?>
</div>
<?php include("modules/reminder.php"); ?>
<div id="containerInfo">
    <div id="leftside">
        <?php include("modules/calendar.php"); ?>
    </div>
    <div id="rightside">