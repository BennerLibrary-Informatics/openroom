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

    $lookuproom = isset($_REQUEST["lookuproom"]) ? $_REQUEST["lookuproom"] : "";
    $date = isset($_REQUEST["date"]) ? $_REQUEST["date"] : "";
    $orderbywhat = isset($_REQUEST["orderbywhat"]) ? $_REQUEST["orderbywhat"] : " ";
    $direction = isset($_REQUEST["direction"]) ? $_REQUEST["direction"] : " ";
    $orderbystr = "";

    if (!(preg_match("/^[A-Za-z0-9]+\s[A-Za-z0-9]/", $lookuproom)) && $lookuproom != "") {
        $errormsg = "Room name is invalid!";
    }
    if (!(preg_match("/^[A-Za-z0-9]+$/", $orderbywhat))) {
        $orderbywhat = " ";
    } else {
        $orderbystr = " ORDER BY " . $orderbywhat;
    }
    if (!(preg_match("/^[A-Za-z0-9]+\s[A-Za-z0-9]/", $direction))) {
        $direction = "";
    }

    $orderbystr .= " " . $direction;

    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title><?php echo $settings["instance_name"]; ?> - Administration - Reports - Daily Schedule with Usernames</title>
        <link rel="stylesheet" type="text/css" href="adminstyle.css"/>
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>
    </head>

    <body>
    <div id="heading"><span
                class="username"><?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "&nbsp;"; ?></span>&nbsp;<?php echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(Admin)</span>&nbsp;" : "";
        echo ($_SESSION["isreporter"] == "TRUE") ? "<span class=\"isreporter\">(Reporter)</span>&nbsp;" : ""; ?>
        |&nbsp;<a href="../index.php">Public View</a>&nbsp;|&nbsp;<a href="../modules/logout.php">Logout</a></div>
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
        <h3><a href="index.php">Administration</a> - Reports - Daily Schedule with Usernames</h3>

        <br/><strong>Daily schedule for rooms with usernames</strong><br/>
        <?php
        if ($date == "") {
          $date = date('Y-m-d');
        }
        $date_mdy = date('m/d/Y', strtotime($date));
        $date = date('Y-m-d H:i:s', strtotime($date));
        $nextDay = date('Y-m-d H:i:s', strtotime($date . "+1 days"));

        $records = getReservationInfo($date, $nextDay);
        $firstRoom = mysqli_fetch_array($records);
        ?>

        <table id="reporttable" align="center">
              <tr class="reportheader">
                <th colspan="5"><h4 style="cursor: default;"><strong><center>
              <?php
                if ($firstRoom["roomname"] == "") {
                  ?>No results found<?php
                }
                else {
                  ?>Room: <?php echo $firstRoom["roomname"];
                }
                echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
                echo $date_mdy;
              ?>
              </center></strong></h4>
                  <a href="report-schedule.php?lookuproom=<?php echo $lookuproom; ?>&orderbywhat=roomname&direction=ASC"></a></th>
              </tr>
              <tr class="reportodd">
                <td><strong>Start Time</strong>&nbsp;
                  <a href="report-schedule.php?lookuproom=<?php echo $lookuproom; ?>&orderbywhat=start&direction=ASC"></a></td>
                <td><strong>End Time</strong>&nbsp;
                  <a href="report-schedule.php?lookuproom=<?php echo $lookuproom; ?>&orderbywhat=end&direction=ASC"></a></td>
                <td><strong>Number in Group</strong>&nbsp;
                  <a href="report-schedule.php?lookuproom=<?php echo $lookuproom; ?>&orderbywhat=numberingroup&direction=ASC"></a></td>
                <td><strong>Purpose</strong>&nbsp;
                  <a href="report-schedule.php?lookuproom=<?php echo $lookuproom; ?>&orderbywhat=timeofrequest&direction=ASC"></a></td>
                <?php
                if ($lookuproom == "") {
                  echo "<td><strong>Username</strong></td>";
                }
                ?>
            </tr>

            <?php
            $records = getReservationInfo($date, $nextDay);
            $count = 1;
            $previousRoomID = 0;
            while ($record = mysqli_fetch_array($records)) {
              if ($record["roomid"] != $previousRoomID) {
                if ($previousRoomID != 0) {
                  echo "<table id='reporttable' align='center'>";
                  echo "<tr class='reportheader'><th colspan = '5'><h4 style='cursor: default;'><strong><center>Room:&nbsp;";
                  echo $record['roomname'];
                  echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
                  echo $date_mdy;
                  echo "</strong></center></h4>";
                  echo "<a href='report-schedule.php?lookuproom=$lookuproom;&orderbywhat=roomname&direction=ASC'></a></th></tr>";
                  echo "<tr class='reportodd'>";
                  echo "<td><strong>Start Time</strong>&nbsp;";
                  echo "<a href='report-schedule.php?lookuproom=$lookuproom;&orderbywhat=start&direction=ASC'></a></td>";
                  echo "<td><strong>End Time</strong>&nbsp;";
                  echo "<a href='report-schedule.php?lookuproom=$lookuproom;&orderbywhat=end&direction=ASC'></a></td>";
                  echo "<td><strong>Number in Group</strong>&nbsp;";
                  echo "<a href='report-schedule.php?lookuproom=$lookuproom;&orderbywhat=numberingroup&direction=ASC'></a></td>";
                  echo "<td><strong>Purpose</strong>&nbsp;";
                  echo "<a href='report-schedule.php?lookuproom=$lookuproom;&orderbywhat=timeofrequest&direction=ASC'></a></td>";
                  if ($lookuproom == "") {
                    echo "<td><strong>Username</strong></td>";
                  }
                  echo "<br>";
                }
                $previousRoomID = $record["roomid"];
              }

              if ($lookuproom == "") {
                $extraTableCell = "<td>" . $record["username"] . "</td>";
              }
              else {
                $extraTableCell = "";
              }

              echo "<tr class=\"reportodd\">";

              //Optional Fields
              $opfields = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservationoptions WHERE reservationid = " . $record["reservationid"] . ";");
              while ($opfield = mysqli_fetch_array($opfields)) {
                  $opfield_uservalue = $opfield["optionvalue"];
              }

              echo "</div></td><td>" . date('g:i a', strtotime($record["start"])) . "</td>" .
                  "<td>" . date('g:i a', strtotime($record["end"])) . "</td>" .
                  "<td>" . $record["numberingroup"] . "</td>" .
                  "<td>" . $opfield_uservalue . "</td>" .
                  $extraTableCell . "</tr>";

              echo "<br>";
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

// function to call msqli_query
function getReservationInfo($date, $nextDay) {
  $lookuproom = isset($_REQUEST["lookuproom"]) ? $_REQUEST["lookuproom"] : "";
  if ($lookuproom == "") {
    $records = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations,rooms WHERE reservations.roomid = rooms.roomid AND reservations.end > '" . $date . "' AND reservations.end < '" . $nextDay . "' ORDER BY rooms.roomposition, reservations.start ASC;");
  }
  else {
    $records = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations,rooms WHERE rooms.roomname ='" . $lookuproom . "' AND reservations.roomid = rooms.roomid AND reservations.end > '" . $date . "' AND reservations.end < '" . $nextDay . "';");
  }
  return $records;
}
?>
