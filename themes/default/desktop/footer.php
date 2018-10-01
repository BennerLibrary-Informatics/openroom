<!--style for the modal policies popup-->
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

.modal-body {padding: 2px 16px;}

.modal-footer {
    padding: 2px 10px;
    background-color: rgb(84, 64, 107);
    color: white;
}
</style>
</div>

<div id="clear_both"></div>
<div class = "col-12">
  <hr/>
</div>
<div class = "copyright">
  <a href onclick="policiesPopUp(); return false;">Policies</a>

  <!-- The Modal -->
  <div id="policiesModal" class="modal">

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
    $text = $settings["policies"];

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
        <h3>Policies</h3>
      </div>
    </div>

  </div>



  <br/>
  Developed by Ball State University Libraries<br/> <!--Put inside div block-->
  Copyright (C) 2012 Ball State University Libraries
</div>
</div>
</div>
</body>
</html>

<script language="javascript" type="text/javascript">

// Get the modal
var modal = document.getElementById('policiesModal');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
function policiesPopUp() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
