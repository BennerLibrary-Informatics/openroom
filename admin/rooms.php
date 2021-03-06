<?php
date_default_timezone_set('America/Chicago');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../includes/or-dbinfo.php");
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
        //Swap roomposition values with room having position++
        case "incorder":
            $opid = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
            if ($opid != "") {
                $thisroom = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms WHERE roomid=" . $opid . ";"));
                $thispos = $thisroom["roomposition"];
                $nextpos = $thispos + 1;
                $thisgroupcount = mysqli_num_rows(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms WHERE roomgroupid=" . $thisroom["roomgroupid"] . ";"));
                if ($nextpos < $thisgroupcount) {
                    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE rooms SET roomposition=" . $thispos . " WHERE roomposition=" . $nextpos . " AND roomgroupid=" . $thisroom["roomgroupid"] . ";");
                    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE rooms SET roomposition=" . $nextpos . " WHERE roomid=" . $opid . ";");
                }
            }
            break;
        //Swap roomposition values with room having position--
        case "decorder":
            $opid = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
            if ($opid != "") {
                $thisroom = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms WHERE roomid=" . $opid . ";"));
                $thispos = $thisroom["roomposition"];
                $prevpos = $thispos - 1;
                $thisgroupcount = mysqli_num_rows(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms WHERE roomgroupid=" . $thisroom["roomgroupid"] . ";"));
                if ($prevpos >= 0) {
                    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE rooms SET roomposition=" . $thispos . " WHERE roomposition=" . $prevpos . " AND roomgroupid=" . $thisroom["roomgroupid"] . ";");
                    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE rooms SET roomposition=" . $prevpos . " WHERE roomid=" . $opid . ";");
                }
            }
            break;
        //Add a new room
        case "addroom":
            $roomname = isset($_REQUEST["roomname"]) ? $_REQUEST["roomname"] : "";
            $roomcapacitymin = isset($_REQUEST["roomcapacitymin"]) ? $_REQUEST["roomcapacitymin"] : "";
            $roomcapacitymax = isset($_REQUEST["roomcapacitymax"]) ? $_REQUEST["roomcapacitymax"] : "";

            //Min capacity must be less than max capacity
            if ($roomcapacitymin > $roomcapacitymax)
              $errormsg = "Min capacity must be less than max capacity";
            //Min caacity must be >= 0
            if ($roomcapacitymin <= 0)
              $errormsg = "Min capacity must be greater than or equal to 0";

            $roomdescription = isset($_REQUEST["roomdescription"]) ? $_REQUEST["roomdescription"] : "";
            $roomgroupid = isset($_REQUEST["roomgroupid"]) ? $_REQUEST["roomgroupid"] : "";
            if ($roomgroupid != "") {
                $roompositionr = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms WHERE roomgroupid = $roomgroupid ORDER BY roomposition DESC;");
                if (mysqli_num_rows($roompositionr) > 0) {
                    $roompositiona = mysqli_fetch_array($roompositionr);
                    $roomposition = $roompositiona["roomposition"] + 1;
                } else {
                    $roomposition = 0;
                }
                if ($roomname != "" && $roomcapacitymin != "" && $roomcapacitymax != "" && $roomdescription != "" && $roomgroupid != "" && $roomposition >= 0 && $errormsg == "") {
                    if (mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO rooms(roomname,roomposition,roomcapacitymin, roomcapacitymax,roomgroupid,roomdescription) VALUES('$roomname',$roomposition,$roomcapacitymin, $roomcapacitymax,$roomgroupid,'$roomdescription');")) {
                        $successmsg = "Room $roomname has been added!";
                    } else {
                        $errormsg = "Unable to add room $roomname. (Make sure you've added a &nbsp;<a href=\"roomgroups.php\">Room Group</a>&nbsp; first!)";
                    }
                }
            } else {
                $errormsg = "Unable to add room. (Make sure you've added a &nbsp;<a href=\"roomgroups.php\">Room Group</a>&nbsp; first!)";
            }
            break;
        //Edit an existing room
        case "editroom":
            $roomname = isset($_REQUEST["roomname"]) ? $_REQUEST["roomname"] : "";
            $roomcapacitymin = isset($_REQUEST["roomcapacitymin"]) ? $_REQUEST["roomcapacitymin"] : "";
            $roomcapacitymax = isset($_REQUEST["roomcapacitymax"]) ? $_REQUEST["roomcapacitymax"] : "";
            $roomdescription = isset($_REQUEST["roomdescription"]) ? $_REQUEST["roomdescription"] : "";
            $roomgroupid = isset($_REQUEST["roomgroupid"]) ? $_REQUEST["roomgroupid"] : "";
            $roomid = isset($_REQUEST["roomid"]) ? $_REQUEST["roomid"] : "";

            //Min capacity must be less than max capacity
            if ($roomcapacitymin > $roomcapacitymax)
              $errormsg = "Min capacity must be less than max capacity";
            //Min caacity must be >= 0
            if ($roomcapacitymin <= 0)
              $errormsg = "Min capacity must be greater than or equal to 0";

            if ($roomname != "" && $roomcapacitymin != "" && $roomcapacitymax != "" && $roomdescription != "" && $roomgroupid != "" && $roomid != "" && $errormsg == "") {
                if (mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE rooms SET roomname='" . $roomname . "', roomcapacitymin=" . $roomcapacitymin . ", roomcapacitymax=" . $roomcapacitymax . ", roomdescription='" . $roomdescription . "', roomgroupid=" . $roomgroupid . " WHERE roomid=" . $roomid . ";")) {
                    $successmsg = "Room $roomname has been updated!";
                } else {
                    $errormsg = "Unable to edit room $roomname. Please try again.";
                }
            }
            break;
        //Delete a room (do NOT delete past reservations (will appear when "include deleted rooms" is checked in reports) and cancel all future reservations)
        //Deleted rooms are moved to the deletedrooms table for reference when reporting
        //Consolidate roompositions to make up for lost room
        case "deleteroom":
            $opid = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
            if ($opid != "") {
                $roomnamea = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms WHERE roomid=" . $opid . ";"));
                $roomname = $roomnamea["roomname"];
                $roomposition = $roomnamea["roomposition"];
                $roomgroupid = $roomnamea["roomgroupid"];
                //Grab all reservations for this room that start AFTER the current time.
                $futureresa = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations WHERE roomid=" . $opid . " AND start >= '" . date("Y-m-d H:i:s") . "';");
                //Cancel each reservation
                while ($futureres = mysqli_fetch_array($futureresa)) {
                    $_POST["reservationid"] = $futureres["reservationid"];
                    ob_start();
                    include("../or-cancel.php");
                    $cancelmsg = ob_get_contents();
                    ob_end_clean();
                }
                //Move this room to deletedrooms table (minus position info)
                if (mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO deletedrooms(roomid,roomname,roomcapacitymin, roomcapacitymax,roomgroupid,roomdescription) VALUES(" . $roomnamea["roomid"] . ",'" . $roomnamea["roomname"] . "'," . $roomnamea["roomcapacitymin"] . "," . $roomnamea["roomcapacitymax"] . ",". $roomnamea["roomgroupid"] . ",'" . $roomnamea["roomdescription"] . "');")) {
                    if (mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM rooms WHERE roomid=" . $opid . ";")) {
                        $rearrr = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms WHERE roomposition > " . $roomposition . " AND roomgroupid = " . $roomgroupid . " ORDER BY roomposition ASC;");
                        while ($rearr = mysqli_fetch_array($rearrr)) {
                            $thisid = $rearr["roomid"];
                            $thispos = $rearr["roomposition"] - 1;
                            mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE rooms SET roomposition=" . $thispos . " WHERE roomid=" . $thisid . ";");
                        }
                        $successmsg = "Successfully deleted room $roomname!";
                    }
                } else {
                    $errormsg = "Unable to delete room $roomname!";
                }
            } else {
                $errormsg = "Unable to delete room $roomname!";
            }
            break;
    }
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title><?php echo $settings["instance_name"]; ?> - Administration - Rooms</title>
        <link rel="stylesheet" type="text/css" href="adminstyle.css"/>
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>
        <script language="javascript" type="text/javascript">
            function confirmdelete(roomid, roomname) {
                var answer = confirm("Are you sure you would like to delete " + roomname + "?\n\nNOTE: Deleting a room will NOT delete past reservations. Any future reservations will be cancelled automatically.");
                if (answer) {
                    window.location = "rooms.php?op=deleteroom&id=" + roomid;
                }
                else {
                }
            }
        </script>
    </head>

    <body>
    <div id="heading"><span
                class="username"><?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "&nbsp;"; ?></span>&nbsp;<?php echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(Admin)</span>&nbsp;" : "";
        echo ($_SESSION["isreporter"] == "TRUE") ? "<span class=\"isreporter\">(Reporter)</span>&nbsp;" : ""; ?>
        |&nbsp;<a href="../index.php">Public View</a>&nbsp;|&nbsp;<a href="https://docs.google.com/spreadsheets/d/1J_AZaNgqMfnnQwj7eCewMU9pe71DXpIBNzVXGI_Xbfk/edit?usp=sharing" target="_blank">Known Issues</a>&nbsp;|&nbsp;<a href="../modules/logout.php">Logout</a></div>
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
        <h3><a href="index.php">Administration</a> - Rooms</h3>
        <table class="roomslist">
            <?php
            $pgroupname = "";
            $rooms = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms ORDER BY roomgroupid ASC, roomposition ASC;");
            while ($room = mysqli_fetch_array($rooms)) {
                $cgroupname = $room["roomgroupid"];
                if ($pgroupname != $cgroupname) {
                    $roomgroupname = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM roomgroups WHERE roomgroupid=" . $cgroupname . ";");
                    $rgn = mysqli_fetch_array($roomgroupname);
                    echo "<tr><td colspan=\"8\" class=\"tabletitle\">" . $rgn["roomgroupname"] . "</td></tr>";
                    echo "<tr><td class=\"tableheader\" colspan=\"1\">Order</td><td class=\"tableheader\" colspan=\"2\">&nbsp&nbsp&nbsp&nbspName</td><td class=\"tableheader\" colspan=\"1\">Min Capacity</td><td class=\"tableheader\" colspan=\"2\">Max Capacity</td><td align=\"left\" class=\"tableheader\" colspan=\"1\">Group</td><td class=\"tableheader\" colspan=\"1\">Description</td><td></td><td></td></tr>";
                }
                $pgroupname = $cgroupname;
                $roomid = $room["roomid"];
                $thisgroupcount = mysqli_num_rows(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms WHERE roomgroupid=" . $cgroupname . ";"));
                $orderstring = "";
                if ($room["roomposition"] >= 0 && $room["roomposition"] < $thisgroupcount - 1) {
                    $orderstring .= "<td><a href=\"rooms.php?op=incorder&id=" . $roomid . "\"><img src=\"images/movedown.gif\" style=\"display: inline;border: 0px;\" /></a></td>";
                } else {
                    $orderstring .= "<td></td>";
                }
                if ($room["roomposition"] < $thisgroupcount && $room["roomposition"] > 0) {
                    $orderstring .= "<td><a href=\"rooms.php?op=decorder&id=" . $roomid . "\"><img src=\"images/moveup.gif\" style=\"display: inline;border: 0px;\" /></a></td>";
                } else {
                    $orderstring .= "<td></td>";
                }
                $roomgroupstr = "<select name=\"roomgroupid\"><option></option>";
                $roomgroups = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM roomgroups;");
                while ($roomgroup = mysqli_fetch_array($roomgroups)) {
                    $selectedstr = "";
                    if ($roomgroup["roomgroupid"] == $room["roomgroupid"]) {
                        $selectedstr = "selected ";
                    }
                    $roomgroupstr .= "<option value=\"" . $roomgroup["roomgroupid"] . "\" " . $selectedstr . ">" . $roomgroup["roomgroupname"] . "</option>";
                }
                $roomgroupstr .= "</select>";


                //Text fields above "Add a New Room"
                echo "<tr>" . $orderstring . "<td><form name=\"editroom\" method=\"POST\" action=\"rooms.php\"><input type=\"hidden\" name=\"op\" value=\"editroom\"/><input type=\"hidden\" name=\"roomid\" value=\"" . $room["roomid"];
                echo "\" /><input name=\"roomname\" type=\"text\" class=\"medtxt\" value=\"" . $room["roomname"] . "\" /></td><td><input class=\"medtxt\" type=\"text\" name=\"roomcapacitymin\" value=\"" . $room["roomcapacitymin"] . "\"/></td><td>";
                echo "</td><td><input class=\"medtxt\" type=\"text\" name=\"roomcapacitymax\" value=\"" . $room["roomcapacitymax"] . "\"/></td><td>";
                echo $roomgroupstr . "</td><td style=\"width:195px\"><textarea class=\"textareaexpand\" rows=\"1\" cols=\"30\" name=\"roomdescription\">" . $room["roomdescription"] . "</textarea></td><td><input type=\"submit\" value=\"Save Changes\" /></form></td>";
                echo "<td><a href=\"javascript:confirmdelete( " . $roomid . ",'" . $room["roomname"] . "')\"><img src=\"../themes/default/desktop/images/fa trash-o.png\" alt=\"Delete Room\" style=\"height: 24px;\"></a></td></tr>\n";

            }
            ?>
        </table>
        <br/><br/>
        <h3>Add a New Room</h3>
        <form name="addroom" method="POST" action="rooms.php">
            <table class="adminform">
                <tr>
                    <td><strong>Room Name:</strong></td>
                    <td><input type="text" name="roomname"/></td>
                </tr>
                <tr>
                    <td><strong>Min Capacity:</strong></td>
                    <td><input type="text" name="roomcapacitymin"/></td>
                </tr>
                <tr>
                    <td><strong>Max Capacity:</strong></td>
                    <td><input type="text" name="roomcapacitymax"/></td>
                </tr>
                <tr>
                    <td><strong>Description:</strong></td>
                    <td><input type="text" name="roomdescription"/></td>
                </tr>
                <tr>
                    <td><strong>Group:</strong></td>
                    <td>
                        <?php
                        $roomgroupstr = "<select name=\"roomgroupid\">";
                        $roomgroups = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM roomgroups;");
                        while ($roomgroup = mysqli_fetch_array($roomgroups)) {
                            $roomgroupstr .= "<option value=\"" . $roomgroup["roomgroupid"] . "\">" . $roomgroup["roomgroupname"] . "</option>";
                        }
                        $roomgroupstr .= "</select>";
                        echo $roomgroupstr;
                        ?>
                        <input type="hidden" name="op" value="addroom"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Add Room"/>
                    </td>
                </tr>

            </table>
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
