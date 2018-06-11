</div>

<div id="clear_both"></div>
<div class = "col-12">
  <hr/>
</div>
<div class = "copyright">
  <a href onclick="policiesPopUp(); return false;">Polices</a><br/>
  Developed by Ball State University Libraries<br/> <!--Put inside div block-->
  Copyright (C) 2012 Ball State University Libraries
</div>
</div>
</div>
</body>
</html>

<script language="javascript" type="text/javascript">
  function policiesPopUp() {
    alert('<?php echo $settings["policies"]; ?>');
  }
</script>