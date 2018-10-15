</div>
<div id="themeswitch" onClick="javascript:document.cookie='theme=desktop';location.reload(true);">Switch to Desktop View
</div>
<br/>
<div id="footnote">
  <a href onclick="policiesPopUp(); return false;">Policies</a>
  <br/>Copyright (C) 2012 Ball State University Libraries<br/>Modified by Olivet Nazarene University
</div>
</body>
<script language="javascript" type="text/javascript">
  function policiesPopUp() {
    alert('<?php echo $settings["policies"]; ?>');
  }
</script>
</html>
