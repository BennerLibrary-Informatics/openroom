<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION["systemid"]);
unset($_SESSION["themepath"]);
unset($_SESSION["username"]);
unset($_SESSION["emailaddress"]);
unset($_SESSION["displayname"]);
unset($_SESSION["isadministrator"]);
unset($_SESSION["isreporter"]);
unset($_SESSION["issupervisor"]);

header("Location:../index.php");
?>
