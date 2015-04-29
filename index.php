<!-- Name: Cequan Zhen 4/28/2015-->
<?php
	include("functions.php"); 

	$website_title="Group1 Search";
	$levels=array("","K","1","2","3","4","5","6","7","8","9","10","11","12");
	$types=array("","Chemistry","Physics","Mathematics","Earth Science","Biology","Geometry & Graphs","Life Science","Social Sciences","Health","Astronomy","Engineering","Others");
	$sorted_popular=0;
	if(isset($_POST["Search"])){
		$search_text=$_POST["search_text"];
		$search_level=$_POST["search_level"];
		$search_type=$_POST["search_type"];
		$sorted_popular=$_POST["popular"];
	}
	else{
		$search_text="";
		$search_level="";
		$search_type="";
	
	}
	
?>

<html>
	<head>
		<title>Gruop1 Website</title>
		<link rel="stylesheet" type="text/css" href="group1.css">

		<style type="text/css">
			body
			{
				width: 90%;
				margin: auto;
				font-size:16px;
				font-family: sans-serif;
			}
			
		</style>
		<script src="jquery-1.11.2.min.js"></script>
		<script type="text/javascript">
			function submitform()
			{
				$("#popular").val(1);
				$("#Search").click()
			}
		</script>
	</head>

	<body>
		<div id="div1">
		<h2><?php echo $website_title ?> </h2>
			<div class="myform">
				<form action="" method="POST" id="myform" name="myform">
					<label for="search_text"><?php echo $website_title ?></label>
					<input type="text" name="search_text" id="search_text" value="<?php echo $search_text ?>"/>
					<input type="submit" id="Search" name="Search" value="Search" />
					<a href="javascript: submitform()" id="most_popular">Most Popular</a>
					<?php 
						echo '<span class="search_level"><label for="search_level">Grade Level:</label>';
						show_list_box("search_level", $levels, $search_level);
						echo '</span>';
						echo '<span class="search_type"><label for="search_type">Subject:</label>';
						show_list_box("search_type", $types, $search_type);
						echo '</span>';
						
					?>
					<input type="hidden" id="popular" name="popular" value=0 />
				</form>
				<br />
			</div>
		</div>
		<?php
			if(strlen($search_text)>0 || strlen($search_level)>0 || strlen($search_level)>0)
            {
                if($sorted_popular!=1){
					search($search_text, $search_level, $search_type);
				}
				else{
					search_by_popular($search_text, $search_level, $search_type);
				}
			}
		?>

	</body>
</html>
