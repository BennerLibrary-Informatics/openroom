<div id="legend">
    <span id="legendtitle">Legend</span>
    <div id = "legendtable" class = "row">
      <div class = "row">
        <div class = "col-lg-4">
            <img src="<?php echo $_SESSION["themepath"]; ?>images/reservebutton.png"/>
        </div>
        <div class = "col-lg-8">
            - Available
        </div>
      </div>

      <div class = "row">
        <div class = "col-lg-4">
            <img src="<?php echo $_SESSION["themepath"]; ?>images/takenbutton.png"/>
        </div>
        <div class = "col-lg-8">
            - Unavailable
        </div>
      </div>

      <div class = "row">
        <div class = "col-lg-4">
            <img src="<?php echo $_SESSION["themepath"]; ?>images/cancelbutton.png"/>
        </div>
        <div class = "col-lg-8">
            - Your Reservation
        </div>
      </div>

      <div class = "row">
        <div class = "col-lg-4">
            <img src="<?php echo $_SESSION["themepath"]; ?>images/closedbutton.png"/>
        </div>
        <div class = "col-lg-8">
            - Closed
        </div>
      </div>
    </div>


    <script language="javaScript">

        function popUp(URL) {
            day = new Date();
            id = day.getTime();
            eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=500');");
        }

    </script>

    <center><a href="javascript:popUp('modules/policies.php');">Policies</a></center>
</div>
