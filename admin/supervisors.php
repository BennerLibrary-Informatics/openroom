<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../includes/or-dbinfo.php");
require_once(__DIR__ . '/../vendor/autoload.php');
if (!(isset($_SESSION["username"])) || $_SESSION["username"] == "") {
    echo "You are not logged in. Please <a href=\"../index.php\">click here</a> and login with an account that is an authorized administrator or reporter.";
} elseif ($_SESSION["isadministrator"] != "TRUE") {
    echo "You must be authorized as an administrator to view this page. Please <a href=\"../index.php\">go back</a>.<br/>If you believe you received this message in error, contact an administrator.";
} elseif ($_SESSION["systemid"] != $settings["systemid"]) {
    echo "You are not logged in. Please <a href=\"../index.php\">click here</a> and login with an account that is an authorized administrator or reporter.";
} else {

    $op = isset($_REQUEST["op"]) ? $_REQUEST["op"] : "";

    $successmsg = "";
    $errormsg = "";

    switch ($op) {
        case "addsupervisor":
            $supervisorname = isset($_REQUEST["supervisorname"]) ? $_REQUEST["supervisorname"] : "";
            if ($supervisorname != "") {
                if (model\Supervisor::add($supervisorname)) {
                    $successmsg = $supervisorname . " has been added to the supervisor list.";
                } else {
                    $errormsg = "Unable to add this supervisor. Try again.";
                }
            } else {
                $errormsg = "Unable to add this supervisor. Try again.";
            }
            break;
        case "deletesupervisor":
            $supervisorname = isset($_REQUEST["supervisorname"]) ? $_REQUEST["supervisorname"] : "";
            if ($supervisorname != "") {
                if (model\Supervisor::remove($supervisorname)) {
                    $successmsg = $supervisorname . " has been deleted from the supervisor list.";
                } else {
                    $errormsg = "Unable to delete this supervisor. Try again.";
                }
            } else {
                $errormsg = "Unable to delete this supervisor. Try again.";
            }
            break;
    }


    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title><?php echo $settings["instance_name"]; ?> - Administration - Supervisors</title>
        <link rel="stylesheet" type="text/css" href="adminstyle.css"/>
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>
        <script language="javascript" type="text/javascript">
            function confirmdelete(username) {
                var answer = confirm("Are you sure you would like to delete this supervisor?");
                if (answer) {
                    window.location = "supervisors.php?op=deletesupervisor&supervisorname=" + username;
                }
                else {

                }
            }
        </script>
    </head>

    <body onLoad="jumpToAnchor();">
    <div id="heading"><span
                class="username"><?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "&nbsp;"; ?></span>&nbsp;<?php echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(Admin)</span>&nbsp;" : "";
        echo ($_SESSION["isreporter"] == "TRUE") ? "<span class=\"isreporter\">(Reporter)</span>&nbsp;" : ""; ?>
        |&nbsp;<a href="../index.php">Public View</a>&nbsp;|&nbsp;<a href="../modules/logout.php">Logout</a></div>
    <div id="container">
        <center>
            <?php if ($_SESSION["isadministrator"] == "TRUE"){
            if ($successmsg != "") {
                echo "<div id=\"successmsg\">" . $successmsg . "</div>";
            }
            if ($errormsg != "") {
                echo "<div id=\"errormsg\">" . $errormsg . "</div>";
            }
            ?>
        </center>
        <h3><a href="index.php">Administration</a> - Supervisors</h3>
        <ul>
            <?php

            $supervisors = model\Supervisor::all();
            foreach ($supervisors as $supervisor) {
                echo "<li>" . $supervisor->username . " <a href=\"javascript:confirmdelete('" . $supervisor->username . "');\">Delete</a></li>";
            }
            ?>
        </ul>
        <br/>
        <h4>Add Supervisor</h4>
        <form name="addsupervisor" action="supervisors.php" method="POST">
            <input type="text" name="supervisorname"/>
            <input type="hidden" name="op" value="addsupervisor"/>
            <input type="submit" value="Add Supervisor"/>
        </form>
        <?php
        }
        ?>
    </div>
    </body>
    </html>
    <?php
}
?>
