<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

<?php
error_reporting(E_ALL);

echo "<script type = 'text/javascript'>
  // When the user scrolls the page, execute myFunction
  window.onscroll = function() {myFunction()};
  // Get the header
  var header = document.getElementById('roomhead');
  // Get the offset position of the navbar
  var sticky = header.offsetTop;
  // Add the sticky class to the header when you reach its scroll position. Remove sticky when you leave the scroll position
  function myFunction() {
    if (window.pageYOffset >= sticky) {
      header.classList.add('sticky');
    } else {
      header.classList.remove('sticky');
    }
  }
  </script>

<style>
  /* The sticky class is added to the header with JS when it reaches its scroll position */
  .sticky {
  position: fixed;
  top: 0;
  width: 100%
  }
  /* Add some top padding to the page content to prevent sudden quick movement (as the header gets a new position at the top of the page (position:fixed and top:0) */
  .sticky + .content {
  padding-top: 102px;
  }
</style>";
date_default_timezone_set('America/Chicago');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pageLoadDT = date('Y-m-d H:i:s');
require_once("../includes/ClockTime.php");
require_once("../includes/or-dbinfo.php");
//If $_POST["fromrange"] and/or $_POST["torange"] aren't set, or manually set to 0, set them to today
//Whether they're set or not, make sure they are from 00:00:00 on the fromrange day and to 23:59:59 on the torange day so all reservations for the day appear
$_POST["fromrange"] = (isset($_POST["fromrange"]) && $_POST["fromrange"] != 0)
                      ? mktime(0, 0, 0, date("n", (int)$_POST["fromrange"]), date("j", (int)$_POST["fromrange"]), date("Y", (int)$_POST["fromrange"]))
                      : mktime(0, 0, 0, date("n"), date("j"), date("Y"));
$_POST["torange"] = (isset($_POST["torange"]) && $_POST["torange"] != 0)
                    ? mktime(23, 59, 59, date("n", (int)$_POST["torange"]), date("j", (int)$_POST["torange"]), date("Y", (int)$_POST["torange"]))
                    : mktime(23, 59, 59, date("n"), date("j"), date("Y"));
$_POST["group"] = (isset($_POST["group"])) ? $_POST["group"] : "";
if ($_SESSION["directRoomLink"] != "") {
  $_POST["group"] = $_SESSION["directRoomLink"];
}
if ($_POST["group"] == "") {
    $groups = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT roomgroupid FROM roomgroups ORDER BY roomgroupid ASC;");
    $group = mysqli_fetch_array($groups);
    $_POST["group"] = $group["roomgroupid"];
}

$groups = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT roomgroupname FROM roomgroups WHERE roomgroupid = " . $_POST["group"] . ";");
$groupname = mysqli_fetch_array($groups);
$_POST["groupname"] = $groupname["roomgroupname"];

//echo $_POST["groupname"];

//Pull reservations and room information from XML API
$getdatarange = include("../or-getdatarange.php");
$getroominfo = include("../or-getroominfo.php");
$xmlreservations = new SimpleXMLElement($getdatarange);
$xmlroominfo = new SimpleXMLElement($getroominfo);
$current_time = new ClockTime((float)$settings["starttime"] ?? 0, 0, 0);
$last_time = new ClockTime((float)$settings["endtime"] ?? 0, 0, 0);

$currentweekday = strtolower(date('l', $_POST["fromrange"]));
$currentmdy = date('l, F d, Y', $_POST["fromrange"]);
$todayonlymdy = date('l, F d, Y');
if ($_SESSION["username"] != "") {
    //Get all groups from DB to create Group Selector
    $groups = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM roomgroups ORDER BY roomgroupid ASC;");

        //Select menu
        $group_str = "<strong>Room Groups:</strong> <select class = 'selectMenu' id ='roomSelection'>";
        $groupOptions = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT roomgroupid, roomgroupname FROM roomgroups ORDER BY roomgroups.roomgroupid ASC;");
        while ($group = mysqli_fetch_array($groupOptions)) {
          $selected_str = "";
          if($group["roomgroupid"]==$_POST["group"]) $selected_str = "selected";
          $group_str .= "<option value='" . $group['roomgroupid'] . "' . $selected_str . >" . $group['roomgroupname'] . "</option>";
        }
        $group_str .= "</select>";
        $group_str .= "<input id='groupSubmit' type='submit' name='submit' value='Submit' onclick=\"dayviewer('" . $_POST["fromrange"] . "','" . $_POST["torange"] . "', '', '');\" />";
        $selectedGroup = $_POST["group"];

        $yesterday = strtotime($currentmdy) - 86400;
        $endyesterday = $yesterday + 86399;
        $tomorrow = strtotime($currentmdy) + 86400;
        $endtomorrow = $tomorrow + 86399;
        $dvout = "<center><div id = \"legend\" class =\"row\"><div id = \"legendText\" style=\"text-align: right\">Reservable: <span id=\"open\" class=\"glyphicon glyphicon-stop\"></span></div><div id = \"legendText\" style=\"text-align: center\">Not Reservable: <span id=\"closedList\">X</span></div><div id = \"legendText\">Your Reservations: <span class=\"glyphicon glyphicon-ok\"></span></div><div id = \"legendText\">Reserved: <span style=\"color: red;\">R</span></div><div id = \"legendText\">Cancel Reservation: <span style=\"color: red;\" class=\"fas fa-trash-alt\"></span></div></div><div class = 'row'></div></center>";
        $dvout .= "<div id=\"dayviewheader\" style=\"position: -webkit-sticky; position: sticky; top: 0; z-index: 1; background-color: c4bcc9;\"><span onclick=\"dayviewer('$yesterday','$endyesterday','$selectedGroup','');hideDiv('calendarDiv');\" class=\"glyphicon glyphicon-circle-arrow-left\"></span>&nbsp;" . $currentmdy . "&nbsp;<span onclick=\"dayviewer('$tomorrow','$endtomorrow','$selectedGroup','');hideDiv('calendarDiv');\" class=\"glyphicon glyphicon-circle-arrow-right\"></span>&nbsp";
        $dvout .= "<span style=\"position: -webkit-sticky; position: sticky; top: 0; z-index: 1; background-color: c4bcc9;\"><input id=\"calendarButton\" type=\"button\" value=\"Show/Hide Calendar\" onclick=\"showHideDiv('calendarDiv')\"/></span></div>";
        //Insert the select menu
        $dvout .= "<div id=\"selectheader\">";
        $dvout .= $group_str;
        $dvout .= "</div>";
        $dvout .= "<div id = roomhead><br>";
        $dvout .= "</div>";
        //or use this line instead to have the horizontal scrollbar at bottom of screen
        //$dvout .=  "<div class =\"table-responsive\">";
        $dvout .= "<table  id=\"dayviewTable\" cellpadding=\"0\" cellspacing=\"0\">";
    //Create optional field form items string for reservation form
    //Select all records from optionalfields table in order of optionorder ascending
    $optionalfields_string = "";
    $optionalfields_array = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM optionalfields ORDER BY optionorder ASC;");
    while ($optionalfield = mysqli_fetch_array($optionalfields_array)) {
        //Determine if required
        if ($optionalfield["optionrequired"] == 1) {
            $optionalfields_string .= "<label><strong><span class=\'requiredmarker\'>*</span>";
        } else {
            $optionalfields_string .= "<strong>";
        }
        //Print field question
        $optionalfields_string .= $optionalfield["optionquestion"] . ":</strong></label> ";
        //Determine if this field is a text field or a select field
        if ($optionalfield["optiontype"] == 0) {
            $optionalfields_string .= "<style> input[type=text] {color: black;} </style><input type=\'text\' color=\'black\' name=\'" . $optionalfield["optionformname"] . "\' /><br/>";
        } else {
            $optionalfields_string .= "<select name=\'" . $optionalfield["optionformname"] . "\'>";
            $optionchoices = explode(";", $optionalfield["optionchoices"]);

            if ($optionalfield["optionrequired"] != 1) {
              $optionalfields_string .="<option></option>";
            }
            foreach ($optionchoices as $choice) {
                $optionalfields_string .= "<option value=\'" . $choice . "\'>" . $choice . "</option>";
            }
            $optionalfields_string .= "</select><br/>";
        }
    }
    //Construct table header
    $dvout .= "<div class = 'row' style='position: -webkit-sticky; position: sticky; top: 40; z-index: 1; background-color: c4bcc9; text-align: left;'><div class = 'col-lg-2 hidden-sm-down hidden-lg-up text-nowrap'><label><b>" . $_POST["groupname"] . ":</b></label></div></div>";
    $dvout .= "<div class = 'row' style='position: -webkit-sticky; position: sticky; top: 40; z-index: 1; background-color: c4bcc9; text-align: left;'><div class = 'col-lg-2 hidden-sm-down hidden-lg-up text-nowrap'><label></label></div>";
   foreach ($xmlroominfo->room as $room) {
       $dvout .= "<div class = 'col-sm' style=\"font-size: 18px; whitespace: nowrap; width: 200px;\"><b>" . $room->name . "</b></div>";
     }
   $dvout .= "</div></div>";

    // INITIAL TIME PRINTS TWICE; THIS IS A QUICK FIX
    $i = -1;
    while ($last_time->isGreaterThan($current_time) || $last_time->isEqualTo($current_time)) {
        //Format time string
        $time_format = $settings["time_format"];
        $current_time_exploded = explode(":", $current_time->getTime());
        $current_time_tf = mktime($current_time_exploded[0], $current_time_exploded[1], $current_time_exploded[2], date("n", $_POST["fromrange"]), date("j", $_POST["fromrange"]), date("Y", $_POST["fromrange"]));
        $time_str = date($time_format, $current_time_tf);
        $end_time_str = date($time_format, strtotime("+30 minutes", $current_time_tf));
        //PRINT HOUR START HOUR ON SECOND ITERATION
        if($i >= 0){
          $dvout .= "<div class = 'row'>";
          $dvout .= "<div class = 'col-lg-2 col-sm-12 text-nowrap dayviewTime'>" . $time_str . "</div>";
          $current_stop = new ClockTime(0, 0, 0);
          $current_stop->setMySQLTime((string)$current_time->getTime());
          $current_stop->addMinutes($settings["interval"] - 1);
          foreach ($xmlroominfo->room as $room) {
              eval("\$roomhours = \$room->hours->" . $currentweekday . "->hourset;");
              $specialroomhours = $room->specialhours->hourset;
              $collision = "";
              //Loop runs for all hoursets of this room on this day
              //Proceed only if room has default hours
              if(isset($roomhours)) {
                  foreach ($roomhours as $hourset) {
                      $roomstart = new ClockTime(0, 0, 0);
                      $roomstart->setMySQLTime((string)$hourset->start);
                      $roomstop = new ClockTime(0, 0, 0);
                      $roomstop->setMySQLTime((string)$hourset->end);
                      //echo $collision .", ";
                      //If a good collision (stalactite, bat, spelunker, salamander, stalagmite) has already occured, don't run this function
                      if ($collision != "bat" && $collision != "stalactite" && $collision != "spelunker" && $collision != "salamander" && $collision != "stalagmite") {
                          $collision = collisionCave($roomstart, $roomstop, $current_time, $current_stop);
                      }
                      //echo $roomstart->getTime() .", ". $roomstop->getTime() .", ". $current_time->getTime() .", ". $current_stop->getTime() .", ". $room->name .", ". $collision ."<br/>";
                  }
              }
              //If special hours exist for this day, throw away previous results and
              //check special hours instead.
              if ((string)$specialroomhours->start[0] != "") {
                  $collision = "";
                  foreach ($specialroomhours as $hourset) {
                      $roomstart = new ClockTime(0, 0, 0);
                      $roomstart->setMySQLTime((string)$hourset->start);
                      $roomstop = new ClockTime(0, 0, 0);
                      $roomstop->setMySQLTime((string)$hourset->end);
                      //echo $collision .", ";
                      //If a good collision (stalactite, bat, spelunker, salamander, stalagmite) has already occured, don't run this function
                      if ($collision != "bat" && $collision != "stalactite" && $collision != "spelunker" && $collision != "salamander" && $collision != "stalagmite") {
                          $collision = collisionCave($roomstart, $roomstop, $current_time, $current_stop);
                      }
                      //echo $roomstart->getTime() .", ". $roomstop->getTime() .", ". $current_time->getTime() .", ". $current_stop->getTime() .", ". $room->name .", ". $collision ."<br/>";
                  }
              }
              //If final collision state is stalactite, bat, spelunker, salamander, or stalagmite, the room is open during this time interval.
              //Check to see if there are any reservations that collide with this time.
              $rescol = FALSE;
              if ($collision == "stalactite" || $collision == "bat" || $collision == "spelunker" || $collision == "salamander" || $collision == "stalagmite") {
                  //Go through each reservation in $xmlreservations, converting start and end to ClockTimes
                  //Then run collisionCave against them to see if a reservation exists here. Refer to
                  //reservations by their reservationid when linking to them.
                  //Use XPath to reduce $xmlreservations to a smaller array of reservations that are only for the current room
                  $reduced_reservations = $xmlreservations->xpath("/reservations/reservation[roomid=" . $room->id . "]");
                  foreach ($reduced_reservations as $reservation) {
                      //foreach($xmlreservations as $reservation){
                      $reservationstart = new ClockTime(0, 0, 0);
                      $reservationstart->setMySQLTime(date('H:i:s', (string)$reservation->start));
                      $reservationend = new ClockTime(0, 0, 0);
                      $reservationend->setMySQLTime(date('H:i:s', (string)$reservation->end));
                      //If the timestamp for the reservation extends beyond the day's 23:59:59 timestamp ($_POST["torange"], change reservationend to 23:59:59
                      if ((int)$reservation->end > $_POST["torange"]) {
                          $reservationend->setTime(23, 59, 59);
                      }
                      //If the timestamp for the reservation begins prior to the day's 00:00:00 timestamp ($_POST["fromrange"], change reservationstart to 00:00:00
                      if ($_POST["fromrange"] > (int)$reservation->start) {
                          $reservationstart->setTime(0, 0, 0);
                      }
                      $res_collision = collisionCave($reservationstart, $reservationend, $current_time, $current_stop);
                      //echo $res_collision .", ". $current_time->getTime() .", ". $current_stop->getTime() .", ". $reservationstart->getTime() .", ". $reservationend->getTime() .", ". $reservation->id ."<br/>";
                      if ((int)$reservation->roomid == (int)$room->id && ($res_collision == "stalactite" || $res_collision == "bat" || $res_collision == "spelunker" || $res_collision == "salamander" || $res_collision == "stalagmite")) {
                          //Determine if this reservation is the current user's or not
                          $info = "";
                          if ($reservation->username != "") {
                              $info .= "<strong>Username</strong>: " . $reservation->username . "<br/>";
                          }
                          foreach ($reservation->optionalfields->optionalfield as $option) {
                              $strip = array("'", "\"");
                              $strip_rep = array("\'", "\'");
                              $info .= "<strong>" . $option->optionname . "</strong>: " . str_replace($strip, $strip_rep, $option->optionvalue) . "<br/>";
                          }
                          if ($_SESSION["username"] != (string)$reservation->username) {
                              //Display "taken" button that shows public info.
                              //$collision = "<span id=\"takenList\" class=\"glyphicon glyphicon-remove\"></span>";
                              if ($isadministrator == "TRUE" || $issupervisor == "TRUE") {
                                $collision =  "<span title=\"Reserved by: $reservation->username\" id=\"takenList\">R</span>";
                                $collision .=  "<span title=\"Cancel Reservation\" onClick=\"cancelQuestion(" . $reservation->id . "," . $_POST["group"] . ");\" id=\"cancelList\" class=\"fas fa-trash-alt\" style=\"font-size: 12pt; cursor: pointer;\"></span>";
                              }
                              else {
                                $collision = "<span id=\"takenList\">R</span>";
                              }
                          } else {
                              if ($isadministrator == "TRUE" || $issupervisor == "TRUE") {
                                $collision =  "<span title=\"Reserved by: $reservation->username\" id=\"reservationList\" class=\"glyphicon glyphicon-ok\"></span>";
                                $collision .=  "<span title=\"Cancel Reservation\" id=\"cancelList\" class=\"fas fa-trash-alt\" style=\"font-size: 12pt; cursor: pointer;\" onClick=\"cancelQuestion(" . $reservation->id . "," . $_POST["group"] . ");\"></span>";
                              }
                              else {
                                $collision =  "<span id=\"reservationList\" class=\"glyphicon glyphicon-ok\"></span>";
                                $collision .=  "<span title=\"Cancel Reservation\" id=\"cancelList\" class=\"fas fa-trash-alt\" style=\"font-size: 12pt; cursor: pointer;\" onClick=\"cancelQuestion(" . $reservation->id . "," . $_POST["group"] . ");\"></span>";
                              }
                              //$collision = "<img style=\"cursor: pointer;\" \" border=\"0\" onClick=\"showPopUp(this,'" . $info . "');\" />";
                          }
                          $rescol = TRUE;
                      }
                  }
              }
              if ($rescol && $collision == "stalactite" || $collision == "bat" || $collision == "spelunker" || $collision == "salamander" || $collision == "stalagmite") {
                  //Display "reserve" button that shows reservation form.
                  $limit_total = unserialize($settings["limit_total"]);
                  $limit_frequency = unserialize($settings["limit_frequency"]);
                  $current_duration = $settings["interval"] + 30;
                  $durationhtml = "<option value=\'" . "30 mins". "\'>" . 30 . " mins</option>";
                  while ($current_duration <= $settings["limit_duration"]) {
                      $durationhtml .= "<option value=\'" . $current_duration . "\'>" . $current_duration . " mins</option>";
                      $current_duration += $settings["interval"];
                  }
                  $capacity = $room->capacity;
                  $capacitymin = $room->capacitymin;
                  $capacity_string = "";
                  for ($cc = (int)$capacitymin; $cc <= $capacity; $cc++) {
                      $capacity_string .= "<option value=\'" . $cc . "\'>" . $cc . "</option>";
                  }
                  //If this user is an administrator, give them the option of inputting and altusername
                  $altusernamestr = "";
                  if ($isadministrator == "TRUE" || $issupervisor == "TRUE") {
                      $altusernamestr = "<strong>Username:</strong> <style> input[type=text] {color: black; margin: 5px;}</style> <input type=\'text\' name=\'altusername\' color=\'black\' /><br/><strong>Email Confirmation: </strong>&nbsp<select name=\'emailconfirmation\'><option value=\'no\'>No</option><option value=\'yes\'>Yes</option></select><br/>";
                  }
                  $info = "<strong>Room</strong>: " . $room->name . "<br/><strong>Start Time</strong>: " . $time_str . "<br/><form name=\'reserve\' action=\'javascript:reserve(" . $_POST["group"] . ");\'>" . $altusernamestr . "<input type=\'hidden\' name=\'roomid\' value=\'" . $room->id . "\' /><input type=\'hidden\' name=\'starttime\' value=\'" . strtotime($currentmdy . " " . $current_time->getTime())
                  . "\' /><input type=\'hidden\' name=\'fullcapacity\' value=\'" . $capacity . "\' /><strong><span class=\'requiredmarker\'>*</span>Duration</strong>: <select name=\'duration\'>" . $durationhtml . "</select><br/><strong><span class=\'requiredmarker\'>*</span>Number in group</strong>: <select name=\'capacity\'>" . $capacity_string . "</select><br/>" . $optionalfields_string . "<br/><center><strong>Reserve this room?</strong>: <a href=\'javascript:reserve(" . $_POST["group"] . ");\'>Yes</a> <a href=\'javascript:closePopUp();\'>No</a></center></form><br/><span class=\'requirednote\'><span class=\'requiredmarker\'>*</span> denotes a required field</span>";
                  //$collision = "<img style=\"cursor: pointer;\" src=\"". $_SESSION["themepath"] ."images/reservebutton.png\" border=\"0\" onClick=\"showPopUp(this,'". $info ."');\" />";
                  $collision = "<span title=\"Capacity: $room->capacity \nDescription: $room->description\" id=\"openList\" class=\"glyphicon glyphicon-stop\"  style=\"cursor: pointer;\"
                                onClick=\"showPopUpReserve(this,'" . $room->name . "','" . $room->description . "','" . $time_str . "','" . $_POST["group"] . "','" . $altusernamestr . "','" . $room->id . "','" . strtotime($currentmdy . " " . $current_time->getTime()) . "','"
                                . $capacity . "','" . $durationhtml . "','" . $capacity_string . "','" . $optionalfields_string . "','" . $end_time_str . "');\" />\n";
              } elseif (!$rescol) {
                  //Display "closed" button that is not interactive.
                  //$collision = "<span id=\"closedList\" class=\"glyphicon glyphicon-stop\"></span>";
                  $collision = "<span id=\"closedList2\">X</span>";
              }
              //creates combined datetime with the current selected date and the time along the sidebar
              $combinedDT = date('Y-m-d H:i:s', strtotime($currentmdy . $current_time->getTime()));
              $dayEndDT = date('Y-m-d H:i:s', strtotime($todayonlymdy . $last_time->getTime()));
              //"closes" rooms as time passes based on the current time on page load
              $sapr = $settings["allow_past_reservations"];
              if ($sapr != "true" && $isadministrator != "TRUE" && $issupervisor != "TRUE") {
                if ($collision != "<span id=\"reservationList\" class=\"glyphicon glyphicon-ok\"></span>" &&
                    //$collision != "<span id=\"takenList\" class=\"glyphicon glyphicon-remove\"></span>") {
                    $collision != "<span id=\"takenList\">R</span>") {
                  if ($pageLoadDT > $combinedDT) {
                    $collision = "<span id=\"closedList2\">X</span>";
                    //$collision = "<span id=\"closedList\" class=\"glyphicon glyphicon-stop\"></span>";
                  }
                  //$combinedDT values that reach this point are the remaining reservation boxes that are still
                  //open for reservation for the rest of the current day. This if statement effectively
                  //closes the room for the rest of the current day, forcing users to consult a supervisor or
                  //admin if they want to make a room reservation for the current day
                  if ($issupervisor != "TRUE") {
                    if ($combinedDT <= $dayEndDT) {
                      $collision = "<span id=\"closedList2\">X</span>";
                    }
                  }
                }
              }
              //$dvout .= "<div class='col' onMouseOver=\"roomDetails('<span id=\'roomdetailsname\'>" . $room->name . "</span><br/><span id=\'roomdetailscapacitylabel\'>Capacity: </span><span id=\'roomdetailscapacity\'>" . $room->capacity . "</span><br/>" . $room->description . "');\">" . $collision . "</div>";
              $dvout .= "<div class='col' style='margin-left: 50px;'> $collision </div>";
            }
            //Increment
            $i++;
        }
        $dvout .= "</div></div>";
        //Increment
        $i++;
        //Increment time by Interval
        $current_time->addMinutes($settings["interval"]);
    }
    echo $dvout;
} //User isn't logged in
else {
    echo "Error: User is not logged in.";
}
?>
