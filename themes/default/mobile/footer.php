</div>
<div id="themeswitch" onClick="javascript:document.cookie='theme=desktop';location.reload(true);">Switch to Desktop View
</div>
<br/>
<div id="footnote">
  <a href onclick="policiesPopUp(); return false;">Polices</a>
  <br/>Developed by Ball State University Libraries<br/>Copyright (C) 2012 Ball State University Libraries
</div>
</body>
<script language="javascript" type="text/javascript">
  function policiesPopUp() {
    alert('<?php echo $settings["policies"]; ?>');
  }
</script>
</html>
