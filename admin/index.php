<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../includes/or-dbinfo.php");

if (!(isset($_SESSION["username"])) || $_SESSION["username"] == "") {
    echo "You are not logged in. Please <a href=\"../index.php\">click here</a> and login with an account that is an authorized administrator or reporter.";
} elseif ($_SESSION["isadministrator"] != "TRUE" && $_SESSION["isreporter"] != "TRUE") {
    echo "You must be authorized as an administrator or reporter to view this page. Please <a href=\"../index.php\">go back</a>.<br/>If you believe you received this message in error, contact an administrator.";
} elseif ($_SESSION["systemid"] != $settings["systemid"]) {
    echo "You are not logged in. Please <a href=\"../index.php\">click here</a> and login with an account that is an authorized administrator or reporter.";
} else {
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title><?php echo $settings["instance_name"]; ?> - Administration</title>
        <link rel="stylesheet" type="text/css" href="adminstyle.css"/>
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>

        <script src="../includes/datechooser/date-functions.js" type="text/javascript"></script>
        <script src="../includes/datechooser/datechooser.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="../includes/datechooser/datechooser.css">
    </head>

    <body>
    <div id="heading"><span
                class="username"><?php echo isset($_SESSION["username"]) ? "<strong>Logged in as</strong>: " . $_SESSION["username"] : "&nbsp;"; ?></span>&nbsp;<?php echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(Admin)</span>&nbsp;" : "";
        echo ($_SESSION["isreporter"] == "TRUE") ? "<span class=\"isreporter\">(Reporter)</span>&nbsp;" : ""; ?>
        |&nbsp;<a href="../index.php">Public View</a>&nbsp;|&nbsp;<a href="../modules/logout.php">Logout</a></div>
    <div id="container">
        <?php if ($_SESSION["isadministrator"] == "TRUE") { ?>
          <div class = "row"> <!--User Management-->
            <div classw = "col-lg-12">
              <h3>User Management</h3>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-10">
              <a href="administrators.php">Administrators</a><em> - Add/Remove users with full administrative
                    access.</em>
            </div>
            <div class = "col-lg-12">
              <a href="reporters.php">Reporters</a><em> - Add/Remove users with limited administrative
                      access.</em>
            </div>
            <div class = "col-lg-12">
              <a href="bans.php">Banned Users</a><em> - Prevent certain users from accessing the reservation
                      system.</em>
            </div>
          </div>

          <div class = "row"> <!--Room Management-->
            <div class = "col-lg-12">
              <h3>Room Management</h3>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-12">
              <a href="hours.php">Open Hours</a><em> - Set up the open and closing time, as shown in the
                      reservation calendar.</em>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-12">
              <a href="specialhours.php">Special Hours</a><em> - Set up room closures, holidays, etc.</em>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-12">
              <a href="rooms.php">Rooms</a><em> - Add, modify, or remove rooms and their descriptions, capacities,
                      names, etc.</em>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-12">
              <a href="defaulthours.php">Default Hours</a><em> - Set up default hours for rooms by weekday.</em>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-12">
              <a href="roomgroups.php">Room Groups</a><em> - Add, modify, or remove room groups.</em>
            </div>
          </div>

          <div class = "row"> <!--Reservation tools-->
            <div class = "col-lg-12">
              <h3>Reservation Tools</h3>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-12">
              <a href="customfields.php">Custom Fields</a><em> - Set up custom form fields that appear when users
                      make reservations.</em>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-12">
              <a href="email.php">Email Setup</a><em> - Set up a list of email addresses to send verbose, terse,
                      or Gmail Event Format reservation/cancellation notices.</em>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-12">
              <a href="multiplereservations.php">Multiple Reservations</a><em> - Create repeating reservations
                      quickly using this tool.</em>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-12">
              <a href="timing.php">Timing and Limitations</a><em> - Change the time interval, time format, and set
                      quantity and duration limits on reservations.</em>
            </div>
          </div>

          <div class = "row"> <!--settings-->
            <div class = "col-lg-12">
              <h3>Settings</h3>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-12">
              <a href="configuration.php">Configuration</a><em> - Change the name of the system, security
                      settings, login settings, email filters, themes, etc.</em>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-12">
              <a href="policies.php">Policies</a><em> - Edit the text on the page that users see when they click
                      "Policies".</em>
            </div>
          </div>
          <div class = "row">
            <div class = "col-lg-12">
              <a href="reminder.php">Reminder Message</a><em> - The Reminder Message appears at the top of every
                      page.</em>
            </div>
          </div>
            <?php
        }
        ?>

        <div class = "row">
          <div class = "col-lg-12">
            <h3>Reports</h3>
          </div>
        </div>
        <div class = "row">
          <div class = "col-lg-12"><!--user lookup-->
            <strong>User Lookup</strong><em> - All reservations made by specified user.</em><br/>
          </div>
          <div class = "col-lg-12">
            <form name="userlookup" method="POST" action="report-userlookup.php">
                Username: <input type="text" name="lookupname"/><input type="submit" value="Lookup"/>
            </form>
          </div>
        </div>
        <div class = "row"> <!--Daily-->
          <div class = "col-lg-12">
            <strong>Daily</strong><em> - Total reservations per day, per room.</em>
          </div>
        </div>
        <div class = "row">
          <div class = "col-lg-12">
            <form name="daily" method="POST" action="report-daily.php">
                For the period starting: <input id="from" size="10" maxlength="10" name="from" type="text">
                <img src="../includes/datechooser/calendar.gif"
                     onclick="showChooser(this, 'from', 'chooserSpan3', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                <div id="chooserSpan3" class="dateChooser select-free"
                     style="display: none; visibility: hidden; width: 160px;"></div>
                and ending: <input id="to" size="10" maxlength="10" name="to" type="text">
                <img src="../includes/datechooser/calendar.gif"
                     onclick="showChooser(this, 'to', 'chooserSpan3', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                <div id="chooserSpan3" class="dateChooser select-free"
                     style="display: none; visibility: hidden; width: 160px;"></div>
                <input type="submit" value="Run Report"/>
            </form>
          </div>
        </div>
        <div class = "row">
          <div class = "col-lg-12">
            <strong>Monthly</strong><em> - Total reservations per month, per room.</em>
          </div>
        </div>
        <div class = "row">
          <div class = "col-lg-12">
            <form name="monthly" method="POST" action="report-monthly.php">
                For the period starting: <select name="startmonth">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select name="startyear">
                    <?php
                    //Get earliest year of reservations
                    $eyear = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations ORDER BY start ASC;"));
                    $eyear = date("Y", strtotime($eyear["start"]));
                    //Get latest year of reservations
                    $lyear = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations ORDER BY end DESC;"));
                    $lyear = date("Y", strtotime($lyear["end"]));
                    while ($eyear <= $lyear) {
                        echo "<option value=\"" . $eyear . "\">" . $eyear . "</option>";
                        $eyear++;
                    }
                    ?>
                </select>
                and ending: <select name="endmonth">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select name="endyear">
                    <?php
                    //Get earliest year of reservations
                    $eyear = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations ORDER BY start ASC;"));
                    $eyear = date("Y", strtotime($eyear["start"]));
                    //Get latest year of reservations
                    $lyear = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations ORDER BY end DESC;"));
                    $lyear = date("Y", strtotime($lyear["end"]));
                    while ($eyear <= $lyear) {
                        echo "<option value=\"" . $eyear . "\">" . $eyear . "</option>";
                        $eyear++;
                    }
                    ?>

                </select>
                <input type="submit" value="Run Report"/>
            </form>
          </div>
        </div>

        <div class = "row"> <!--Cancellation reports-->
          <div class = "col-lg-12">
            <h3>Cancellation Reports</h3>
          </div>
        </div>
        <div class = "row">
          <div class = "col-lg-11 col-lg-pull-1 ">
            <strong>Daily</strong><em> - Total cancellations per day, per room.</em>
          </div>
        </div>
        <div class = "row">
          <div class = "col-lg-12">
            <form name="daily" method="POST" action="report-canceldaily.php">
                For the period starting: <input id="fromc" size="10" maxlength="10" name="fromc"
                                                type="text">
                <img src="../includes/datechooser/calendar.gif"
                     onclick="showChooser(this, 'fromc', 'chooserSpan3', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                <div id="chooserSpan3" class="dateChooser select-free"
                     style="display: none; visibility: hidden; width: 160px;"></div>
                and ending: <input id="toc" size="10" maxlength="10" name="toc" type="text">
                <img src="../includes/datechooser/calendar.gif"
                     onclick="showChooser(this, 'toc', 'chooserSpan3', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                <div id="chooserSpan3" class="dateChooser select-free"
                     style="display: none; visibility: hidden; width: 160px;"></div>
                <input type="submit" value="Run Report"/>
            </form>
          </div>
        </div>

        <div class = "row"> <!--Monthly-->
          <div class = "col-lg-12">
            <strong>Monthly</strong><em> - Total cancellations per month, per room.</em>
          </div>
        </div>
        <div class = "row">
          <div class = "col-lg-12">
            <form name="monthly" method="POST" action="report-cancelmonthly.php">
                For the period starting: <select name="startmonth">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select name="startyear">
                    <?php
                    //Get earliest year of reservations
                    $eyear = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations ORDER BY start ASC;"));
                    $eyear = date("Y", strtotime($eyear["start"]));
                    //Get latest year of reservations
                    $lyear = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations ORDER BY end DESC;"));
                    $lyear = date("Y", strtotime($lyear["end"]));
                    while ($eyear <= $lyear) {
                        echo "<option value=\"" . $eyear . "\">" . $eyear . "</option>";
                        $eyear++;
                    }
                    ?>
                </select>
                and ending: <select name="endmonth">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select name="endyear">
                    <?php
                    //Get earliest year of reservations
                    $eyear = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations ORDER BY start ASC;"));
                    $eyear = date("Y", strtotime($eyear["start"]));
                    //Get latest year of reservations
                    $lyear = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations ORDER BY end DESC;"));
                    $lyear = date("Y", strtotime($lyear["end"]));
                    while ($eyear <= $lyear) {
                        echo "<option value=\"" . $eyear . "\">" . $eyear . "</option>";
                        $eyear++;
                    }
                    ?>

                </select>
                <input type="submit" value="Run Report"/>
            </form>
          </div>
        </div>

    </div>
    </body>
    </html>
    <?php
}
?>
