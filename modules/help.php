<?php
require_once("../includes/or-dbinfo.php");
//This "module" is meant to be loaded as a standalone pop-up page.
?>

<html>

<head>
    <title>Help</title>
</head>

<body>
<center>
    <a href="javascript:window.close();">Close</a>
</center>
<h2>Help</h2>
<?php
require_once(__DIR__ . '/../vendor/autoload.php');
$setting = model\Setting::find('help');
$about = nl2br($setting->get_value());
echo $about;
?>
<br/>
<center>
    <a href="javascript:window.close();">Close</a>
</center>
</body>
</html>
