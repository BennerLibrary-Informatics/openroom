<!--style for the modal help popup-->
<style>
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
}

.modal-content {
    position: relative;
    margin: auto;
    padding: 0;
    border: 5px solid #888;
    width: 35%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
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

.close {
    color: white;
    float: right;
    font-size: 35px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.modal-header {
    padding: 2px 16px;
    background-color: rgb(84, 64, 107);
    color: white;
}

.modal-body {
  padding: 2px 16px;
  color: black;
  text-align: left;
  word-wrap: break-word;
}

.modal-footer {
    padding: 2px 10px;
    background-color: rgb(84, 64, 107);
    color: white;
}
</style>
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
 <div id="heading" >
   <div class = "row" >
   <div class = "col-1 margin-top-20 margin-left-10 margin-bottom-15">
     <a href="https://library.olivet.edu/index.php" style="text-decoration:none; color:white;"><img src = 'https://library.olivet.edu/img/logo.svg'>
     </div>
     <div id = "headerText" class = "col-11 float-right padding-left-30">
        <div class = "row openroomtitle">
            <div class = "col">Benner Library</div>
           </a>
            <div class = "col text-right">Open Room</div>
        </div>
            <div class = "ONUtitle">
              <a href="https://library.olivet.edu/index.php" style="text-decoration:none; color:white;">
                Olivet Nazarene University
              <a style="text-decoration:none; color:white; float: right;">
                Reserve a Space
            </div>
          </a>
          </a>
    </div>
   </div>
	<span class="username">
	<?php
    if (!isset($_SESSION["systemid"])) {
      $_SESSION["systemid"] = "";
    }

    if ($_SESSION["systemid"] == $settings["systemid"]) {

    if (!isset($_SESSION["username"])) {
      $_SESSION["isadministrator"] = "FALSE";
      $_SESSION["isreporter"] = "FALSE";
    }

    echo isset($_SESSION["username"]) ? $_SESSION["username"] : "&nbsp;"; ?></span>&nbsp;<?php echo ($_SESSION["isadministrator"] == "TRUE") ? "<span class=\"isadministrator\">(<a href=\"admin/index.php\">Admin</a>)</span>&nbsp;" : "";
    echo ($_SESSION["isreporter"] == "TRUE") ? "<span class=\"isreporter\">(<a href=\"admin/index.php\">Reporter</a>)</span>&nbsp;" : "";

    if ($settings["login_method"] == "normal" && isset($_SESSION["username"])) {
         if($_SESSION["username"] != "") {
            echo "|&nbsp;<a href=\"editaccount.php\">Edit Account</a>&nbsp;";
         }
    }
    if(isset($_SESSION["username"])) {
        if ($_SESSION["username"] != "") {
            echo "|";
            ?>
            <!--<div id="clear_both"></div>
            <div class = "col-12">
            </div>
            <div class = "copyright">-->
              <a href onclick="helpPopUp(); return false;">Help</a>

              <!-- The Modal -->
              <div id="helpModal" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                  <div class="modal-header">
                    <span class="close">&times;</span>
                    <h2></h2>
                  </div>
                  <div class="modal-body">
                <?php
                // The Regular Expression filter
                $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
                // The Text you want to filter for urls
                $text = $settings["help"];
                // Check if there is a url in the text
                if(preg_match($reg_exUrl, $text, $url)) {

                       // make the urls hyper links
                       echo preg_replace($reg_exUrl, '<a href="'.$url[0].'" rel="nofollow" target="_blank">'.$url[0].'</a>', $text);

                } else {
                       // if no urls in the text just return the text
                       echo $text;
                }
                ?>
                  </div>
                  <div class="modal-footer">
                    <h3>Help</h3>
                  </div>
                </div>
              </div>
            </body>
            </html>
  <?php
        }
    }
    if(isset($_SESSION["username"])) {
        if ($_SESSION["username"] != "") {
            echo "|&nbsp;<a href=\"modules/logout.php\">Logout</a>";
        }
    }
    }
    ?>
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

  // Get the <span> element that closes the modal
  var helpSpan = document.getElementsByClassName("close")[0];

  // When the user clicks the button, open the modal
  function helpPopUp() {
      helpModal.style.display = "block";
  }

  // When the user clicks on <span> (x), close the modal
  helpSpan.onclick = function() {
      helpModal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == helpModal) {
        helpModal.style.display = "none";
    }
  }
</script>

<?php include("modules/reminder.php"); ?>
<div id="containerInfo">
<?php
if(isset($_SESSION["username"])) {
    if ($_SESSION["username"] != "") {
      echo "<center><input id=\"calendarButton\" type=\"button\" value=\"Show/Hide Calendar\" onclick=\"showHideDiv('calendarDiv')\"/></center>";
    }
}
?>
    <div id="calendarDiv" style="display:none">
        <center>
        <?php include("modules/calendar.php"); ?>
        </center>
    </div>
    <div id="rightside">
