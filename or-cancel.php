<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("includes/or-dbinfo.php");
//Check if user is logged in. Set $username accordingly.
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";
//Check if user is an administrative user. Set $isadministrator accordingly.
$isadministrator = isset($_SESSION["isadministrator"]) ? $_SESSION["isadministrator"] : "FALSE";
$issupervisor = isset($_SESSION["issupervisor"]) ? $_SESSION["issupervisor"] : "FALSE";
$reservationid = isset($_POST["reservationid"]) ? $_POST["reservationid"] : 0;
$reservation_res = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservations WHERE reservationid='" . $reservationid . "';");
$reservation = mysqli_fetch_array($reservation_res);
$res_username = $reservation["username"];
$errormsg = "";
if (($isadministrator || $username == $res_username || $issupervisor) && $username != "") {
    //Simply transfer this reservation to the cancelled table. Its ID will still be used when reporting and checking its optional fields (which are left alone).
    $cancel_res = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO cancelled(reservationid,start,end,roomid,username,timeofrequest) VALUES('" . $reservationid . "','" . $reservation["start"] . "','" . $reservation["end"] . "','" . $reservation["roomid"] . "','" . $reservation["username"] . "','" . $reservation["timeofrequest"] . "');");
    //Then delete it from the reservations table
    if ($cancel_res) {
        $remove_res = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM reservations WHERE reservationid=" . $reservationid . ";");
        if ($remove_res) {
            $email_can_verbose = implode(",", unserialize($settings["email_can_verbose"]));
            $email_cond_verbose = implode(",", unserialize($settings["email_cond_verbose"]));
            $email_system = $settings["email_system"];
            //Get user's email address
            //If using login_method ldap just use the user's username and the ldap_baseDN dc's
            //If using login_method normal you must grab the user's email property from the users table
            $user_email = "";
            $domain = "";
            if ($settings["login_method"] == "ldap") {
                // 	$ldapdn = explode(",", $settings["ldap_baseDN"]);
                // 	$count = 0;
                // 	foreach($ldapdn as $dn){
                // 		if(substr($dn,0,3) == "dc="){
                // 			if($count > 0){
                // 				$dotstr = ".";
                // 			}
                // 			$domain .= $dotstr . substr($dn,3);
                // 			$count++;
                // 		}
                // 	}
                // 	$user_email = $username ."@". $domain;
                if ($res_username == $_SESSION["username"]) {
                    $user_email = $_SESSION["username"] . "@olivet.edu";
                } else {
                    require_once(__DIR__ . '/vendor/autoload.php');
                    $user = new model\User($res_username);
                    $user_email = $user->get_emailaddress();
                }
            }
            if ($settings["login_method"] == "normal") {
                $emailrecord = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE username='" . $res_username . "';");
                if ($emailrecord) {
                    $user_emaila = mysqli_fetch_array($emailrecord);
                    $user_email = $user_emaila["email"];
                }
            }
            $roomname = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM rooms WHERE roomid=" . $reservation["roomid"] . ";"));
            $capacity = $roomname["roomcapacitymax"];
            $roomname = $roomname["roomname"];
            $starttime = strtotime($reservation["start"]);
            $endtime = strtotime($reservation["end"]);
            $endtime += 60;
            $duration = $endtime - $starttime;
            //Create verbose email message
            //VERBOSE
            $verbose_msg = "Your reservation for Room " . $roomname . " from " . date("F j, Y g:i a", $starttime) . " to " . date("F j, Y g:i a", $endtime) . " has been cancelled.<br/><br/>" . "Thank you for using " . $settings["instance_name"] . "! \n\n";
            $verbose_msg .= "Please call 815-939-5354 if you need further assistance";

            $bccstr = "";
            if ($email_can_verbose != "") {
                $bccstr = "\r\nBcc: " . $email_can_verbose;
            }
            mail($user_email, $settings["instance_name"] . " Cancellation", $verbose_msg, "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\nFrom: " . $email_system . "\r\nReturn-Path: " . $email_system . "\r\nReply-To: " . $email_system . $bccstr . "',' -f" . $email_system);
            //On Condition emails
            //Get the current email_condition and email_value
            //If condition == "none" skip this, if it is "duration" or "capacity" check those values
            //If it is something else, check that particular optional field
            if ($settings["email_condition"] != "none") {
                if ($settings["email_condition"] == "duration" && $duration >= $settings["email_condition_value"]) {
                    mail($email_cond_verbose, $settings["instance_name"] . " Cancellation (Condition Met)", $verbose_msg, "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\nFrom: " . $email_system . "\r\nReturn-Path: " . $email_system . "\r\nReply-To: " . $email_system);
                } elseif ($settings["email_condition"] == "capacity" && $capacity >= $settings["email_condition_value"]) {
                    mail($email_cond_verbose, $settings["instance_name"] . " Cancellation (Condition Met)", $verbose_msg, "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\nFrom: " . $email_system . "\r\nReturn-Path: " . $email_system . "\r\nReply-To: " . $email_system);
                } else {
                    $thecond = $settings["email_condition"];
                    //Get optionname from optionalfields table
                    $optionnamearray = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM optionalfields WHERE optionformname ='" . $thecond . "';"));
                    $optionname = $optionnamearray["optionname"];
                    //Get any record with this optionname and this reservation's ID from reservationoptions table
                    $thisoptions = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM reservationoptions WHERE reservationid=" . $reservationid . " AND optionname='" . $optionname . "';"));
                    $thisov = $thisoptions["optionvalue"];
                    if ($thisov == $settings["email_condition_value"]) {
                        mail($email_cond_verbose, $settings["instance_name"] . " Cancellation (Condition Met)", $verbose_msg, "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\nFrom: " . $email_system . "\r\nReturn-Path: " . $email_system . "\r\nReply-To: " . $email_system);
                    }
                }
            }
            echo "This reservation has been cancelled!|" . $starttime . "|" . mktime(23, 59, 59, date("m", $starttime), date("d", $starttime), date("Y", $starttime));
        } else {
            mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM cancelled WHERE reservationid=" . $reservationid . ";");
            $errormsg = "There was a problem cancelling your reservation. If this problem persists, please contact an administrator. Code: E-Cancel-Inner";
        }
    } else {
        $errormsg = "There was a problem cancelling your reservation. If this problem persists, please contact an administrator. Code: E-Cancel-Outer";
    }
} else {
    if ($username == "") {
        $errormsg = "Error: User was not logged in.";
    } else {
        $errormsg = "You do not have access to this reservation.";
    }
}
echo $errormsg;
?>
