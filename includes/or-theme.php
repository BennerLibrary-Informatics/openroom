<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("includes/or-dbinfo.php");
require_once('vendor/autoload.php');

/*
*Simply include this file and it will set SESSION["device"] appropriately.
*This file determines the type of device through a simple detection script.
*This information is then used along with the settings table to set the
*correct theme path in the SESSION. If no theme is specified
*themes/default is used.
*/
$_SESSION["device"] = "";

$detect = new \Mobile_Detect();
if ($_SESSION["device"] != "mobile" && $_SESSION["device"] != "desktop") {
    $_COOKIE["theme"] = "";
}

if ($detect->isMobile() || $detect->isTablet()) {
    $_SESSION["device"] = "mobile";
} else {
    $_SESSION["device"] = "desktop";
}

if ((isset($_COOKIE["theme"])) && ($_COOKIE["theme"] == "mobile" || $_COOKIE["theme"] == "desktop")) {
    $_SESSION["device"] = $_COOKIE["theme"];
}

if ($settings["theme"] == "") $settings["theme"] = "default";

$_SESSION["themepath"] = "themes/" . $settings["theme"] . "/" . $_SESSION["device"] . "/";
?>
