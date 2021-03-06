<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Chicago');
include("../includes/or-dbinfo.php");

if (!(isset($_SESSION["username"])) || $_SESSION["username"] == "") {
    echo "You are not logged in. Please <a href=\"../index.php\">click here</a> and login with an account that is an authorized administrator or reporter.";
} elseif ($_SESSION["isadministrator"] != "TRUE" && $_SESSION["isreporter"] != "TRUE") {
    echo "You must be authorized as an administrator or reporter to view this page. Please <a href=\"../index.php\">go back</a>.<br/>If you believe you received this message in error, contact an administrator.";
} elseif ($_SESSION["systemid"] != $settings["systemid"]) {
    echo "You are not logged in. Please <a href=\"../index.php\">click here</a> and login with an account that is an authorized administrator or reporter.";
} else {

    $successmsg = "";
    $errormsg = "";

    $lookupname = isset($_REQUEST["lookupname"]) ? $_REQUEST["lookupname"] : "";
    $orderbywhat = isset($_REQUEST["orderbywhat"]) ? $_REQUEST["orderbywhat"] : " ";
    $direction = isset($_REQUEST["direction"]) ? $_REQUEST["direction"] : " ";
    $orderbystr = "";

    if (!(preg_match("/^[A-Za-z0-9]+$/", $lookupname)) && $lookupname != "") {
        $errormsg = "Username is invalid!";
    }
    if (!(preg_match("/^[A-Za-z0-9]+$/", $orderbywhat))) {
        $orderbywhat = " ";
    } else {
        $orderbystr = " ORDER BY " . $orderbywhat;
    }
    if (!(preg_match("/^[A-Za-z0-9]+$/", $direction))) {
        $direction = "";
    }

    $orderbystr .= " " . $direction;

    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title><?php echo $settings["instance_name"]; ?> - Administration - Reports - User Lookup</title>
        <link rel="stylesheet" type="text/css" href="adminstyle.css"/>
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>
    </head>

    <body>
    <div id="heading"><span
                class="username"><?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "&nbsp;"; ?></span>&nbsp;<?php echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(Admin)</span>&nbsp;" : "";
        echo ($_SESSION["isreporter"] == "TRUE") ? "<span class=\"isreporter\">(Reporter)</span>&nbsp;" : ""; ?>
        |&nbsp;<a href="../index.php">Public View</a>&nbsp;|&nbsp;<a href="https://docs.google.com/spreadsheets/d/1J_AZaNgqMfnnQwj7eCewMU9pe71DXpIBNzVXGI_Xbfk/edit?usp=sharing" target="_blank">Known Issues</a>&nbsp;|&nbsp;<a href="../modules/logout.php">Logout</a></div>
    <div id="container">
        <center>
            <?php if ($_SESSION["isadministrator"] == "TRUE" || $_SESSION["isreporter"] == "TRUE"){
            if ($successmsg != "") {
                echo "<div id=\"successmsg\">" . $successmsg . "</div>";
            }
            if ($errormsg != "") {
                echo "<div id=\"errormsg\">" . $errormsg . "</div>";
            }
            ?>
        </center>
        <h3><a href="index.php">Administration</a> - Reports - User Lookup</h3>

        <br/><br/><strong>User Lookup for: <?php
        if ($lookupname == "") {
          echo "All Users <em>(showing 50 most recent reservations)</em></br>***sorting is disabled for all user lookup";
        }
        else {
          echo $lookupname;
        }?></strong><br/><br/>

        <table id="reporttable">
            <tr class="reportodd">
                <td><strong>Room</strong>&nbsp;<a
                            href="report-userlookup.php?lookupname=<?php echo $lookupname; ?>&orderbywhat=roomname&direction=ASC"><img
                                src="images/moveup.gif" border="0"/></a><a
                            href="report-userlookup.php?lookupname=<?php echo $lookupname; ?>&orderbywhat=roomname&direction=DESC"><img
                                src="images/movedown.gif" border="0"/></a></td>
                <td><strong>Information</strong></td>
                <td><strong>Start Time</strong>&nbsp;<a
                            href="report-userlookup.php?lookupname=<?php echo $lookupname; ?>&orderbywhat=start&direction=ASC"><img
                                src="images/moveup.gif" border="0"/></a><a
                            href="report-userlookup.php?lookupname=<?php echo $lookupname; ?>&orderbywhat=start&direction=DESC"><img
                                src="images/movedown.gif" border="0"/></a></td>
                <td><strong>End Time</strong>&nbsp;<a
                            href="report-userlookup.php?lookupname=<?php echo $lookupname; ?>&orderbywhat=end&direction=ASC"><img
                                src="images/moveup.gif" border="0"/></a><a
                            href="report-userlookup.php?lookupname=<?php echo $lookupname; ?>&orderbywhat=end&direction=DESC"><img
                                src="images/movedown.gif" border="0"/></a></td>
                <td><strong># in Group</strong>&nbsp;<a
                            href="report-userlookup.php?lookupname=<?php echo $lookupname; ?>&orderbywhat=numberingroup&direction=ASC"><img
                                src="images/moveup.gif" border="0"/></a><a
                            href="report-userlookup.php?lookupname=<?php echo $lookupname; ?>&orderbywhat=numberingroup&direction=DESC"><img
                                src="images/movedown.gif" border="0"/></a></td>
                <td><strong>Time of Request</strong>&nbsp;<a
                            href="report-userlookup.php?lookupname=<?php echo $lookupname; ?>&orderbywhat=timeofrequest&direction=ASC"><img
                                src="images/moveup.gif" border="0"/></a><a
                            href="report-userlookup.php?lookupname=<?php echo $lookupname; ?>&orderbywhat=timeofrequest&direction=DESC"><img
                                src="images/movedown.gif" border="0"/></a></td>
                <?php
                if ($lookupname == "") {
                  echo "<td><strong>Username</strong></td>";
                }?>
            </tr>

            <?php
            if ($lookupname == "") {
              $records = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations,rooms WHERE reservations.roomid = rooms.roomid ORDER BY timeofrequest DESC LIMIT 50;");
            }
            else {
              $records = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations,rooms WHERE reservations.username='" . $lookupname . "' AND reservations.roomid = rooms.roomid" . $orderbystr . ";");
            }
            $count = 2;
            while ($record = mysqli_fetch_array($records)) {
              if ($lookupname == "") {
                $extraTableCell = "<td>" . $record["username"] . "</td>";
              }
              else {
                $extraTableCell = "";
              }
                if ($count == 2) {
                    echo "<tr class=\"reporteven\">";
                    $count = 1;
                } else {
                    echo "<tr class=\"reportodd\">";
                    $count = 2;
                }
                echo "<td>" . $record["roomname"] . "</td><td><div class=\"reportscroll\">";

                //Optional Fields
                $opfields = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservationoptions WHERE reservationid = " . $record["reservationid"] . ";");
                while ($opfield = mysqli_fetch_array($opfields)) {
                    echo "<strong>" . $opfield["optionname"] . ": </strong>" . $opfield["optionvalue"] . "<br/>";
                }

                echo "</div></td><td>" . date('Y-m-d g:i a', strtotime($record["start"])) . "</td>" .
                    "<td>" . date('Y-m-d g:i a', strtotime($record["end"])) . "</td>" .
                    "<td>" . $record["numberingroup"] . "</td>" .
                    "<td>" . date('Y-m-d g:i a', strtotime($record["timeofrequest"])) . "</td>" .
                    $extraTableCell . "</tr>";
            }

            ?>

        </table>

        <br/>
        <?php
        }
        ?>
    </div>
    </body>
    </html>
    <?php
}
?>
