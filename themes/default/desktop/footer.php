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

.policiesClose, .aboutClose {
    color: grey;
    margin-left: auto;
    font-size: 35px;
    font-weight: bold;
    background: none;
    border: none;
}

.policiesClose:hover, .aboutClose:hover,
.policiesClose:focus, .aboutClose:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.modal-header {
    padding: 2px 16px;
    background-color: white;
    color: black;
}

.modal-body {
  padding: 2px 16px;
  word-wrap: break-word;
}

.modal-footer {
    padding: 2px 10px;
    background-color: white;
    color: black;
}
</style>
</div>

<div id="clear_both"></div>
<div class = "col-12">
  <hr/>
</div>
<div class = "copyright">
    <a href onclick="aboutPopUp(); return false;">About</a>

    <!-- The Modal -->
    <div id="aboutModal" class="modal">

      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="aboutClose" aria-label="policiesClose">
                <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <div class="modal-body">
      <?php
        // The Regular Expression filter
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        // The Text you want to filter for urls
        $text = $settings["about"];
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
      <h3>About</h3>
    </div>
  </div>
</div>
<br/>

  <a href onclick="policiesPopUp(); return false;">Policies</a>

  <!-- The Modal -->
  <div id="policiesModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="policiesClose" aria-label="policiesClose">
              <span aria-hidden="true">&times;</span>
        </button>
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
  Copyright (C) 2012 Ball State University Libraries<br/> <!--Put inside div block-->
  Modified by Olivet Nazarene University
</div>
</div>
</div>
</body>
</html>

<script language="javascript" type="text/javascript">

// Get the modal
var policiesModal = document.getElementById('policiesModal');
var aboutModal = document.getElementById('aboutModal');

// Get the <span> element that closes the modal
var policiesSpan = document.getElementsByClassName("policiesClose")[0];
var aboutSpan = document.getElementsByClassName("aboutClose")[0];

// When the user clicks the button, open the modal
function policiesPopUp() {
    policiesModal.style.display = "block";
}

function aboutPopUp() {
    aboutModal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
policiesSpan.onclick = function() {
    policiesModal.style.display = "none";
}

aboutSpan.onclick = function() {
    aboutModal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.addEventListener("click", function(event) {
    if (event.target == policiesModal) {
        policiesModal.style.display = "none";
    }
    if (event.target == aboutModal) {
        aboutModal.style.display = "none";
    }
})
</script>
