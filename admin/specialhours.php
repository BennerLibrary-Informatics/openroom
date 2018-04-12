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

    $op = isset($_REQUEST["op"]) ? $_REQUEST["op"] : "";

    $successmsg = "";
    $errormsg = "";

    switch ($op) {
        case "addspecialhours":
            $from = isset($_REQUEST["from"]) ? $_REQUEST["from"] : "";
            $to = isset($_REQUEST["to"]) ? $_REQUEST["to"] : "";
            $starthour = isset($_REQUEST["starthour"]) ? $_REQUEST["starthour"] : "";
            $startminute = isset($_REQUEST["startminute"]) ? $_REQUEST["startminute"] : "";
            $endhour = isset($_REQUEST["endhour"]) ? $_REQUEST["endhour"] : "";
            $endminute = isset($_REQUEST["endminute"]) ? $_REQUEST["endminute"] : "";
            $affectedrooms = isset($_REQUEST["affectedrooms"]) ? $_REQUEST["affectedrooms"] : "";

            //All fields required
            if ($from != "" && $to != "" && $starthour != "" && $startminute != "" && $endhour != "" && $endminute != "" && $affectedrooms != "" && is_array($affectedrooms)) {
                //Make sure from and to are in proper formats
                if ((preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/", $from)) && (preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/", $to))) {
                    //Make sure from occurs before to
                    $froma = strtotime($from);
                    $toa = strtotime($to);
                    if ($toa >= $froma) {
                        //Make sure endtime is greater than starttime
                        $starttime = new ClockTime($starthour, $startminute, 0);
                        $endtime = new ClockTime($endhour, $endminute, 0);
                        if (($endtime->isGreaterThan($starttime)) || ($starttime->getTime() == "00:00:00" && $endtime->getTime() == "00:00:00")) {
                            //Add to roomspecialhours table for each roomid in affectedrooms
                            $frommysql = date("Y-m-d H:i:s", $froma);
                            $tomysql = date("Y-m-d H:i:s", $toa);
                            foreach ($affectedrooms as $aroom) {
                                $room = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms WHERE roomid=" . $aroom . ";"));
                                if (mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO roomspecialhours(roomid,fromrange,torange,start,end) VALUES(" . $aroom . ",'" . $frommysql . "','" . $tomysql . "','" . $starttime->getTime() . "','" . $endtime->getTime() . "');")) {
                                    $successmsg = "Special Hours have been added!";
                                } else {
                                    $errormsg .= "<br/>Unable to add special hours to room " . $room["roomname"] . ". Please try again!";
                                }
                            }
                        } else {
                            $errormsg .= "<br/>Opening time must occur before Closing time!";
                        }
                    } else {
                        $errormsg .= "<br/>From date must occur BEFORE To date.";
                    }
                } else {
                    $errormsg .= "<br/>From or To date should be in the format of mm/dd/yyyy.";
                }
            } else {
                $errormsg .= "<br/>Some parameters are missing. Make sure all form fields are filled out.";
            }
            break;

        case "deletespecialhours":
            $roomspecialhoursid = isset($_REQUEST["roomspecialhoursid"]) ? $_REQUEST["roomspecialhoursid"] : "";
            if (mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM roomspecialhours WHERE roomspecialhoursid=" . $roomspecialhoursid . ";")) {
                $successmsg = "Special hours deleted.";
            } else {
                $errormsg = "Unable to delete special hours. Please try again.";
            }
            break;
    }


    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title><?php echo $settings["instance_name"]; ?> - Administration - Special Hours</title>
        <link rel="stylesheet" type="text/css" href="adminstyle.css"/>
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>
        <script language="javascript" type="text/javascript">
            function confirmdelete(roomspecialhoursid) {
                var answer = confirm("Are you sure you would like to delete these special hours?");
                if (answer) {
                    window.location = "specialhours.php?op=deletespecialhours&roomspecialhoursid=" + roomspecialhoursid;
                }
                else {

                }
            }
        </script>

        <script src="../includes/datechooser/date-functions.js" type="text/javascript"></script>
        <script src="../includes/datechooser/datechooser.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="../includes/datechooser/datechooser.css">

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
        <h3><a href="index.php">Administration</a> - Special Hours</h3>
        <div class = "row specialhourslist"> <!--HERE-->
          <?php
          $pgroupname = "";
          $rooms = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms ORDER BY roomgroupid ASC, roomposition ASC;");
          while ($room = mysqli_fetch_array($rooms)) {
              $cgroupname = $room["roomgroupid"];
              if ($pgroupname != $cgroupname) {
                  $roomgroupname = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM roomgroups WHERE roomgroupid=" . $cgroupname . ";");
                  $rgn = mysqli_fetch_array($roomgroupname);
                  echo "<div class = 'col-lg-8'>" . $rgn["roomgroupname"] . "</div>";
              }
              $pgroupname = $cgroupname;

                echo "<tr><td><strong>" . $room["roomname"] . "</strong><ul>";

              $specialhoursa = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM roomspecialhours WHERE roomid=" . $room["roomid"] . ";");
              while ($specialhours = mysqli_fetch_array($specialhoursa)) {
                  $from = explode(" ", $specialhours["fromrange"]);
                  $to = explode(" ", $specialhours["torange"]);
                  echo "<li><strong>Date Range: </strong>" . $from[0] . " -- " . $to[0] . " | <strong>Hours:</strong> " . $specialhours["start"] . " -- " . $specialhours["end"] . " | <a href=\"javascript:confirmdelete(" . $specialhours["roomspecialhoursid"] . ");\">X</a></li>";
              }

              echo "</ul></td></tr>";
          }
          ?>
        </div>
        <br/>
        <hr/>
        <br/>

        <div class = "row col-lg-12">
          <h3>Add Special Hours</h3>
        </div>
        <div class = "row col-lg-12">
          <em>Note: Please be sure to cancel any current reservations that may be removed as a result of adding special
              hours. This will be automated in a future version of this system.</em><br/>
        </div>

        <form name="addspecialhours" action="specialhours.php" method="POST">
          <div class = "row">
            <div class = "col-lg-3">
              <strong>From:</strong>
            </div>
            <div class = "col-lg-9">
              <input id="from" size="10" maxlength="10" name="from" type="text">
              <img src="../includes/datechooser/calendar.gif"
                   onclick="showChooser(this, 'from', 'chooserSpan3', 1950, 2060, Date.patterns.ShortDatePattern, false);">
              <div id="chooserSpan3" class="dateChooser select-free"
                   style="display: none; visibility: hidden; width: 160px;"></div>
          </div>
          </div>

            <div class = "row">
              <div class = "col-lg-3">
                <strong>To:</strong>
              </div>
              <div class = "col-lg-9">
                <input id="to" size="10" maxlength="10" name="to" type="text">
                <img src="../includes/datechooser/calendar.gif"
                     onclick="showChooser(this, 'to', 'chooserSpan3', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                <div id="chooserSpan3" class="dateChooser select-free"
                     style="display: none; visibility: hidden; width: 160px;"></div>
              </div>
            </div>

            <div class = "row">
              <div class = "col-lg-3">
                <strong>Open:</strong>
              </div>
              <div class = "col-lg-9">
                <select name="starthour">
                    <?php
                    for ($i = 0; $i <= 24; $i++) {
                        echo "<option value=\"" . $i . "\">" . $i . "</option>";
                    }
                    ?>
                </select>:<select name="startminute">
                    <?php
                    for ($i = 0; $i <= 59; $i++) {
                        echo "<option value=\"" . $i . "\">" . $i . "</option>";
                    }
                    ?>
                </select>
              </div>
            </div>

            <div class = "row">
              <div class = "col-lg-3">
                <strong>Close:</strong>
              </div>
              <div class = "col-lg-8">
                <select name="endhour">
                    <?php
                    for ($i = 0; $i <= 24; $i++) {
                        echo "<option value=\"" . $i . "\">" . $i . "</option>";
                    }
                    ?>
                </select>:<select name="endminute">
                    <?php
                    for ($i = 0; $i <= 59; $i++) {
                        echo "<option value=\"" . $i . "\">" . $i . "</option>";
                    }
                    ?>
                </select>
              </div>
            </div>

            <div class = "row">
              <div class = "col-lg-12">
                <em>(To close room for entire day, leave hours set to 00:00-00:00.)</em>
              </div>
            </div>

            <div class = "row col-lg-12">
              <strong>Rooms Affected:</strong>
            </div>

            <div class = "row">
              <?php
              $pgroupname = "";
              $rooms = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms ORDER BY roomgroupid ASC, roomposition ASC;");
              $to5 = 0;
              $to1 = 0;
              while ($room = mysqli_fetch_array($rooms)) {
                  $cgroupname = $room["roomgroupid"];
                  if ($pgroupname != $cgroupname) {
                      $roomgroupname = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM roomgroups WHERE roomgroupid=" . $cgroupname . ";");
                      $rgn = mysqli_fetch_array($roomgroupname);
                      if ($to1 > 0) echo "</table>\n";
                      echo "<table><tr><td colspan=\"5\">" . $rgn["roomgroupname"] . "</td></tr>";
                      $to5 = 0;
                      $to1++;
                  }
                  $pgroupname = $cgroupname;
                  if ($to5 == 0) echo "<tr>";
                  echo "<td><input type=\"checkbox\" name=\"affectedrooms[]\" value=\"" . $room["roomid"] . "\" /><strong>" . $room["roomname"] . "</strong></td>\n";
                  if ($to5 < 5) $to5++;
                  if ($to5 == 5) {
                      echo "</tr>";
                      $to5 = 0;
                  }
              }
              ?>
            </div>

            <br/>
            <input type="hidden" name="op" value="addspecialhours"/>
            <input type="submit" value="Add Special Hours"/><br/><br/><br/>
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
