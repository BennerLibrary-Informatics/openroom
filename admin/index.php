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
                class="username"><?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "&nbsp;"; ?></span>&nbsp;<?php echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(Admin)</span>&nbsp;" : "";
        echo ($_SESSION["isreporter"] == "TRUE") ? "<span class=\"isreporter\">(Reporter)</span>&nbsp;" : ""; ?>
        |&nbsp;<a href="../index.php">Public View</a>&nbsp;|&nbsp;<a href="https://docs.google.com/spreadsheets/d/1J_AZaNgqMfnnQwj7eCewMU9pe71DXpIBNzVXGI_Xbfk/edit?usp=sharing" target="_blank">Known Issues</a>&nbsp;|&nbsp;<a href="../modules/logout.php">Logout</a></div>
    <div id="container">
        <?php if ($_SESSION["isadministrator"] == "TRUE") { ?>
            <h3>User Management</h3>
            <ul>

                <li><a href="administrators.php">Administrators</a><em> - Add/Remove users with full administrative
                        access (can make reservations in past and for other users, and has access to admin settings).</em></li>
                <li><a href="supervisors.php">Supervisors</a><em> - Add/Remove users with limited administrative
                        access (can make reservations in past and for other users, but has no access to admin settings).</em></li>
                <li><a href="reporters.php">Reporters</a><em> - Add/Remove users with limited administrative
                        access (can't make reservations in past or for other users, but has access to reporting settings).</em></li>
                <li><a href="bans.php">Banned Users</a><em> - Prevent certain users from accessing the reservation
                        system.</em></li>
            </ul>
            <h3>Room Management</h3>
            <ul>
                <li><a href="hours.php">Open Hours</a><em> - Set up the open and closing time, as shown on the reservation page.</em></li>
                <li><a href="specialhours.php">Special Hours</a><em> - Set up room closures, holidays, etc.</em></li>
                <li><a href="rooms.php">Rooms</a><em> - Add, modify, or remove rooms and their descriptions, capacities,
                        names, etc.</em></li>
                <li><a href="defaulthours.php">Default Hours</a><em> - Set up default hours for rooms by weekday.</em>
                </li>
                <li><a href="roomgroups.php">Room Groups</a><em> - Add, modify, or remove room groups.</em></li>
            </ul>
            <h3>Reservation Tools</h3>
            <ul>
                <li><a href="customfields.php">Custom Fields</a><em> - Set up custom form fields that appear when users
                        make reservations.</em></li>
                <li><a href="email.php">Email Setup</a><em> - Set up "monitoring" email addresses to receive
                        reservation/cancellation notices as BCC emails.</em></li>
                <li><a href="multiplereservations.php">Multiple Reservations</a><em> - Create repeating reservations
                        quickly using this tool.</em></li>
                <li><a href="timing.php">Timing and Limitations</a><em> - Change the time interval, time format, and set
                        quantity and duration limits on reservations.</em></li>
            </ul>
            <h3>Settings</h3>
            <ul>
                <li><a href="about.php">About</a><em> - Edit the text on the page that users see when they click
                        "About".</em></li>
                <li><a href="configuration.php">Configuration</a><em> - Change the name of the system, security
                        settings, login settings, email filters, themes, etc.</em></li>
                <li><a href="help.php">Help</a><em> - Edit the text on the page that users see when they click
                        "Help".</em></li>
                <li><a href="policies.php">Policies</a><em> - Edit the text on the page that users see when they click
                        "Policies".</em></li>
                <li><a href="reminder.php">Reminder Message</a><em> - The Reminder Message appears at the top of every
                        page.</em></li>
            </ul>
            <?php
        }
        ?>

        <h3>Reports</h3>
        <ul>
          <li>
              <strong>Daily Schedule</strong><em> - All reservations made for a specific room. Leave the fields blank to lookup all rooms for today.</em><br/>
              <ul>
                  <li>
                      <form name="roomlookup" method="POST" action="report-schedule.php">
                        <?php
                          echo "Room: <select name='lookuproom'>";
                          echo "<option>Choose a room</option>";
                          $roomOptions = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT roomname FROM rooms ORDER BY rooms.roomgroupid, rooms.roomname ASC;");
                          while ($room = mysqli_fetch_array($roomOptions)) {
                              echo "<option value='" . $room['roomname'] . "'>" . $room['roomname'] . "</option>";
                          }
                          echo "</select>";
                        ?>
                          Date: <input id="dateDailySched" size="10" maxlength="10" name="date" type="text" placeholder="MM/DD/YYYY">
                          <img src="../includes/datechooser/calendar.gif"
                               style="cursor: pointer;" onclick="showChooser(this, 'dateDailySched', 'chooserSpan3', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                          <div id="chooserSpan3" class="dateChooser select-free"
                               style="display: none; visibility: hidden; width: 160px;"></div>
                          <input type="submit" value="Run Report"/>
                      </form>
                  </li>
              </ul>
          </li>
          <li>
              <strong>Daily Schedule with Usernames</strong><em> - All reservations with the reserver's username included</em><br/>
              <ul>
                  <li>
                      <form name="roomlookup" method="POST" action="report-usernames.php">
                            <?php
                              echo "Room: <select name='lookuproom'>";
                              echo "<option>Choose a room</option>";
                              $roomOptions = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT roomname FROM rooms ORDER BY rooms.roomgroupid, rooms.roomname ASC;");
                              while ($room = mysqli_fetch_array($roomOptions)) {
                                  echo "<option value='" . $room['roomname'] . "'>" . $room['roomname'] . "</option>";
                              }
                              echo "</select>";
                            ?>
                          Date: <input id="dateDailySchedUsername" size="10" maxlength="10" name="date" type="text" placeholder="MM/DD/YYYY">
                          <img src="../includes/datechooser/calendar.gif"
                               style="cursor: pointer;" onclick="showChooser(this, 'dateDailySchedUsername', 'chooserSpan3', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                          <div id="chooserSpan3" class="dateChooser select-free"
                               style="display: none; visibility: hidden; width: 160px;"></div>
                          <input type="submit" value="Run Report"/>
                      </form>
                  </li>
              </ul>
          </li>
            <li>
                <strong>User Lookup</strong><em> - All reservations made by specified user. Leave the field blank to lookup all users.</em><br/>
                <ul>
                    <li>
                        <form name="userlookup" method="POST" action="report-userlookup.php">
                            Username: <input type="text" name="lookupname"/><input type="submit" value="Lookup"/>
                        </form>
                    </li>
                </ul>
            </li>
            <li>
                <strong>Daily</strong><em> - Total reservations per day, per room.</em>
                <ul>
                    <li>
                        <form name="daily" method="POST" action="report-daily.php">
                            For the date: <input id="from" size="10" maxlength="10" name="from" type="text" placeholder="MM/DD/YYYY" required>
                            <img src="../includes/datechooser/calendar.gif"
                                 style="cursor: pointer;" onclick="showChooser(this, 'from', 'chooserSpan3', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                            <div id="chooserSpan3" class="dateChooser select-free"
                                 style="display: none; visibility: hidden; width: 160px;"></div>
                            and ending: <input id="to" size="10" maxlength="10" name="to" type="text" placeholder="MM/DD/YYYY" required>
                            <img src="../includes/datechooser/calendar.gif"
                                 style="cursor: pointer;" onclick="showChooser(this, 'to', 'chooserSpan3', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                            <div id="chooserSpan3" class="dateChooser select-free"
                                 style="display: none; visibility: hidden; width: 160px;"></div>
                            <input type="submit" value="Run Report"/>
                        </form>
                    </li>
                </ul>
            </li>
            <li><strong>Monthly</strong><em> - Total reservations per month, per room.</em>
                <ul>
                    <li>
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
                    </li>
                </ul>
            </li>
        </ul>
        <h3>Cancellation Reports</h3>
        <ul>
            <li>
                <strong>Daily</strong><em> - Total cancellations per day, per room.</em>
                <ul>
                    <li>
                        <form name="daily" method="POST" action="report-canceldaily.php">
                            For the period starting: <input id="fromc" size="10" maxlength="10" name="fromc"
                                                            type="text" placeholder="MM/DD/YYYY" required>
                            <img src="../includes/datechooser/calendar.gif"
                                 style="cursor: pointer;" onclick="showChooser(this, 'fromc', 'chooserSpan3', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                            <div id="chooserSpan3" class="dateChooser select-free"
                                 style="display: none; visibility: hidden; width: 160px;"></div>
                            and ending: <input id="toc" size="10" maxlength="10" name="toc" type="text" placeholder="MM/DD/YYYY" required>
                            <img src="../includes/datechooser/calendar.gif"
                                 style="cursor: pointer;" onclick="showChooser(this, 'toc', 'chooserSpan3', 1950, 2060, Date.patterns.ShortDatePattern, false);">
                            <div id="chooserSpan3" class="dateChooser select-free"
                                 style="display: none; visibility: hidden; width: 160px;"></div>
                            <input type="submit" value="Run Report"/>
                        </form>
                    </li>
                </ul>
            </li>
            <li><strong>Monthly</strong><em> - Total cancellations per month, per room.</em>
                <ul>
                    <li>
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
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    </body>
    </html>
    <?php
}
?>
