<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../includes/or-dbinfo.php");
include("../includes/ClockTime.php");

if (!(isset($_SESSION["username"])) || $_SESSION["username"] == "") {
    echo "You are not logged in. Please <a href=\"../index.php\">click here</a> and login with an account that is an authorized administrator or reporter.";
} elseif ($_SESSION["isadministrator"] != "TRUE") {
    echo "You must be authorized as an administrator to view this page. Please <a href=\"../index.php\">go back</a>.<br/>If you believe you received this message in error, contact an administrator.";
} elseif ($_SESSION["systemid"] != $settings["systemid"]) {
    echo "You are not logged in. Please <a href=\"../index.php\">click here</a> and login with an account that is an authorized administrator or reporter.";
} else {
    $optionalfields_string = "";
    $optionalfields_array = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM optionalfields ORDER BY optionorder ASC;");
    while ($optionalfield = mysqli_fetch_array($optionalfields_array)) {
        //Determine if required
        if ($optionalfield["optionrequired"] == 1) {
            $optionalfields_string .= "<strong><span class=\'requiredmarker\'>*</span>";
        } else {
            $optionalfields_string .= "<strong>";
        }
        //Print field question
        $optionalfields_string .= $optionalfield["optionquestion"] . "</strong>: ";

        $optionformname = $optionalfield["optionformname"];

        //Determine if this field is a text field or a select field
        if ($optionalfield["optiontype"] == 0) {
            $postvalue = $optionalfield["optionformname"];
            $postvalue = str_replace("'", "&apos;", $postvalue);
            $postvalue = str_replace('"', "&quot;", $postvalue);
            $postvalue = str_replace("\\", "", $postvalue);
            $optionalfields_string .= "<style> input[type=text] {color: black;} </style><input type='text' color='black' name='" . $optionformname . "' value='" . $postvalue . "' /><br/>";
        } else {
            $optionalfields_string .= "<select name='" . $optionformname . "'>";
            $optionchoices = explode(";", $optionalfield["optionchoices"]);
            $optionchkd = "";
            if ($optionalfield["optionrequired"] != 1) {
              $optionalfields_string .="<option></option>";
            }
            foreach ($optionchoices as $choice) {
                if ($choice == $optionalfield["optionformname"]) {
                    $optionchkd = "selected";
                }
                $optionalfields_string .= "<option value='" . $choice . "' " . $optionchkd . ">" . $choice . "</option>";
            }
            $optionalfields_string .= "</select><br/>";
        }
    }

    $op = isset($_REQUEST["op"]) ? $_REQUEST["op"] : "";

    $successmsg = "";
    $errormsg = "";

    if ($op == "multiplereservations") {
        $roomid = isset($_POST["roomid"]) ? $_POST["roomid"] : "";
        $from = isset($_POST["from"]) ? $_POST["from"] : "";
        $to = isset($_POST["to"]) ? $_POST["to"] : "";
        $starthour = isset($_POST["starthour"]) ? $_POST["starthour"] : "";
        $startminute = isset($_POST["startminute"]) ? $_POST["startminute"] : "";
        $startperiod = isset($_POST["startperiod"]) ? $_POST["startperiod"] : "";
        $duration = isset($_POST["duration"]) ? $_POST["duration"] : "";
        $daysineffect = isset($_POST["daysineffect"]) ? $_POST["daysineffect"] : "";

        if($startperiod == "PM" && $starthour != 12)
          $starthour += 12;

        //Make sure from and to are in proper formats
        if ((preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/", $from)) && (preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/", $to))) {
            //Make sure from occurs before to
            $froma = strtotime($from);
            $toa = strtotime($to);
            if ($toa >= $froma) {
                //Make sure duration is a number
                if (preg_match("/^\\d*$/", $duration) && $duration != "") {
                    //Make sure at least one day was checked
                    if ($daysineffect != "" && $roomid != "" && $starthour != "" && $startminute != "" && $startperiod != "") {
                        //Go through each day and run a check using or-getdatarange.
                        //If anything is returned for the selected room mark it and save the information so it can be printed out report style.
                        $frommonth = date("n", $froma);
                        $fromday = date("j", $froma);
                        $fromyear = date("Y", $froma);
                        $tomonth = date("n", $toa);
                        $today = date("j", $toa);
                        $toyear = date("Y", $toa);
                        $starttime = new ClockTime(0, 0, 0);
                        $starttime->setTime($starthour, $startminute, 0);
                        $starttime = mktime($starthour, $startminute, 0, $frommonth, $fromday, $fromyear);
                        $endtime = $starttime + ($duration * 60);
                        $curtime = $starttime;
                        $lasttime = mktime($starthour, $startminute, 0, $tomonth, $today, $toyear);

                        $availstr = "";
                        $chkorres = "";
                        if ($_POST["onlychecking"] == "TRUE") {
                            $chkorres = "Checking ";
                        } else {
                            $chkorres = "Reserving ";
                        }

                        while ($curtime <= $lasttime) {

                            $availstr .= $chkorres . date("n/j/Y g:i A", $curtime) . " to " . date("n/j/Y g:i A", ($curtime + ($duration * 60))) . "... ";
                            $problemflag = "";
                            //Make sure this is one of the weekdays that was checked
                            $curwkdy = strtolower(date("l", $curtime));
                            foreach ($daysineffect as $dayineffect) {
                                if ($curwkdy == $dayineffect) {
                                    $_POST["fromrange"] = $curtime;
                                    $_POST["torange"] = $curtime + ($duration * 60);
                                    $_POST["ajax_indicator"] = "FALSE";
                                    $_POST["duration"] = $duration;
                                    $_POST["roomid"] = $roomid;
                                    $_POST["starttime"] = $curtime;
                                    ob_start();
                                    include("../or-reserve.php");
                                    $obreserve = ob_get_contents();
                                    ob_end_clean();
                                    if ($obreserve != "Your reservation has been made!<br/>") {
                                        $problemflag = $obreserve;
                                    }
                                }
                            }
                            if ($problemflag != "") {
                                $availstr .= $problemflag . "<br/><br/>";
                            } else {
                                $availstr .= "OK!<br/><br/>";
                            }
                            //Increment by one day
                            $curtime = strtotime("+1 day", $curtime);
                        }
                        if ($_POST["onlychecking"] == "TRUE") {
                            $availstr .= "<form name=\"multiplereservations\" action=\"multiplereservations.php\" method=\"POST\"><input type=\"hidden\" name=\"roomid\" value=\"" . $roomid . "\" /><input type=\"hidden\" name=\"from\" value=\"" . $from . "\" /><input type=\"hidden\" name=\"to\" value=\"" . $to . "\" /><input type=\"hidden\" name=\"starthour\" value=\"" . $starthour . "\" /><input type=\"hidden\" name=\"startminute\" value=\"" . $startminute . "\" /><input type=\"hidden\" name=\"duration\" value=\"" . $duration . "\" />";

                            //loop through and make fake checkboxes for dayseffected
                            foreach ($daysineffect as $day) {
                                $availstr .= "<input type=\"hidden\" name=\"daysineffect[]\" value=\"" . $day . "\" checked />";
                            }

                            $availstr .= "To confirm these reservations, please click \"Finalize Reservations\" above and resubmit this form.<br/><br/><br/>";
                        } else {
                            $availstr .= "<br/>These reservations have been made.";
                        }
                    } else {
                        $errormsg = "Please select at least one day for this reservation to occur on.";
                    }
                } else {
                    $errormsg = "Duration must be a number of minutes.";
                }
            } else {
                $errormsg = "From date must occur before To date.";
            }
        } else {
            $errormsg = "There was an error reading the date. Please click the calendar and select a date and try again.";
        }
    }


    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title><?php echo $settings["instance_name"]; ?> - Administration - Multiple Reservations</title>
        <link rel="stylesheet" type="text/css" href="adminstyle.css"/>
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>
        <script src="../includes/datechooser/date-functions.js" type="text/javascript"></script>
        <script src="../includes/datechooser/datechooser.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="../includes/datechooser/datechooser.css">
    </head>

    <body onLoad="jumpToAnchor();">
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
        <h3><a href="index.php">Administration</a> - Multiple Reservations</h3><br/>
        <span class="notetext">Use this form to make repeating reservations. Choose the date range, what time you would like the reservation to start, the duration, and which days of the week it should occur on.<br/>Clicking "Check Availability" will show a report of what reservations can be made.</span><br/>
        <form name="multiplereservations" action="multiplereservations.php" method="POST">
            <table>
                <tr>
                    <td>
                        <strong>Username:</strong>
                    </td>
                    <td>
                        <input type="text" name="altusername" value="<?php
                        if(isset($_POST["altusername"]))
                          echo $_POST["altusername"]; ?>"/>
                          <em>(The username of the user you're making these reservations for.)</em>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Room:</strong>
                    </td>
                    <td>
                        <select name="roomid">
                            <?php
                            $rooms = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms ORDER BY roomgroupid, roomname ASC;");
                            while ($room = mysqli_fetch_array($rooms)) {
                                $selectstr = "";
                                if (isset($_POST["roomid"]) && isset($room["roomid"])
                                    && $_POST["roomid"] == $room["roomid"]) {
                                      $selectstr = "selected";
                                }
                                echo "<option value=" . $room["roomid"] . " " . $selectstr . ">" . $room["roomname"] . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>From:</strong>
                    </td>
                    <td>
                        <input id="from" size="10" maxlength="10" name="from" type="text"
                               value="<?php if(isset($_POST["from"]))
                                          echo $_POST["from"]; ?>" placeholder="MM/DD/YYYY"/>
                        <img src="../includes/datechooser/calendar.gif"
                             style="cursor: pointer;" onclick="showChooser(this, 'from', 'chooserSpan4', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                        <div id="chooserSpan4" class="dateChooser select-free"
                             style="display: none; visibility: hidden; width: 160px; z-index: 1;"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>To:</strong>
                    </td>
                    <td>
                        <input id="to" size="10" maxlength="10" name="to" type="text"
                               value="<?php if(isset($_POST["to"]))
                                         echo $_POST["to"]; ?>" placeholder="MM/DD/YYYY"/>
                        <img src="../includes/datechooser/calendar.gif"
                             style="cursor: pointer;" onclick="showChooser(this, 'to', 'chooserSpan4', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                        <div id="chooserSpan4" class="dateChooser select-free"
                             style="display: none; visibility: hidden; width: 160px; z-index: 1;"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Reservation Time:</strong>
                    </td>
                    <td>
                        <select name="starthour" style="width: 38px;">
                            <?php
                            if (isset($_POST["starthour"])) {
                              echo "<option value=\"" . $_POST["starthour"] . "\">" . $_POST["starthour"] . "</option>";
                            }
                            else {
                              for ($i = 0; $i <= 12; $i++) {
                                echo "<option value=\"" . $i . "\">" . $i . "</option>";
                              }
                            }
                            ?>
                        </select>:<select name="startminute">
                            <?php
                            if (isset($_POST["startminute"])) {
                              echo "<option value=\"" . $_POST["startminute"] . "\">" . sprintf("%02d", $_POST["startminute"]) . "</option>";
                            }
                            else {
                              for ($i = 0; $i <= 59; $i=$i + 15) {
                                     if ($i == 0) {
                                       $formatted_value = sprintf("%02d", $i);
                                       echo "<option value=\"". $i . "\">" . $formatted_value . "</option>";
                                     }
                                     else
                                       echo "<option value=\"". $i . "\">" . $i . "</option>";
                                }
                              }
                              ?>
                        </select> <select name="startperiod">
                            <?php
                            $timePeriods = ["AM" , "PM"];
                            if (isset($_POST["startperiod"])) {
                                echo "<option value=\"" . $_POST["startperiod"] . "\">" . $_POST["startperiod"] . "</option>";
                            }
                            else {
                              for ($i = 0; $i < 2; $i++) {

                                  echo "<option value=\"" . $timePeriods[$i] . "\">" . $timePeriods[$i] . "</option>";
                              }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Duration:</strong>
                    </td>
                    <td>
                        <input type="text" size="5" name="duration" value="<?php if(isset($_POST["duration"]))
                                  echo $_POST["duration"]; ?>"/> (in minutes)
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Optional Fields</strong>
                    </td>
                    <td>
                        <?php echo $optionalfields_string; ?>
                    </td>
                </tr>
            </table>
          </br>

            <table>
                <tr>
                    <?php
                    $dayarray = array("sunday" => "", "monday" => "", "tuesday" => "", "wednesday" => "", "thursday" => "", "friday" => "", "saturday" => "");
                    if(isset($_POST["daysineffect"]))
                      foreach ($_POST["daysineffect"] as $affectedday) {
                        $dayarray[$affectedday] = "checked";
                    }
                    ?>
                    <td><strong>Days Affected: &nbsp&nbsp</strong><label><input type="checkbox" id="sunday" name="daysineffect[]" value="sunday" <?php echo $dayarray["sunday"]; ?>/><strong>Sunday&nbsp</strong></label>
                    </td>
                    <td><label><input type="checkbox" id="monday" name="daysineffect[]" value="monday" <?php echo $dayarray["monday"]; ?>/><strong>Monday&nbsp</strong></label>
                    </td>
                    <td><label><input type="checkbox" id="tuesday" name="daysineffect[]"
                               value="tuesday" <?php echo $dayarray["tuesday"]; ?>/><strong>Tuesday&nbsp</strong></label></td>
                    <td><label><input type="checkbox" id="wednesday" name="daysineffect[]"
                               value="wednesday" <?php echo $dayarray["wednesday"]; ?>/><strong>Wednesday&nbsp</strong><label></td>
                    <td><label><input type="checkbox" id="thursday" name="daysineffect[]"
                               value="thursday" <?php echo $dayarray["thursday"]; ?>/><strong>Thursday&nbsp</strong><label></td>
                    <td><label><input type="checkbox" id="friday" name="daysineffect[]" value="friday" <?php echo $dayarray["friday"]; ?>/><strong>Friday&nbsp</strong></label>
                    </td>
                    <td><label><input type="checkbox" id="saturday" name="daysineffect[]"
                               value="saturday" <?php echo $dayarray["saturday"]; ?>/><strong>Saturday&nbsp&nbsp</strong></label></td>
                               <td>&nbsp;&nbsp;<button type="button" style="position: relative; margin-bottom: 10px;" onclick="checkAll();">Select All</button></td>
                </tr>
            </table>
            <br/>
            <input type="hidden" name="op" value="multiplereservations"/>
            <?php
            if (isset($_POST["onlychecking"]) && $_POST["onlychecking"] != "multireserve") {
                $onlychksel = "checked";
                $onlychkselb = "";
            } else {
                $onlychksel = "";
                $onlychkselb = "checked";
            }
            ?>
            <label><input type="radio" name="onlychecking" value="TRUE" <?php echo $onlychksel; ?>/><strong>Check
                Availability</strong></label> or <label><input type="radio" name="onlychecking"
                                                value="multireserve" <?php echo $onlychkselb; ?> /><strong>Finalize
                Reservations</strong></label><br/>
            <input type="submit" value="Submit"/><br/><br/><br/>
        </form>
        <?php
        if (isset($availstr) && $availstr != "") {
            echo $availstr;
        }
        }
        ?>
    </div>
    </body>
    </html>
    <script language="javascript" type="text/javascript">
        //code to select all day of the week checkboxes
        var firstClick = true;
        function checkAll() {
            for (var i = 0; i <= 6; i++) {
                if (i == 0) var boxID = document.getElementById("sunday");
                if (i == 1) var boxID = document.getElementById("monday");
                if (i == 2) var boxID = document.getElementById("tuesday");
                if (i == 3) var boxID = document.getElementById("wednesday");
                if (i == 4) var boxID = document.getElementById("thursday");
                if (i == 5) var boxID = document.getElementById("friday");
                if (i == 6) var boxID = document.getElementById("saturday");
                var boxValue = boxID.value;
                if (firstClick == true) {
                    document.getElementById(boxValue).checked = true;
                }
                else if (firstClick == false) {
                    document.getElementById(boxValue).checked = false;
                }
            }
            firstClick = !firstClick;
        }
    </script>
    <?php
}
?>
