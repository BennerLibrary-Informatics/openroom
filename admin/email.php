<?php
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
        case "email_res_verbose":
            $email_res_verbose = isset($_REQUEST["email_res_verbose"]) ? $_REQUEST["email_res_verbose"] : "";

            $email_res_verbose = trim($email_res_verbose);
            $email_res_verbose = str_replace(" ", "", $email_res_verbose);
            $email_res_verbose = explode(",", $email_res_verbose);
            $email_res_verbose = serialize($email_res_verbose);
            if (mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE settings SET settingvalue='" . $email_res_verbose . "' WHERE settingname='email_res_verbose';")) {
                $successmsg = "OnReservations-Addresses has been updated.";
            } else {
                $errormsg = "Unable to update OnReservations-Addresses. Make sure each email address is separated by a comma.";
            }

            break;
        case "email_can_verbose":
            $email_can_verbose = isset($_REQUEST["email_can_verbose"]) ? $_REQUEST["email_can_verbose"] : "";

            $email_can_verbose = trim($email_can_verbose);
            $email_can_verbose = str_replace(" ", "", $email_can_verbose);
            $email_can_verbose = explode(",", $email_can_verbose);
            $email_can_verbose = serialize($email_can_verbose);
            if (mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE settings SET settingvalue='" . $email_can_verbose . "' WHERE settingname='email_can_verbose';")) {
                $successmsg = "OnCancellation-Addresses has been updated.";
            } else {
                $errormsg = "Unable to update OnCancellation-Addresses. Make sure each email address is separated by a comma.";
            }

            break;
        case "email_system":
            $email_system = isset($_REQUEST["email_system"]) ? $_REQUEST["email_system"] : "";
            if ($email_system != "" && preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', $email_system)) {
                if (mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE settings SET settingvalue='" . $email_system . "' WHERE settingname='email_system';")) {
                    $successmsg = "System Email has been updated.";
                } else {
                    $errormsg = "Unable to set System Email. Please try again.";
                }
            } else {
                $errormsg = "Unable to set System Email. Make sure the format is correct.";
            }
            break;
        case "phone_number":
            $phone_number = isset($_REQUEST["phone_number"]) ? $_REQUEST["phone_number"] : "";
            if ($phone_number != "" && preg_match("/^(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/", $phone_number)) {
                if (mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE settings SET settingvalue='" . $phone_number . "' WHERE settingname='phone_number';")) {
                    $successmsg = "Phone Number has been updated.";
                } else {
                    $errormsg = "Unable to set Phone Number. Please try again.";
                }
            } else {
                $errormsg = "Unable to set Phone Number. Make sure the format is correct.";
            }
            break;
        case "email_message":
            $email_message = isset($_REQUEST["email_message"]) ? $_REQUEST["email_message"] : "";
                if (mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE settings SET settingvalue='" . $email_message . "' WHERE settingname='email_message';")) {
                    $successmsg = "Email Message has been updated.";
                } else {
                    $errormsg = "Unable to set Email Message. Please try again.";
                }
            break;
        case "condition":
            $email_condition = isset($_REQUEST["email_condition"]) ? $_REQUEST["email_condition"] : "";
            $email_condition_value = isset($_REQUEST["email_condition_value"]) ? $_REQUEST["email_condition_value"] : "";
            if ((mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE settings SET settingvalue='" . $email_condition . "' WHERE settingname='email_condition';")) && (mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE settings SET settingvalue='" . $email_condition_value . "' WHERE settingname='email_condition_value';"))) {
                $successmsg = "Condition has been updated.";
            } else {
                $errormsg = "Unable to update Condition. Please try again.";
            }
            break;
        case "email_cond_verbose":
            $email_cond_verbose = isset($_REQUEST["email_cond_verbose"]) ? $_REQUEST["email_cond_verbose"] : "";
            $email_cond_verbose = trim($email_cond_verbose);
            $email_cond_verbose = str_replace(" ", "", $email_cond_verbose);
            $email_cond_verbose = explode(",", $email_cond_verbose);
            $email_cond_verbose = serialize($email_cond_verbose);
            if (mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE settings SET settingvalue='" . $email_cond_verbose . "' WHERE settingname='email_cond_verbose';")) {
                $successmsg = "OnCondition-Addresses has been updated.";
            } else {
                $errormsg = "Unable to update OnCondition-Addresses. Make sure each email address is separated by a comma.";
            }
            break;
    }

    require_once(__DIR__ . '/../vendor/autoload.php');
    $settings = model\Setting::fetch_all();
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title><?php echo $settings["instance_name"]; ?> - Administration - Email Setup</title>
        <link rel="stylesheet" type="text/css" href="adminstyle.css"/>
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>
        <script language="javascript" type="text/javascript">
            function confirmdelete(roomgroupid) {
                var answer = confirm("Are you sure?");
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

            $email_res_verbose = implode(",", unserialize($settings["email_res_verbose"]));
            $email_can_verbose = implode(",", unserialize($settings["email_can_verbose"]));
            $email_cond_verbose = implode(",", unserialize($settings["email_cond_verbose"]));
            ?>
        </center>
        <h3><a href="index.php">Administration</a> - Email Setup</h3><br/><hr/>
        An email will be sent BCC to any email addresses entered in the fields below whenever the specified action occurs. You can enter multiple addresses in a single field by separating them with semicolons. <strong>Ex: email1@domain.com;email2@domain.com</strong><hr/></span>
        <ul id="settingsul">
            <li>
                On Reservations - <span class="notetext">This email is sent whenever a reservation is made.</span>
                <ul>
                    <li>
                        <form name="onreserveverbose" action="email.php" method="POST">Addresses: <input type="text"
                                                                                                       name="email_res_verbose"
                                                                                                       value="<?php echo $email_res_verbose; ?>"/><input
                                    type="hidden" name="op" value="email_res_verbose"/><input type="submit"
                                                                                              value="Save"/></form>
                    </li>
                </ul>
                <br/>
            </li>
            <li>
                On Condition - <span class="notetext">This email is sent whenever certain conditions are met when reservations or cancellations are made.<br/>(*This will NOT be sent to users.)<br/>(*When dealing with Duration and Number In Group, the condition is met when the user enters a value greater than or equal to the conditional value.)</span>
                <ul>
                    <li>
                        <form name="oncondition" action="email.php" method="POST">When <select name="email_condition">
                                <option value="">None</option>
                                <option value="duration"<?php if ($settings["email_condition"] == "duration") echo " selected"; ?>>
                                    Duration
                                </option>
                                <option value="capacity"<?php if ($settings["email_condition"] == "capacity") echo " selected"; ?>>
                                    Number In Group
                                </option>
                                <?php
                                //Grab all optional fields
                                $ofselected = "";
                                $ofs = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM optionalfields;");
                                while ($of = mysqli_fetch_array($ofs)) {
                                    if ($settings["email_condition"] == $of["optionformname"]) {
                                        $ofselected = " selected";
                                    }
                                    echo "<option value=\"" . $of["optionformname"] . "\"" . $ofselected . ">" . $of["optionname"] . "</option>";
                                }
                                ?>
                            </select> = <input type="text" name="email_condition_value"
                                               value="<?php echo $settings["email_condition_value"]; ?>"/>
                            <input type="hidden" name="op" value="condition"/>
                            <input type="submit" value="Save"/></form>
                        <ul>
                            <li>
                                <form name="onconditionverbose" action="email.php" method="POST">Addresses: <input
                                            type="text" name="email_cond_verbose"
                                            value="<?php echo $email_cond_verbose; ?>"/><input type="hidden" name="op"
                                                                                               value="email_cond_verbose"/><input
                                            type="submit" value="Save"/></form>
                            </li>
                        </ul>
                    </li>
                </ul>
                <br/>
            </li>
            <li>
                On Cancellations - <span class="notetext">This email is sent whenever a reservation is cancelled.</span>
                <ul>
                    <li>
                        <form name="oncancelverbose" action="email.php" method="POST">Addresses: <input type="text"
                                                                                                      name="email_can_verbose"
                                                                                                      value="<?php echo $email_can_verbose; ?>"/><input
                                    type="hidden" name="op" value="email_can_verbose"/><input type="submit"
                                                                                              value="Save"/></form>
                    </li>
                </ul>
                <br/>
            </li>
            <li>
                System Address - <span class="notetext">This address is used in the "from" and "reply-to" fields. It is also the address that will be used for users to contact administrators.</span>
                <ul>
                    <li>
                        <form name="systemaddress" action="email.php" method="POST">
                            <input type="text" name="email_system" value="<?php echo $settings["email_system"]; ?>"/>
                            <input type="hidden" name="op" value="email_system"/>
                            <input type="submit" value="Save"/>
                        </form>
                    </li>
                </ul>
            </li>
            <br/>
            <li>
                Phone Number - <span class="notetext">This is the number that will be used for users to contact administrators.</span>
                <ul>
                    <li>
                        <form name="phonenumber" action="email.php" method="POST">
                            <input type="text" name="phone_number" value="<?php echo $settings["phone_number"]; ?>"/>
                            <input type="hidden" name="op" value="phone_number"/>
                            <input type="submit" value="Save"/>
                        </form>
                    </li>
                </ul>
            </li>
            <br/>
            <li>
                Email Message - <span class="notetext">This is the customizable part of the email message that will be sent to users.
                                                       The following reservation details will also be dynamically added: username, date and time, number in group, any optional field data, and policies link.
                                                       The email message ends with a line referring the user to contact whatever phone number is set in the field above if they have questions.</span>
                  <form name="emailMessage" action="email.php" method="POST">
                    <textarea name="email_message" cols="50" rows="10"/><?php echo $settings["email_message"]; ?></textarea>
                    <br/>
                    <input type="hidden" name="op" value="email_message"/>
                    <input type="submit" value="Save Changes"/>
                  </form>
            </li>
        </ul>
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
