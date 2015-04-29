<?php
	if(isset($_GET["lesson_id"])){
		$id=intval($_GET["lesson_id"]);
		include("functions.php");
		show_lesson_page($id);
	}
?>
