<script type="text/javascript">
    function ajaxAuthenticate() {
        var xmlHttp;
        try {
            // Firefox, Opera 8.0+, Safari
            xmlHttp = new XMLHttpRequest();
        }
        catch (e) {
            // Internet Explorer
            try {
                xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch (e) {
                try {
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                catch (e) {
                    alert("Your browser does not support AJAX!");
                    return false;
                }
            }
        }

        xmlHttp.onreadystatechange = function () {

            if (xmlHttp.readyState == 4) {
                var xmldoc = xmlHttp.responseXML;
                var authenticated = xmldoc.getElementsByTagName('authenticated')[0].firstChild.nodeValue;
                alert(authenticated);
                var errormessage = xmldoc.getElementsByTagName('errormessage')[0].firstChild;

                if (authenticated == "false") {
                    if (errormessage.nodeValue == "No such object") errormessage.nodeValue = "Incorrect username or password.";
                    document.getElementById('errormessage').style.visibility = "visible";
                    document.getElementById('errormessage').innerHTML = ("<strong>Error: <\/strong>" + errormessage.nodeValue);
                }
                else {
                    window.location.href = "index.php";
                }
            }
        };
        urlstring = "or-authenticate.php";

        params = "username=" + encodeURIComponent(document.getElementById("authentication").username.value) + "&password=" + encodeURIComponent(document.getElementById("authentication").password.value) + "&ajax_indicator=TRUE";

        xmlHttp.open("POST", urlstring, true);
        xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlHttp.send(params);
    }

    function init() {
        document.getElementsByTagName('input')[0].focus();
    }

    window.onload = init;

</script>

<div id="loginform">
    <form id="authentication" onsubmit="return false" action="">
      <div id="loginusername" class = "form-group row">
        <div class = "col-lg-3 col-md-3 col-sm-12 loginlabel offset-lg-1">
          <label for="loginusernamelabel">Username:</label>
        </div>
        <div class = "col-lg-4 col-md-6 col-sm-12">
              <input type="text" name="username" id="usernamefield"
                     class="form-control form-control-sm" placeholder="Your Olivet Username" autofocus="autofocus"/>
        </div>
      </div>

      <div id="loginpassword" class = "form-group row">
        <div class = "col-lg-3 col-md-3 col-sm-12 passwordlabel offset-lg-1">
          <label for="loginpasswordlabel">Password:</label>
        </div>
        <div class = "col-lg-4 col-md-6 col-sm-12">
          <input type="password" name="password" id="passwordfield"
                 class="form-control form-control-sm" placeholder="Your Olivet password"/>
        </div>
      </div>
      <div class = "form-group row">
        <div class = "col-lg-4 offset-lg-4">
          <input id="loginsubmitbutton" class="button btn btn-primary" type="submit" value="Log In"
            onclick="ajaxAuthenticate()"/>
        </div>
        <!--commenting out section because we are going to use LDAP instead of normal login, so users
            don't need to be shown a 'create account' button
        <div class = "col-lg-4 col-md-6 col-sm-6 ">
            <?php
            //if ($settings["login_method"] == "normal") {
            //    echo "<a href=\"createaccount.php\" class = 'createacc'><span class = 'registrationlink btn btn-primary createaccbutt' >Create Account</span></a>";
            //}
            ?>
        </div>-->
      </div>
      <div class="col-lg-4 offset-lg-4">
        <a href="https://changemypassword.olivet.edu/" style="float: right;"> Forgot Password?</a>
      </div>
    </form>
    <br><div id="errormessage"></div>
</div>
