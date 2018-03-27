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
        case "ban":
            $bannedusername = isset($_REQUEST["bannedusername"]) ? $_REQUEST["bannedusername"] : "";
            if ($bannedusername != "") {
                if (model\BannedUser::add($bannedusername)) {
                    $successmsg = $bannedusername . " has been banned.";
                } else {
                    $errormsg = "Unable to ban this user. Try again.";
                }
            } else {
                $errormsg = "Unable to ban this user. Try again.";
            }
            break;
        case "unban":
            $bannedusername = isset($_REQUEST["bannedusername"]) ? $_REQUEST["bannedusername"] : "";
            if ($bannedusername != "") {
                if (model\BannedUser::remove($bannedusername)) {
                    $successmsg = $bannedusername . " has been unbanned.";
                } else {
                    $errormsg = "Unable to unban this user. Try again.";
                }
            } else {
                $errormsg = "Unable to unban this user. Try again.";
            }
            break;
    }


    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title><?php echo $settings["instance_name"]; ?> - Administration - Banned Users</title>
        <link rel="stylesheet" type="text/css" href="adminstyle.css"/>
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>
        <script language="javascript" type="text/javascript">
            function confirmdelete(username) {
                var answer = confirm("Are you sure you would like to unban this user?");
                if (answer) {
                    window.location = "bans.php?op=unban&bannedusername=" + username;
                }
                else {

                }
            }
        </script>
    </head>

    <body onLoad="jumpToAnchor();">
    <div id="heading"><span
                class="username"><?php echo isset($_SESSION["username"]) ? "<strong>Logged in as</strong>: " . $_SESSION["username"] : "&nbsp;"; ?></span>&nbsp;<?php echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(Admin)</span>&nbsp;" : "";
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
        <h3><a href="index.php">Administration</a> - Banned Users</h3>

        <div class = "row">
          <?php
          $bannedusers = model\BannedUser::all();
          foreach ($bannedusers as $banneduser) {
              echo "<div class = 'col-sm-12'>" . $banneduser->username . " <a href=\"javascript:confirmdelete('" . $banneduser->username . "');\">Delete</a></div>";
          }
          ?>
        </div>
        <div class = "row col-sm-12">
          <h4>Ban User</h4>
        </div>
        <div class = "row col-sm-12">
          <form name="banuser" action="bans.php" method="POST">
              <input type="text" name="bannedusername"/>
              <input type="hidden" name="op" value="ban"/>
              <input type="submit" value="Ban User"/>
          </form>
        </div>

        <?php
        }
        ?>
    </div> <!-- end of container -->
    </body>
    </html>
    <?php
}
?>
