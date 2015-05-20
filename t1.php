<-- test -->
<link rel="stylesheet" type="text/css" href="group1.css">
<?php
	include("dbconnect.php");
	include("functions.php");
	$txt="";
	$level="5";
	$type="math";
	search($txt, $level, $type);
?>
