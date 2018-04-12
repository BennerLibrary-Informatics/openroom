<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("includes/or-theme.php");
include($_SESSION["themepath"] . "header.php");

if ($_SESSION["systemid"] == $settings["systemid"]) {

    //Form Processing
    $submitted = isset($_POST["submitted"]) ? $_POST["submitted"] : "";
    $errormsg = "";

    $emaila = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE username='" . $_SESSION["username"] . "';"));

    if ($submitted == "1") {
        $password = isset($_POST["password"]) ? $_POST["password"] : "";
        $confirm = isset($_POST["confirm"]) ? $_POST["confirm"] : "";
        $email = isset($_POST["email"]) ? $_POST["email"] : "";

        if ($password != "" || $confirm != "") {
            if ($password != $confirm) {
                $errormsg .= "New Password and Conform Password do not match.<br/>";
            } //Passwords have been entered and match so put in a new password
            else {
                $encpass = sha1($password);
            }
        } //No password change, set encpass equal to old password
        else {
            $encpass = $emaila["password"];
        }

        if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
            $errormsg .= "Invalid email address.<br/>";
        }

        if ($errormsg == "") {
            //Update account for this user
            if (mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE users SET password = '" . $encpass . "', email = '" . $email . "' WHERE username='" . $_SESSION["username"] . "';")) {
                $successmsg = "Your account has been updated!";
            } else {
                $errormsg = "Account could not be updated.<br/>";
            }
        }
    }

    $emaila = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE username='" . $_SESSION["username"] . "';"));
    $email = $emaila["email"];

    ?>

    <center>
        <?php
        if ($successmsg != "") {
            echo "<div id=\"successmsg\">" . $successmsg . "</div>";
        }
        if ($errormsg != "") {
            echo "<div id=\"errormsg\">" . $errormsg . "</div>";
        }
        ?>
    </center>
    <h3><a href="index.php"><?php echo $settings["instance_name"]; ?></a> - Edit Account</h3>

    <form name="editaccount" method="POST" action="editaccount.php">
<<<<<<< HEAD
      <div class = "row">
        <div class = "col-2 offset-2 text-right"><label for = "password">Change Password:</label></div>
        <div class = "col-3"><input id = "password" type="password" name="password"/></div>
      </div>

      <div class = "row">
        <div class = "col-2 offset-2 text-right"<label for = "passwordconf">Confirm Password:</label></div>
        <div class = "col-3"><input id = "passwordconf" type="password" name="confirm"/></div>
      </div>

      <div class = "row">
        <div class = "col-2 offset-2 text-right"><label for = "">Email: </label></div>
        <div class = "col-3"><input type="text" class = "form-control" name="email" value="<?php echo $email; ?>"/></div>
        <div class = "row"><em><?php
                $emailfilters = unserialize($settings["email_filter"]);
                $comma = 0;
                foreach ($emailfilters as $filter) {
                    if ($comma == 0) {
                        echo "example@" . $filter;
                        $comma = 1;
                    } else {
                        echo ", example@" . $filter;
                    }
                }
                ?></em>
        </div>
      </div>
      <div class = "row col-6 subbutt centered">
=======
      <div class = "form-group row">
        <div class = "col-lg-3 col-md-4 col-sm-12 offset-lg-1 passwordlabel">
          <label for = "password">Change Password:</label>
        </div>
        <div class = "col-lg-4 col-md-6 col-sm-12 passwordlabel">
          <input id = "password" type="password" name="password"/>
        </div>
      </div>

      <div class = "form-group row">
        <div class = "col-lg-3 col-md-4  col-sm-12 offset-lg-1 passwordlabel">
          <label for = "passwordconf">Confirm Password:</label>
        </div>
        <div class = "col-lg-4 col-md-6 col-sm-12">
          <input id = "passwordconf" type="password" name="confirm"/>
        </div>
      </div>

      <div class = "form-group row">
        <div class = "col-lg-1 col-md-2 col-sm-12 offset-md-2 offset-lg-3">
          <label for = "">Email: </label>
        </div>
        <div class = "col-lg-4 col-md-6 col-sm-12">
          <input type="text" class = "form-control" name="email" value="<?php echo $email; ?>"/>
        </div>
        <div class = "col-md-10 col-lg-4 emailFilter"><em><?php
            $emailfilters = unserialize($settings["email_filter"]);
            $comma = 0;
            foreach ($emailfilters as $filter) {
                if ($comma == 0) {
                    echo "example@" . $filter;
                    $comma = 1;
                } else {
                    echo ", example@" . $filter;
                }
            }
            ?></em>
          </div>
      </div>
      <div class = "col-sm-6 offset-lg-3 offset-sm-3 subbutt ">
>>>>>>> parent of 0f7c9b3... Merge pull request #27 from BennerLibrary-Informatics/allen-css
        <input type="hidden" name="submitted" value="1"/><input type="submit" value="Save"/>
      </div>
    </form>

    <?php
    include($_SESSION["themepath"] . "footer.php");
} else {
    echo "You are not logged in. Please <a href=\"index.php\">click here</a> and login with an authorized account.";
}
?>
