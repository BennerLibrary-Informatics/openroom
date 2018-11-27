
</div>
<?php
require_once("includes/or-dbinfo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title><?php echo $settings["instance_name"]; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION["themepath"]; ?>style.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <meta http-equiv="Content-Script-Type" content="text/javascript"/>
</head>

<body>
<div id = "container">
 <div id="heading">
     <a href="https://library.olivet.edu/index.php" style="text-decoration:none; color:white; float: left; margin-top: 20; margin-left: 10; margin-right: -7;"><img src = 'https://library.olivet.edu/img/logo.svg'>
     <div id = "headerText">
       <div class = "ONUtitle">
         <a href="https://library.olivet.edu/index.php" style="text-decoration:none; color:white; margin-left: 8;">
           Benner Library
         <a style="text-decoration:none; color:white; float: right;">
           Open Room
       </div>
         </a>
         </a>
       </div>
       <div class = "ONUsubtitle">
         <a href="https://library.olivet.edu/index.php" style="text-decoration:none; color:white; margin-left: 15;">
           Olivet Nazarene University
         <a style="text-decoration:none; color:white; float: right;">
           Reserve a Space
       </div>
         </a>
         </a>
         <div class = "usernametitle">
           <span class="username">
             <?php
             if (!isset($_SESSION["systemid"])) {
               $_SESSION["systemid"] = "";
             }

             if ($_SESSION["systemid"] == $settings["systemid"]) {

               if (!isset($_SESSION["username"])) {
                 $_SESSION["isadministrator"] = "FALSE";
                 $_SESSION["isreporter"] = "FALSE";
                 $_SESSION["issupervisor"] = "FALSE";
               }
               echo isset($_SESSION["username"]) ? $_SESSION["username"] : "&nbsp;"; ?></span>&nbsp;<?php
               echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(<a href=\"admin/index.php\" style=\"color:white;\">Admin</a>)</span>&nbsp;" : "";
               echo ($_SESSION["isreporter"] == "TRUE") ? "<span class=\"isreporter\">(<a href=\"admin/index.php\" style=\"color:white;\">Reporter</a>)</span>&nbsp;" : "";
               echo ($_SESSION["issupervisor"] == "TRUE") ? "<span class=\"issupervisor\">(<a style=\"color:white;\">Supervisor</a>)</span>&nbsp;" : "";
             }
              ?>
            </div>
 </div>
<?php include("modules/reminder.php"); ?>
<div id="whiteBreak"></div>
<div id="menuHeader">
    <span class="username">
      <p style="float: left;">&nbsp;</p>
      <a href onclick="aboutPopUp(); return false;" style= "color:white;">About</a>
      <!-- The Modal -->
      <div id="aboutModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
          <div class="modal-header">
            About
          </div>
        <div class="modal-body">
          <?php
            $text = $settings["about"];
            echo $text;
          ?>
        </div>
      <div class="modal-footer">
        <button type="button" class="aboutClose" aria-label="aboutClose">
              <span aria-hidden="true"><b>&times;</b> Close</span>
        </button>
      </div>
    </div>
  </div> |
    <a href onclick="policiesPopUp(); return false;" style= "color:white;">Policies</a>
    <!-- The Modal -->
    <div id="policiesModal" class="modal">
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          Policies
        </div>
        <div class="modal-body">
          <?php
            $text = $settings["policies"];
            echo $text;
          ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="policiesClose" aria-label="policiesClose">
                <span aria-hidden="true"><b>&times;</b> Close</span>
          </button>
        </div>
      </div>
    </div>

    <?php
      if (!isset($_SESSION["systemid"])) {
        $_SESSION["systemid"] = "";
      }
      if(isset($_SESSION["username"])) {
          if ($_SESSION["username"] != "") {
            echo "<p style=\"float: right;\">&nbsp;</p>";
              echo "<a href=\"modules/logout.php\"style=\"color:white; float: right;\">Logout</a>";
          }
      }

      if ($_SESSION["systemid"] == $settings["systemid"]) {

      if(isset($_SESSION["username"])) {
          if ($_SESSION["username"] != "") {
              echo "<p style=\"float: right;\">&nbsp;|&nbsp;</p>";
              ?>
                <a href onclick="helpPopUp(); return false;" style= "color:white; float: right;">Help</a>

                <!-- The Modal -->
                <div id="helpModal" class="modal">
                  <!-- Modal content -->
                  <div class="modal-content">
                    <div class="modal-header">
                      Help
                    </div>
                    <div class="modal-body">
                      <?php
                        $text = $settings["help"];
                        echo $text;
                      ?>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="helpClose" aria-label="helpClose">
                            <span aria-hidden="true"><b>&times;</b> Close</span>
                      </button>
                    </div>
                  </div>
                </div>
              </body>
              </html>
    <?php
          }
      }

      }

      if ($settings["login_method"] == "normal" && isset($_SESSION["username"])) {
           if($_SESSION["username"] != "") {
              echo "<p style=\"float: right;\">&nbsp;|&nbsp;</p>";
              echo "<a href=\"editaccount.php\"style=\"color:white; float: right;\">Edit Account</a>";
            }
      }
      ?>
    </span>
</div>
<script language="javascript" type="text/javascript">
function showHideDiv(ele) {
				var srcElement = document.getElementById(ele);
				if (srcElement != null) {
					if (srcElement.style.display == "block") {
						srcElement.style.display = 'none';
					}
					else {
						srcElement.style.display = 'block';
					}
					return false;
				}
			}
function hideDiv(ele) {
      var srcElement = document.getElementById(ele);
      if (srcElement != null) {
        if (srcElement.style.display == "block") {
          srcElement.style.display = 'none';
        }
        return false;
      }
    }
  // Get the modal
  var helpModal = document.getElementById('helpModal');
  var aboutModal = document.getElementById('aboutModal');
  var policiesModal = document.getElementById('policiesModal');

  // Get the <span> element that closes the modal
  var helpSpan = document.getElementsByClassName("helpClose")[0];
  var aboutSpan = document.getElementsByClassName("aboutClose")[0];
  var policiesSpan = document.getElementsByClassName("policiesClose")[0];

  // When the user clicks the button, open the modal
  function helpPopUp() {
      helpModal.style.display = "block";
  }
  function aboutPopUp() {
      aboutModal.style.display = "block";
  }
  function policiesPopUp() {
      policiesModal.style.display = "block";
  }

  // When the user clicks on <span> (x), close the modal
  if (typeof helpSpan !== 'undefined') {
    helpSpan.onclick = function() {
        helpModal.style.display = "none";
    }
  }
  aboutSpan.onclick = function() {
      aboutModal.style.display = "none";
  }
  policiesSpan.onclick = function() {
      policiesModal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.addEventListener("click", function(event) {
    if (event.target == helpModal) {
        helpModal.style.display = "none";
    }
    if (event.target == aboutModal) {
        aboutModal.style.display = "none";
    }
    if (event.target == policiesModal) {
        policiesModal.style.display = "none";
    }
  })
</script>
<!--style for the modal help popup-->
<style>
.modal {
    background-color: rgba(43,62,66,.85);
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 3; /* Sit on top */
    padding-top: 30px; /* Location of the box */
    padding-bottom: 30px;
}

.modal-content {
    position: relative;
    margin: auto;
    padding: 0;
    border: 5px solid #888;
    width: 35%;
    border: 1px solid transparent;
    border-color: #54406b;
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.4s;
    animation-name: animatetop;
    animation-duration: 0.4s
}

@-webkit-keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}

@keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}

.helpClose, .aboutClose, .policiesClose {
    border: 1px solid transparent;
    border-radius: 3px;
    border-color: #54406b;
    margin-left: auto;
    padding-top: 5px;
    padding-bottom: 5px;
    font-size: 14px;
    background: none;
}

.helpClose:hover, .aboutClose:hover, .policiesClose:hover,
.helpClose:focus, .aboutClose:focus, .policiesClose:focus {
    color: red;
    background: #e8deda;
    text-decoration: none;
    cursor: pointer;
}

.modal-header {
    font-size: 16px;
    font-family: sans-serif;
    padding: 10px 15px;
    background-color: white;
    border-top-right-radius: 9px;
    border-top-left-radius: 9px;
    color: black;
    justify-content: flex-start;
    font-weight: bold;
}

.modal-body {
  padding: 2px 16px;
  color: black;
  text-align: left;
  word-wrap: break-word;
  border-top: 1px solid transparent;
  border-color: #54406b;
}

.modal-footer {
    padding: 10px 15px;
    border-bottom-right-radius: 9px;
    border-bottom-left-radius: 9px;
    background-color: white;
    color: black;
}
</style>
<div id="containerInfo">
    <div id="calendarDiv" style="display:none">
        <center>
        <?php include("modules/calendar.php"); ?>
        </center>
    </div>
    <div id="rightside">
