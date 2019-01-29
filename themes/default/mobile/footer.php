</div>
<!--
Temporarily removed Switch to Desktop button until we can figure out why it's not working
<div id="themeswitch" onClick="javascript:document.cookie='theme=desktop';location.reload(true);">Switch to Desktop View
</div>
-->
<br/>
<div id="footnote">
  <a href onclick="aboutPopUp(); return false;">About</a> |
  <a href onclick="policiesPopUp(); return false;">Policies</a>
  <br/>Copyright (C) 2012 Ball State University Libraries<br/>Modified by Olivet Nazarene University
</div>
<!-- The Modals -->
<div id="aboutModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      About
    </div>
  <div class="modal-body">
    <?php echo $settings["about"]; ?>
  </div>
<div class="modal-footer">
  <button type="button" class="aboutClose" aria-label="aboutClose">
        <span aria-hidden="true"><b>&times;</b> Close</span>
  </button>
</div>
  </div>
</div>
<div id="policiesModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      Policies
    </div>
  <div class="modal-body">
    <?php echo $settings["policies"]; ?>
  </div>
<div class="modal-footer">
  <button type="button" class="policiesClose" aria-label="policiesClose">
        <span aria-hidden="true"><b>&times;</b> Close</span>
  </button>
</div>
  </div>
</div>
</body>
<script language="javascript" type="text/javascript">
  // Get the modal
  var aboutModal = document.getElementById('aboutModal');
  var policiesModal = document.getElementById('policiesModal');
  // Get the <span> element that closes the modal
  var aboutSpan = document.getElementsByClassName("aboutClose")[0];
  var policiesSpan = document.getElementsByClassName("policiesClose")[0];
  // When the user clicks the button, open the modal
  function aboutPopUp() {
      aboutModal.style.display = "block";
  }
  function policiesPopUp() {
      policiesModal.style.display = "block";
  }
  // When the user clicks on <span> (x), close the modal
  aboutSpan.onclick = function() {
      aboutModal.style.display = "none";
  }
  policiesSpan.onclick = function() {
      policiesModal.style.display = "none";
  }
  // When the user clicks anywhere outside of the modal, close it
  window.addEventListener("click", function(event) {
    if (event.target == aboutModal) {
        aboutModal.style.display = "none";
    }
    if (event.target == policiesModal) {
        policiesModal.style.display = "none";
    }
  })
</script>
</html>
