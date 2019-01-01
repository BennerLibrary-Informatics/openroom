<?php
$optionalfieldsarraytemp = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM optionalfields ORDER BY optionorder ASC;");
?>
<script language="javascript" type="text/javascript">
    //findPos(object)
    //Returns position of object for use with dayview popup
    //Source: http://www.quirksmode.org/js/findpos.html
    function findPos(obj) {
        var curleft = curtop = 0;
        if (obj.offsetParent) {
            do {
                curleft += obj.offsetLeft;
                curtop += obj.offsetTop;
            } while (obj = obj.offsetParent);
            return [curleft, curtop];
        }
    }

    function showPopUp(obj, content) {
        var popup = document.getElementById("popup");
        popup.style.visibility = "visible";
        popup.style.display = "inline";
        var popleft = (findPos(obj)[0] + ((obj.width / 3) * 2));
        var poptop = (findPos(obj)[1] + ((obj.height / 3) * 2));
        //determine browser window width
        var myWidth = 0, myHeight = 0;
        if (typeof(window.innerWidth) == 'number') {
            //Non-IE
            myWidth = window.innerWidth;
            myHeight = window.innerHeight;
        } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
            //IE 6+ in 'standards compliant mode'
            myWidth = document.documentElement.clientWidth;
            myHeight = document.documentElement.clientHeight;
        } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
            //IE 4 compatible
            myWidth = document.body.clientWidth;
            myHeight = document.body.clientHeight;
        }
        //limit is the width of the browser window minus the width of the popup
        var limit = myWidth - popup.clientWidth - 20;
        //If popup is too far to the right, shift it left by its width
        if (popleft >= limit) {
            popleft = popleft - popup.clientWidth;
        }
        popup.style.left = popleft + "px";
        popup.style.top = poptop + "px";
        /*&popup.innerHTML = "<div id=\"popupClose\"><span onClick=\"closePopUp()\">Close<\/span><\/div>" + content;*/
    }

    function showPopUpReserve(obj, roomname, roomdescription, time_str, group, altusernamestr, roomid, currentmdyandtime, capacity, durationhtml, capacity_string, optionalfields_string, end_time_str) {
        var popup = document.getElementById("popup");
        popup.style.visibility = "visible";
        popup.style.display = "inline";
        var popleft = (findPos(obj)[0] + ((obj.width / 3) * 2));
        var poptop = (findPos(obj)[1] + ((obj.height / 3) * 2));
        //determine browser window width
        var myWidth = 0, myHeight = 0;
        if (typeof(window.innerWidth) == 'number') {
            //Non-IE
            myWidth = window.innerWidth;
            myHeight = window.innerHeight;
        } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
            //IE 6+ in 'standards compliant mode'
            myWidth = document.documentElement.clientWidth;
            myHeight = document.documentElement.clientHeight;
        } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
            //IE 4 compatible
            myWidth = document.body.clientWidth;
            myHeight = document.body.clientHeight;
        }
        //limit is the width of the browser window minus the width of the popup
        var limit = myWidth - popup.clientWidth - 20;
        //If popup is too far to the right, shift it left by its width
        if (popleft >= limit) {
            popleft = popleft - popup.clientWidth;
        }

        popup.style.left = popleft + "px";
        popup.style.top = poptop + "px";
        popup.innerHTML = "<div class = 'col-12'><div class = 'col-12 text-center'><label class = 'roomtitle'>" /*<div id=\"popupClose\"><\/div>*/ + roomname + "</label><br/>" + roomdescription + "</div><br/>"
        + "<div class = 'row'><div class = 'col-sm-6'><label><strong>Start</strong>:</label> " + time_str + "</div> <div id = 'endReservationTime' class = 'col-sm-6'><label><strong>End</strong>:</label> " + end_time_str + "</div> <div class = 'col-sm-6'></div></div>"
        + "<form name=\'reserve\' action=\'javascript:reserve(" + group + ");\'>"
        + "<div class = 'row'><div class = 'col-sm-6'><label>Duration</label></strong>: <select name=\'duration\' onchange=\'changeEndReservationTime(this);\'>"  + durationhtml + "</select></div>"
        + "<div class = 'col-sm-6'><label></span>Members</strong>: </label> <select name=\'capacity\'>" + capacity_string + "</select></div></div>"
        + "<hr><div class = 'row col-12'>" + altusernamestr + "<input type=\'hidden\' name=\'roomid\' value=\'"+ roomid + "\' />"
          + "<input type=\'hidden\' name=\'starttime\' value=\'"+ currentmdyandtime + "\' />"
          + "<input type=\'hidden\' name=\'fullcapacity\' value=\'" + capacity + "\' /><strong></div><div class = 'row col-12'"+ optionalfields_string + "</div>"
        + "<hr><div class = 'row'><div class = 'col-6 text-center bottombutton1'><a href=\'javascript:reserve(" + group + ");\'> Reserve</a> </div><div class = 'col-6 text-center bottombutton2'><a id = '' href=\'javascript:closePopUp();\'>Cancel</a></div></div></form>";
    }

    function changeEndReservationTime(select) {
        var starttimeint = parseInt(document.reserve.starttime.value);
        var endtimeint = starttimeint + 60*parseInt(select.options[select.selectedIndex].text.substring(0, select.options[select.selectedIndex].text.length - 5));
        var endtimedate = new Date(0);
        //endtimeint begins at 12am, but library opens at 7am hence the 5 below
        endtimedate.setUTCSeconds(endtimeint);
        document.getElementById("endReservationTime").innerHTML = "<label><strong>End</strong>:</label> "  + endtimedate.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}).toLowerCase();
    }

    function closePopUp() {
        document.getElementById("popup").style.visibility = "hidden";
        document.getElementById("popup").style.display = "none";
    }

    function cancelQuestion(reservationid, groupid) {
        if (confirm('Cancel this reservation?')) {
          cancel(reservationid, groupid);
        }
        else {
          //do nothing
        }
    }

    function cancel(reservationid, groupid) {
        //Cancel reservation using or-cancel.php
        var req;
        try {
            req = new XMLHttpRequest();
        } catch (err1) {
            try {
                req = new ActiveXObject("Msxm12.XMLHTTP");
            } catch (err2) {
                try {
                    req = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (err3) {
                    req = false;
                }
            }
        }
        if (req != false) var xmlhttp = req;

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4) {
                var brokenstring = xmlhttp.responseText.split("|");
                if (brokenstring[0] == "Error: User is not logged in.") location.reload(true);
                document.getElementById("popup").innerHTML = "<div id=\"popupClose\"><span onClick=\"closePopUp()\">x<\/span><\/div>" + brokenstring[0];
                setTimeout(function() { closePopUp(); }, 5000);
                if (document.getElementById("calendarButton") != null) {
                  document.getElementById("calendarButton").style.visibility = "visible";
                }
                dayviewer(brokenstring[1], brokenstring[2], groupid, '');
            }
            else {
                if (document.getElementById("calendarButton") != null) {
                  document.getElementById("calendarButton").style.visibility = "hidden";
                }
                document.getElementById("dayviewModule").innerHTML = "";
                document.getElementById("loader").innerHTML = "<br\/><br\/><br\/><center><img src='<?php echo $_SESSION["themepath"]; ?>images\/ajax-loader.gif' \/><br\/>Cancelling, please wait...<\/center>";
            }
        };

        urlstring = "or-cancel.php";

        params = "reservationid=" + reservationid;

        xmlhttp.open("POST", urlstring, true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(params);
    }

    function reserve(groupid) {
        try {
            req = new XMLHttpRequest();
        } catch (err1) {
            try {
                req = new ActiveXObject("Msxm12.XMLHTTP");
            } catch (err2) {
                try {
                    req = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (err3) {
                    req = false;
                }
            }
        }
        if (req != false) var xmlhttp = req;
        var starttime = parseInt(document.reserve.starttime.value);
        var duration = parseInt(document.reserve.duration.value);
        if (document.reserve.altusername) {
            var altusername = document.reserve.altusername.value;
        }
        else {
            var altusername = "";
        }
        if (document.reserve.emailconfirmation) {
            var emailconfirmation = document.reserve.emailconfirmation.value;
        }
        else {
            var emailconfirmation = "";
        }
        var endtime = starttime + (duration * 60);

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4) {
                var brokenstring = xmlhttp.responseText.split("|");
                if (brokenstring[0] == "Error: User is not logged in.") location.reload(true);
                document.getElementById("popup").innerHTML = "<div id=\"popupClose\"><span onClick=\"closePopUp()\">x<\/span><\/div>" + brokenstring[0];
                setTimeout(function() { closePopUp(); }, 5000);
                dayviewer(starttime, brokenstring[2], groupid, '');
            }
            else {
                document.getElementById("popup").innerHTML = "Reserving...";
            }
        };

        urlstring = "or-reserve.php";

        params = "altusername=" + altusername + "&emailconfirmation=" + emailconfirmation + "&duration=" + document.reserve.duration.value + "&roomid=" + document.reserve.roomid.value + "&starttime=" + document.reserve.starttime.value + "&capacity=" + document.reserve.capacity.value + "&fullcapacity=" + document.reserve.fullcapacity.value +
        "<?php
            //Use the session var of optional fields to grab field values from the reserve form
            while ($optionalfield = mysqli_fetch_array($optionalfieldsarraytemp)) {
                echo "&" . $optionalfield["optionformname"] . "=\"+ document.reserve." . $optionalfield["optionformname"] . ".value +\"";
            }

            ?>";

        xmlhttp.open("POST", urlstring, true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(params);
    }
</script>
<div id="popup"></div>
<div id="dayviewModule"></div>
