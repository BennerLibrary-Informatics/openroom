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
    <img src = '<?php echo $_SESSION["themepath"];?>images\/Booklogo.svg'\/>
  </div>
  <div id = "headerText" class = "col-11 float-right padding-left-30">
    <div class = "row openroomtitle">
      <div class = "col">Benner Library</div>
      <div class = "col text-right">Open Room</div>
    </div>
    <div class = "ONUtitle">
      Olivet Nazerene University
   </div>
 </div>
</div>
   <div class="col align-self-end userhead">
   <?php
     if ($_SESSION["systemid"] == $settings["systemid"]){
       echo isset($_SESSION["username"]) ? "<span class = 'username'><strong>Hello, </strong>" . $_SESSION["username"] : "&nbsp;"; ?></span>&nbsp;<?php echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(<a href=\"admin/index.php\"style=\"color:white;\">Admin</a>)</span>&nbsp;" : "";
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
 </div>

<?php include("modules/reminder.php"); ?>
<div id="containerInfo">
    <div id="leftside">
        <!-- <img src="<?php echo $_SESSION["themepath"]; ?>images/openroom09.png" alt="OpenRoom"/> -->


        <?php include("modules/calendar.php"); ?>




        <!-- <?php include("modules/roomdetails.php"); ?> -->

    </div>
    <div id="rightside">
