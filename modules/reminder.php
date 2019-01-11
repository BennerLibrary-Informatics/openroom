<?php
if ($settings["remindermessage"] != "") {
    $reminder_string = htmlentities($settings["remindermessage"], ENT_COMPAT, 'UTF-8');
    echo "<div id=\"remindermessage\"><span class=\"remindermessage\"><span style=\"font-size: large; color: yellow;\">&#9888;&nbsp; </span>" . $reminder_string . "</span></div>";
}
?>
